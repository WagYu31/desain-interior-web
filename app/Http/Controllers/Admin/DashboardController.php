<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Project;
use App\Models\TeamMember;
use App\Services\DelayRiskService;

class DashboardController extends Controller
{
    protected DelayRiskService $riskService;

    public function __construct(DelayRiskService $riskService)
    {
        $this->riskService = $riskService;
    }

    public function index()
    {
        // 1. Hitung data Pemesanan
        $pendingOrdersCount = Order::whereHas('latestDetail', function ($query) {
            $query->where('status', 'pending');
        })->count();
        
        $inProgressOrdersCount = Order::whereHas('latestDetail', function ($query) {
            $query->where('status', 'in_progress');
        })->count();
        
        $completedOrdersCount = Order::whereHas('latestDetail', function ($query) {
            $query->where('status', 'completed');
        })->count();
        
        $totalOrdersCount = Order::count();

        // 2. Hitung data lain yang dibutuhkan oleh view
        $totalProjectsCount = Project::count();
        $totalUsersCount = User::role('user')->count();
        $totalTeamMembersCount = TeamMember::count();
        
        // 3. Get risk analytics summary
        $riskSummary = $this->riskService->getRiskSummary();
        
        // 4. Recent orders (last 5)
        $recentOrders = Order::with(['user', 'latestDetail'])
            ->latest('order_date')
            ->take(5)
            ->get();
        
        return view('admin.dashboard', [
            'totalProjects' => $totalProjectsCount,
            'totalOrders' => $totalOrdersCount,
            'pendingOrders' => $pendingOrdersCount,
            'inProgressOrders' => $inProgressOrdersCount,
            'completedOrders' => $completedOrdersCount,
            'totalUsers' => $totalUsersCount,
            'totalTeamMembers' => $totalTeamMembersCount,
            'riskSummary' => $riskSummary,
            'recentOrders' => $recentOrders,
        ]);
    }
}