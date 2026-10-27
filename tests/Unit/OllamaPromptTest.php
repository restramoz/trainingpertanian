<?php

namespace Tests\Unit;

use App\Services\OllamaService;
use App\Services\SchemeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OllamaPromptTest extends TestCase
{
    use RefreshDatabase;

    private function fakeSuccessfulOllama(): void
    {
        config([
            'services.ollama.base_url' => 'http://dummy-ollama.local',
            'services.ollama.model' => 'dummy-model',
        ]);

        $content = str_repeat('kata ', 7500);
        Http::fake(['*' => Http::response([
            'message' => ['content' => json_encode([
                'title' => 'Judul Test',
                'slug' => 'judul-test',
                'excerpt' => 'Excerpt test',
                'content' => $content,
                'seo_title' => 'SEO Title Test',
                'meta_description' => 'Meta Description Test',
                'suggested_faqs' => [],
                'image_brief' => [],
            ])],
        ], 200)]);
    }

    public function test_prompts_carry_agricultural_identity(): void
    {
        $this->fakeSuccessfulOllama();

        (new OllamaService())->generateArticle([
            'city_name' => 'Bandung',
            'city_slug' => 'bandung',
            'word_count' => 7500,
        ]);

        Http::assertSent(function (object $request): bool {
            $system = $request['messages'][0]['content'];
            $user = $request['messages'][1]['content'];

            return str_contains($system, 'kompetensi pertanian')
                && str_contains($system, 'Tlaning Pertanian')
                && str_contains($user, 'artikel pelatihan pertanian');
        });
    }

    public function test_prompts_exclude_legacy_identity(): void
    {
        $this->fakeSuccessfulOllama();

        (new OllamaService())->generateArticle([
            'city_name' => 'Bandung',
            'city_slug' => 'bandung',
            'word_count' => 7500,
        ]);

        Http::assertSent(function (object $request): bool {
            $prompts = $request['messages'][0]['content'].' '.$request['messages'][1]['content'];

            return ! str_contains($prompts, 'Ahli K3 Umum')
                && ! str_contains($prompts, 'Alat Berat')
                && ! str_contains($prompts, 'SMK3')
                && ! str_contains($prompts, 'K3 Listrik');
        });
    }

    public function test_service_slug_injects_targeted_service_context(): void
    {
        $this->fakeSuccessfulOllama();

        $scheme = (new SchemeService())->find('perunggasan-jenjang-5');
        $this->assertNotNull($scheme);
        $title = (string) $scheme['title'];

        (new OllamaService())->generateArticle([
            'city_name' => 'Bandung',
            'city_slug' => 'bandung',
            'word_count' => 7500,
            'service_slug' => 'perunggasan-jenjang-5',
        ]);

        Http::assertSent(function (object $request) use ($title): bool {
            $system = $request['messages'][0]['content'];
            $user = $request['messages'][1]['content'];

            return str_contains($system, 'Program spesifik: '.$title)
                && str_contains($user, '- Program Spesifik: '.$title);
        });
    }
}