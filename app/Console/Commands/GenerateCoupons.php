<?php

namespace App\Console\Commands;

use App\Models\Coupon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateCoupons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coupon:generate
                            {--prefix=COUPON : Prefix for coupon codes}
                            {--count=1 : Number of coupons to generate}
                            {--type=percentage : Discount type (percentage or fixed)}
                            {--value= : Discount value (required)}
                            {--min-purchase=0 : Minimum purchase amount}
                            {--max-discount= : Maximum discount amount (for percentage type)}
                            {--max-uses= : Maximum total uses (null = unlimited)}
                            {--max-uses-per-user=1 : Maximum uses per user}
                            {--days= : Valid for X days from now}
                            {--name= : Coupon name (optional)}
                            {--description= : Coupon description (optional)}
                            {--created-by=1 : Admin user ID who creates the coupons}
                            {--coupon-type=public : Coupon type (public, user_specific, request_specific)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate multiple coupon codes at once';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $prefix = strtoupper($this->option('prefix'));
        $count = (int) $this->option('count');
        $type = $this->option('type');
        $value = $this->option('value');
        $minPurchase = (float) $this->option('min-purchase');
        $maxDiscount = $this->option('max-discount') ? (float) $this->option('max-discount') : null;
        $maxUses = $this->option('max-uses') ? (int) $this->option('max-uses') : null;
        $maxUsesPerUser = (int) $this->option('max-uses-per-user');
        $days = $this->option('days') ? (int) $this->option('days') : null;
        $name = $this->option('name');
        $description = $this->option('description');
        $createdBy = (int) $this->option('created-by');
        $couponType = $this->option('coupon-type');

        // Validate required fields
        if (!$value) {
            $this->error('Discount value is required! Use --value=X');
            return 1;
        }

        if (!in_array($type, ['percentage', 'fixed'])) {
            $this->error('Type must be either "percentage" or "fixed"');
            return 1;
        }

        if (!in_array($couponType, ['public', 'user_specific', 'request_specific'])) {
            $this->error('Coupon type must be either "public", "user_specific", or "request_specific"');
            return 1;
        }

        if ($type === 'percentage' && ($value <= 0 || $value > 100)) {
            $this->error('Percentage value must be between 0 and 100');
            return 1;
        }

        if ($count <= 0 || $count > 1000) {
            $this->error('Count must be between 1 and 1000');
            return 1;
        }

        // Calculate valid_until if days provided
        $validUntil = $days ? now()->addDays($days) : null;

        // Generate coupons
        $this->info("Generating {$count} coupon(s)...");
        $progressBar = $this->output->createProgressBar($count);
        $progressBar->start();

        $generated = [];
        $failed = 0;

        for ($i = 0; $i < $count; $i++) {
            $attempts = 0;
            $maxAttempts = 10;
            
            while ($attempts < $maxAttempts) {
                // Generate unique code
                $code = $this->generateUniqueCode($prefix);
                
                // Check if code already exists
                if (!Coupon::where('code', $code)->exists()) {
                    try {
                        // Create coupon name if not provided
                        $couponName = $name ?? "{$prefix} Discount";
                        if ($count > 1 && !$name) {
                            $couponName .= " #" . ($i + 1);
                        }

                        $coupon = Coupon::create([
                            'code' => $code,
                            'name' => $couponName,
                            'description' => $description,
                            'discount_type' => $type,
                            'discount_value' => $value,
                            'min_purchase_amount' => $minPurchase,
                            'max_discount_amount' => $maxDiscount,
                            'max_total_uses' => $maxUses,
                            'max_uses_per_user' => $maxUsesPerUser,
                            'current_uses' => 0,
                            'valid_until' => $validUntil,
                            'status' => 'active',
                            'coupon_type' => $couponType,
                            'created_by' => $createdBy,
                            'stackable_with_loyalty_tier' => false,
                            'stackable_with_points' => false,
                        ]);

                        $generated[] = $coupon;
                        break;
                    } catch (\Exception $e) {
                        $this->error("\nFailed to create coupon: " . $e->getMessage());
                        $failed++;
                        break;
                    }
                }
                
                $attempts++;
            }

            if ($attempts >= $maxAttempts) {
                $this->error("\nFailed to generate unique code after {$maxAttempts} attempts");
                $failed++;
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        // Display results
        $this->info("✅ Successfully generated " . count($generated) . " coupon(s)");
        
        if ($failed > 0) {
            $this->warn("⚠️  Failed to generate {$failed} coupon(s)");
        }

        // Display summary table
        if (count($generated) > 0) {
            $this->newLine();
            $this->info('📋 Generated Coupons:');
            $this->newLine();

            $tableData = [];
            foreach ($generated as $coupon) {
                $tableData[] = [
                    $coupon->code,
                    $coupon->getDiscountLabel(),
                    $coupon->valid_until ? $coupon->valid_until->format('M d, Y') : 'No expiry',
                    $coupon->max_uses_per_user . ' per user',
                ];
            }

            $this->table(
                ['Code', 'Discount', 'Valid Until', 'Max Uses'],
                $tableData
            );

            // Display summary
            $this->newLine();
            $this->info('📊 Summary:');
            $this->line("  • Prefix: {$prefix}");
            $this->line("  • Type: " . ucfirst($type));
            $this->line("  • Value: {$value}" . ($type === 'percentage' ? '%' : ' PHP'));
            if ($minPurchase > 0) {
                $this->line("  • Min. Purchase: ₱" . number_format($minPurchase, 2));
            }
            if ($maxDiscount) {
                $this->line("  • Max. Discount: ₱" . number_format($maxDiscount, 2));
            }
            if ($validUntil) {
                $this->line("  • Valid Until: " . $validUntil->format('F d, Y'));
            }
            $this->line("  • Max Uses Per User: {$maxUsesPerUser}");
        }

        return 0;
    }

    /**
     * Generate a unique coupon code with the given prefix
     */
    private function generateUniqueCode(string $prefix): string
    {
        // Generate random alphanumeric string (excluding similar characters: 0, O, I, 1)
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $randomPart = '';
        
        for ($i = 0; $i < 6; $i++) {
            $randomPart .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $prefix . '-' . $randomPart;
    }
}
