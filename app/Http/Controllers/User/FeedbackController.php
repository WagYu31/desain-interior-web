<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CustomerFeedback;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    /**
     * Store a new feedback for a completed order
     */
    public function store(Request $request, Order $order)
    {
        // Verify user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Verify order is completed
        if ($order->latestDetail?->status !== 'completed') {
            return back()->with('error', 'Anda hanya bisa memberikan feedback untuk pesanan yang sudah selesai.');
        }

        // Check if feedback already exists
        if ($order->feedback) {
            return back()->with('error', 'Anda sudah memberikan feedback untuk pesanan ini.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
            'would_recommend' => 'nullable|boolean',
        ]);

        CustomerFeedback::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'rating' => $validated['rating'],
            'review' => $validated['review'] ?? null,
            'would_recommend' => $validated['would_recommend'] ?? true,
        ]);

        return back()->with('success', 'Terima kasih! Feedback Anda telah disimpan.');
    }
}
