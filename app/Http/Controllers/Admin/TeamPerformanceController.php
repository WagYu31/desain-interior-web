<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TeamPerformanceService;
use Illuminate\Http\Request;

class TeamPerformanceController extends Controller
{
    protected TeamPerformanceService $performanceService;

    public function __construct(TeamPerformanceService $performanceService)
    {
        $this->performanceService = $performanceService;
    }

    /**
     * Display the team performance dashboard
     */
    public function index()
    {
        $teamPerformance = $this->performanceService->getAllTeamPerformance();
        $summary = $this->performanceService->getSummary();

        return view('admin.analytics.team-performance', compact('teamPerformance', 'summary'));
    }
}
