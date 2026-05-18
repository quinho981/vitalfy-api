<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Transcript;
use App\Support\PlanLimits;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show(): JsonResponse
    {
        $user = Auth::user();
        $remainingTranscripts = null;
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $plan = $user->plan();

        if (!$user->hasProPlan()) {
            $transcriptsUsed = Transcript::fromUserBetweenDates($user->id, $startOfMonth, $endOfMonth)->withTrashed()->count();
            $remainingTranscripts = PlanLimits::FREE_MONTHLY_TRANSCRIPTS - $transcriptsUsed;
        }
        
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
            ],
            'plan' => $plan,
            'remaining' => $remainingTranscripts
        ]);
    }

    public function update(UpdateUserRequest $request)
    {
        $user = $request->user();

        $user->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
        ], 200);
    }
}
