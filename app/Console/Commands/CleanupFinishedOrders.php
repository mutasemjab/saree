<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class CleanupFinishedOrders extends Command
{
    protected $signature = 'orders:cleanup-finished {--hours=1 : Hours threshold for finished orders}';
    protected $description = 'Remove finished orders (completed/cancelled) from Firestore that are older than specified hours';

    protected $projectId;
    protected $baseUrl;

    public function __construct()
    {
        parent::__construct();
        $this->projectId = config('firebase.project_id');
        $this->baseUrl = "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents";
    }

    public function handle()
    {
        $hoursThreshold = $this->option('hours');
        $this->info("Starting cleanup of finished orders older than {$hoursThreshold} hour(s) from Firestore...");

        $thresholdTime = Carbon::now()->subHours($hoursThreshold);

        // Get finished orders (only needed fields)
        $finishedOrders = Order::select('id', 'order_status', 'updated_at')
            ->whereIn('order_status', [4, 5, 6, 7]) // Delivered, Cancelled by user, Cancelled by driver, No drivers
            ->where('updated_at', '<=', $thresholdTime)
            ->get();

        $totalOrders = $finishedOrders->count();

        if ($totalOrders === 0) {
            $this->info('No finished orders found to cleanup from Firestore.');
            Log::info('CleanupFinishedOrders: No orders found to cleanup.');
            return Command::SUCCESS;
        }

        $this->info("Found {$totalOrders} finished order(s) to remove from Firestore");

        // Show breakdown by status
        $byStatus = $finishedOrders->groupBy('order_status');
        $statusNames = [
            4 => 'Delivered',
            5 => 'Cancelled by user',
            6 => 'Cancelled by driver',
            7 => 'No drivers available'
        ];

        $this->info("\nBreakdown by status:");
        foreach ($byStatus as $status => $orders) {
            $statusName = $statusNames[$status] ?? "Status {$status}";
            $this->info("  - {$statusName}: {$orders->count()} orders");
        }
        $this->newLine();

        $successCount = 0;
        $failCount = 0;
        $notFoundCount = 0;

        $progressBar = $this->output->createProgressBar($totalOrders);
        $progressBar->start();

        foreach ($finishedOrders as $order) {
            try {
                $removed = $this->removeOrderFromFirestore($order->id);

                if ($removed === 'not_found') {
                    $notFoundCount++;
                } elseif ($removed) {
                    $successCount++;
                } else {
                    $failCount++;
                }

                $progressBar->advance();

            } catch (\Exception $e) {
                $failCount++;
                Log::error("Failed to remove order #{$order->id} from Firestore", [
                    'order_id' => $order->id,
                    'status' => $order->order_status,
                    'error' => $e->getMessage()
                ]);

                $progressBar->advance();
            }
        }

        $progressBar->finish();
        $this->newLine(2);

        // Summary
        $this->info("=== Cleanup Summary ===");
        $this->info("Total orders processed: {$totalOrders}");
        $this->info("Successfully removed: {$successCount}");
        $this->info("Not found (already removed): {$notFoundCount}");
        $this->info("Failed: {$failCount}");

        Log::info('CleanupFinishedOrders completed', [
            'total' => $totalOrders,
            'success' => $successCount,
            'not_found' => $notFoundCount,
            'failed' => $failCount,
            'hours_threshold' => $hoursThreshold,
            'by_status' => $byStatus->map->count()->toArray()
        ]);

        return Command::SUCCESS;
    }

    /**
     * Remove order from Firestore using REST API
     */
    private function removeOrderFromFirestore($orderId)
    {
        try {
            // Check if document exists
            $getResponse = Http::timeout(10)->get(
                "{$this->baseUrl}/ride_requests/{$orderId}"
            );

            if ($getResponse->status() === 404) {
                return 'not_found';
            }

            // Delete the document
            $deleteResponse = Http::timeout(10)->delete(
                "{$this->baseUrl}/ride_requests/{$orderId}"
            );

            if ($deleteResponse->successful()) {
                Log::info("Order #{$orderId} removed from Firestore");
                return true;
            } else {
                Log::error("Failed to delete order #{$orderId} from Firestore", [
                    'status' => $deleteResponse->status(),
                    'body' => $deleteResponse->body()
                ]);
                return false;
            }

        } catch (\Exception $e) {
            Log::error("Error removing order #{$orderId} from Firestore: " . $e->getMessage());
            return false;
        }
    }
}
