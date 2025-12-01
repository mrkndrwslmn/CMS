<?php

namespace App\Services;

use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        try {
            $serviceAccountPath = config('firebase.service_account_path');
            
            if (!file_exists($serviceAccountPath)) {
                \Log::error('Firebase service account file not found: ' . $serviceAccountPath);
                $this->messaging = null;
                return;
            }
            
            $factory = (new Factory)->withServiceAccount($serviceAccountPath);
            $this->messaging = $factory->createMessaging();
        } catch (\Exception $e) {
            \Log::error('Failed to initialize Firebase: ' . $e->getMessage());
            $this->messaging = null;
        }
    }

    /**
     * Send push notification to a single user
     */
    public function sendToUser(User $user, array $data, array $notification = null): bool
    {
        if (!$user->fcm_token) {
            Log::warning("User {$user->id} has no FCM token");
            return false;
        }

        return $this->send($user->fcm_token, $data, $notification);
    }

    /**
     * Send push notification to multiple users
     */
    public function sendToUsers(array $users, array $data, array $notification = null): array
    {
        $results = [];
        
        foreach ($users as $user) {
            if ($user instanceof User && $user->fcm_token) {
                $results[$user->id] = $this->send($user->fcm_token, $data, $notification);
            }
        }

        return $results;
    }

    /**
     * Send new message notification
     */
    public function sendNewMessageNotification(Message $message): bool
    {
        $recipient = $message->recipient;
        
        if (!$recipient || !$recipient->fcm_token) {
            return false;
        }

        $sender = $message->sender;
        $project = $message->project;
        
        $notification = [
            'title' => 'New Message',
            'body' => "{$sender->fullName}: " . \Str::limit($message->message, 100),
        ];

        $data = [
            'type' => 'new_message',
            'message_id' => (string) $message->id,
            'project_id' => (string) $message->project_id,
            'conversation_id' => (string) $message->conversation_id,
            'sender_id' => (string) $sender->id,
            'sender_name' => $sender->fullName,
            'project_title' => $project ? $project->title : 'Unknown Project',
            'role' => $recipient->role,
            'click_action' => "/{$recipient->role}/messages/{$message->project_id}",
        ];

        return $this->sendToUser($recipient, $data, $notification);
    }

    /**
     * Send group chat message notification
     */
    public function sendGroupChatNotification(Message $message, User $recipient): bool
    {
        if (!$message->groupChat) {
            Log::warning('Message does not belong to a group chat');
            return false;
        }

        $sender = $message->sender;
        $project = $message->project;
        $groupChat = $message->groupChat;

        $notification = [
            'title' => "{$sender->fullName} (Group: {$groupChat->getDisplayName()})",
            'body' => substr($message->message, 0, 100),
        ];

        $data = [
            'type' => 'group_chat_message',
            'group_chat_id' => (string) $message->group_chat_id,
            'message_id' => (string) $message->id,
            'sender_id' => (string) $sender->id,
            'sender_name' => $sender->fullName,
            'project_id' => (string) $message->project_id,
            'project_title' => $project ? $project->title : 'Unknown Project',
            'role' => $recipient->role,
            'click_action' => "/{$recipient->role}/group-chats/{$message->group_chat_id}",
        ];

        return $this->sendToUser($recipient, $data, $notification);
    }

    /**
     * Send push notification using Kreait Firebase SDK
     */
    protected function send(string $token, array $data, array $notification = null): bool
    {
        if (!$this->messaging) {
            Log::warning('Firebase messaging not initialized, skipping notification');
            return false;
        }
        
        try {
            // Build the message
            $messageBuilder = CloudMessage::withTarget('token', $token)
                ->withData($data);

            // Add notification if provided
            if ($notification) {
                $messageBuilder = $messageBuilder->withNotification(
                    Notification::create(
                        $notification['title'] ?? 'Notification',
                        $notification['body'] ?? ''
                    )
                );
            }

            $message = $messageBuilder;

            // Send the message
            $this->messaging->send($message);

            Log::info('Firebase notification sent successfully', [
                'token' => substr($token, 0, 20) . '...',
                'notification' => $notification,
            ]);

            return true;

        } catch (\Kreait\Firebase\Exception\MessagingException $e) {
            Log::error('Firebase messaging error: ' . $e->getMessage());
            return false;
        } catch (\Exception $e) {
            Log::error('Firebase send failed: ' . $e->getMessage());
            return false;
        }
    }
}
