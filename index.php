<?php 
require_once 'includes/header.php'; 
require_once 'includes/db.php';

// Fetch slideshow images
$stmt = $pdo->query("SELECT * FROM media WHERE category = 'slideshow' ORDER BY sort_order ASC");
$slideshow_images = $stmt->fetchAll();

// Fetch gallery images (all of them)
$stmt = $pdo->query("SELECT * FROM media WHERE category = 'gallery' ORDER BY sort_order ASC");
$gallery_images = $stmt->fetchAll();

// Fetch attire images
$stmt = $pdo->query("SELECT * FROM media WHERE category = 'attire' ORDER BY sort_order ASC");
$attire_images = $stmt->fetchAll();

// Fetch venue images
$stmt = $pdo->query("SELECT * FROM media WHERE category = 'venue' ORDER BY sort_order ASC LIMIT 2");
$venue_images = $stmt->fetchAll();

// Fetch pre-wedding video
$stmt = $pdo->query("SELECT * FROM media WHERE category = 'pre_wedding_video' ORDER BY sort_order ASC LIMIT 1");
$pre_wedding_video = $stmt->fetch();

// Fetch venue main images
$stmt = $pdo->query("SELECT * FROM media WHERE category = 'venue_main' ORDER BY sort_order ASC");
$venue_main_images = $stmt->fetchAll();

// Registry images will be fetched directly in the section to ensure data integrity

// Fetch other details images
$stmt = $pdo->query("SELECT * FROM media WHERE category = 'other_details' ORDER BY sort_order ASC");
$other_details_images = $stmt->fetchAll();

// Fetch active FAQs
$stmt = $pdo->query("SELECT * FROM faqs WHERE status = 'active' ORDER BY sort_order ASC, id ASC");
$faqs = $stmt->fetchAll();

// Fetch RSVP well wishes (non-empty messages)
$wishes_stmt = $pdo->query("SELECT full_name, attendance, message, submitted_at FROM rsvps WHERE TRIM(message) != '' AND message IS NOT NULL ORDER BY submitted_at DESC");
$wishes = $wishes_stmt->fetchAll();

// Fetch RSVP background video
$rsvp_bg_stmt = $pdo->prepare("SELECT * FROM media WHERE category = 'rsvp_bg_video' LIMIT 1");
$rsvp_bg_stmt->execute();
$rsvp_bg_video = $rsvp_bg_stmt->fetch();
?>

<!-- Hero Section -->
<section class="relative min-h-[calc(100vh-80px)] md:h-[921px] py-12 md:py-0 flex items-center justify-center overflow-hidden">
<div id="hero-slideshow" class="absolute inset-0 z-0">
<?php foreach ($slideshow_images as $index => $img): ?>
<div class="slideshow-item absolute inset-0 <?= $index === 0 ? 'opacity-100' : 'opacity-0' ?> transition-opacity duration-1000 bg-cover bg-center brightness-75" style="background-image: url('<?= BASE_URL ?>/<?= htmlspecialchars($img['file_path']) ?>');"></div>
<?php endforeach; ?>
</div>
<div class="relative z-10 text-center text-white px-gutter w-full max-w-4xl">
<p class="font-label-caps text-label-caps mb-4 tracking-[0.4em]"><?= htmlspecialchars(get_setting('hero_tagline', 'SAVE THE DATE')) ?></p>
    <h1 class="font-display-lg text-4xl md:text-[84px] mb-6 italic leading-tight"><?= SITE_TITLE ?></h1>
<div class="signature-line !bg-white/60"></div>
<p class="font-headline-md text-headline-md mb-2"><?= WEDDING_DATE ?></p>

<!-- Countdown Timer -->
<div id="countdown" class="flex justify-center gap-6 mt-8 mb-8" data-date="<?= WEDDING_DATE ?>">
<div class="flex flex-col items-center">
<span id="days" class="text-4xl md:text-5xl font-light">00</span>
<span class="text-[10px] uppercase tracking-widest opacity-70 mt-1">Days</span>
</div>
<div class="flex flex-col items-center">
<span id="hours" class="text-4xl md:text-5xl font-light">00</span>
<span class="text-[10px] uppercase tracking-widest opacity-70 mt-1">Hours</span>
</div>
<div class="flex flex-col items-center">
<span id="minutes" class="text-4xl md:text-5xl font-light">00</span>
<span class="text-[10px] uppercase tracking-widest opacity-70 mt-1">Minutes</span>
</div>
<div class="flex flex-col items-center">
<span id="seconds" class="text-4xl md:text-5xl font-light">00</span>
<span class="text-[10px] uppercase tracking-widest opacity-70 mt-1">Seconds</span>
</div>
</div>

