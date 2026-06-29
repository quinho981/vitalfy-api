<?php

use App\Enums\PriceIdsEnum;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\DocumentTemplateController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TranscriptController;
use App\Http\Controllers\TranscriptTypesController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Laravel\Cashier\Http\Controllers\WebhookController;

Route::middleware('throttle:auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink']);
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);
});

Route::post('/stripe/webhook', [WebhookController::class, 'handleWebhook']);

Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware('signed')
    ->name('verification.verify');

Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);

Route::middleware([
    'auth:sanctum', 
    'throttle:api'
])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/email/resend-verification', [EmailVerificationController::class, 'resend'])
        ->middleware('throttle:6,1');
    Route::post('/change-password', [AuthController::class, 'changePassword']);

    Route::get('/tokens', [AuthController::class, 'tokens']);
    Route::delete('/tokens/{id}', [AuthController::class, 'revokeToken']);

    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'show']);
        Route::put('/', [UserController::class, 'update']);
    });
    
    Route::prefix('documents')->group(function () {
        Route::post('/generate', [DocumentController::class, 'generate']);
        Route::post('/refine', [DocumentController::class, 'refine'])
            ->middleware('check.subscription');
        Route::post('/{document}/regenerate-insights', [DocumentController::class, 'regenerateInsights']);
        Route::put('/{document}', [DocumentController::class, 'update']);
        Route::get('/{document}/pdf', [DocumentController::class, 'generatePdf']);
    });
    Route::get('user/transcripts', [TranscriptController::class, 'indexByUser']);

    Route::prefix('transcripts')->group(function () {
        Route::middleware([
            'free.transcript.limit', 
            'throttle:transcripts'
        ])->group(function () {
            Route::post('/', [TranscriptController::class, 'store']);
            Route::post('/generate-document', [TranscriptController::class, 'storeAndGenerateDocument']);
        });
        Route::get('/user/filter', [TranscriptController::class, 'filterUserTranscripts']);
        Route::put('/{transcript}', [TranscriptController::class, 'update']);
        Route::get('/{transcript}', [TranscriptController::class, 'show']);
        Route::get('/{transcript}/conversations', [TranscriptController::class, 'getConversations']);
        Route::delete('/{transcript}', [TranscriptController::class, 'delete']);
    });

    Route::prefix('templates')->group(function () {
        Route::get('/', [DocumentTemplateController::class, 'index']);
        Route::get('/minimal', [DocumentTemplateController::class, 'listIdNameTemplate']);
        Route::get('/with-documents-count', [DocumentTemplateController::class, 'listTemplatesWithUserDocumentsCount']);
        Route::get('/count-categories', [DocumentTemplateController::class, 'listCountCategories']);
    });
    Route::get('transcript-types', [TranscriptTypesController::class, 'index']);
    Route::get('transcript-types/minimal', [TranscriptTypesController::class, 'listMinimal']);
    
    Route::prefix('dashboard')->group(function () {
        Route::get('/summary', [TranscriptController::class, 'getDashboardSummary']);
        Route::get('/charts', [TranscriptController::class, 'getDashboardCharts']);
        Route::get('/last-transcripts', [TranscriptController::class, 'getlatestRecentTranscripts']);
    });

    Route::prefix('subscription')->group(function () {
        Route::get('/', [SubscriptionController::class, 'index']);
        Route::post('/checkout', [SubscriptionController::class, 'checkout']);
        Route::post('/cancel', [SubscriptionController::class, 'cancel']);
        Route::get('/verify-checkout', [SubscriptionController::class, 'verifyCheckout']);
    });
});

Route::middleware('throttle:stream')->get('/stream/insights-ai/{documentId}', function (string $documentId) {
    return response()->stream(function () use ($documentId) {

        $timeout = 15;
        $start = time();
        while (true) {
            $response = Cache::pull("insights_ai_{$documentId}"); // pega e apaga

            if ($response) {
                echo "data: " . json_encode($response) . "\n\n";
                ob_flush();
                flush();
                break; // encerra a conexão após enviar
            }

            if ((time() - $start) > $timeout) {
                echo "event: timeout\n";
                echo "data: {}\n\n";
                ob_flush();
                flush();
                break;
            }

            sleep(1);
        }
    }, 200, [
        'Content-Type' => 'text/event-stream',
        'Cache-Control' => 'no-cache',
        'Connection' => 'keep-alive',
    ]);
});
