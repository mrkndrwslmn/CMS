<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../utils/PaymentGateway.php';
require_once __DIR__ . '/../models/Transaction.php';
require_once __DIR__ . '/../models/TopUp.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class PaymentController extends Controller
{
    private $paymentGateway;
    private $transactionModel;
    private $topUpModel;

    public function __construct()
    {
        // Set Manila timezone
        date_default_timezone_set('Asia/Manila');

        $this->paymentGateway = new PaymentGateway(false); // false for sandbox mode
        $this->transactionModel = new Transaction();
        $this->topUpModel = new TopUp();
    }

    /**
     * Process checkout and redirect to PayMaya
     */
    public function checkout()
    {
        // Check if user is logged in
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            // User not logged in, redirect to login page
            header('Location: ' . BASE_URL . 'auth/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendJsonResponse(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        // Get data from POST request
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Invalid data format']);
            return;
        }

        // Validate required data
        if (!isset($data['items']) || !isset($data['totalAmount']) || !isset($data['serviceID'])) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Missing required parameters']);
            return;
        }

        // Create unique reference number
        $referenceNumber = 'TRSDTR-' . time() . '-' . $_SESSION['user_id'];

        // Set redirect URLs with explicit status parameters
        $redirectUrls = [
            'success' => BASE_URL . 'payment/success?ref=' . $referenceNumber . '&status=success',
            'failure' => BASE_URL . 'payment/failure?ref=' . $referenceNumber . '&status=failed',
            'cancel' => BASE_URL . 'payment/cancel?ref=' . $referenceNumber . '&status=cancelled'
        ];

        // Get user information
        $userID = $_SESSION['user_id'];
        $email = $_SESSION['email'] ?? '';
        $fullName = $_SESSION['fullName'] ?? '';

        // Create buyer information
        $buyer = [
            'firstName' => explode(' ', $fullName)[0] ?? '',
            'lastName' => explode(' ', $fullName)[1] ?? '',
            'contact' => [
                'email' => $email
            ]
        ];

        // Create checkout
        $response = $this->paymentGateway->createCheckout(
            $data['items'],
            $data['totalAmount'],
            'PHP',
            $referenceNumber,
            $buyer,
            $redirectUrls
        );

        if ($response['error']) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Payment gateway error: ' . $response['message']]);
            return;
        }

        // Store transaction information
        $checkoutId = $response['data']['checkoutId'] ?? null;

        if ($checkoutId) {
            // Save transaction to database
            $transactionData = [
                'userID' => $userID,
                'serviceID' => $data['serviceID'],
                'amount' => $data['totalAmount'],
                'referenceNumber' => $referenceNumber,
                'checkoutId' => $checkoutId,
                'status' => 'pending',
                'packageType' => $data['packageType'] ?? 'basic',
                'addOns' => json_encode($data['selectedAddOns'] ?? [])
            ];

            error_log("Transaction data: " . print_r($transactionData, true));

            $transactionId = $this->transactionModel->createTransaction($transactionData);

            if ($transactionId) {
                // Return checkout URL to redirect user
                $this->sendJsonResponse([
                    'success' => true,
                    'checkoutUrl' => $response['data']['redirectUrl'],
                    'transactionId' => $transactionId,
                    'referenceNumber' => $referenceNumber
                ]);
            } else {
                // Log the error
                error_log("Failed to save transaction: " . print_r($transactionData, true));

                // Still allow the user to proceed to payment
                $this->sendJsonResponse([
                    'success' => true,
                    'checkoutUrl' => $response['data']['redirectUrl'],
                    'referenceNumber' => $referenceNumber,
                    'warning' => 'Transaction record could not be saved, but you can still proceed with payment.'
                ]);
            }
        } else {
            $this->sendJsonResponse(['success' => false, 'message' => 'Invalid checkout ID from payment gateway']);
        }
    }

    /**
     * Handle successful payment
     */
    public function success()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $referenceNumber = $_GET['ref'] ?? '';
        $status = $_GET['status'] ?? '';
        error_log("Payment Success: Reference Number = " . $referenceNumber . ", Status = " . $status);

        // Redirect to correct endpoint if status doesn't match
        if ($status && $status !== 'success') {
            header('Location: ' . BASE_URL . 'payment/' . $status . '?ref=' . $referenceNumber . '&status=' . $status);
            exit;
        }

        if (empty($referenceNumber)) {
            error_log("Payment Success: Empty reference number");
            $this->view('error/error', ['message' => 'Invalid reference number', 'error_code' => 400]);
            return;
        }

        try {
            // Debug mode disabled for production
            $debugMode = false;
            $debugInfo = [];
            $debugInfo['reference'] = $referenceNumber;

            // Update transaction status
            $updated = $this->transactionModel->updateTransactionStatus($referenceNumber, 'paid');
            error_log("Payment Success: Transaction status updated to 'paid' = " . ($updated ? 'Yes' : 'No'));
            $debugInfo['status_updated'] = $updated ? 'Yes' : 'No';

            // Get transaction details
            $transaction = $this->transactionModel->getTransactionByReference($referenceNumber);
            $debugInfo['transaction_found'] = $transaction ? 'Yes' : 'No';

            if ($transaction) {
                // Log transaction data for debugging
                error_log("Payment Success: Transaction data = " . print_r($transaction, true));
                $debugInfo['transaction_data'] = $transaction;

                // Send notification message to the Kreytor
                $this->sendMessageToKreytor($transaction);

                // Render the success page with transaction details
                $viewData = ['transaction' => $transaction];

                // Only include debug info in development
                if ($debugMode) {
                    $viewData['debugInfo'] = $debugInfo;
                }

                $this->view('payment/success', $viewData);
            } else {
                error_log("Payment Success: Transaction not found with reference: " . $referenceNumber);

                // Try to get all orders with similar reference
                $similarOrders = [];
                try {
                    $similarOrders = $this->transactionModel->findSimilarReferenceNumbers($referenceNumber);
                    if ($similarOrders) {
                        error_log("Found similar orders: " . print_r($similarOrders, true));
                        $debugInfo['similar_orders'] = $similarOrders;
                    }
                } catch (Exception $ex) {
                    error_log("Error finding similar orders: " . $ex->getMessage());
                    $debugInfo['similar_orders_error'] = $ex->getMessage();
                }

                if ($debugMode) {
                    // In debug mode, show a special page with all the debug information
                    $this->view('payment/success', [
                        'transaction' => [
                            'referenceNumber' => $referenceNumber,
                            'status' => 'completed',
                            'createdAt' => date('Y-m-d H:i:s'),
                            'amount' => 0,
                        ],
                        'debugInfo' => $debugInfo,
                        'error' => 'Transaction not found with reference: ' . $referenceNumber
                    ]);
                } else {
                    $this->view('error/error', [
                        'message' => 'Transaction not found with reference: ' . $referenceNumber,
                        'error_code' => 404
                    ]);
                }
            }
        } catch (Exception $e) {
            error_log("Payment Success: Exception - " . $e->getMessage());
            $debugInfo['exception'] = $e->getMessage();

            if ($debugMode) {
                $this->view('payment/success', [
                    'transaction' => [
                        'referenceNumber' => $referenceNumber,
                        'status' => 'error',
                        'createdAt' => date('Y-m-d H:i:s'),
                        'amount' => 0,
                    ],
                    'debugInfo' => $debugInfo,
                    'error' => 'Exception: ' . $e->getMessage()
                ]);
            } else {
                $this->view('error/error', [
                    'message' => 'An error occurred while processing your payment: ' . $e->getMessage(),
                    'error_code' => 500
                ]);
            }
        }
    }

    /**
     * Handle failed payment
     */
    public function failure()
    {
        $referenceNumber = $_GET['ref'] ?? '';
        $status = $_GET['status'] ?? '';
        error_log("Payment Failure: Reference Number = " . $referenceNumber . ", Status = " . $status);

        // Redirect to correct endpoint if status doesn't match
        if ($status && $status !== 'failed') {
            header('Location: ' . BASE_URL . 'payment/' . $status . '?ref=' . $referenceNumber . '&status=' . $status);
            exit;
        }

        if (empty($referenceNumber)) {
            $this->view('error/error', ['message' => 'Invalid reference number']);
            return;
        }

        // Update transaction status
        $this->transactionModel->updateTransactionStatus($referenceNumber, 'failed');

        $this->view('payment/failure', ['referenceNumber' => $referenceNumber]);
    }

    /**
     * Handle cancelled payment
     */
    public function cancel()
    {
        $referenceNumber = $_GET['ref'] ?? '';
        $status = $_GET['status'] ?? '';
        error_log("Payment Cancel: Reference Number = " . $referenceNumber . ", Status = " . $status);

        // Redirect to correct endpoint if status doesn't match
        if ($status && $status !== 'cancelled') {
            header('Location: ' . BASE_URL . 'payment/' . $status . '?ref=' . $referenceNumber . '&status=' . $status);
            exit;
        }

        if (empty($referenceNumber)) {
            $this->view('error/error', ['message' => 'Invalid reference number']);
            return;
        }

        // Update transaction status
        $this->transactionModel->updateTransactionStatus($referenceNumber, 'cancelled');

        $this->view('payment/cancel', ['referenceNumber' => $referenceNumber]);
    }

    /**
     * Send JSON response
     */
    private function sendJsonResponse($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Process top-up and redirect to PayMaya
     */
    public function topUp()
    {
        // Check if user is logged in
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            // User not logged in, redirect to login page
            header('Location: ' . BASE_URL . 'auth/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendJsonResponse(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        // Get data from POST request
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Invalid data format']);
            return;
        }

        // Validate required data
        if (!isset($data['amount']) || !isset($data['method'])) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Missing required parameters']);
            return;
        }

        // Create unique reference number
        $referenceNumber = 'KREYT-TOPUP-' . time() . '-' . $_SESSION['user_id'];

        // Set redirect URLs with explicit status parameters
        $redirectUrls = [
            'success' => BASE_URL . 'payment/topup/success?ref=' . $referenceNumber . '&status=success',
            'failure' => BASE_URL . 'payment/topup/failure?ref=' . $referenceNumber . '&status=failed',
            'cancel' => BASE_URL . 'payment/topup/cancel?ref=' . $referenceNumber . '&status=cancelled'
        ];

        // Get user information
        $userID = $_SESSION['user_id'];
        $email = $_SESSION['email'] ?? '';
        $fullName = $_SESSION['fullName'] ?? '';

        // Create buyer information
        $buyer = [
            'firstName' => explode(' ', $fullName)[0] ?? '',
            'lastName' => explode(' ', $fullName)[1] ?? '',
            'contact' => [
                'email' => $email
            ]
        ];

        // Create items array for PayMaya
        $items = [
            [
                'name' => 'Account Top-up',
                'quantity' => 1,
                'code' => 'TOPUP-' . $userID,
                'description' => 'Account balance top-up',
                'amount' => [
                    'value' => $data['amount'],
                    'details' => [
                        'discount' => 0,
                        'serviceCharge' => 0,
                        'shippingFee' => 0,
                        'tax' => 0,
                        'subtotal' => $data['amount']
                    ]
                ],
                'totalAmount' => [
                    'value' => $data['amount'],
                    'details' => [
                        'discount' => 0,
                        'serviceCharge' => 0,
                        'shippingFee' => 0,
                        'tax' => 0,
                        'subtotal' => $data['amount']
                    ]
                ]
            ]
        ];

        // Create checkout
        $response = $this->paymentGateway->createCheckout(
            $items,
            $data['amount'],
            'PHP',
            $referenceNumber,
            $buyer,
            $redirectUrls
        );

        if ($response['error']) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Payment gateway error: ' . $response['message']]);
            return;
        }

        // Store transaction information
        $checkoutId = $response['data']['checkoutId'] ?? null;

        if ($checkoutId) {
            // Save top-up to database
            $topUpData = [
                'userID' => $userID,
                'amount' => $data['amount'],
                'referenceNumber' => $referenceNumber,
                'checkoutId' => $checkoutId,
                'status' => 'pending'
            ];

            error_log("Top-up data: " . print_r($topUpData, true));

            $topUpId = $this->topUpModel->createTopUp($topUpData);

            if ($topUpId) {
                // Return checkout URL to redirect user
                $this->sendJsonResponse([
                    'success' => true,
                    'checkoutUrl' => $response['data']['redirectUrl'],
                    'referenceNumber' => $referenceNumber
                ]);
            } else {
                // Log the error
                error_log("Failed to save top-up: " . print_r($topUpData, true));
                $this->sendJsonResponse(['success' => false, 'message' => 'Failed to save top-up transaction']);
            }
        } else {
            $this->sendJsonResponse(['success' => false, 'message' => 'Invalid checkout ID from payment gateway']);
        }
    }

    /**
     * Handle successful top-up
     */
    public function topUpSuccess()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $referenceNumber = $_GET['ref'] ?? '';
        $status = $_GET['status'] ?? '';
        error_log("Top-up Success: Reference Number = " . $referenceNumber . ", Status = " . $status);

        // Redirect to correct endpoint if status doesn't match
        if ($status && $status !== 'success') {
            header('Location: ' . BASE_URL . 'payment/topup/' . $status . '?ref=' . $referenceNumber . '&status=' . $status);
            exit;
        }

        if (empty($referenceNumber)) {
            error_log("Top-up Success: Empty reference number");
            $this->view('error/error', ['message' => 'Invalid reference number', 'error_code' => 400]);
            return;
        }

        try {
            // Update top-up status
            $updated = $this->topUpModel->updateTopUpStatus($referenceNumber, 'paid');
            error_log("Top-up Success: Status updated to 'paid' = " . ($updated ? 'Yes' : 'No'));

            // Get top-up details
            $topUp = $this->topUpModel->getTopUpByReference($referenceNumber);

            if ($topUp) {
                // Create view data
                $viewData = [
                    'topUp' => $topUp,
                    'title' => 'Top-up Successful',
                    'message' => 'Your account has been successfully topped up with ₱' . number_format($topUp['amount'], 2) . '.'
                ];

                // Render success view
                $this->view('payment/topup-success', $viewData);
            } else {
                $this->view('error/error', ['message' => 'Top-up transaction not found', 'error_code' => 404]);
            }
        } catch (Exception $e) {
            error_log("Top-up Success: Exception - " . $e->getMessage());
            $this->view('error/error', ['message' => 'An error occurred: ' . $e->getMessage(), 'error_code' => 500]);
        }
    }

    /**
     * Handle failed top-up
     */
    public function topUpFailure()
    {
        $referenceNumber = $_GET['ref'] ?? '';
        $status = $_GET['status'] ?? '';
        error_log("Top-up Failure: Reference Number = " . $referenceNumber . ", Status = " . $status);

        // Redirect to correct endpoint if status doesn't match
        if ($status && $status !== 'failed') {
            header('Location: ' . BASE_URL . 'payment/topup/' . $status . '?ref=' . $referenceNumber . '&status=' . $status);
            exit;
        }

        if (empty($referenceNumber)) {
            $this->view('error/error', ['message' => 'Invalid reference number']);
            return;
        }

        // Update top-up status
        $this->topUpModel->updateTopUpStatus($referenceNumber, 'failed');

        $this->view('payment/topup-failure', ['referenceNumber' => $referenceNumber]);
    }

    /**
     * Handle cancelled top-up
     */
    public function topUpCancel()
    {
        $referenceNumber = $_GET['ref'] ?? '';
        $status = $_GET['status'] ?? '';
        error_log("Top-up Cancel: Reference Number = " . $referenceNumber . ", Status = " . $status);

        // Redirect to correct endpoint if status doesn't match
        if ($status && $status !== 'cancelled') {
            header('Location: ' . BASE_URL . 'payment/topup/' . $status . '?ref=' . $referenceNumber . '&status=' . $status);
            exit;
        }

        if (empty($referenceNumber)) {
            $this->view('error/error', ['message' => 'Invalid reference number']);
            return;
        }

        // Update top-up status
        $this->topUpModel->updateTopUpStatus($referenceNumber, 'cancelled');

        $this->view('payment/topup-cancel', ['referenceNumber' => $referenceNumber]);
    }

    /**
     * Get all top-ups for the current user
     */
    public function getUserTopUps()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            $this->sendJsonResponse(['success' => false, 'message' => 'User not logged in']);
            return;
        }

        $userID = $_SESSION['user_id'];
        $topUps = $this->topUpModel->getUserTopUps($userID);

        $this->sendJsonResponse(['success' => true, 'topUps' => $topUps]);
    }

    /**
     * Send an automatic message to the Kreytor about the new order
     * 
     * @param array $transaction The transaction data
     * @return boolean Success status
     */
    private function sendMessageToKreytor($transaction)
    {
        if (empty($transaction) || !isset($transaction['freelancerID']) || !isset($transaction['userID'])) {
            error_log("Cannot send message to Kreytor: Invalid transaction data");
            return false;
        }

        try {
            require_once __DIR__ . '/../models/Message.php';
            require_once __DIR__ . '/../models/User.php';

            $messageModel = new Message();
            $userModel = new User();

            // Format the service information message
            $messageText = "🎉 New Order Placed! 🎉\n\n";
            $messageText .= "You have received a new order for: " . ($transaction['serviceTitle'] ?? 'Service') . "\n";
            $messageText .= "Package: " . ($transaction['packageType'] ?? 'Standard') . "\n";
            $messageText .= "Amount: ₱" . number_format(($transaction['amount'] ?? 0), 2) . "\n\n";
            $messageText .= "Reference: " . ($transaction['referenceNumber'] ?? 'Unknown') . "\n\n";
            $messageText .= "Please review and accept this order to start working on it.\n\n";

            // Add HTML links for the accept button and order details
            $acceptLink = BASE_URL . "kreytor/orders/accept/" . ($transaction['orderID'] ?? '');
            $messageText .= "<a href=\"" . $acceptLink . "\" style=\"display: inline-block; background-color: #4CAF50; color: white; padding: 10px 15px; text-align: center; text-decoration: none; border-radius: 5px; margin: 10px 0;\">Accept Order</a>\n\n";

            // Add a clickable link for order details
            $orderDetailsLink = BASE_URL . "kreytor/orders/" . ($transaction['orderID'] ?? '');
            $messageText .= "Or <a href=\"" . $orderDetailsLink . "\" style=\"color: #1E88E5; text-decoration: underline;\">click here</a> to view order details.";

            // Save the message to the database
            $clientID = $transaction['userID'];
            $kreytorID = $transaction['freelancerID'];

            $savedMessage = $messageModel->saveMessage($clientID, $kreytorID, $messageText);

            if (!$savedMessage) {
                error_log("Failed to save message in database");
                return false;
            }

            // Send push notification if possible
            $recipientFcmToken = $userModel->getFcmToken($kreytorID);

            if ($recipientFcmToken) {
                try {
                    // Initialize Firebase
                    $factory = (new Factory)
                        ->withServiceAccount(__DIR__ . '/../../config/kreytFirebase.json');
                    $messaging = $factory->createMessaging();

                    // Get sender profile
                    $senderProfile = $userModel->getProfileById($clientID);

                    // Create the message
                    $cloudMessage = CloudMessage::withTarget('token', $recipientFcmToken)
                        ->withNotification(Notification::create(
                            'New Order Received',
                            'You have a new order for ' . ($transaction['serviceTitle'] ?? 'your service')
                        ))
                        ->withData([
                            'type' => 'new_order',
                            'order_id' => (string)($transaction['id'] ?? ''),
                            'sender_id' => (string)$clientID,
                            'receiver_id' => (string)$kreytorID,
                            'message' => $messageText,
                            'created_at' => date('Y-m-d H:i:s'),
                        ]);

                    $messaging->send($cloudMessage);
                    error_log("Push notification sent to Kreytor ID: " . $kreytorID);
                } catch (Exception $e) {
                    error_log("Failed to send push notification: " . $e->getMessage());
                    // Continue anyway since message is saved in database
                }
            }

            error_log("Message sent to Kreytor ID: " . $kreytorID . " about new order");
            return true;
        } catch (Exception $e) {
            error_log("Error sending message to Kreytor: " . $e->getMessage());
            return false;
        }
    }
}