<p class="font-body-lg text-body-lg tracking-widest uppercase"><?= htmlspecialchars(get_setting('hero_locations', 'LOKAL NG TRECE MARTIRES CITY • PRECIOUS GARDEN EVENTS PLACE')) ?></p>
</div>
</section>
<!-- Pre-Wedding Video Section -->
<section class="py-section-padding px-8 md:px-[80px] bg-surface" id="story">
<div class="max-w-container-max mx-auto">
<div class="grid md:grid-cols-12 gap-gutter items-center">
<div class="md:col-span-6 order-2 md:order-1">
<div class="relative aspect-video border border-secondary/20 p-2 bg-white shadow-lg rounded-xl overflow-hidden">
<?php 
$youtube_link = get_setting('video_youtube_link', '');
$youtube_id = !empty($youtube_link) ? get_youtube_id($youtube_link) : null;

if ($youtube_id): 
?>
    <iframe id="story-youtube-iframe" class="w-full h-full rounded-lg" 
            src="https://www.youtube.com/embed/<?= htmlspecialchars($youtube_id) ?>?autoplay=0&rel=0&enablejsapi=1" 
            title="Our Pre-Wedding Video" 
            frameborder="0" 
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
            allowfullscreen>
    </iframe>
<?php elseif ($pre_wedding_video): ?>
    <video id="story-html5-video" class="w-full h-full object-cover rounded-lg" preload="metadata" playsinline controls>
        <source src="<?= BASE_URL ?>/<?= htmlspecialchars($pre_wedding_video['file_path']) ?>#t=0.001" type="video/<?= pathinfo($pre_wedding_video['file_path'], PATHINFO_EXTENSION) ?>">
        Your browser does not support the video tag.
    </video>
<?php else: ?>
    <div class="w-full h-full bg-slate-100 flex flex-col items-center justify-center text-slate-400 gap-2">
        <span class="material-symbols-outlined text-4xl">videocam_off</span>
        <p class="font-body-md font-bold">No Pre-Wedding Video Uploaded</p>
    </div>
<?php endif; ?>
</div>
</div>
<div class="md:col-span-6 order-1 md:order-2 md:pl-stack-lg">
<span class="font-label-caps text-label-caps text-secondary mb-4 block"><?= htmlspecialchars(get_setting('video_section_subtitle', 'PRE-WEDDING VIDEO')) ?></span>
<h2 class="font-headline-lg text-headline-lg mb-stack-md text-primary"><?= htmlspecialchars(get_setting('video_section_title', 'Our Pre-Wedding Video')) ?></h2>
<div class="space-y-6 text-on-surface-variant font-body-lg">
<?= nl2br(htmlspecialchars(get_setting('video_description', "A celebration of our journey leading up to this special day. We captured these moments to share our joy, love, and anticipation with all of you.\n\nSit back, relax, and enjoy a glimpse into our story and the lifetime of love that lies ahead of us. We are so excited to celebrate with you!"))) ?>
</div>

<?php if (!empty($youtube_link)): ?>
<div class="mt-8 flex flex-col sm:flex-row items-start gap-4">
    <a href="<?= htmlspecialchars($youtube_link) ?>" target="_blank" class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-amber-600 to-amber-700 hover:from-amber-700 hover:to-amber-800 text-white font-label-caps tracking-widest uppercase text-xs rounded-lg shadow-lg hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 group">
        <span class="material-symbols-outlined text-lg animate-pulse">hd</span>
        View video in HD 2160p
        <span class="material-symbols-outlined text-sm transition-transform duration-300 group-hover:translate-x-1">open_in_new</span>
    </a>
</div>
<?php endif; ?>

