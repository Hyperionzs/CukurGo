<?php

if (!function_exists('layoutRenderHead')) {
    /**
     * @param array<string, string> $options
     */
    function layoutRenderHead(array $options = []): void
    {
        $title = $options['title'] ?? 'CukurGo';
        $description = $options['description'] ?? 'Booking barbershop online cepat dan praktis dengan CukurGo.';
        $ogTitle = $options['og_title'] ?? $title;
        $ogDescription = $options['og_description'] ?? $description;
        $assetPrefix = $options['asset_prefix'] ?? '';
        $canonicalUrl = $options['canonical_url'] ?? '';
        $ogType = $options['og_type'] ?? 'website';

        $titleEsc = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        $descriptionEsc = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');
        $ogTitleEsc = htmlspecialchars($ogTitle, ENT_QUOTES, 'UTF-8');
        $ogDescriptionEsc = htmlspecialchars($ogDescription, ENT_QUOTES, 'UTF-8');
        $assetPrefixEsc = htmlspecialchars($assetPrefix, ENT_QUOTES, 'UTF-8');
        $canonicalEsc = htmlspecialchars($canonicalUrl, ENT_QUOTES, 'UTF-8');
        $ogTypeEsc = htmlspecialchars($ogType, ENT_QUOTES, 'UTF-8');

        echo '<!DOCTYPE html>';
        echo '<html lang="id">';
        echo '<head>';
        echo '<meta charset="UTF-8">';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<title>' . $titleEsc . '</title>';
        echo '<meta name="description" content="' . $descriptionEsc . '">';
        if ($canonicalEsc !== '') {
            echo '<link rel="canonical" href="' . $canonicalEsc . '">';
        }
        echo '<meta property="og:locale" content="id_ID">';
        echo '<meta property="og:type" content="' . $ogTypeEsc . '">';
        echo '<meta property="og:title" content="' . $ogTitleEsc . '">';
        echo '<meta property="og:description" content="' . $ogDescriptionEsc . '">';
        if ($canonicalEsc !== '') {
            echo '<meta property="og:url" content="' . $canonicalEsc . '">';
        }
        echo '<meta property="og:site_name" content="CukurGo">';
        echo '<link rel="icon" type="image/svg+xml" href="' . $assetPrefixEsc . 'assets/img/favicon.svg">';
        echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
        echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
        echo '<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Oswald:wght@400;500;600;700&display=swap" rel="stylesheet">';
        echo '<link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">';
        echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">';
        echo '<link rel="stylesheet" href="' . $assetPrefixEsc . 'assets/css/style.css">';
        if (!empty($options['extra_head'])) {
            echo $options['extra_head'];
        }
        echo '</head>';
    }
}

if (!function_exists('layoutRenderNavbar')) {
    function layoutRenderNavbar(string $variant = 'public'): void
    {
        if ($variant === 'admin') {
            echo '<nav class="navbar navbar-dark bg-dark border-bottom border-secondary mb-4">';
            echo '<div class="container">';
            echo '<a class="navbar-brand text-gold fs-4" href="dashboard.php" style="font-family: \'Lobster\', cursive;">CukurGo ADMIN</a>';
            echo '<div class="d-flex align-items-center gap-3">';
            echo '<span class="text-muted small d-none d-sm-inline">Panel Monitoring Antrean</span>';
            echo '<a class="btn btn-sm btn-outline-light" href="logout.php">Keluar</a>';
            echo '</div>';
            echo '</div>';
            echo '</nav>';
            return;
        }

        echo '<nav class="navbar navbar-expand-lg navbar-dark bg-black border-bottom border-dark sticky-top py-3 shadow-lg">';
        echo '<div class="container">';
        echo '<a class="navbar-brand text-gold fs-1" href="Index.php" style="font-family: \'Lobster\', cursive; margin-right: 2rem;">CukurGo</a>';
        echo '<button class="navbar-toggler shadow-none border-0" type="button" data-bs-toggle="collapse" data-bs-target="#cukurgoNav" aria-controls="cukurgoNav" aria-expanded="false" aria-label="Toggle navigation">';
        echo '<span class="navbar-toggler-icon"></span>';
        echo '</button>';
        echo '<div class="collapse navbar-collapse" id="cukurgoNav">';
        echo '<ul class="navbar-nav mx-auto mb-3 mb-lg-0 mt-3 mt-lg-0 gap-lg-4 text-center fw-medium">';
        echo '<li class="nav-item"><a class="nav-link text-light" href="Index.php">Home</a></li>';
        echo '<li class="nav-item"><a class="nav-link text-light" href="Index.php#features-section">Keunggulan</a></li>';
        echo '<li class="nav-item"><a class="nav-link text-light" href="Index.php#services-section">Layanan</a></li>';
        echo '<li class="nav-item"><a class="nav-link text-light" href="Privacy.php">Privasi</a></li>';
        echo '</ul>';
        echo '<div class="d-grid d-lg-block">';
        echo '<button type="button" class="btn btn-gold text-dark fw-bold rounded-pill px-4 shadow" data-bs-toggle="modal" data-bs-target="#bookingModal">Book Now</button>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</nav>';
    }
}

if (!function_exists('layoutRenderFooter')) {
    function layoutRenderFooter(string $text = ''): void
    {
        $footerText = $text !== '' ? $text : 'CukurGo - Booking barbershop online yang cepat dan rapi. © ' . date('Y');
        echo '<footer class="text-center text-gold py-4 small bg-black border-top border-dark">';
        echo htmlspecialchars($footerText, ENT_QUOTES, 'UTF-8');
        echo '</footer>';
        echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>';
    }
}
