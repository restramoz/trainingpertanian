<?php

namespace Tests\Unit;

use App\Services\OllamaService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OllamaServiceTest extends TestCase
{
    private function baseConfig(): void
    {
        config([
            'services.ollama.base_url' => 'http://dummy-ollama.local',
            'services.ollama.model' => 'dummy-model',
            'services.whatsapp.phone' => '081234567890',
        ]);
    }

    public function test_successful_ollama_response_is_parsed(): void
    {
        $this->baseConfig();

        $content = str_repeat('kata ', 7500);
        $body = [
            'message' => [
                'content' => json_encode([
                    'title' => 'Judul Test',
                    'slug' => 'judul-test',
                    'excerpt' => 'Excerpt test',
                    'content' => $content,
                    'seo_title' => 'SEO Title Test',
                    'meta_description' => 'Meta Description Test',
                    'suggested_faqs' => [['q' => 'Q1', 'a' => 'A1']],
                    'image_brief' => [
                        'concept' => 'Konsep',
                        'english_prompt' => 'Prompt',
                        'aspect_ratio' => '16:9',
                        'recommended_alt' => 'Alt',
                    ],
                ]),
            ],
        ];

        Http::fake(['*' => Http::response($body, 200)]);

        $service = new OllamaService();
        $result = $service->generateArticle([
            'city_name' => 'Bandung',
            'city_slug' => 'bandung',
            'word_count' => 7500,
            'topic' => 'Dummy Topic',
        ]);

        $this->assertTrue($result['success'] ?? false);
        $this->assertSame('Judul Test', $result['title']);
    }

    public function test_extracts_target_json_fields(): void
    {
        $this->baseConfig();

        $content = str_repeat('kata ', 7500);
        $body = [
            'message' => [
                'content' => json_encode([
                    'title' => 'Judul Test',
                    'slug' => 'judul-test',
                    'excerpt' => 'Excerpt test',
                    'content' => $content,
                    'seo_title' => 'SEO Title Test',
                    'meta_description' => 'Meta Description Test',
                    'suggested_faqs' => [['q' => 'Q1', 'a' => 'A1']],
                    'image_brief' => [
                        'concept' => 'Konsep',
                        'english_prompt' => 'Prompt',
                        'aspect_ratio' => '16:9',
                        'recommended_alt' => 'Alt',
                    ],
                ]),
            ],
        ];

        Http::fake(['*' => Http::response($body, 200)]);

        $service = new OllamaService();
        $result = $service->generateArticle([
            'city_name' => 'Bandung',
            'city_slug' => 'bandung',
            'word_count' => 7500,
            'topic' => 'Dummy Topic',
        ]);

        $this->assertTrue($result['success'] ?? false);
        $this->assertSame('judul-test', $result['slug']);
        $this->assertSame('Excerpt test', $result['excerpt']);
        $this->assertSame('SEO Title Test', $result['seo_title']);
        $this->assertSame('Meta Description Test', $result['meta_description']);
        $this->assertCount(1, $result['suggested_faqs']);
        $this->assertSame('Konsep', $result['image_brief']['concept']);
        $this->assertSame('Prompt', $result['image_brief']['english_prompt']);
        $this->assertSame('16:9', $result['image_brief']['aspect_ratio']);
        $this->assertSame('Alt', $result['image_brief']['recommended_alt']);
    }

    public function test_continues_when_initial_article_below_6000(): void
    {
        $this->baseConfig();

        $firstContent = str_repeat('kata ', 1000);
        $secondContent = str_repeat('lanjut ', 5500);

        $call = 0;
        Http::fake(function () use (&$call, $firstContent, $secondContent) {
            $call++;
            if ($call === 1) {
                return Http::response([
                    'message' => [
                        'content' => json_encode([
                            'title' => 'Judul Test',
                            'slug' => 'judul-test',
                            'excerpt' => 'Excerpt test',
                            'content' => $firstContent,
                            'seo_title' => 'SEO Title Test',
                            'meta_description' => 'Meta Description Test',
                            'suggested_faqs' => [],
                            'image_brief' => [],
                        ]),
                    ],
                ], 200);
            }

            return Http::response([
                'message' => [
                    'content' => json_encode([
                        'title' => 'Judul Test',
                        'slug' => 'judul-test',
                        'excerpt' => 'Excerpt test',
                        'content' => $secondContent,
                        'seo_title' => 'SEO Title Test',
                        'meta_description' => 'Meta Description Test',
                        'suggested_faqs' => [],
                        'image_brief' => [],
                    ]),
                ],
            ], 200);
        });

        $service = new OllamaService();
        $result = $service->generateArticle([
            'city_name' => 'Bandung',
            'city_slug' => 'bandung',
            'word_count' => 7500,
            'topic' => 'Dummy Topic',
        ]);

        $this->assertTrue($result['success'] ?? false);
        $this->assertGreaterThanOrEqual(6000, $service->countWords($result['content']));
    }

    public function test_rejects_article_below_6000_after_continuation_attempts(): void
    {
        $this->baseConfig();

        $shortContent = str_repeat('kata ', 100);
        Http::fake(['*' => Http::response([
            'message' => [
                'content' => json_encode([
                    'title' => 'Judul Test',
                    'slug' => 'judul-test',
                    'excerpt' => 'Excerpt test',
                    'content' => $shortContent,
                    'seo_title' => 'SEO Title Test',
                    'meta_description' => 'Meta Description Test',
                    'suggested_faqs' => [],
                    'image_brief' => [],
                ]),
            ],
        ], 200)]);

        $service = new OllamaService();
        $result = $service->generateArticle([
            'city_name' => 'Bandung',
            'city_slug' => 'bandung',
            'word_count' => 7500,
            'topic' => 'Dummy Topic',
        ]);

        $this->assertFalse($result['success'] ?? true);
        $this->assertArrayHasKey('error', $result);
    }

    public function test_rejects_article_exceeding_9000_words(): void
    {
        $this->baseConfig();

        $overContent = str_repeat('kata ', 10000);
        Http::fake(['*' => Http::response([
            'message' => [
                'content' => json_encode([
                    'title' => 'Judul Test',
                    'slug' => 'judul-test',
                    'excerpt' => 'Excerpt test',
                    'content' => $overContent,
                    'seo_title' => 'SEO Title Test',
                    'meta_description' => 'Meta Description Test',
                    'suggested_faqs' => [],
                    'image_brief' => [],
                ]),
            ],
        ], 200)]);

        $service = new OllamaService();
        $result = $service->generateArticle([
            'city_name' => 'Bandung',
            'city_slug' => 'bandung',
            'word_count' => 7500,
            'topic' => 'Dummy Topic',
        ]);

        $this->assertFalse($result['success'] ?? true);
        $this->assertArrayHasKey('error', $result);
    }

    public function test_http_error_returns_failure(): void
    {
        $this->baseConfig();

        Http::fake(['*' => Http::response('Server Error', 500)]);

        $service = new OllamaService();
        $result = $service->generateArticle([
            'city_name' => 'Bandung',
            'city_slug' => 'bandung',
            'word_count' => 7500,
            'topic' => 'Dummy Topic',
        ]);

        $this->assertFalse($result['success'] ?? true);
        $this->assertArrayHasKey('error', $result);
    }

    public function test_timeout_returns_failure(): void
    {
        $this->baseConfig();

        Http::fake(['*' => function () {
            throw new ConnectionException('cURL error 28: Operation timed out');
        }]);

        $service = new OllamaService();
        $result = $service->generateArticle([
            'city_name' => 'Bandung',
            'city_slug' => 'bandung',
            'word_count' => 7500,
            'topic' => 'Dummy Topic',
        ]);

        $this->assertFalse($result['success'] ?? true);
        $this->assertArrayHasKey('error', $result);
    }

    public function test_malformed_response_can_be_recovered(): void
    {
        $this->baseConfig();

        $content = str_repeat('kata ', 7500);
        // Malformed outer JSON but valid content field.
        $raw = '{"title":"Judul Test","content":"' . str_replace('"', '\\"', $content) . '"';
        Http::fake(['*' => Http::response([
            'message' => ['content' => $raw],
        ], 200)]);

        $service = new OllamaService();
        $result = $service->generateArticle([
            'city_name' => 'Bandung',
            'city_slug' => 'bandung',
            'word_count' => 7500,
            'topic' => 'Dummy Topic',
        ]);

        $this->assertTrue($result['success'] ?? false);
        $this->assertSame('Dummy Topic', $result['title']);
    }

    public function test_preserves_metadata_and_whatsapp_callouts(): void
    {
        $this->baseConfig();

        $content = str_repeat('kata ', 7500);
        $body = [
            'message' => [
                'content' => json_encode([
                    'title' => 'Judul Test',
                    'slug' => 'judul-test',
                    'excerpt' => 'Excerpt test',
                    'content' => $content,
                    'seo_title' => 'SEO Title Test',
                    'meta_description' => 'Meta Description Test',
                    'suggested_faqs' => [['q' => 'Q1', 'a' => 'A1']],
                    'image_brief' => [
                        'concept' => 'Konsep',
                        'english_prompt' => 'Prompt',
                        'aspect_ratio' => '16:9',
                        'recommended_alt' => 'Alt',
                    ],
                ]),
            ],
        ];

        Http::fake(['*' => Http::response($body, 200)]);

        $service = new OllamaService();
        $result = $service->generateArticle([
            'city_name' => 'Bandung',
            'city_slug' => 'bandung',
            'word_count' => 7500,
            'topic' => 'Dummy Topic',
        ]);

        $this->assertTrue($result['success'] ?? false);
        $this->assertStringContainsString('bg-concrete-100', $result['content']);
        $calloutCount = substr_count($result['content'], 'bg-concrete-100');
        $this->assertSame(5, $calloutCount);
        $this->assertSame('Judul Test', $result['title']);
        $this->assertSame('Konsep', $result['image_brief']['concept']);
    }
}