<div class="signature-line !ml-0 mt-stack-md"></div>
</div>
</div>
</div>
</section>
<!-- Venue Section -->
<section class="py-section-padding px-8 md:px-[80px] bg-primary text-white" id="venue">
<div class="max-w-container-max mx-auto text-center mb-stack-lg">
<span class="font-label-caps text-label-caps text-secondary-container mb-4 block"><?= htmlspecialchars(get_setting('venue_section_subtitle', 'THE CELEBRATION')) ?></span>
<h2 class="font-headline-lg text-headline-lg mb-4"><?= htmlspecialchars(get_setting('venue_section_title', 'Save the Date')) ?></h2>
<p class="font-body-lg text-on-primary-container"></p>
</div>
<div class="grid md:grid-cols-2 gap-stack-md max-w-container-max mx-auto">
<div class="p-0 border border-white/10 flex flex-col items-center text-center overflow-hidden">
<?php if (isset($venue_images[0])): ?>
<div class="w-full h-64 overflow-hidden mb-6">
<img class="w-full h-full object-cover hover:scale-105 transition-transform duration-700" src="<?= BASE_URL ?>/<?= htmlspecialchars($venue_images[0]['file_path']) ?>" alt="<?= htmlspecialchars($venue_images[0]['alt_text']) ?>"/>
</div>
<?php else: ?>
<span class="material-symbols-outlined text-4xl text-secondary-container mb-4 mt-8">church</span>
<?php endif; ?>
<div class="p-6">
<h3 class="font-headline-md text-headline-md mb-2"><?= htmlspecialchars(get_setting('venue_ceremony_title', 'The Ceremony')) ?></h3>
<p class="font-body-md text-on-primary-container font-bold text-amber-200"><?= htmlspecialchars(get_setting('venue_ceremony_name', 'Iglesia Ni Cristo Lokal ng Trece Martires City')) ?></p>
<p class="font-body-md text-on-primary-container mt-2"><?= htmlspecialchars(get_setting('venue_ceremony_time', "Two O'Clock (2PM) in the Afternoon")) ?></p>
<a href="<?= htmlspecialchars(get_setting('venue_ceremony_maps_url', 'https://maps.app.goo.gl/iZZWxB1GoanBz8BR9')) ?>" target="_blank" class="directions-btn group mt-6 inline-flex items-center gap-2.5 relative overflow-hidden">
    <span class="directions-btn-shimmer"></span>
    <span class="material-symbols-outlined text-base directions-btn-icon">near_me</span>
    <span class="directions-btn-label">Get Directions</span>
    <span class="material-symbols-outlined text-sm directions-btn-arrow">arrow_forward</span>
</a>
</div>
</div>
<div class="p-0 border border-white/10 flex flex-col items-center text-center overflow-hidden">
<?php if (isset($venue_images[1])): ?>
<div class="w-full h-64 overflow-hidden mb-6">
<img class="w-full h-full object-cover hover:scale-105 transition-transform duration-700" src="<?= BASE_URL ?>/<?= htmlspecialchars($venue_images[1]['file_path']) ?>" alt="<?= htmlspecialchars($venue_images[1]['alt_text']) ?>"/>
</div>
<?php else: ?>
<span class="material-symbols-outlined text-4xl text-secondary-container mb-4 mt-8">liquor</span>
<?php endif; ?>
<div class="p-6">
<h3 class="font-headline-md text-headline-md mb-2"><?= htmlspecialchars(get_setting('venue_reception_title', 'The Reception')) ?></h3>
<p class="font-body-md text-on-primary-container font-bold text-amber-200"><?= htmlspecialchars(get_setting('venue_reception_name', 'Precious Garden Events Place')) ?></p>
<p class="font-body-md text-on-primary-container mt-2"><?= htmlspecialchars(get_setting('venue_reception_time', "Four O'Clock (4PM) in the Afternoon")) ?></p>
<a href="<?= htmlspecialchars(get_setting('venue_reception_maps_url', 'https://maps.app.goo.gl/zKEA2wewnyRxkFLu6')) ?>" target="_blank" class="directions-btn group mt-6 inline-flex items-center gap-2.5 relative overflow-hidden">
    <span class="directions-btn-shimmer"></span>
    <span class="material-symbols-outlined text-base directions-btn-icon">near_me</span>
    <span class="directions-btn-label">Get Directions</span>
    <span class="material-symbols-outlined text-sm directions-btn-arrow">arrow_forward</span>
</a>
</div>
</div>
</div>
<!-- Venue Gallery Grid -->
<div class="mt-stack-lg max-w-container-max mx-auto px-4 md:px-0">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php if (!empty($venue_main_images)): ?>
            <?php foreach ($venue_main_images as $img): ?>
                <div class="aspect-[4/5] overflow-hidden rounded-xl shadow-lg border border-white/10 group cursor-pointer">
                    <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 gallery-trigger" 
                         src="<?= BASE_URL ?>/<?= htmlspecialchars($img['file_path']) ?>" 
                         alt="<?= htmlspecialchars($img['alt_text']) ?>"
                         data-alt="<?= htmlspecialchars($img['alt_text']) ?>"/>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

