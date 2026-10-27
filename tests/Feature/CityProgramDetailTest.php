<?php

namespace Tests\Feature;

use Tests\TestCase;

class CityProgramDetailTest extends TestCase
{
    /**
     * Ensure the main agricultural programs render with city context.
     */
    public function test_main_programs_render_canonical_content_with_city_context(): void
    {
        // 1. Perunggasan Jenjang 5 in Bandung
        $response = $this->get(route('city.program.detail', ['city' => 'bandung', 'program' => 'perunggasan-jenjang-5']));
        $response->assertStatus(200);
        $response->assertSee('Perunggasan Jenjang 5');
        $content = $response->getContent();
        // Should not show placeholder text
        $this->assertFalse(strpos($content, 'Konten detail program belum tersedia.') !== false);

        // 2. Perunggasan Jenjang 6 in Malang
        $response2 = $this->get(route('city.program.detail', ['city' => 'malang', 'program' => 'perunggasan-jenjang-6']));
        $response2->assertStatus(200);
        $response2->assertSee('Perunggasan Jenjang 6');

        // 3. FASILITATOR PERTANIAN ORGANIK TANAMAN in Surabaya
        $response3 = $this->get(route('city.program.detail', ['city' => 'surabaya', 'program' => 'fasilitator-pertanian-organik-tanaman']));
        $response3->assertStatus(200);
        $response3->assertSee('FASILITATOR PERTANIAN ORGANIK TANAMAN');
    }

    /**
     * Ensure full program slugs resolve to a valid program.
     */
    public function test_full_program_slugs_resolve_correctly(): void
    {
        $response = $this->get(route('city.program.detail', ['city' => 'bandung', 'program' => 'perunggasan-jenjang-5']));
        $response->assertStatus(200);
        $response->assertSee('Perunggasan Jenjang 5');
    }

    /**
     * Invalid city slug should return 404.
     */
    public function test_invalid_city_returns_404(): void
    {
        $response = $this->get(route('city.program.detail', ['city' => 'nonexistent-city', 'program' => 'perunggasan-jenjang-5']));
        $response->assertStatus(404);
    }

    /**
     * Invalid program slug should return 404.
     */
    public function test_invalid_program_returns_404(): void
    {
        $response = $this->get(route('city.program.detail', ['city' => 'bandung', 'program' => 'invalid']));
        $response->assertStatus(404);
    }
}
