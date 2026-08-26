<?php
// Extracted and derived from Figma Make CSS and PRD v4.1

// Tentukan halaman aktif (default ke beranda jika belum di-set)
$currentPage = $currentPage ?? 'beranda';
?>
<header class="sticky top-0 z-[1000] border-b border-slate-200 bg-white">
    <div class="absolute inset-x-0 top-0 h-1 bg-accent"></div>
    
    <div class="mx-auto flex min-h-[72px] w-full max-w-7xl items-center justify-between gap-6 px-6 sm:px-8 lg:px-12">
        <!-- Logo -->
        <a href="<?= BASE_URL ?>/" class="flex items-center gap-3 no-underline">
            <img src="<?= aset('assets/img/logo-lampung-kecil.png') ?>" alt="Lambang Provinsi Lampung" class="h-10 w-auto shrink-0">
            <div class="text-left">
                <div class="text-base font-semibold leading-tight text-primary-dark">
                    SIMAGANG
                </div>
                <div class="text-xs leading-tight text-slate-600">
                    BAPPEDA PROVINSI LAMPUNG
                </div>
            </div>
        </a>

        <!-- Desktop Nav -->
        <nav class="hidden items-center gap-1 md:flex">
            <?php 
                $links = [
                    'beranda' => ['label' => 'Beranda', 'url' => '/'],
                    'alur' => ['label' => 'Alur Pengajuan', 'url' => '/alur'],
                    'persyaratan' => ['label' => 'Persyaratan', 'url' => '/persyaratan'],
                    'faq' => ['label' => 'FAQ', 'url' => '/faq'],
                ];
                foreach ($links as $id => $link) {
                    $isActive = ($currentPage === $id) ? 'active' : '';
                    $activeClasses = $isActive ? 'bg-primary-soft text-primary-dark' : '';
                    echo "<a href='" . BASE_URL . $link['url'] . "' class='rounded-md px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-primary {$activeClasses}'>{$link['label']}</a>";
                }
            ?>
        </nav>

        <!-- Actions -->
        <div class="flex items-center gap-2">
            <a href="<?= BASE_URL ?>/login" class="hidden min-h-10 items-center rounded-lg border border-slate-200 bg-white px-4 text-sm font-medium text-slate-700 transition hover:border-primary hover:text-primary sm:inline-flex">Masuk</a>
            <a href="<?= BASE_URL ?>/register" class="hidden min-h-10 items-center rounded-lg bg-primary px-4 text-sm font-medium text-white transition hover:bg-primary-dark sm:inline-flex">Daftar Sekarang</a>
            <button class="inline-flex min-h-10 items-center justify-center rounded-lg border border-slate-200 bg-white p-2 text-slate-700 md:hidden" id="mobile-menu-toggle" aria-label="Menu">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon-menu"><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="18" x2="20" y2="18"/></svg>
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon-close" style="display:none;"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
    </div>
    
    <!-- Mobile menu -->
    <div class="absolute left-0 right-0 hidden border-b border-slate-200 bg-white p-4 shadow-md md:hidden" id="mobile-nav">
        <?php foreach ($links as $id => $link): ?>
            <?php $isActive = ($currentPage === $id) ? 'active' : ''; ?>
            <a href="<?= BASE_URL . $link['url'] ?>" class="mb-1 block rounded-md px-3 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-primary <?= $isActive ?>">
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