</section>
<!-- Gallery Section (Bento Grid) -->
<section class="py-section-padding px-8 md:px-[80px] bg-surface-bright" id="gallery">
<div class="max-w-container-max mx-auto">
<div class="text-center mb-stack-lg">
<span class="font-label-caps text-label-caps text-secondary mb-4 block"><?= htmlspecialchars(get_setting('gallery_subtitle', 'MOMENTS')) ?></span>
<h2 class="font-headline-lg text-headline-lg text-primary"><?= htmlspecialchars(get_setting('gallery_title', 'Gallery')) ?></h2>
</div>
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 auto-rows-[200px] md:auto-rows-[300px]">
<?php 
$bento_images = array_slice($gallery_images, 0, 4);
$extra_images = array_slice($gallery_images, 4);

$grid_classes = [
    0 => 'col-span-2 row-span-2',
    1 => 'col-span-1 row-span-1',
    2 => 'col-span-1 row-span-2',
    3 => 'col-span-1 row-span-1'
];
foreach ($bento_images as $index => $img): 
    $class = $grid_classes[$index] ?? 'col-span-1 row-span-1';
?>
<div class="<?= $class ?> overflow-hidden cursor-pointer group">
<img class="w-full h-full object-cover hover:scale-105 transition-transform duration-700 gallery-trigger" data-alt="<?= htmlspecialchars($img['alt_text']) ?>" src="<?= BASE_URL ?>/<?= htmlspecialchars($img['file_path']) ?>"/>
</div>
<?php endforeach; ?>
</div>

<?php if (!empty($extra_images)): ?>
<div id="extra-gallery" class="hidden mt-4">
<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
<?php foreach ($extra_images as $img): ?>
<div class="aspect-square overflow-hidden cursor-pointer group">
<img class="w-full h-full object-cover hover:scale-105 transition-transform duration-700 gallery-trigger" data-alt="<?= htmlspecialchars($img['alt_text']) ?>" src="<?= BASE_URL ?>/<?= htmlspecialchars($img['file_path']) ?>"/>
</div>
<?php endforeach; ?>
</div>
</div>

<div class="text-center mt-12">
<button id="toggle-gallery" class="px-8 py-4 border border-secondary text-secondary font-label-caps hover:bg-secondary hover:text-white transition-all duration-300">
<?= htmlspecialchars(get_setting('gallery_button_text', 'See more pictures')) ?>
</button>
</div>
<?php endif; ?>
</div>
</section>
<!-- Attire Section -->
<section class="py-section-padding px-8 md:px-[80px] bg-surface-container-low" id="attire">
<div class="max-w-[800px] mx-auto text-center">
<span class="font-label-caps text-label-caps text-secondary mb-4 block"><?= htmlspecialchars(get_setting('attire_subtitle', 'DRESS CODE')) ?></span>
<h2 class="font-headline-lg text-headline-lg text-primary mb-stack-md"><?= htmlspecialchars(get_setting('attire_title', 'Formal Attire')) ?></h2>
<div class="signature-line"></div>

