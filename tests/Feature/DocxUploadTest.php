<?php

namespace Tests\Feature;

use App\Models\Material;
use App\Models\User;
use App\Services\OCRService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery\MockInterface;
use Tests\TestCase;

class DocxUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();
    }

    public function test_material_can_be_uploaded_as_docx(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        // Create a dummy DOCX file
        $tempFile = tempnam(sys_get_temp_dir(), 'test_docx');
        $zip = new \ZipArchive;
        $zip->open($tempFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $zip->addFromString('word/document.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"><w:body><w:p><w:r><w:t>This is a test DOCX content.</w:t></w:r></w:p></w:body></w:document>');
        $zip->close();

        $file = new UploadedFile($tempFile, 'test.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', null, true);

        $this->mock(OCRService::class, function (MockInterface $mock) {
            $mock->shouldReceive('processMaterial')
                ->once()
                ->andReturn([
                    'title' => 'DOCX Title',
                    'summary' => '### Overview\nDOCX Summary',
                    'concepts' => [],
                    'raw_content' => 'This is a test DOCX content.',
                ]);
        });

        $response = $this->actingAs($user)->postJson(route('materials.store'), [
            'files' => [$file],
        ]);

        unlink($tempFile);

        $response->assertStatus(200);

        $material = Material::where('user_id', $user->id)->first();
        $this->assertNotNull($material);
        $this->assertEquals('DOCX Title', $material->title);
        $this->assertEquals('completed', $material->status);
    }

    public function test_docx_extraction_logic(): void
    {
        $service = new class extends OCRService
        {
            public function test_extract(string $path)
            {
                return $this->extractTextFromDocx($path);
            }
        };

        $tempFile = tempnam(sys_get_temp_dir(), 'test_docx');
        $zip = new \ZipArchive;
        $zip->open($tempFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $zip->addFromString('word/document.xml', '<?xml version="1.0" encoding="UTF-8"?><w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"><w:body><w:p><w:r><w:t>Hello World</w:t></w:r></w:p></w:body></w:document>');
        $zip->close();

        $text = $service->test_extract($tempFile);
        unlink($tempFile);

        $this->assertEquals('Hello World', $text);
    }
}
