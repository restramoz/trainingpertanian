<?php

namespace App\Http\Controllers;

use App\Services\RegionalService;
use App\Services\SchemeService;
use Illuminate\Contracts\View\View;

class ProgramController extends Controller
{
    public function __construct(private readonly SchemeService $schemes) {}

    /**
     * Display the specified program or service page by slug.
     */
    public function show(string $slug): View
    {
        $program = $this->schemes->find($slug);

        if ($program === null) {
            abort(404, 'Layanan atau program pelatihan tidak ditemukan.');
        }

        // Recommend related schemes from the same authoritative catalog
        $relatedPrograms = collect($this->schemes->all())
            ->filter(fn (array $p, string $key): bool => $key !== $program['slug'])
            ->values()
            ->take(6)
            ->all();

        return view('programs.show', [
            'program' => $program,
            'relatedPrograms' => $relatedPrograms,
            'otherPrograms' => $relatedPrograms,
        ]);
    }

    /**
     * Display a city-specific service detail page preserving regional context.
     *
     * @param string $city      Slug of the city/regency (e.g. "bandung", "kab-bekasi")
     * @param string $program   Catalog slug (e.g. "perunggasan-jenjang-5")
     */
    public function cityProgram(string $city, string $program, RegionalService $regional): View
    {
        $cityData = $regional->findCity($city);
        abort_if($cityData === null, 404, 'Kota/Kabupaten tidak ditemukan.');

        $programData = $this->schemes->find($program);

        abort_if($programData === null, 404, 'Layanan tidak ditemukan.');

        $relatedPrograms = collect($this->schemes->all())
            ->filter(fn (array $p, string $key): bool => $key !== $programData['slug'])
            ->values()
            ->take(6)
            ->all();

        $waMessage = "Halo, saya dari {$cityData['name']} dan ingin konsultasi mengenai {$programData['title']}.";

        return view('city.program-detail', [
            'city' => $cityData,
            'program' => $programData,
            'relatedPrograms' => $relatedPrograms,
            'otherPrograms' => $relatedPrograms,
            'waMessage' => $waMessage,
        ]);
    }
}