<?php if (!empty($attire_images)): ?>
<div class="grid md:grid-cols-2 gap-8 mt-16">
<?php foreach ($attire_images as $img): ?>
<div class="space-y-4">
<div class="aspect-[3/4] overflow-hidden border border-secondary/20 p-2 bg-white shadow-sm cursor-pointer group">
<img class="w-full h-full object-cover transition-transform duration-700 hover:scale-105 gallery-trigger" src="<?= BASE_URL ?>/<?= htmlspecialchars($img['file_path']) ?>" alt="<?= htmlspecialchars($img['alt_text']) ?>" data-alt="<?= htmlspecialchars($img['alt_text']) ?>"/>
</div>
<?php if ($img['alt_text']): ?>
<p class="font-label-caps text-secondary text-sm"><?= htmlspecialchars($img['alt_text']) ?></p>
<?php endif; ?>
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>
</div>
</section>
<!-- Other Details Section -->
<section id="other-details" class="py-section-padding px-8 md:px-[80px] bg-surface-container-low">
    <div class="max-w-container-max mx-auto">
        <div class="text-center mb-stack-lg">
            <span class="font-label-caps text-label-caps text-secondary mb-4 block"><?= htmlspecialchars(get_setting('details_subtitle', 'INVITATION DETAILS')) ?></span>
            <h2 class="font-headline-lg text-headline-lg text-primary mb-4"><?= htmlspecialchars(get_setting('details_title', 'Other Details')) ?></h2>
            <div class="signature-line"></div>
            <p class="font-body-lg text-on-surface-variant max-w-[600px] mx-auto mt-4"><?= htmlspecialchars(get_setting('details_description', 'We have reserved 1 seat just for you. Please confirm your attendance through our wedding website. Kindly RSVP on or before August 3, 2026 .')) ?></p>
        </div>

        <?php if (!empty($other_details_images)): ?>
        <div id="other-details-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <?php foreach ($other_details_images as $img): ?>
            <div class="group overflow-hidden rounded-2xl shadow-md border border-secondary/10 bg-white cursor-pointer"
                 onclick="openOtherDetailsLightbox('<?= BASE_URL ?>/<?= htmlspecialchars($img['file_path']) ?>', '<?= htmlspecialchars($img['alt_text']) ?>')">
                <div class="w-full overflow-hidden">
                    <img class="w-full h-auto object-contain transition-transform duration-700 group-hover:scale-110"
                         src="<?= BASE_URL ?>/<?= htmlspecialchars($img['file_path']) ?>"
                         alt="<?= htmlspecialchars($img['alt_text'] ?: 'Invitation Detail') ?>"/>
                </div>
                <?php if ($img['alt_text']): ?>
                <div class="px-4 py-3 border-t border-secondary/10">
                    <p class="font-label-caps text-secondary text-xs tracking-widest"><?= htmlspecialchars($img['alt_text']) ?></p>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-16 text-on-surface-variant">
            <span class="material-symbols-outlined text-5xl mb-4 block opacity-30">info</span>
            <p class="font-body-lg italic opacity-50">Details coming soon. Check back after your RSVP.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Other Details Lightbox -->
<div id="other-details-lightbox" class="fixed inset-0 z-[200] hidden flex items-center justify-center bg-black/80 backdrop-blur-md p-4 md:p-12">
    <button onclick="closeOtherDetailsLightbox()" class="absolute top-6 right-6 text-white hover:text-amber-500 transition-colors z-50">
        <span class="material-symbols-outlined text-4xl">close</span>
    </button>
    <div class="relative max-w-full max-h-full flex flex-col items-center justify-center transition-all duration-500">
        <img id="od-lightbox-img" src="" alt="" class="max-w-full max-h-[85vh] object-contain shadow-2xl border-4 border-white/10 rounded-xl"/>
        <p id="od-lightbox-caption" class="mt-4 text-white font-label-caps text-sm tracking-widest"></p>
    </div>
</div>

<script>
function openOtherDetailsLightbox(src, caption) {
    document.getElementById('od-lightbox-img').src = src;
    document.getElementById('od-lightbox-caption').innerText = caption || '';
    const lb = document.getElementById('other-details-lightbox');
    lb.classList.remove('hidden');
}
function closeOtherDetailsLightbox() {
    document.getElementById('other-details-lightbox').classList.add('hidden');
    document.getElementById('od-lightbox-img').src = '';
}
document.addEventListener('DOMContentLoaded', function() {
    const lb = document.getElementById('other-details-lightbox');
    if (lb) {
        lb.addEventListener('click', function(e) { if (e.target === lb) closeOtherDetailsLightbox(); });
        document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeOtherDetailsLightbox(); });
    }
});
</script>

