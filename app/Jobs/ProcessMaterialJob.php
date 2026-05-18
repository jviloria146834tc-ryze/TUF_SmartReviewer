<?php

namespace App\Jobs;

use App\Models\Material;
use App\Services\OCRService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessMaterialJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300; // 5 minutes for OCR

    public function __construct(
        public int $materialId,
        public array $fullPaths,
        public string $fileHash,
        public int $userId,
        public string $originalName
    ) {}

    public function handle(OCRService $ocrService): void
    {
        $material = Material::find($this->materialId);
        
        if (!$material) {
            return;
        }

        try {
            $data = $ocrService->processMaterial($this->fullPaths, $this->fileHash, $this->userId);

            if (isset($data['error'])) {
                $material->update(['status' => 'failed']);
                return;
            }

            $material->update([
                'title' => $data['title'] ?? $this->originalName,
                'source_name' => $this->originalName,
                'summary' => $data['summary'] ?? 'No summary available.',
                'concepts' => $data['concepts'] ?? [],
                'raw_content' => $data['raw_content'] ?? null,
                'status' => 'completed',
            ]);
        } catch (\Exception $e) {
            $material->update(['status' => 'failed']);
            throw $e;
        }
    }
}
