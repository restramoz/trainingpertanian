<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Article;
use App\Support\WhatsApp;

echo "Starting fix for Article ID 9...\n";

// Find the article
$article = Article::find(9);

if (!$article) {
    echo "ERROR: Article ID 9 not found.\n";
    exit(1);
}

echo "Found article: {$article->title}\n";
echo "Status: {$article->status}\n";

// Backup the original content
$originalContent = $article->content;
$backupFile = storage_path('app/article_9_backup_' . date('Ymd_His') . '.html');
file_put_contents($backupFile, $originalContent);
echo "Backup saved to: $backupFile\n";

// The pattern to match the fallback callouts
// They all have the same structure with "Hubungi admin untuk informasi konsultasi." in a span
$fallbackPattern = '~<div class="my-6 p-4 rounded-xl bg-concrete-100 border border-concrete-300 flex flex-col sm:flex-row items-center justify-between gap-4 not-prose">.*?Hubungi admin untuk informasi konsultasi\.</span></div>~s';

// Count how many fallback callouts exist
$fallbackCount = preg_match_all($fallbackPattern, $originalContent, $matches);
echo "Found $fallbackCount fallback callouts to replace.\n";

if ($fallbackCount === 0) {
    echo "No fallback callouts found. Nothing to do.\n";
    exit(0);
}

// Get consultation numbers
$consultationNumbers = WhatsApp::consultationNumbers();
echo "Using consultation numbers: " . implode(', ', $consultationNumbers) . "\n";

// Replace each fallback callout with a proper clickable one
// We'll replace them one by one, rotating through the 4 numbers
$newContent = $originalContent;
$replaced = 0;

for ($i = 0; $i < $fallbackCount; $i++) {
    $number = $consultationNumbers[$i % count($consultationNumbers)];
    $url = WhatsApp::consultationUrl($number);
    
    if ($url === null) {
        echo "WARNING: Invalid URL for number $number, skipping.\n";
        continue;
    }
    
    $message = "Halo, saya ingin konsultasi mengenai {$article->title} untuk wilayah Jakarta Selatan.";
    $waUrl = WhatsApp::url($message, $number);
    
    $calloutAction = '<a href="' . $waUrl . '" target="_blank" rel="noopener noreferrer"'
        . ' class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-semibold text-xs transition shrink-0">'
        . '<span>Konsultasi WhatsApp</span> &rarr;'
        . '</a>';
    
    $calloutHtml = '<div class="my-6 p-4 rounded-xl bg-concrete-100 border border-concrete-300 flex flex-col sm:flex-row items-center justify-between gap-4 not-prose">'
        . '<div>'
        . '<div class="text-xs font-bold uppercase tracking-wider text-teal-700">Konsultasi ' . $article->title . '</div>'
        . '<div class="text-sm font-semibold text-navy-950">Butuh informasi pelatihan atau skema kompetensi pertanian?</div>'
        . '</div>'
        . $calloutAction
        . '</div>';
    
    // Replace the first occurrence only
    $newContent = preg_replace($fallbackPattern, $calloutHtml, $newContent, 1);
    $replaced++;
    echo "Replaced callout $replaced with number: $number -> $waUrl\n";
}

if ($replaced !== $fallbackCount) {
    echo "WARNING: Only replaced $replaced out of $fallbackCount callouts.\n";
}

// Verify no fallback text remains
$remainingFallbacks = substr_count($newContent, 'Hubungi admin untuk informasi konsultasi.');
if ($remainingFallbacks > 0) {
    echo "ERROR: Still $remainingFallbacks fallback texts remaining!\n";
    exit(1);
}

// Verify all 5 callouts now have valid wa.me links
$waMeCount = substr_count($newContent, 'wa.me/');
echo "Total wa.me links in content: $waMeCount\n";

// Verify the consultation block at the end is still intact
if (!str_contains($newContent, 'article-consultation-numbers')) {
    echo "ERROR: The article-consultation-numbers block was removed!\n";
    exit(1);
}

echo "Consultation block at end is preserved.\n";

// Count the article-consultation-numbers links
$consultationLinks = 0;
foreach ($consultationNumbers as $num) {
    $url = WhatsApp::consultationUrl($num);
    $consultationLinks += substr_count($newContent, $url);
}
echo "Consultation block links found: $consultationLinks (expected 4)\n";

// Save the updated content
$article->content = $newContent;
$article->save();

echo "\nArticle ID 9 updated successfully!\n";
echo "Replaced $replaced fallback callouts with clickable WhatsApp links.\n";
echo "The 4-number consultation block at the end is preserved.\n";