<!-- FAQ Section -->
<section id="faq" class="py-section-padding px-8 md:px-[80px] bg-primary text-white border-t border-b border-white/10">
    <div class="max-w-container-max mx-auto">
        <div class="text-center mb-stack-lg">
            <span class="font-label-caps text-label-caps text-secondary-container mb-4 block"><?= htmlspecialchars(get_setting('faq_subtitle', 'HAVE QUESTIONS?')) ?></span>
            <h2 class="font-headline-lg text-headline-lg text-white mb-4 font-serif italic"><?= htmlspecialchars(get_setting('faq_title', 'Frequently Asked Questions')) ?></h2>
            <div class="signature-line !bg-white/30"></div>
            <p class="font-body-lg text-on-primary-container max-w-[600px] mx-auto mt-4"><?= htmlspecialchars(get_setting('faq_description', "We have compiled answers to some of the most common questions of our guests. If you can't find what you need, feel free to reach out.")) ?></p>
        </div>

        <?php if (!empty($faqs)): ?>
        <div class="max-w-[800px] mx-auto space-y-4">
            <?php foreach ($faqs as $faq): ?>
            <div class="faq-item border border-white/10 rounded-2xl bg-white/5 transition-all duration-300 hover:bg-white/10 hover:border-secondary-container/30">
                <button type="button" class="faq-toggle w-full flex items-center justify-between p-6 text-left focus:outline-none">
                    <span class="font-serif text-lg md:text-xl text-white font-semibold tracking-wide transition-colors duration-300">
                        <?= htmlspecialchars($faq['question']) ?>
                    </span>
                    <span class="faq-icon material-symbols-outlined text-secondary-container ml-4 transition-transform duration-300 select-none">
                        add
                    </span>
                </button>
                <div class="faq-content max-h-0 overflow-hidden transition-all duration-500 ease-in-out">
                    <div class="p-6 pt-0 border-t border-white/5 font-body-md text-slate-300 leading-relaxed">
                        <?= nl2br(htmlspecialchars($faq['answer'])) ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-16 text-slate-300 bg-white/5 border border-white/10 rounded-2xl max-w-[800px] mx-auto">
            <span class="material-symbols-outlined text-5xl mb-4 block opacity-50 text-secondary-container">help_center</span>
            <p class="font-body-lg italic opacity-70">Check back soon for answers to your questions!</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- RSVP Section -->
