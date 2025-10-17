<?php

class PaymentGateway
{
    private $apiKey;
    private $secretKey;
    private $baseUrl;
    private $isProduction;

    public function __construct($isProduction = false)
    {
        $this->isProduction = $isProduction;

        if ($isProduction) {
            // Production credentials
            $this->apiKey = "pk-your-production-public-key";
            $this->secretKey = "sk-your-production-secret-key";
            $this->baseUrl = "https://pg.paymaya.com";
        } else {
            // Sandbox credentials
            $this->apiKey = "pk-g22PoMDALOTY26b8Q69ffHM7O15ag8iHJycHzUhtIPh";
            $this->secretKey = "sk-Zsff5FaPJQnSwScdfpA7svxhGxPvtph9S1qLiwK0H58";
            $this->baseUrl = "https://pg-sandbox.paymaya.com";
        }
    }

    /**
     * Create a payment checkout
     * 
     * @param array $items Array of items to be purchased
     * @param float $totalAmount The total amount to be paid
     * @param string $currency The currency code (default: PHP)
     * @param string $referenceNumber Unique reference number for the transaction
     * @param array $buyer Buyer information (name, contact, etc.)
     * @param array $redirectUrls URLs for success, failure, and cancel
     * @return array Response from the API
     */
    public function createCheckout($items, $totalAmount, $currency = "PHP", $referenceNumber, $buyer, $redirectUrls)
    {
        $url = $this->baseUrl . "/checkout/v1/checkouts";

        $data = [
            "totalAmount" => [
                "value" => $totalAmount,
                "currency" => $currency
            ],
            "items" => $items,
            "requestReferenceNumber" => $referenceNumber,
            "redirectUrl" => [
                "success" => $redirectUrls['success'],
                "failure" => $redirectUrls['failure'],
                "cancel" => $redirectUrls['cancel']
            ],
            "buyer" => $buyer
        ];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                "accept: application/json",
                "authorization: Basic " . base64_encode($this->apiKey . ":" . $this->secretKey),
                "content-type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return ["error" => true, "message" => $err];
        } else {
            return ["error" => false, "data" => json_decode($response, true)];
        }
    }

    /**
     * Verify the status of a payment
     * 
     * @param string $checkoutId The checkout ID returned from the createCheckout method
     * @return array Response from the API
     */
    public function verifyPayment($checkoutId)
    {
        $url = $this->baseUrl . "/checkout/v1/checkouts/" . $checkoutId;

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "accept: application/json",
                "authorization: Basic " . base64_encode($this->apiKey . ":" . $this->secretKey)
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return ["error" => true, "message" => $err];
        } else {
            return ["error" => false, "data" => json_decode($response, true)];
        }
    }
}
