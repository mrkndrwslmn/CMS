<?php

namespace App\Mail;

use App\Models\Coupon;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CouponAssignedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public User $user,
        public Coupon $coupon,
        public ServiceRequest $serviceRequest
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Great News! A Discount Coupon Has Been Assigned to Your Request',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.coupon-assigned',
            with: [
                'userName' => $this->user->name,
                'couponCode' => $this->coupon->code,
                'couponName' => $this->coupon->name,
                'discountType' => $this->coupon->discount_type,
                'discountValue' => $this->coupon->discount_value,
                'minPurchase' => $this->coupon->min_order_amount,
                'validUntil' => $this->coupon->valid_until,
                'requestId' => $this->serviceRequest->id,
                'originalBudget' => $this->serviceRequest->approved_budget,
                'discountAmount' => $this->serviceRequest->coupon_discount_amount,
                'finalAmount' => $this->serviceRequest->approved_budget - $this->serviceRequest->coupon_discount_amount,
            ],
        );
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