<section class="relative py-section-padding px-8 md:px-[80px] bg-surface-container-low overflow-hidden" id="rsvp">
    <?php if ($rsvp_bg_video): ?>
        <video class="absolute inset-0 w-full h-full object-cover z-0 pointer-events-none" autoplay loop muted playsinline>
            <source src="<?= BASE_URL ?>/<?= htmlspecialchars($rsvp_bg_video['file_path']) ?>" type="video/<?= pathinfo($rsvp_bg_video['file_path'], PATHINFO_EXTENSION) ?>">
        </video>
        <!-- Overlay mask to balance contrast and readability -->
        <div class="absolute inset-0 bg-[#fcfaf7]/75 dark:bg-slate-950/75 backdrop-blur-[1px] z-0"></div>
    <?php endif; ?>

    <div class="relative max-w-container-max mx-auto z-10">
        <div class="grid lg:grid-cols-12 gap-12 items-stretch">
            
            <!-- Left Column: RSVP Form -->
            <div class="lg:col-span-5 bg-white/75 dark:bg-slate-900/75 backdrop-blur-md border border-amber-200/40 dark:border-slate-800 p-8 md:p-12 shadow-md rounded-2xl flex flex-col justify-between">
                <div>
                    <div class="text-center lg:text-left mb-8">
                        <h2 class="font-display-lg text-headline-lg italic text-primary leading-tight"><?= htmlspecialchars(get_setting('rsvp_title', 'Kindly Respond')) ?></h2>
                        <div class="signature-line !ml-0 mt-2 mb-4 w-16 hidden lg:block"></div>
                        <p class="font-body-md text-on-surface-variant mt-2 text-sm">Please let us know if you can join us on our special day.</p>
                    </div>
                    <div id="rsvp-message"></div>
                    
                    <?php if (get_setting('rsvp_enabled', '1') == '1'): ?>
                        <form id="rsvp-form" class="space-y-6">
                            <div class="space-y-1">
                                <label class="font-label-caps text-[11px] tracking-widest uppercase text-primary font-bold">Full Name</label>
                                <input name="full_name" required class="w-full border-0 border-b border-outline-variant focus:border-secondary focus:ring-0 bg-transparent py-2 font-body-md" type="text"/>
                            </div>
                            <div class="grid sm:grid-cols-2 gap-6">
                                <div class="space-y-1">
                                    <label class="font-label-caps text-[11px] tracking-widest uppercase text-primary font-bold">Attendance</label>
                                    <select name="attendance" required class="w-full border-0 border-b border-outline-variant focus:border-secondary focus:ring-0 bg-transparent py-2 font-body-md">
                                        <option value="Yes">Yes, I'll be there</option>
                                        <option value="No">Sorry, I can't make it</option>
                                    </select>
                                </div>
                                <div class="space-y-1">
                                    <label class="font-label-caps text-[11px] tracking-widest uppercase text-primary font-bold">Contact Number</label>
                                    <input name="contact_number" required class="w-full border-0 border-b border-outline-variant focus:border-secondary focus:ring-0 bg-transparent py-2 font-body-md" type="tel"/>
                                </div>
                            </div>
                            <div class="space-y-1">
                                <label class="font-label-caps text-[11px] tracking-widest uppercase text-primary font-bold">Message (Optional)</label>
                                <textarea name="message" rows="3" class="w-full border-0 border-b border-outline-variant focus:border-secondary focus:ring-0 bg-transparent py-2 font-body-md placeholder:text-outline-variant" placeholder="Leave a message for the couple..."></textarea>
                            </div>
                            <button class="w-full bg-primary-container text-white py-4 font-serif tracking-widest uppercase text-sm hover:bg-primary transition-all duration-500 mt-6" type="submit">
                                Submit RSVP
                            </button>
                        </form>
                    <?php else: ?>
                        <div class="p-8 text-center bg-amber-500/10 border border-amber-500/30 rounded-xl space-y-4 my-4">
                            <span class="material-symbols-outlined text-4xl text-amber-700 dark:text-amber-500">lock</span>
                            <h3 class="font-serif text-lg font-bold text-primary dark:text-slate-100">RSVP Submissions Are Closed</h3>
                            <p class="font-body-md text-on-surface-variant text-sm text-slate-600 dark:text-slate-300">
                                <?= nl2br(htmlspecialchars(get_setting('rsvp_disabled_message', 'RSVP submissions for our wedding are currently closed.'))) ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Column: Guest Well Wishes -->
            <div class="lg:col-span-7 bg-white/75 dark:bg-slate-900/75 backdrop-blur-md border border-amber-200/40 dark:border-slate-800 p-8 md:p-12 shadow-md rounded-2xl flex flex-col">
                <div class="mb-6">
                    <h3 class="font-display-lg text-headline-md italic leading-tight wishes-heading-shimmer">Messages of Love</h3>
                    <div class="signature-line !ml-0 mt-2 mb-4 w-16"></div>
                    <p class="font-body-md text-on-surface-variant text-sm">Congratulatory messages and well wishes from our loved ones.</p>
                </div>
                
                <div id="wishes-list" class="space-y-4 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar flex-grow">
                    <?php if (!empty($wishes)): ?>
                        <?php foreach ($wishes as $wish): ?>
                            <div class="wish-card bg-white/50 dark:bg-slate-950/50 backdrop-blur-sm border border-amber-200/10 p-5 rounded-2xl flex gap-4 transition-all duration-300 hover:border-amber-200/40 hover:bg-white/70">
                                <div class="flex-shrink-0">
                                    <div class="wish-avatar wish-avatar-shimmer w-10 h-10 rounded-full flex items-center justify-center text-secondary font-bold text-sm uppercase ring-2 ring-amber-200/30 shadow-sm">
                                        <?= htmlspecialchars(mb_substr(trim($wish['full_name']), 0, 1)) ?>
                                    </div>
                                </div>
                                <div class="flex-grow">
                                    <div class="mb-1">
                                        <span class="font-bold text-primary font-serif leading-tight">
                                            <?= htmlspecialchars($wish['full_name']) ?>
                                        </span>
                                    </div>
                                    <p class="font-body-md text-on-surface-variant italic leading-relaxed text-slate-600 dark:text-slate-300">
                                        "<?= htmlspecialchars($wish['message']) ?>"
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div id="no-wishes-placeholder" class="flex flex-col items-center justify-center py-16 text-center text-on-surface-variant h-full">
                            <span class="material-symbols-outlined text-4xl text-amber-600/60 mb-2 animate-pulse">favorite</span>
                            <p class="font-body-md italic text-slate-400">Be the first to leave a message of love for the couple!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

    <!-- RSVP Success Modal -->
    <div id="rsvp-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="bg-white max-w-md w-full p-8 md:p-12 text-center shadow-2xl transform transition-all duration-500 scale-95 opacity-0" id="rsvp-modal-content">
            <span class="material-symbols-outlined text-6xl text-amber-500 mb-6">celebration</span>
            <h3 class="font-display-lg text-headline-lg text-primary mb-4" id="rsvp-modal-title">Thank You!</h3>
            <p class="font-body-lg text-on-surface-variant mb-8" id="rsvp-modal-text">Your response has been saved.</p>
            <button id="close-rsvp-modal" class="w-full bg-primary text-white py-4 font-serif tracking-widest uppercase text-sm hover:bg-secondary transition-all duration-300">
                Close
            </button>
        </div>
    </div>
