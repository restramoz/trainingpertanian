@extends('layouts.app')

@section('title', 'Skema Kompetensi Pertanian Provinsi ' . $province['name'] . ' — Konsultasi Resmi')
@section('meta_description', 'Informasi skema kompetensi dan program pelatihan pertanian untuk wilayah Provinsi ' . $province['name'] . '.')
@section('wa_message', $waMessage)

@section('content')
    {{-- Breadcrumb --}}
    <div class="bg-neutral-100 border-b border-neutral-200">
        <x-breadcrumb :items="[
            ['label' => 'Wilayah', 'url' => null],
            ['label' => $province['name'], 'url' => null],
        ]" />
    </div>

    {{-- Hero --}}
    <section class="relative bg-industry-black text-white overflow-hidden border-b border-dark-mahogany" aria-labelledby="prov-hero-heading">
        <div class="absolute inset-0 opacity-10 pointer-events-none" aria-hidden="true">
            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <defs><pattern id="grid-p" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="#FFF" stroke-width="1"/></pattern></defs>
                <rect width="100%" height="100%" fill="url(#grid-p)" />
            </svg>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 lg:py-20">
            <div class="max-w-3xl">
                <!-- Mobile hero image - Responsive with aspect-ratio -->
                <div class="lg:hidden mb-5 -mx-4 sm:-mx-6 lg:-mx-8">
                    <img src="{{ asset('images/home/hero.webp') }}" alt="Aktivitas pertanian di Provinsi {{ $province['name'] }}" width="1600" height="900" loading="eager" fetchpriority="high" decoding="async" class="hero-image-mobile w-full object-cover object-center rounded-xl" />
                </div>
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-burnt-brick border border-burnt-brick/80 text-white text-xs sm:text-sm font-semibold mb-5 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-safety-orange animate-pulse"></span>
                    Informasi Skema &amp; Layanan Konsultasi
                </div>
                <h1 id="prov-hero-heading" class="font-extrabold tracking-tight text-white leading-tight uppercase text-[clamp(1.75rem,4.5vw,2.5rem)] sm:text-[clamp(2rem,5vw,3rem)] lg:text-[clamp(2.25rem,4.5vw,3.5rem)]">
                    Skema Kompetensi<br><span class="text-safety-orange">Pertanian Provinsi {{ $province['name'] }}</span>
                </h1>
                <p class="mt-6 text-base sm:text-lg text-neutral-200 leading-relaxed">
                    Pusat informasi skema kompetensi dan program pelatihan pertanian untuk badan usaha, fasilitas produksi, dan mitra agribisnis di <strong class="text-white">Provinsi {{ $province['name'] }}</strong>.
                </p>
                <div class="mt-7 flex flex-col sm:flex-row gap-3 sm:gap-4 w-full">
                    <x-wa-cta variant="primary" size="lg" :message="$waMessage" label="Konsultasi Wilayah {{ $province['name'] }}" class="w-full sm:w-auto bg-safety-orange hover:bg-burnt-brick text-industry-black hover:text-white font-bold shadow-lg" />
                </div>
                <div class="mt-8 pt-6 border-t border-neutral-800 grid grid-cols-2 gap-4 sm:gap-6">
                    <div class="min-w-0"><div class="text-[10px] sm:text-xs text-neutral-400 uppercase tracking-wider">Pulau</div><div class="text-sm sm:text-base font-extrabold text-white mt-0.5">{{ $province['island'] }}</div></div>
                    <div class="min-w-0"><div class="text-[10px] sm:text-xs text-neutral-400 uppercase tracking-wider">Kab/Kota</div><div class="text-sm sm:text-base font-extrabold text-safety-orange mt-0.5">{{ $province['total_cities'] }}</div></div>
                    <div class="min-w-0"><div class="text-[10px] sm:text-xs text-neutral-400 uppercase tracking-wider">Layanan</div><div class="text-sm sm:text-base font-extrabold text-white mt-0.5">{{ count($programs) }} Skema</div></div>
                    <div class="min-w-0"><div class="text-[10px] sm:text-xs text-neutral-400 uppercase tracking-wider">Lingkup</div><div class="text-sm sm:text-base font-extrabold text-safety-orange mt-0.5">Pertanian &amp; Agribisnis</div></div>
                </div>
            </div>
        </div>
    </section>

    {{-- Disclaimer --}}
    <section class="py-10 bg-neutral-50 border-b border-neutral-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-start gap-4">
                <svg class="w-6 h-6 text-burnt-brick shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <p class="text-sm text-dark-mahogany leading-relaxed">
                    <strong class="text-industry-black">Catatan penting:</strong> Halaman ini adalah portal informasi dan konsultasi program. Penyelenggaraan pelatihan tatap muka, in-house training di kota/kabupaten dalam Provinsi {{ $province['name'] }}, jadwal batch, dan persyaratan administrasi dikoordinasikan secara langsung melalui tim konsultan kami.
                </p>
            </div>
        </div>
    </section>

    {{-- Program Links --}}
    <section id="program" class="py-14 sm:py-20 bg-white border-b border-neutral-200" aria-labelledby="prov-program-heading">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mb-10">
                <span class="text-xs font-bold uppercase tracking-wider text-burnt-brick bg-neutral-100 px-3 py-1 rounded-full border border-burnt-brick/20">Layanan Unggulan</span>
                <h2 id="prov-program-heading" class="mt-3 font-extrabold text-industry-black tracking-tight text-[clamp(1.5rem,4vw,2rem)] sm:text-[clamp(1.75rem,4.5vw,2.25rem)]">
                    Skema Kompetensi &amp; Layanan di {{ $province['name'] }}
                </h2>
                <p class="mt-3 text-sm sm:text-base text-dark-mahogany">Pilih skema kompetensi atau layanan konsultasi sesuai kebutuhan organisasi Anda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($programs as $prog)
                <a href="{{ route('program.show', $prog['slug']) }}" class="group flex flex-col bg-white rounded-xl border border-neutral-200 hover:border-burnt-brick hover:shadow-md transition-all overflow-hidden focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-burnt-brick">
                    <div class="h-1.5 bg-safety-orange"></div>
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="text-xs font-bold uppercase tracking-wider text-burnt-brick mb-1">{{ $prog['category_label'] ?? 'Layanan' }}</div>
                        <h3 class="text-lg font-bold text-industry-black mb-2 group-hover:text-burnt-brick transition-colors">{{ $prog['title'] }}</h3>
                        <p class="text-xs sm:text-sm text-dark-mahogany flex-1 leading-relaxed line-clamp-3">{{ $prog['hero_subheading'] }}</p>
                        <div class="mt-4 pt-4 border-t border-neutral-100 text-xs font-bold text-burnt-brick flex items-center justify-between">
                            <span>Rincian Program</span>
                            <span>&rarr;</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Cities in Province --}}
    @if(count($province['cities']) > 0)
    <section id="kota" class="py-14 sm:py-20 bg-neutral-50 border-b border-neutral-200" aria-labelledby="prov-kota-heading">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 id="prov-kota-heading" class="text-2xl font-extrabold text-industry-black mb-8">
                Kabupaten &amp; Kota di Provinsi {{ $province['name'] }}
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                @foreach($province['cities'] as $c)
                <a href="{{ route('regional.city', $c['slug']) }}" class="block px-3 py-2.5 rounded-lg bg-white border border-neutral-200 hover:border-burnt-brick hover:bg-neutral-100 text-sm font-medium text-dark-mahogany hover:text-burnt-brick transition-colors truncate focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-burnt-brick">
                    {{ $c['name'] }}
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- FAQ --}}
    <section id="faq" class="py-14 sm:py-20 bg-white border-b border-neutral-200" aria-labelledby="prov-faq-heading">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="text-xs font-bold uppercase tracking-wider text-burnt-brick bg-neutral-100 px-3 py-1 rounded-full border border-burnt-brick/20">Tanya Jawab</span>
                <h2 id="prov-faq-heading" class="mt-3 text-2xl font-extrabold text-industry-black tracking-tight">FAQ: Skema Kompetensi di {{ $province['name'] }}</h2>
            </div>
            <div class="space-y-4">
                @foreach([
                    [
                        'q' => 'Apakah skema kompetensi dan layanan konsultasi tersedia untuk wilayah Provinsi ' . $province['name'] . '?',
                        'a' => 'Ya, halaman ini menyajikan seluruh skema kompetensi pertanian yang tersedia, dan layanan konsultasi dapat dikonfirmasi untuk kota dan kabupaten di Provinsi ' . $province['name'] . '.',
                    ],
                    [
                        'q' => 'Skema kompetensi apa saja yang tersedia?',
                        'a' => 'Daftar skema kompetensi pertanian yang lengkap tersedia pada bagian katalog di halaman ini, beserta unit kompetensi masing-masing. Rincian dijelaskan pada halaman program setiap skema.',
                    ],
                    [
                        'q' => 'Bagaimana cara pengajuan konsultasi untuk kebutuhan di ' . $province['name'] . '?',
                        'a' => 'Hubungi tim kami melalui WhatsApp. Kebutuhan skema, profil kegiatan usaha, dan persyaratan administrasi akan didiskusikan terlebih dahulu sebelum proposal resmi disiapkan.',
                    ],
                ] as $faq)
                <details class="group bg-white rounded-xl border border-neutral-200 p-5 sm:p-6 open:border-burnt-brick shadow-xs">
                    <summary class="flex justify-between items-center gap-4 font-bold text-industry-black cursor-pointer list-none focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-burnt-brick rounded">
                        <span>{{ $faq['q'] }}</span>
                        <span class="w-7 h-7 rounded-full bg-neutral-100 flex items-center justify-center shrink-0 group-open:rotate-180 transition-transform">
                            <svg class="w-4 h-4 text-burnt-brick" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </span>
                    </summary>
                    <p class="mt-4 pt-4 border-t border-neutral-100 text-sm text-dark-mahogany leading-relaxed">{{ $faq['a'] }}</p>
                </details>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Final CTA --}}
    <section class="py-16 sm:py-24 bg-industry-black text-white" aria-labelledby="prov-cta-heading">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="max-w-3xl mx-auto">
                <h2 id="prov-cta-heading" class="text-2xl sm:text-3xl font-extrabold uppercase tracking-tight">
                    Konsultasi Program Pertanian<br><span class="text-safety-orange">Provinsi {{ $province['name'] }}</span>
                </h2>
                <p class="mt-4 text-base text-neutral-200 leading-relaxed">
                    Hubungi kami untuk informasi skema kompetensi, halaman program, atau kebutuhan konsultasi di Provinsi {{ $province['name'] }}.
                </p>
                <div class="mt-7 flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4 w-full">
                    <x-wa-cta variant="primary" size="lg" :message="$waMessage" label="Konsultasi Sekarang" class="w-full sm:w-auto bg-safety-orange hover:bg-burnt-brick text-industry-black hover:text-white font-bold shadow-lg" />
                    <x-button :href="route('home')" variant="outline" size="lg" class="w-full sm:w-auto text-white border-2 border-neutral-400 hover:bg-neutral-800">Kembali ke Beranda</x-button>
                </div>
            </div>
        </div>
    </section>
@endsection
