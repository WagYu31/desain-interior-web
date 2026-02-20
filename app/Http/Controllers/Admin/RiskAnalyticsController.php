<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DelayRiskService;
use Illuminate\Http\Request;

class RiskAnalyticsController extends Controller
{
    protected DelayRiskService $riskService;

    public function __construct(DelayRiskService $riskService)
    {
        $this->riskService = $riskService;
    }

    /**
     * Display the risk analytics dashboard
     */
    public function index()
    {
        $ordersWithRisk = $this->riskService->getActiveOrdersWithRisk();
        $summary = $this->riskService->getRiskSummary();

        return view('admin.analytics.risk', compact('ordersWithRisk', 'summary'));
    }

    /**
     * Get risk scores as JSON for AJAX updates
     */
    public function getRiskScores()
    {
        $ordersWithRisk = $this->riskService->getActiveOrdersWithRisk();
        $summary = $this->riskService->getRiskSummary();

        return response()->json([
            'summary' => $summary,
            'orders' => $ordersWithRisk->map(function ($item) {
                return [
                    'id' => $item['order']->id,
                    'user_order_id' => $item['order']->user_order_id,
                    'customer_name' => $item['order']->contact_name,
                    'risk_score' => $item['risk_score'],
                    'risk_level' => $item['risk_level'],
                    'has_blockers' => $item['has_blockers'],
                    'detected_issues' => $item['detected_issues'],
                ];
            })->values(),
        ]);
    }
}