</section>

<!-- Registry Section -->
<section class="py-section-padding px-8 md:px-[80px] bg-surface" id="registry">
<div class="max-w-container-max mx-auto text-center">
<span class="font-label-caps text-label-caps text-secondary mb-4 block"><?= htmlspecialchars(get_setting('gift_subtitle', '- - - - - - -')) ?></span>
<h2 class="font-headline-lg text-headline-lg text-primary mb-stack-md"><?= htmlspecialchars(get_setting('gift_title', 'Gift Guide')) ?></h2>
<p class="font-body-lg text-on-surface-variant max-w-[600px] mx-auto mb-stack-lg">
                    <?= nl2br(htmlspecialchars(get_setting('gift_description', "Your presence and prayers are the greatest gifts we could ask for. However, if you wish to bless us further, a monetary gift would be sincerely appreciated as we begin our new journey together."))) ?>
                </p>


<?php 
// Direct fetch for maximum reliability
$qr_stmt = $pdo->query("SELECT * FROM media WHERE category = 'qr_code' ORDER BY sort_order ASC");
$all_qrs = $qr_stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($all_qrs) > 0): 
?>
<div class="mt-16 pt-12 border-t border-secondary/10 max-w-[900px] mx-auto">
    <h3 class="font-display-lg text-headline-md text-primary mb-6 italic"><?= htmlspecialchars(get_setting('gift_banking_title', 'Gift via Banking / QR Code')) ?></h3>
    
    <button onclick="document.getElementById('qr-container').classList.toggle('hidden')" class="mb-10 px-8 py-3 border border-secondary text-secondary font-label-caps tracking-widest uppercase hover:bg-secondary hover:text-white transition-all duration-300 mx-auto block">
        <?= htmlspecialchars(get_setting('gift_button_text', 'See QR Codes')) ?>
    </button>

    <div id="qr-container" class="hidden transition-all duration-500">
        <div class="flex flex-wrap justify-center gap-12">
            <?php foreach ($all_qrs as $qr): ?>
                <div class="flex flex-col items-center space-y-4 group">
                    <div class="w-56 h-56 bg-white border border-secondary/20 p-4 shadow-sm group-hover:shadow-md transition-all duration-300 cursor-pointer overflow-hidden">
                        <img class="w-full h-full object-contain gallery-trigger transition-transform duration-500 group-hover:scale-105" 
                             src="<?= BASE_URL ?>/<?= htmlspecialchars($qr['file_path']) ?>" 
                             alt="<?= htmlspecialchars($qr['alt_text'] ?: 'Payment QR Code') ?>"
                             data-alt="<?= htmlspecialchars($qr['alt_text'] ?: 'Payment QR Code') ?>"/>
                    </div>
                    <?php if ($qr['alt_text']): ?>
                        <p class="font-label-caps text-secondary text-sm font-bold tracking-widest bg-secondary/5 px-4 py-1 rounded-full"><?= htmlspecialchars($qr['alt_text']) ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <p class="font-body-md text-on-surface-variant mt-12 max-w-[500px] mx-auto italic"><?= htmlspecialchars(get_setting('gift_qr_footer', 'Scan any of the QR codes above to send your gift. We are truly grateful for your support!')) ?></p>
    </div>
</div>
<?php endif; ?>
</div>
</section>



                <!-- Gallery Lightbox Modal -->
                <div id="gallery-lightbox" class="fixed inset-0 z-[200] hidden flex items-center justify-center bg-black/80 backdrop-blur-md p-4 md:p-12">
                    <button id="close-lightbox" class="absolute top-6 right-6 text-white hover:text-amber-500 transition-colors z-50">
                        <span class="material-symbols-outlined text-4xl">close</span>
                    </button>
                    <div class="relative max-w-full max-h-full flex items-center justify-center transform transition-all duration-500 scale-95 opacity-0" id="lightbox-content">
                        <img id="lightbox-img" src="" alt="Full size image" class="max-w-full max-h-[90vh] object-contain shadow-2xl border-4 border-white/10"/>
                        <p id="lightbox-caption" class="absolute -bottom-10 left-0 w-full text-center text-white font-label-caps text-sm tracking-widest"></p>
                    </div>
                </div>

<?php require_once 'includes/footer.php'; ?>
