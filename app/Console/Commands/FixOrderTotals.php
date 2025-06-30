<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;

class FixOrderTotals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:fix-totals {--dry-run : Preview changes without applying them}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and fix order totals by recalculating from order items';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        $this->info('Checking order totals...');

        $orders = Order::with('orderItems')->get();
        $fixed = 0;
        $total = $orders->count();

        $progressBar = $this->output->createProgressBar($total);
        $progressBar->start();

        foreach ($orders as $order) {
            $calculatedTotal = $order->orderItems->sum('price');
            $currentTotal = $order->total_price;
            $discrepancy = abs($calculatedTotal - $currentTotal);

            if ($discrepancy > 0.01) { // إذا كان الفرق أكبر من قرش واحد
                $this->newLine();
                $this->warn("Order #{$order->id}: Current total: {$currentTotal}, Calculated: {$calculatedTotal}, Discrepancy: {$discrepancy}");

                if (!$dryRun) {
                    $order->update(['total_price' => $calculatedTotal]);
                    $this->info("✓ Fixed Order #{$order->id}");
                    $fixed++;
                } else {
                    $this->comment("Would fix Order #{$order->id}");
                    $fixed++;
                }
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        if ($fixed > 0) {
            if ($dryRun) {
                $this->info("Found {$fixed} orders with incorrect totals (use --no-dry-run to fix them)");
            } else {
                $this->info("Successfully fixed {$fixed} orders");
            }
        } else {
            $this->info("All order totals are correct! ✓");
        }

        return 0;
    }
}
