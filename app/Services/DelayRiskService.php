<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class DelayRiskService
{
    /**
     * Negative sentiment keywords in Indonesian for detecting potential blockers
     */
    private array $negativeKeywords = [
        // Material issues
        'habis', 'kosong', 'tunggu material', 'belum datang', 'material belum',
        // Weather issues  
        'hujan', 'banjir', 'cuaca buruk', 'cuaca',
        // Damage issues
        'rusak', 'pecah', 'retak', 'bocor', 'cacat',
        // Delay indicators
        'delay', 'terlambat', 'tertunda', 'belum selesai', 'mundur',
        // General blockers
        'kendala', 'masalah', 'hambatan', 'stuck', 'macet', 'berhenti',
        // Waiting
        'menunggu', 'tunggu', 'pending', 'hold'
    ];

    /**
     * Get all active orders with calculated risk scores
     */
    public function getActiveOrdersWithRisk(): Collection
    {
        $activeOrders = Order::with(['details', 'latestDetail', 'user'])
            ->whereHas('latestDetail', function ($query) {
                $query->whereIn('status', ['pending', 'in_progress']);
            })
            ->get();

        return $activeOrders->map(function ($order) {
            $riskData = $this->calculateRiskScore($order);
            $sentimentData = $this->analyzeSentiment($order);
            
            return [
                'order' => $order,
                'risk_score' => $riskData['score'],
                'risk_level' => $riskData['level'],
                'risk_factors' => $riskData['factors'],
                'sentiment' => $sentimentData['sentiment'],
                'detected_issues' => $sentimentData['issues'],
                'has_blockers' => $sentimentData['has_blockers'],
            ];
        })->sortByDesc('risk_score');
    }

    /**
     * Calculate delay risk score for a single order
     */
    public function calculateRiskScore(Order $order): array
    {
        $factors = [];
        $score = 0;

        // Factor 1: Update Frequency Gap (40% weight)
        $updateGapFactor = $this->calculateUpdateGapFactor($order);
        $score += $updateGapFactor['value'] * 0.4;
        $factors[] = $updateGapFactor;

        // Factor 2: Status Duration (40% weight)
        $durationFactor = $this->calculateDurationFactor($order);
        $score += $durationFactor['value'] * 0.4;
        $factors[] = $durationFactor;

        // Factor 3: Project Complexity (20% weight)
        $complexityFactor = $this->calculateComplexityFactor($order);
        $score += $complexityFactor['value'] * 0.2;
        $factors[] = $complexityFactor;

        // Determine risk level
        $level = match (true) {
            $score >= 2.0 => 'high',
            $score >= 1.2 => 'medium',
            default => 'low'
        };

        return [
            'score' => round($score, 2),
            'level' => $level,
            'factors' => $factors,
        ];
    }

    /**
     * Calculate update frequency gap factor
     */
    private function calculateUpdateGapFactor(Order $order): array
    {
        $details = $order->details;
        
        if ($details->count() < 2) {
            // Not enough data, check days since order creation
            $daysSinceCreation = $order->created_at->diffInDays(now());
            $value = min($daysSinceCreation / 7, 3.0); // Expect update within 7 days
            
            return [
                'name' => 'Frekuensi Update',
                'value' => $value,
                'description' => $daysSinceCreation > 7 
                    ? "Belum ada update sejak {$daysSinceCreation} hari" 
                    : "Order baru, belum cukup data"
            ];
        }

        // Calculate average days between updates
        $updateDates = $details->pluck('created_at')->sort();
        $gaps = [];
        
        for ($i = 1; $i < $updateDates->count(); $i++) {
            $gaps[] = $updateDates[$i]->diffInDays($updateDates[$i - 1]);
        }
        
        $avgGap = count($gaps) > 0 ? array_sum($gaps) / count($gaps) : 3;
        $avgGap = max($avgGap, 1); // Minimum 1 day average
        
        // Days since last update
        $daysSinceLastUpdate = $order->latestDetail->created_at->diffInDays(now());
        
        // Factor = current gap / average gap (capped at 3.0)
        $value = min($daysSinceLastUpdate / $avgGap, 3.0);
        
        $description = $daysSinceLastUpdate > $avgGap 
            ? "Update melambat: {$daysSinceLastUpdate} hari (biasanya " . round($avgGap) . " hari)"
            : "Update normal: {$daysSinceLastUpdate} hari";

        return [
            'name' => 'Frekuensi Update',
            'value' => round($value, 2),
            'description' => $description
        ];
    }

    /**
     * Calculate status duration factor
     */
    private function calculateDurationFactor(Order $order): array
    {
        // Find when order entered in_progress status
        $inProgressDetail = $order->details
            ->where('status', 'in_progress')
            ->sortBy('created_at')
            ->first();

        if (!$inProgressDetail) {
            // Still pending
            $daysPending = $order->created_at->diffInDays(now());
            $value = min($daysPending / 14, 2.0); // Expect to start within 14 days
            
            return [
                'name' => 'Durasi Status',
                'value' => $value,
                'description' => "Masih pending selama {$daysPending} hari"
            ];
        }

        $daysInProgress = $inProgressDetail->created_at->diffInDays(now());
        
        // Expected duration based on project type (default 30 days)
        $expectedDuration = 30;
        
        // Adjust based on room count if available
        if ($order->room_count) {
            $rooms = (int) filter_var($order->room_count, FILTER_SANITIZE_NUMBER_INT);
            $expectedDuration += ($rooms * 5); // Add 5 days per room
        }

        $value = min($daysInProgress / $expectedDuration, 2.0);
        
        $description = $daysInProgress > $expectedDuration
            ? "Melebihi estimasi: {$daysInProgress}/{$expectedDuration} hari"
            : "Dalam estimasi: {$daysInProgress}/{$expectedDuration} hari";

        return [
            'name' => 'Durasi Status',
            'value' => round($value, 2),
            'description' => $description
        ];
    }

    /**
     * Calculate project complexity factor
     */
    private function calculateComplexityFactor(Order $order): array
    {
        $complexityPoints = 0;
        
        // Room count complexity
        if ($order->room_count) {
            $rooms = (int) filter_var($order->room_count, FILTER_SANITIZE_NUMBER_INT);
            $complexityPoints += min($rooms / 5, 1); // 5 rooms = 1 point
        }
        
        // Design type complexity
        $designTypes = is_array($order->design_type) ? count($order->design_type) : 1;
        $complexityPoints += min($designTypes / 3, 1); // 3 types = 1 point
        
        // Client type complexity
        if ($order->client_type === 'corporate') {
            $complexityPoints += 0.5;
        }

        $value = min($complexityPoints, 2.0);
        
        return [
            'name' => 'Kompleksitas Proyek',
            'value' => round($value, 2),
            'description' => match (true) {
                $value >= 1.5 => 'Kompleksitas tinggi',
                $value >= 0.8 => 'Kompleksitas sedang',
                default => 'Kompleksitas rendah'
            }
        ];
    }

    /**
     * Analyze sentiment of all progress notes
     */
    public function analyzeSentiment(Order $order): array
    {
        $allNotes = $order->details
            ->pluck('progress_description')
            ->filter()
            ->toArray();

        $detectedIssues = [];
        $hasBlockers = false;

        foreach ($allNotes as $note) {
            $issues = $this->detectIssuesInNote($note);
            if (!empty($issues)) {
                $hasBlockers = true;
                $detectedIssues = array_merge($detectedIssues, $issues);
            }
        }

        // Remove duplicates
        $detectedIssues = array_unique($detectedIssues);

        $sentiment = match (true) {
            count($detectedIssues) >= 3 => 'negative',
            count($detectedIssues) >= 1 => 'warning',
            default => 'positive'
        };

        return [
            'sentiment' => $sentiment,
            'issues' => $detectedIssues,
            'has_blockers' => $hasBlockers,
        ];
    }

    /**
     * Detect issues in a single note
     */
    private function detectIssuesInNote(string $note): array
    {
        $noteLower = strtolower($note);
        $foundIssues = [];

        foreach ($this->negativeKeywords as $keyword) {
            if (str_contains($noteLower, strtolower($keyword))) {
                $foundIssues[] = $this->categorizeKeyword($keyword);
            }
        }

        return array_unique($foundIssues);
    }

    /**
     * Categorize keyword into issue type
     */
    private function categorizeKeyword(string $keyword): string
    {
        $categories = [
            'Kendala Material' => ['habis', 'kosong', 'tunggu material', 'belum datang', 'material belum'],
            'Kendala Cuaca' => ['hujan', 'banjir', 'cuaca buruk', 'cuaca'],
            'Kerusakan/Cacat' => ['rusak', 'pecah', 'retak', 'bocor', 'cacat'],
            'Keterlambatan' => ['delay', 'terlambat', 'tertunda', 'belum selesai', 'mundur'],
            'Hambatan Lain' => ['kendala', 'masalah', 'hambatan', 'stuck', 'macet', 'berhenti'],
            'Menunggu' => ['menunggu', 'tunggu', 'pending', 'hold'],
        ];

        foreach ($categories as $category => $keywords) {
            if (in_array(strtolower($keyword), array_map('strtolower', $keywords))) {
                return $category;
            }
        }

        return 'Lainnya';
    }

    /**
     * Get summary statistics for dashboard
     */
    public function getRiskSummary(): array
    {
        $ordersWithRisk = $this->getActiveOrdersWithRisk();

        return [
            'total_active' => $ordersWithRisk->count(),
            'high_risk' => $ordersWithRisk->where('risk_level', 'high')->count(),
            'medium_risk' => $ordersWithRisk->where('risk_level', 'medium')->count(),
            'low_risk' => $ordersWithRisk->where('risk_level', 'low')->count(),
            'with_blockers' => $ordersWithRisk->where('has_blockers', true)->count(),
        ];
    }
}
