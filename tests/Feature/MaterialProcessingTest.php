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

class MaterialProcessingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Disable middleware for tests to avoid CSRF and other issues
        $this->withoutMiddleware();
        Storage::fake('public');
    }

    public function test_material_can_be_uploaded_as_text(): void
    {
        $user = User::factory()->create();

        $this->mock(OCRService::class, function (MockInterface $mock) {
            $mock->shouldReceive('processMaterial')
                ->once()
                ->andReturn([
                    'title' => 'Test Title',
                    'summary' => '### Overview\nTest Summary',
                    'concepts' => [['title' => 'Concept 1', 'short_explanation' => 'Exp 1']],
                    'raw_content' => 'Full text content',
                ]);
        });

        $response = $this->actingAs($user)->postJson(route('materials.store'), [
            'content' => 'Some pasted study material content',
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('materials', [
            'user_id' => $user->id,
            'title' => 'Test Title',
            'status' => 'completed',
        ]);
    }

    public function test_material_can_be_uploaded_as_file(): void
    {
        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('lecture.pdf', 100, 'application/pdf');

        $this->mock(OCRService::class, function (MockInterface $mock) {
            $mock->shouldReceive('processMaterial')
                ->once()
                ->andReturn([
                    'title' => 'PDF Title',
                    'summary' => '### Overview\nPDF Summary',
                    'concepts' => [],
                    'raw_content' => 'Extracted PDF text',
                ]);
        });

        $response = $this->actingAs($user)->postJson(route('materials.store'), [
            'files' => [$file],
        ]);

        $response->assertStatus(200);

        $material = Material::where('user_id', $user->id)->first();
        $this->assertNotNull($material);
        $this->assertEquals('PDF Title', $material->title);
        $this->assertEquals('completed', $material->status);
    }

    public function test_material_deduplication_for_same_user(): void
    {
        $user = User::factory()->create();
        $content = 'Identical content';
        $fileHash = md5($content);

        Material::factory()->create([
            'user_id' => $user->id,
            'file_hash' => $fileHash,
            'status' => 'completed',
        ]);

        $response = $this->actingAs($user)->postJson(route('materials.store'), [
            'content' => $content,
        ]);

        $response->assertStatus(200);
        $this->assertEquals(1, Material::where('user_id', $user->id)->where('file_hash', $fileHash)->count());
    }

    public function test_quiz_can_be_generated(): void
    {
        $user = User::factory()->create();
        $material = Material::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'file_hash' => 'hash123',
            'original_path' => 'uploads/test',
        ]);

        Storage::disk('public')->put('uploads/test/content.txt', 'test content');

        $this->mock(OCRService::class, function (MockInterface $mock) {
            $mock->shouldReceive('generateQuiz')
                ->once()
                ->andReturn([
                    'questions' => [
                        [
                            'type' => 'multiple_choice',
                            'question_text' => 'What is 1+1?',
                            'options' => ['1', '2', '3', '4'],
                            'correct_answer' => '2',
                            'explanation' => 'Simple math',
                        ],
                    ],
                ]);
        });

        $response = $this->actingAs($user)->postJson(route('materials.generate_quiz', $material->id), [
            'num_questions' => 5,
            'quiz_type' => 'multiple_choice',
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('quizzes', [
            'material_id' => $material->id,
        ]);

        $this->assertDatabaseHas('questions', [
            'question_text' => 'What is 1+1?',
        ]);
    }
}
