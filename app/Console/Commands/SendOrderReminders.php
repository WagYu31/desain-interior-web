<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendOrderReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder notifications for orders that need attention';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for orders that need reminders...');

        // Get active orders
        $orders = Order::with(['latestDetail', 'user'])
            ->whereHas('latestDetail', function ($query) {
                $query->whereIn('status', ['pending', 'in_progress']);
            })
            ->get();

        $remindersCount = 0;
        $deadlineWarningsCount = 0;

        foreach ($orders as $order) {
            $shouldNotify = false;
            $reasons = [];

            // Check 1: No update in >= 3 days
            if ($order->needs_reminder) {
                $reasons[] = 'Tidak ada update selama ' . $order->days_since_last_update . ' hari';
                $shouldNotify = true;
            }

            // Check 2: Deadline approaching (within 7 days)
            if ($order->deadline_date) {
                $daysUntilDeadline = now()->diffInDays($order->deadline_date, false);
                
                if ($daysUntilDeadline >= 0 && $daysUntilDeadline <= 7) {
                    $reasons[] = 'Deadline dalam ' . $daysUntilDeadline . ' hari';
                    $shouldNotify = true;
                    $deadlineWarningsCount++;
                } elseif ($daysUntilDeadline < 0) {
                    $reasons[] = 'Sudah melewati deadline ' . abs($daysUntilDeadline) . ' hari';
                    $shouldNotify = true;
                    $deadlineWarningsCount++;
                }
            }

            // Check 3: Budget warning
            if ($order->is_budget_warning) {
                $reasons[] = 'Budget sudah terpakai ' . $order->budget_usage_percent . '%';
                $shouldNotify = true;
            }

            // Send notification if needed and not sent recently
            if ($shouldNotify) {
                $lastReminder = $order->last_reminder_sent_at;
                
                // Only send if no reminder in last 24 hours
                if (!$lastReminder || $lastReminder->diffInHours(now()) >= 24) {
                    // Get admin users to notify
                    $admins = User::role(['admin', 'owner'])->get();
                    
                    // Create notification data (for database notification)
                    foreach ($admins as $admin) {
                        $admin->notify(new \App\Notifications\OrderReminderNotification($order, $reasons));
                    }

                    // Update last reminder timestamp
                    $order->update(['last_reminder_sent_at' => now()]);
                    
                    $remindersCount++;
                    $this->line("  → Reminder sent for Order #{$order->id} ({$order->contact_name})");
                }
            }
        }

        $this->info("Completed! Sent {$remindersCount} reminders, {$deadlineWarningsCount} deadline warnings.");
        
        return Command::SUCCESS;
    }
}
