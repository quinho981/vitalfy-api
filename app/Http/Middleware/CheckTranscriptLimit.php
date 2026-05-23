<?php

namespace App\Http\Middleware;

use App\Models\Transcript;
use App\Support\PlanLimits;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTranscriptLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if($user->hasProPlan()) {
            return $next($request);
        }

        $currentMonthStart = now()->startOfMonth();
        $currentMonthEnd = now()->endOfMonth();
        $monthlyTranscriptCount = Transcript::fromUserBetweenDates($user->id, $currentMonthStart, $currentMonthEnd)->count();

        if ($monthlyTranscriptCount >= PlanLimits::FREE_MONTHLY_TRANSCRIPTS) {
            return response()->json([
                'success' => false,
                'message' => 'You have reached your monthly transcription limit.'
            ], 429);
        }

        return $next($request);
    }
}
