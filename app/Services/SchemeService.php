<?php

namespace App\Services;

use Illuminate\Support\Str;

/**
 * Loads schemes.json — the authoritative catalog of agricultural competency
 * schemes — and exposes it in the program-data shape consumed by the program,
 * regional, sitemap, and admin surfaces.
 *
 * The source file is read-only: it is never rewritten or normalized, scheme
 * and unit names are preserved verbatim (client spellings included), and
 * slugs are derived at runtime. Missing fields render as neutral
 * "needs verification / contact us" placeholders; no claims are invented.
 */
class SchemeService
{
    /**
     * In-memory index built once per request. Keyed by derived slug.
     */
    protected ?array $programs = null;

    /**
     * All schemes, keyed by derived slug.
     *
     * @throws \RuntimeException when the source file is missing or invalid.
     */
    public function all(): array
    {
        return $this->load();
    }

    /**
     * Find a scheme by its derived (or normalized) slug.
     */
    public function find(string $slug): ?array
    {
        $all = $this->load();

        $key = Str::slug($slug);

        return $all[$key] ?? null;
    }

    protected function load(?string $path = null): array
    {
        $path ??= base_path('schemes.json');
        $default = $path === (base_path('schemes.json'));

        if ($default && $this->programs !== null) {
            return $this->programs;
        }

        $raw = is_file($path) ? file_get_contents($path) : false;

        if ($raw === false) {
            throw new \RuntimeException('Unable to read schemes.json from the project root.');
        }

        $decoded = json_decode($raw, true);

        if (
            ! is_array($decoded)
            || ! isset($decoded['schemes'])
            || ! is_array($decoded['schemes'])
        ) {
            throw new \RuntimeException('schemes.json does not contain the expected "schemes" structure.');
        }

        $programs = $this->index($decoded['schemes']);

        if ($default) {
            $this->programs = $programs;
        }

        return $programs;
    }

    /**
     * Index schemes into the program-compatible shape, resolving slug
     * collisions with a deterministic suffix (-2, -3, ...).
     */
    protected function index(array $schemes): array
    {
        $programs = [];

        foreach ($schemes as $scheme) {
            $name = trim((string) ($scheme['name'] ?? ''));

            if ($name === '') {
                throw new \RuntimeException('schemes.json contains a scheme without a name.');
            }

            $base = Str::slug($name);
            $slug = $base;
            $suffix = 2;
            while (isset($programs[$slug])) {
                $slug = $base . '-' . $suffix;
                $suffix++;
            }

            $programs[$slug] = $this->program($slug, $name, $scheme);
        }

        return $programs;
    }

    /**
     * Map one scheme entry to the program-data contract.
     *
     * @return array Program-compatible keys (see existing program views),
     *               plus the untouched "lsp" and "units" source data.
     */
    protected function program(string $slug, string $name, array $scheme): array
    {
        $units = array_values($scheme['units'] ?? []);
        $lsp = $scheme['lsp'] ?? null;
        $unitCount = count($units);

        return [
            'slug'    => $slug,
            // Program-page contract keys
            'category'         => 'scheme',
            'category_label'   => 'Skema Kompetensi',
            'level'            => $lsp ?? 'Skema Kompetensi',
            'title'            => $name,
            'badge'            => $lsp ?? 'Skema Kompetensi',
            'meta_title'       => $name . ' | Skema Kompetensi',
            'meta_description' => 'Skema kompetensi ' . $name . ': ' . $unitCount . ' unit kompetensi. Rincian unit dan kode disertakan; penyelenggaraan dikonfirmasi melalui konsultasi.',
            'hero_heading'     => 'Skema',
            'hero_highlight'   => $name,
            'hero_subheading'  => 'Daftar unit kompetensi skema ' . $name . ' tersedia pada halaman ini; rincian penyelenggaraan dikonfirmasi melalui konsultasi.',
            'wa_message'       => 'Halo, saya ingin konsultasi mengenai skema ' . $name . '.',
            'introduction'     => 'Skema kompetensi ' . $name . ' mencakup ' . $unitCount . ' unit kompetensi terkait agribisnis. Daftar unit beserta kode unit disertakan pada halaman ini. Persyaratan penyelenggaraan dikonfirmasi melalui konsultasi.',
            'target_audience'  => [
                'Audiens peserta mengikuti ketentuan penyelenggara skema; kecocokan latar belakang dikonfirmasi melalui konsultasi.',
            ],
            'roles_and_responsibilities' => [
                [
                    'title'       => 'Kompetensi Skema',
                    'description' => 'Unit-unit berikut menjadi lingkup kompetensi yang dinilai pada skema ini.',
                ],
            ],
            'curriculum_topics' => array_map(
                static fn (array $unit): string => trim((string) ($unit['code'] ?? '')) . ' — ' . trim((string) ($unit['name'] ?? '')),
                $units
            ),
            'training_info' => [
                'jadwal'      => ['label' => 'Jadwal Pelatihan',    'status' => 'Hubungi kami',      'detail' => 'Jadwal dikonfirmasi melalui konsultasi dengan penyelenggara.'],
                'durasi'      => ['label' => 'Durasi Program',      'status' => 'Perlu verifikasi',  'detail' => 'Durasi dan moda penyelenggaraan dikonfirmasi bersama penyelenggara.'],
                'investasi'   => ['label' => 'Investasi Biaya',     'status' => 'Hubungi kami',      'detail' => 'Biaya tidak dipublikasikan. Hubungi kami untuk penawaran resmi.'],
                'persyaratan' => ['label' => 'Persyaratan Peserta', 'status' => 'Perlu verifikasi',  'detail' => 'Persyaratan peserta dikonfirmasi ke penyelenggara skema.'],
                'otorisasi'   => ['label' => 'Status Otorisasi',    'status' => 'Perlu verifikasi',  'detail' => 'Status skema kompetensi dikonfirmasi bersama LSP penyelenggara.'],
            ],
            'faqs' => [
                [
                    'question' => 'Berapa durasi pelaksanaan skema ini?',
                    'answer'   => 'Durasi belum dipublikasikan dan perlu verifikasi melalui konsultasi dengan penyelenggara.',
                ],
                [
                    'question' => 'Bagaimana cara mendapatkan informasi biaya?',
                    'answer'   => 'Biaya tidak dipublikasikan. Silakan hubungi kami melalui WhatsApp untuk informasi resmi.',
                ],
            ],
            // Untouched source data
            'lsp'   => $lsp,
            'units' => $units,
        ];
    }
}
