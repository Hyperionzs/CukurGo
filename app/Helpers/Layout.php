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
        echo '<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">';
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
            echo '<a class="navbar-brand fw-bold text-gold" href="dashboard.php">CukurGo ADMIN</a>';
            echo '<div class="d-flex align-items-center gap-3">';
            echo '<span class="text-muted small d-none d-sm-inline">Panel Monitoring Antrean</span>';
            echo '<a class="btn btn-sm btn-outline-light" href="logout.php">Keluar</a>';
            echo '</div>';
            echo '</div>';
            echo '</nav>';
            return;
        }

        echo '<nav class="navbar navbar-dark bg-dark border-bottom border-secondary">';
        echo '<div class="container">';
        echo '<a class="navbar-brand fw-bold text-gold" href="Index.php">CukurGo</a>';
        echo '</div>';
        echo '</nav>';
    }
}

if (!function_exists('layoutRenderFooter')) {
    function layoutRenderFooter(string $text = ''): void
    {
        $footerText = $text !== '' ? $text : 'CukurGo - Booking barbershop online yang cepat dan rapi.';
        echo '<footer class="text-center text-muted py-4 small">';
        echo htmlspecialchars($footerText, ENT_QUOTES, 'UTF-8');
        echo '</footer>';
    }
}

