<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;

class EmailSenderService
{
    /**
     * Available email sender types
     */
    public const SENDER_AUTH = 'auth';
    public const SENDER_PROJECTS = 'projects';
    public const SENDER_SUPPORT = 'support';
    public const SENDER_NOTIFICATIONS = 'notifications';
    public const SENDER_BILLING = 'billing';
    public const SENDER_DEFAULT = 'default';

    /**
     * Get sender configuration for specific email type
     */
    public function getSender(string $type = self::SENDER_DEFAULT): array
    {
        $senders = Config::get('mail.senders', []);
        
        // Return specific sender if exists, otherwise fallback to default
        return $senders[$type] ?? $senders[self::SENDER_DEFAULT] ?? [
            'address' => Config::get('mail.from.address'),
            'name' => Config::get('mail.from.name'),
        ];
    }

    /**
     * Get sender address for specific email type
     */
    public function getSenderAddress(string $type = self::SENDER_DEFAULT): string
    {
        $sender = $this->getSender($type);
        return $sender['address'];
    }

    /**
     * Get sender name for specific email type
     */
    public function getSenderName(string $type = self::SENDER_DEFAULT): string
    {
        $sender = $this->getSender($type);
        return $sender['name'];
    }

    /**
     * Get all available sender types
     */
    public function getAvailableSenders(): array
    {
        return [
            self::SENDER_AUTH,
            self::SENDER_PROJECTS,
            self::SENDER_SUPPORT,
            self::SENDER_NOTIFICATIONS,
            self::SENDER_BILLING,
            self::SENDER_DEFAULT,
        ];
    }

    /**
     * Get sender configuration with descriptive labels
     */
    public function getSendersWithLabels(): array
    {
        return [
            self::SENDER_AUTH => [
                'config' => $this->getSender(self::SENDER_AUTH),
                'label' => 'Authentication & Account Management',
                'description' => 'Login confirmations, password resets, account verifications',
            ],
            self::SENDER_PROJECTS => [
                'config' => $this->getSender(self::SENDER_PROJECTS),
                'label' => 'Project Communications',
                'description' => 'Project updates, milestones, deliverables, progress reports',
            ],
            self::SENDER_SUPPORT => [
                'config' => $this->getSender(self::SENDER_SUPPORT),
                'label' => 'Customer Support',
                'description' => 'Help requests, technical support, customer service',
            ],
            self::SENDER_NOTIFICATIONS => [
                'config' => $this->getSender(self::SENDER_NOTIFICATIONS),
                'label' => 'System Notifications',
                'description' => 'Automated alerts, system updates, status changes',
            ],
            self::SENDER_BILLING => [
                'config' => $this->getSender(self::SENDER_BILLING),
                'label' => 'Billing & Payments',
                'description' => 'Invoices, payment confirmations, billing notifications',
            ],
            self::SENDER_DEFAULT => [
                'config' => $this->getSender(self::SENDER_DEFAULT),
                'label' => 'General Communications',
                'description' => 'General emails, announcements, miscellaneous messages',
            ],
        ];
    }

    /**
     * Automatically determine sender type based on email content or class
     */
    public function autoDetectSender(string $mailClass): string
    {
        $className = class_basename($mailClass);
        
        // Authentication related emails
        if (str_contains($className, 'Password') || 
            str_contains($className, 'Login') || 
            str_contains($className, 'Verification') ||
            str_contains($className, 'Auth') ||
            str_contains($className, 'Credentials') ||
            str_contains($className, 'Account')) {
            return self::SENDER_AUTH;
        }

        // Project related emails
        if (str_contains($className, 'Project') || 
            str_contains($className, 'Milestone') || 
            str_contains($className, 'Completed') ||
            str_contains($className, 'Approved') ||
            str_contains($className, 'Revision')) {
            return self::SENDER_PROJECTS;
        }

        // Payment related emails
        if (str_contains($className, 'Payment') || 
            str_contains($className, 'Invoice') || 
            str_contains($className, 'Billing') ||
            str_contains($className, 'Confirmed')) {
            return self::SENDER_BILLING;
        }

        // Support related emails
        if (str_contains($className, 'Support') || 
            str_contains($className, 'Help') || 
            str_contains($className, 'Request') ||
            str_contains($className, 'Service')) {
            return self::SENDER_SUPPORT;
        }

        // Notification related emails
        if (str_contains($className, 'Notification') || 
            str_contains($className, 'Alert') || 
            str_contains($className, 'Update')) {
            return self::SENDER_NOTIFICATIONS;
        }

        // Default fallback
        return self::SENDER_DEFAULT;
    }

    /**
     * Validate that all required senders are configured
     */
    public function validateConfiguration(): array
    {
        $issues = [];
        $senders = Config::get('mail.senders', []);

        foreach ($this->getAvailableSenders() as $senderType) {
            if (!isset($senders[$senderType])) {
                $issues[] = "Missing sender configuration for: {$senderType}";
                continue;
            }

            $sender = $senders[$senderType];
            
            if (empty($sender['address'])) {
                $issues[] = "Missing email address for sender: {$senderType}";
            }

            if (empty($sender['name'])) {
                $issues[] = "Missing name for sender: {$senderType}";
            }

            // Validate email format
            if (!empty($sender['address']) && !filter_var($sender['address'], FILTER_VALIDATE_EMAIL)) {
                $issues[] = "Invalid email address for sender {$senderType}: {$sender['address']}";
            }
        }

        return $issues;
    }
}