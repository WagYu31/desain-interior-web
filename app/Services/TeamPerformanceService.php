<?php

namespace App\Services;

use App\Models\TeamMember;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\CustomerFeedback;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TeamPerformanceService
{
    /**
     * Get performance statistics for all team members
     */
    public function getAllTeamPerformance(): Collection
    {
        $teamMembers = TeamMember::all();
        
        return $teamMembers->map(function ($member) {
            return $this->getTeamMemberPerformance($member);
        })->sortByDesc('total_projects');
    }

    /**
     * Get performance statistics for a single team member
     */
    public function getTeamMemberPerformance(TeamMember $member): array
    {
        // Get all order details where this team member was assigned
        // Note: HTML form selects store IDs as strings in JSON (e.g. ["9","10"]),
        // so we need to search for both string and integer versions
        $assignedDetails = OrderDetail::where(function ($query) use ($member) {
            $query->whereJsonContains('team_member_ids', (string) $member->id)
                  ->orWhereJsonContains('team_member_ids', $member->id);
        })->get();
        
        // Get unique orders
        $orderIds = $assignedDetails->pluck('order_id')->unique();
        $orders = Order::with(['details', 'latestDetail', 'feedback'])
            ->whereIn('id', $orderIds)
            ->get();

        // Calculate statistics
        $totalProjects = $orders->count();
        $completedProjects = $orders->filter(fn($o) => $o->latestDetail?->status === 'completed')->count();
        $inProgressProjects = $orders->filter(fn($o) => $o->latestDetail?->status === 'in_progress')->count();
        
        // Calculate average completion time (for completed orders)
        $completionTimes = [];
        foreach ($orders->filter(fn($o) => $o->latestDetail?->status === 'completed') as $order) {
            $startDetail = $order->details->where('status', 'in_progress')->sortBy('created_at')->first();
            $endDetail = $order->details->where('status', 'completed')->first();
            
            if ($startDetail && $endDetail) {
                $completionTimes[] = $startDetail->created_at->diffInDays($endDetail->created_at);
            }
        }
        $avgCompletionDays = count($completionTimes) > 0 
            ? round(array_sum($completionTimes) / count($completionTimes), 1) 
            : null;

        // Calculate customer satisfaction (average rating from feedbacks)
        $feedbacks = CustomerFeedback::whereIn('order_id', $orderIds)->get();
        $avgRating = $feedbacks->count() > 0 
            ? round($feedbacks->avg('rating'), 1) 
            : null;
        $totalFeedbacks = $feedbacks->count();

        // Calculate on-time delivery rate
        $ordersWithDeadline = $orders->whereNotNull('deadline_date');
        $onTimeDeliveries = 0;
        foreach ($ordersWithDeadline->filter(fn($o) => $o->latestDetail?->status === 'completed') as $order) {
            $completedDetail = $order->details->where('status', 'completed')->first();
            if ($completedDetail && $completedDetail->created_at->lte($order->deadline_date)) {
                $onTimeDeliveries++;
            }
        }
        $onTimeRate = $ordersWithDeadline->count() > 0 
            ? round(($onTimeDeliveries / $ordersWithDeadline->count()) * 100, 1) 
            : null;

        return [
            'member' => $member,
            'total_projects' => $totalProjects,
            'completed_projects' => $completedProjects,
            'in_progress_projects' => $inProgressProjects,
            'avg_completion_days' => $avgCompletionDays,
            'avg_rating' => $avgRating,
            'total_feedbacks' => $totalFeedbacks,
            'on_time_rate' => $onTimeRate,
        ];
    }

    /**
     * Get summary statistics
     */
    public function getSummary(): array
    {
        $allPerformance = $this->getAllTeamPerformance();
        
        $topPerformer = $allPerformance->sortByDesc('completed_projects')->first();
        $highestRated = $allPerformance->whereNotNull('avg_rating')->sortByDesc('avg_rating')->first();
        
        return [
            'total_team_members' => $allPerformance->count(),
            'total_active_projects' => $allPerformance->sum('in_progress_projects'),
            'total_completed_projects' => $allPerformance->sum('completed_projects'),
            'top_performer' => $topPerformer,
            'highest_rated' => $highestRated,
        ];
    }
}
