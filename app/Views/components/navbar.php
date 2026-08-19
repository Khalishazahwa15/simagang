<?php
// Extracted and derived from Figma Make CSS and PRD v4.1

// Tentukan halaman aktif (default ke beranda jika belum di-set)
$currentPage = $currentPage ?? 'beranda';
?>
<header class="navbar-header">
    <!-- Thin gold accent line -->
    <div class="navbar-accent"></div>
    
    <div class="navbar-inner">
        <!-- Logo -->
        <a href="<?= BASE_URL ?>/" class="navbar-logo">
            <div class="navbar-logo-icon">
                <span>S</span>
            </div>
            <div class="navbar-logo-text">
                <div class="navbar-logo-title">
                    SIMAGANG
                </div>
                <div class="navbar-logo-subtitle">
                    BAPPEDA PROVINSI LAMPUNG
                </div>
            </div>
        </a>

        <!-- Desktop Nav -->
        <nav class="navbar-nav hidden-mobile">
            <?php 
                $links = [
                    'beranda' => ['label' => 'Beranda', 'url' => '/'],
                    'alur' => ['label' => 'Alur Pengajuan', 'url' => '/alur'],
                    'persyaratan' => ['label' => 'Persyaratan', 'url' => '/persyaratan'],
                    'faq' => ['label' => 'FAQ', 'url' => '/faq'],
                ];
                foreach ($links as $id => $link) {
                    $isActive = ($currentPage === $id) ? 'active' : '';
                    echo "<a href='" . BASE_URL . $link['url'] . "' class='navbar-link {$isActive}'>{$link['label']}</a>";
                }
            ?>
        </nav>

        <!-- Actions -->
        <div class="navbar-actions">
            <a href="<?= BASE_URL ?>/login" class="btn btn-outline" style="padding: 8px 18px; font-size: 13.5px; border-radius: 8px;">Masuk</a>
            <a href="<?= BASE_URL ?>/login" class="btn btn-primary" style="padding: 8px 18px; font-size: 13.5px; border-radius: 8px;">Ajukan Magang</a>
            
            <button class="mobile-menu-btn mobile-only" id="mobile-menu-toggle" aria-label="Menu">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon-menu"><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="18" x2="20" y2="18"/></svg>
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon-close" style="display:none;"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
    </div>
    
    <!-- Mobile menu -->
    <div class="mobile-nav" id="mobile-nav" style="display: none;">
        <?php foreach ($links as $id => $link): ?>
            <?php $isActive = ($currentPage === $id) ? 'active' : ''; ?>
            <a href="<?= BASE_URL . $link['url'] ?>" class="mobile-nav-link <?= $isActive ?>">
                <?= $link['label'] ?>
            </a>
        <?php endforeach; ?>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('mobile-menu-toggle');
        const mobileNav = document.getElementById('mobile-nav');
        if (toggleBtn && mobileNav) {
            const iconMenu = toggleBtn.querySelector('.icon-menu');
            const iconClose = toggleBtn.querySelector('.icon-close');
            
            toggleBtn.addEventListener('click', function() {
                const isOpen = mobileNav.style.display === 'block';
                mobileNav.style.display = isOpen ? 'none' : 'block';
                if(iconMenu) iconMenu.style.display = isOpen ? 'block' : 'none';
                if(iconClose) iconClose.style.display = isOpen ? 'none' : 'block';
            });
        }
    });
</script>
