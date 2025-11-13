<?php

namespace App\Mail;

use App\Models\Coupon;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CouponExpiringMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public User $user,
        public Coupon $coupon
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Coupon "' . $this->coupon->code . '" Expires Soon!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $daysUntilExpiry = now()->diffInDays($this->coupon->valid_until);
        
        return new Content(
            view: 'emails.coupon-expiring',
            with: [
                'userName' => $this->user->name,
                'couponCode' => $this->coupon->code,
                'couponName' => $this->coupon->name,
                'discountType' => $this->coupon->discount_type,
                'discountValue' => $this->coupon->discount_value,
                'expiryDate' => $this->coupon->valid_until,
                'daysUntilExpiry' => $daysUntilExpiry,
                'minPurchase' => $this->coupon->min_order_amount,
                'usesRemaining' => $this->getUsesRemaining(),
            ],
        );
    }

    /**
     * Get remaining uses for the user.
     */
    private function getUsesRemaining(): ?int
    {
        if (!$this->coupon->uses_per_user) {
            return null; // Unlimited
        }

        $used = $this->coupon->usages()
            ->where('user_id', $this->user->id)
            ->count();

        return max(0, $this->coupon->uses_per_user - $used);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
