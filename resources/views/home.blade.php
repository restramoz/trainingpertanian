@extends('layouts.app')

@section('title', 'Tlaning Pertanian — Pelatihan & Skema Kompetensi Pertanian')
@section('meta_description', 'Katalog skema kompetensi dan program pelatihan pertanian: rincian unit kompetensi, penyelenggara, serta layanan konsultasi untuk kebutuhan pertanian dan agribisnis.')

@section('content')
<noscript>
    <style>.scheme-extra[hidden]{display:block!important;}</style>
</noscript>
@php
    $regionalService = resolve(App\Services\RegionalService::class);
    $regionalService = resolve(App\Services\RegionalService::class);
    $provinces = $regionalService->getAllProvinces();
    $allPrograms = app(\App\Services\SchemeService::class)->all();
@endphp

    <!-- HERO SECTION (LIGHT SURFACE) -->
    <section class="relative bg-white text-dark-mahogany overflow-hidden border-b border-neutral-200" aria-labelledby="hero-heading">
        <!-- Subtle Grid Pattern Accent -->
        <div class="absolute inset-0 opacity-[0.04] pointer-events-none" aria-hidden="true">
            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid-pattern" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="#0F5132" stroke-width="1" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid-pattern)" />
            </svg>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 lg:py-20">
            <div class="max-w-3xl">
                <!-- Mobile Hero Image - Responsive with aspect-ratio -->
                <div class="lg:hidden mb-6">
                    <img src="{{ asset('images/home/hero.webp') }}" alt="Aktivitas pertanian Indonesia: petani bekerja di sawah" width="1600" height="900" loading="eager" fetchpriority="high" decoding="async" class="hero-image-mobile w-full object-cover object-center rounded-xl shadow-md" />
                </div>
                <!-- Focus Badge -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-dark-mahogany text-white text-xs sm:text-sm font-semibold mb-5 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-safety-orange animate-pulse"></span>
                    Pelatihan &amp; Skema Kompetensi Pertanian
                </div>

                <!-- Main Heading - Responsive with clamp() -->
                <h1 id="hero-heading" class="font-extrabold tracking-tight text-industry-black leading-tight uppercase text-[clamp(1.875rem,4.5vw,3.125rem)] sm:text-[clamp(2.25rem,5vw,3.75rem)] lg:text-[clamp(2.5rem,4.5vw,4.5rem)]">
                    Pelatihan <span class="text-burnt-brick">Pertanian</span> &amp; Skema Kompetensi
                </h1>

                <!-- Subheading / Paragraph -->
                <p class="mt-5 text-sm sm:text-base lg:text-lg text-dark-mahogany leading-relaxed max-w-2xl">
                    Jelajahi skema kompetensi pertanian dengan rincian unit lengkap, atau konsultasikan kebutuhan pelatihan dan agribisnis Anda — dari hulu produksi hingga pengolahan dan pemasaran hasil pertanian.
                </p>

                <!-- Actions - Full width on mobile, auto on larger screens -->
                <div class="mt-7 flex flex-col sm:flex-row gap-3 sm:gap-4 w-full">
                    <x-wa-cta variant="primary" size="lg" label="Konsultasi Program via WhatsApp" class="w-full sm:w-auto shadow-md bg-safety-orange hover:bg-burnt-brick text-industry-black hover:text-white font-bold" />
                    <x-button href="#pelatihan" variant="secondary" size="lg" class="w-full sm:w-auto bg-white hover:bg-neutral-100 text-dark-mahogany font-bold border-2 border-dark-mahogany">
                        Lihat Program Pelatihan
                    </x-button>
                </div>

                <!-- Desktop Hero Image - Full width with proper responsive handling -->
                <div class="hidden lg:block mt-10 -mx-4 sm:-mx-6 lg:-mx-8">
                    <img src="{{ asset('images/home/hero.webp') }}" alt="Aktivitas pertanian Indonesia: petani bekerja di sawah" width="1600" height="900" loading="eager" fetchpriority="high" decoding="async" class="hero-image-desktop w-full object-cover object-center" />
                </div>

                <!-- Value Highlights Grid - Responsive with min-width protection -->
                <div class="mt-8 pt-6 border-t border-neutral-300 stats-grid">
                    <div class="stat-item min-w-0">
                        <div class="stat-value text-xl sm:text-2xl font-extrabold text-burnt-brick">{{ count($allPrograms) }}</div>
                        <div class="stat-label text-[11px] sm:text-xs text-dark-mahogany mt-0.5 font-semibold leading-snug">Skema Kompetensi</div>
                    </div>
                    <div class="stat-item min-w-0">
                        <div class="stat-value text-xl sm:text-2xl font-extrabold text-industry-black">Lingkup</div>
                        <div class="stat-label text-[11px] sm:text-xs text-dark-mahogany mt-0.5 font-semibold leading-snug">Pertanian &amp; Agribisnis</div>
                    </div>
                    <div class="stat-item min-w-0">
                        <div class="stat-value text-xl sm:text-2xl font-extrabold text-burnt-brick">Konsultasi</div>
                        <div class="stat-label text-[11px] sm:text-xs text-dark-mahogany mt-0.5 font-semibold leading-snug">Konfirmasi penyelenggaraan</div>
                    </div>
                    <div class="stat-item min-w-0">
                        <div class="stat-value text-xl sm:text-2xl font-extrabold text-industry-black">Nasional</div>
                        <div class="stat-label text-[11px] sm:text-xs text-dark-mahogany mt-0.5 font-semibold leading-snug">Jangkauan {{ count($provinces) }} Provinsi</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@php
    $ambasisi = [];
    $lspNusantara = [];
    foreach ($allPrograms as $slug => $item) {
        if (($item['lsp'] ?? null) === null) {
            $ambasisi[$slug] = $item;
        } elseif (($item['lsp'] ?? '') === 'LSP Pertanian Nusantara') {
            $lspNusantara[$slug] = $item;
        }
    }
