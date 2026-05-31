<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessGenerateInsightsAI;
use App\Models\Document;
use App\Services\DocumentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\LaravelPdf\Facades\Pdf;

class DocumentController extends Controller
{
    protected DocumentService $documentService;

    public function __construct(DocumentService $documentService)
    {
        $this->documentService = $documentService;
    }

    public function update(Document $document, Request $request): Document
    {
        $this->authorize('update', $document);

        $data = $request->all();
        $document->update($data);

        return $document;
    }

    public function generate(Request $request): JsonResponse
    {
        $document = $this->documentService->createDocumentAndDispatchInsights($request->all());

        return response()->json($document, 201);
    }

    public function regenerateInsights(Document $document): JsonResponse
    {
        $this->authorize('update', $document);

        $conversation = $document->transcript->conversation;

        ProcessGenerateInsightsAI::dispatch($document->id, $conversation);

        return response()->json([
            'message' => 'Insights regeneration started'
        ], 200);
    }

    public function refine(Request $request): JsonResponse
    {
        $refined = $this->documentService->refineDocument($request->all());

        return response()->json([
            'content' => $refined
        ], 200);
    }
     
    public function generatePdf(Document $document)
    {
        $this->authorize('update', $document);

        return Pdf::view('pdf.clinical_document', [
            'content' => $document->result,
            'patient_name' => $document->transcript->user->name,
            'template_name' => $document->documentTemplate->name,
            'created_at' => \Carbon\Carbon::parse($document->created_at)->format('d/m/Y H:i'),
        ])
        ->format('A4')
        ->name("document_{$document->id}.pdf")
        ->download();
    }
}
