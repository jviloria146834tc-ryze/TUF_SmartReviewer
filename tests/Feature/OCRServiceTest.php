<?php

namespace Tests\Feature;

use App\Services\OCRService;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class OCRServiceTest extends TestCase
{
    public function test_ocr_service_handles_error_response(): void
    {
        // Mocking Gemini is complex without a dedicated fake,
        // but we can at least test that the service handles empty API keys or missing files.
        Config::set('gemini.api_key', '');

        $service = new OCRService;
        $result = $service->processMaterial(['non_existent_file.pdf']);

        $this->assertArrayHasKey('error', $result);
    }

    public function test_ocr_service_path_logic(): void
    {
        // This just verifies the service exists and can be instantiated
        $service = app(OCRService::class);
        $this->assertInstanceOf(OCRService::class, $service);
    }
}