@endphp

    <!-- SECTION 1A: SKEMA KOMPETENSI AGRIBISNIS AMBASISI -->
    <section id="skema-agribisnis" data-scheme-section="agribisnis" class="py-16 sm:py-24 bg-white" aria-labelledby="agribisnis-heading">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center mb-10">
                <div class="lg:col-span-2 text-center lg:text-left">
                    <span class="text-xs sm:text-sm font-bold uppercase tracking-wider text-burnt-brick bg-neutral-100 px-3 py-1 rounded-full border border-burnt-brick/20">
                        Katalog Skema
                    </span>
                    <h2 id="agribisnis-heading" class="mt-3 text-2xl sm:text-3xl lg:text-4xl font-extrabold text-industry-black tracking-tight">
                        Skema Kompetensi Agribisnis Ambasisi
                    </h2>
                    <p class="mt-4 text-base text-dark-mahogany leading-relaxed">
                        Kelompok utama berisi {{ count($ambasisi) }} skema kompetensi pertanian dari sumber Agribisnis Ambasisi. Data menampilkan 6 skema terlebih dahulu; pilih "Lihat Semua Skema" untuk melihat seluruhnya.
                    </p>
                </div>
                <div class="hidden md:block">
                    <img src="{{ asset('images/home/agribisnis.webp') }}" alt="Aktivitas pertanian dan pengembangan kompetensi agribisnis" width="900" height="600" loading="lazy" decoding="async" class="w-full h-auto max-h-[320px] md:max-h-[400px] object-cover object-center rounded-2xl shadow-md" />
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="grid-agribisnis">
                @foreach($ambasisi as $slug => $item)
                    @php $extra = $loop->iteration > 6; @endphp
                    <div class="scheme-card {{ $extra ? 'scheme-extra' : '' }} flex flex-col bg-white rounded-xl border border-neutral-200 shadow-sm hover:shadow-md transition-shadow overflow-hidden group" data-scheme-slug="{{ $slug }}" data-scheme-category="agribisnis" data-unit-count="{{ count($item['units']) }}" @if($extra) hidden @endif>
                        <div class="h-1.5 bg-burnt-brick"></div>
                        <div class="p-5 flex-1 flex flex-col">
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="text-xs font-bold uppercase tracking-wider text-dark-mahogany break-words">{{ $item['lsp'] ?: 'Agribisnis Ambasisi' }}</span>
                                <span class="text-[10px] bg-neutral-100 text-dark-mahogany px-2 py-0.5 rounded font-semibold border border-neutral-200 shrink-0">{{ count($item['units']) }} unit</span>
                            </div>
                            <h3 class="text-base font-bold text-industry-black mb-2 group-hover:text-burnt-brick transition-colors break-words">
                                {{ $item['title'] }}
                            </h3>
                            <p class="text-xs text-dark-mahogany mb-4">
                                {{ count($item['units']) }} unit kompetensi
                            </p>
                            <div class="space-y-2 mt-auto">
                                <x-wa-cta variant="secondary" size="md" :message="$item['wa_message']" :label="'Konsultasi ' . $item['title']" class="w-full justify-center text-xs" />
                                <a href="{{ route('program.show', $slug) }}" class="inline-flex items-center justify-center w-full text-xs font-bold text-burnt-brick hover:text-safety-orange transition-colors py-1 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-burnt-brick rounded">
                                    Detail Skema &amp; Unit &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-10 text-center">
                <button type="button" class="js-show-all inline-flex items-center justify-center px-6 py-3 text-sm font-bold text-white bg-burnt-brick hover:bg-leaf rounded-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-burnt-brick transition-colors" aria-expanded="false" data-extra-count="{{ max(0, count($ambasisi) - 6) }}">
                    Lihat Semua Skema
                </button>
                <p class="mt-3 text-xs text-dark-mahogany">
                    Menampilkan 6 dari {{ count($ambasisi) }} skema. Saat diperluas, seluruh {{ count($ambasisi) }} skema tampil; pilih tombol yang sama untuk menampilkan lebih sedikit.
                </p>
            </div>
        </div>
    </section>

    <!-- SECTION 1B: SKEMA KOMPETENSI LSP PERTANIAN NUSANTARA -->
    <section id="skema-lsp-nusantara" data-scheme-section="lsp-pertanian-nusantara" class="py-16 sm:py-24 bg-neutral-50 border-t border-neutral-200" aria-labelledby="lsp-heading">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center mb-10">
                <div class="lg:col-span-2 text-center lg:text-left">
                    <span class="text-xs sm:text-sm font-bold uppercase tracking-wider text-burnt-brick bg-white px-3 py-1 rounded-full border border-burnt-brick/20">
                        Skema Terkait LSP
                    </span>
                    <h2 id="lsp-heading" class="mt-3 text-2xl sm:text-3xl lg:text-4xl font-extrabold text-industry-black tracking-tight">
                        Skema Kompetensi LSP Pertanian Nusantara
                    </h2>
                    <p class="mt-4 text-base text-dark-mahogany leading-relaxed">
                        Kelompok kedua berisi {{ count($lspNusantara) }} skema yang berasosiasi dengan LSP Pertanian Nusantara. Tidak ada hubungan lisensi/akreditasi yang diklaim; field lsp hanya menunjukkan asosiasi sumber.
                    </p>
                </div>
                <div class="hidden md:block">
                    <img src="{{ asset('images/home/lsp.webp') }}" alt="Konteks pelatihan pertanian dan pengembangan kompetensi" width="900" height="600" loading="lazy" decoding="async" class="w-full h-auto max-h-[320px] md:max-h-[400px] object-cover object-center rounded-2xl shadow-md" />
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="grid-lsp-nusantara">
                @foreach($lspNusantara as $slug => $item)
                    <div class="scheme-card flex flex-col bg-white rounded-xl border border-neutral-200 shadow-sm hover:shadow-md transition-shadow overflow-hidden group" data-scheme-slug="{{ $slug }}" data-scheme-category="lsp-pertanian-nusantara" data-unit-count="{{ count($item['units']) }}">
                        <div class="h-1.5 bg-leaf"></div>
                        <div class="p-5 flex-1 flex flex-col">
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="text-xs font-bold uppercase tracking-wider text-dark-mahogany break-words">{{ $item['lsp'] }}</span>
                                <span class="text-[10px] bg-neutral-100 text-dark-mahogany px-2 py-0.5 rounded font-semibold border border-neutral-200 shrink-0">{{ count($item['units']) }} unit</span>
                            </div>
                            <h3 class="text-base font-bold text-industry-black mb-2 group-hover:text-burnt-brick transition-colors break-words">
                                {{ $item['title'] }}
                            </h3>
                            <p class="text-xs text-dark-mahogany mb-4">
                                {{ count($item['units']) }} unit kompetensi
                            </p>
                            <div class="space-y-2 mt-auto">
                                <x-wa-cta variant="secondary" size="md" :message="$item['wa_message']" :label="'Konsultasi ' . $item['title']" class="w-full justify-center text-xs" />
                                <a href="{{ route('program.show', $slug) }}" class="inline-flex items-center justify-center w-full text-xs font-bold text-leaf hover:text-burnt-brick transition-colors py-1 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-burnt-brick rounded">
                                    Detail Skema &amp; Unit &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-10 text-center">
                <button type="button" class="js-show-all inline-flex items-center justify-center px-6 py-3 text-sm font-bold text-dark-mahogany bg-neutral-200 hover:bg-neutral-300 rounded-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-burnt-brick transition-colors" aria-expanded="true" aria-disabled="true" disabled data-extra-count="0">
                    Lihat Semua Skema
                </button>
                <p class="mt-3 text-xs text-dark-mahogany">
                    Semua {{ count($lspNusantara) }} skema sudah ditampilkan sejak awal; tombol ini hanya penanda konsistensi katalog.
                </p>
            </div>
        </div>
    </section>


    <!-- KEUNGGULAN SECTION -->
    <section id="keunggulan" class="py-16 sm:py-24 bg-neutral-50 border-t border-neutral-200" aria-labelledby="keunggulan-heading">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-14">
                <span class="text-xs sm:text-sm font-bold uppercase tracking-wider text-burnt-brick bg-white px-3 py-1 rounded-full border border-burnt-brick/20 shadow-xs">
                    Mengapa Memilih Kami
                </span>
                <h2 id="keunggulan-heading" class="mt-3 text-2xl sm:text-3xl lg:text-4xl font-extrabold text-industry-black tracking-tight">
                    Keunggulan Tlaning Pertanian
                </h2>
                <p class="mt-4 text-base text-dark-mahogany">
                    Informasi skema yang disajikan apa adanya, dengan halaman wilayah untuk konteks layanan dan konsultasi yang transparan.
                </p>
            </div>

            <div class="mb-8 hidden md:block">
                <img src="{{ asset('images/home/training.webp') }}" alt="Pembinaan dan pelatihan kompetensi pertanian" width="900" height="600" loading="lazy" decoding="async" class="w-full h-auto max-h-[280px] md:max-h-[350px] object-cover object-center rounded-2xl shadow-md" />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Pillar 1 -->
                <div class="p-6 rounded-xl bg-white border border-neutral-200 shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-lg bg-burnt-brick/10 text-burnt-brick flex items-center justify-center mb-5">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-industry-black mb-2">Fokus Sektor Pertanian</h3>
                    <p class="text-sm text-dark-mahogany leading-relaxed">
                        Seluruh skema berada dalam lingkup pertanian dan agribisnis; unit kompetensi setiap skema tersedia lengkap pada halaman program masing-masing.
                    </p>
                </div>

                <!-- Pillar 2 -->
                <div class="p-6 rounded-xl bg-white border border-neutral-200 shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-lg bg-burnt-brick/10 text-burnt-brick flex items-center justify-center mb-5">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-industry-black mb-2">Mengikuti Struktur Skema</h3>
                    <p class="text-sm text-dark-mahogany leading-relaxed">
                        Katalog tersusun sesuai skema kompetensi beserta jenjang dan unit yang tercantum, tanpa pengurangan atau perubahan nama dari daftar penyelenggara.
                    </p>
                </div>

                <!-- Pillar 3 -->
                <div class="p-6 rounded-xl bg-white border border-neutral-200 shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-lg bg-burnt-brick/10 text-burnt-brick flex items-center justify-center mb-5">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-industry-black mb-2">Jangkauan Wilayah</h3>
                    <p class="text-sm text-dark-mahogany leading-relaxed">
                        Halaman provinsi, kota/kabupaten, dan kecamatan tersedia sebagai konteks administratif jangkauan layanan di seluruh Indonesia.
                    </p>
                </div>

                <!-- Pillar 4 -->
                <div class="p-6 rounded-xl bg-white border border-neutral-200 shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-lg bg-burnt-brick/10 text-burnt-brick flex items-center justify-center mb-5">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-industry-black mb-2">Konsultasi Responsif</h3>
                    <p class="text-sm text-dark-mahogany leading-relaxed">
                        Informasi jadwal, persyaratan peserta, atau skema penyelenggaraan dikonfirmasi transparan melalui WhatsApp tanpa biaya tersembunyi di halaman web.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ SECTION -->
    <section id="faq" class="py-16 sm:py-24 bg-white border-t border-neutral-200" aria-labelledby="faq-heading">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="text-xs sm:text-sm font-bold uppercase tracking-wider text-burnt-brick bg-neutral-100 px-3 py-1 rounded-full border border-burnt-brick/20 shadow-xs">
                    Tanya Jawab
                </span>
                <h2 id="faq-heading" class="mt-3 text-2xl sm:text-3xl font-extrabold text-industry-black tracking-tight">
                    Pertanyaan yang Sering Diajukan
                </h2>
                <p class="mt-3 text-sm sm:text-base text-dark-mahogany">
                    Seputar skema kompetensi dan program pelatihan pertanian.
                </p>
            </div>

            <div class="space-y-4">
                <details class="group bg-neutral-50 rounded-lg border border-neutral-200 p-5 open:ring-2 open:ring-burnt-brick transition-all shadow-xs">
                    <summary class="font-bold text-industry-black cursor-pointer list-none flex items-center justify-between focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-burnt-brick rounded">
                        <span>Apakah pelatihan ini membuat peserta memperoleh sertifikat atau lisensi resmi?</span>
                        <svg class="w-5 h-5 text-burnt-brick group-open:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </summary>
                    <p class="mt-3 text-sm text-dark-mahogany leading-relaxed">
                        Program-program ini dirancang sebagai pembinaan kompetensi. Status sertifikasi resmi, penunjukan jabatan, atau lisensi khusus diatur oleh instansi berwenang dan perusahaan masing-masing melalui proses tersendiri. Verifikasi selalu dilakukan kepada pihak yang berwenang.
                    </p>
                </details>

                <details class="group bg-neutral-50 rounded-lg border border-neutral-200 p-5 open:ring-2 open:ring-burnt-brick transition-all shadow-xs">
                    <summary class="font-bold text-industry-black cursor-pointer list-none flex items-center justify-between focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-burnt-brick rounded">
                        <span>Program mana yang cocok untuk tim kami?</span>
                        <svg class="w-5 h-5 text-burnt-brick group-open:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </summary>
                    <p class="mt-3 text-sm text-dark-mahogany leading-relaxed">
                        Pilih berdasarkan lingkup kerja yang dibutuhkan; setiap skema memiliki halaman sendiri dengan daftar unit kompetensi, dan konsultasi pengarahan tersedia via WhatsApp.
                    </p>
                </details>

                <details class="group bg-neutral-50 rounded-lg border border-neutral-200 p-5 open:ring-2 open:ring-burnt-brick transition-all shadow-xs">
                    <summary class="font-bold text-industry-black cursor-pointer list-none flex items-center justify-between focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-burnt-brick rounded">
                        <span>Apakah tersedia skema in-house training di lokasi perusahaan?</span>
                        <svg class="w-5 h-5 text-burnt-brick group-open:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </summary>
                    <p class="mt-3 text-sm text-dark-mahogany leading-relaxed">
                        Kebutuhan penyelenggaraan dan ketersediaan skema in-house dikonfirmasi langsung bersama penyelenggara program, termasuk jadwal dan persyaratan fasilitas lokasi.
                    </p>
                </details>

                <details class="group bg-neutral-50 rounded-lg border border-neutral-200 p-5 open:ring-2 open:ring-burnt-brick transition-all shadow-xs">
                    <summary class="font-bold text-industry-black cursor-pointer list-none flex items-center justify-between focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-burnt-brick rounded">
                        <span>Bagaimana cara mendapatkan informasi jadwal dan biaya program?</span>
                        <svg class="w-5 h-5 text-burnt-brick group-open:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </summary>
                    <p class="mt-3 text-sm text-dark-mahogany leading-relaxed">
                        Jadwal dan investasi biaya tidak dipublikasikan di halaman web. Hubungi kami melalui WhatsApp akan membantu mengonfirmasi ketersediaan program, jadwal, beserta penawaran resmi sesuai kebutuhan.
                    </p>
                </details>
            </div>
        </div>
    </section>

    <!-- FINAL CTA BANNER (DARK SURFACE) -->
    <section id="kontak" class="bg-dark-mahogany text-white py-16 sm:py-20 border-t border-industry-black relative overflow-hidden" aria-labelledby="cta-heading">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <span class="inline-block px-3 py-1 text-xs font-bold uppercase tracking-wider text-safety-orange bg-industry-black rounded-full border border-dark-mahogany mb-4">
                Konsultasi &amp; Pendaftaran Program
            </span>
            <div class="mb-8 hidden md:block">
                <img src="{{ asset('images/home/consult.webp') }}" alt="Konsultasi pengembangan kompetensi pertanian" width="900" height="600" loading="lazy" decoding="async" class="mx-auto w-full max-w-2xl h-auto max-h-[320px] md:max-h-[400px] rounded-2xl shadow-xl object-cover object-center" />
            </div>
            <h2 id="cta-heading" class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight uppercase">
                Bangun Kompetensi Pertanian Tim Anda
            </h2>
            <p class="mt-4 text-base sm:text-lg text-neutral-200 max-w-2xl mx-auto leading-relaxed">
                Hubungi kami via WhatsApp untuk konsultasi skema kompetensi, program pelatihan pertanian, atau rekomendasi skema sesuai kebutuhan organisasi Anda.
            </p>
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                <x-wa-cta variant="primary" size="lg" label="Konsultasi via WhatsApp Sekarang" class="shadow-lg bg-safety-orange hover:bg-burnt-brick text-industry-black hover:text-white font-bold" />
            </div>
        </div>
    </section>

    <!-- REGION DIRECTORY SECTION -->
    <section id="wilayah" class="py-14 bg-neutral-50 border-t border-neutral-200" aria-label="Jelajahi Wilayah">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <span class="text-xs font-bold uppercase tracking-wider text-burnt-brick bg-white px-3 py-1 rounded-full border border-burnt-brick/20">
                    Jangkauan Wilayah
                </span>
                <h2 class="text-2xl font-extrabold text-industry-black mt-2">Skema Kompetensi Pertanian di {{ count($provinces) }} Provinsi</h2>
                <p class="text-sm text-dark-mahogany mt-1">Jelajahi halaman provinsi dan kota untuk konteks layanan di wilayah Anda.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($provinces as $prov)
                    <a href="{{ route('regional.province', $prov['slug']) }}" class="block p-4 bg-white rounded-lg border border-neutral-200 hover:border-burnt-brick hover:shadow-sm transition group">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-sm text-industry-black group-hover:text-burnt-brick transition-colors">{{ $prov['name'] }}</span>
                            @if(!empty($prov['total_cities']))
                                <span class="text-[11px] text-dark-mahogany bg-neutral-100 px-2 py-0.5 rounded border border-neutral-200">{{ $prov['total_cities'] }} Kab/Kota</span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endsection
