<?php
// Initialize variables safely
$role = \App\Core\Auth::role() ?? 'mahasiswa';
$nama = \App\Core\Auth::user()['nama'] ?? 'Pengguna';
$initial = strtoupper(substr($nama, 0, 1));
$currentPage = $currentPage ?? 'dashboard';

// Define menus based on role
$menus = [];
if ($role === 'admin') {
    $menus = [
        ['is_header' => true, 'label' => 'Operasional'],
        ['icon' => '<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><line x1="3" x2="21" y1="9" y2="9"/><line x1="9" x2="9" y1="21" y2="9"/></svg>', 'label' => 'Dashboard', 'url' => BASE_URL . '/admin/dashboard', 'id' => 'dashboard'],
        ['icon' => '<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>', 'label' => 'Daftar Pengajuan', 'url' => BASE_URL . '/sekretariat/pengajuan', 'id' => 'pengajuan'],
        ['icon' => '<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>', 'label' => 'Peserta Magang', 'url' => BASE_URL . '/sekretariat/peserta', 'id' => 'peserta'],
        ['icon' => '<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>', 'label' => 'Arsip Dokumen', 'url' => BASE_URL . '/sekretariat/dokumen', 'id' => 'dokumen'],
        ['icon' => '<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="20" x2="12" y2="10"/><line x1="18" y1="20" x2="18" y2="4"/><line x1="6" y1="20" x2="6" y2="16"/></svg>', 'label' => 'Laporan & Ekspor', 'url' => BASE_URL . '/sekretariat/laporan', 'id' => 'laporan'],
        
        ['is_header' => true, 'label' => 'Sistem'],
        ['icon' => '<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>', 'label' => 'Kelola Divisi', 'url' => BASE_URL . '/admin/bidang', 'id' => 'bidang'],
        ['icon' => '<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>', 'label' => 'Pengguna Internal', 'url' => BASE_URL . '/admin/users', 'id' => 'users'],
        ['icon' => '<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>', 'label' => 'Audit Log', 'url' => BASE_URL . '/admin/audit-log', 'id' => 'audit_log'],
    ];
} elseif ($role === 'sekretariat') {
    $menus = [
        ['is_header' => true, 'label' => 'Menu Utama'],
        ['icon' => '<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><line x1="3" x2="21" y1="9" y2="9"/><line x1="9" x2="9" y1="21" y2="9"/></svg>', 'label' => 'Dashboard', 'url' => BASE_URL . '/sekretariat/dashboard', 'id' => 'dashboard'],
        ['icon' => '<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>', 'label' => 'Daftar Pengajuan', 'url' => BASE_URL . '/sekretariat/pengajuan', 'id' => 'pengajuan'],
        ['icon' => '<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>', 'label' => 'Peserta Magang', 'url' => BASE_URL . '/sekretariat/peserta', 'id' => 'peserta'],
        ['icon' => '<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>', 'label' => 'Arsip Dokumen', 'url' => BASE_URL . '/sekretariat/dokumen', 'id' => 'dokumen'],
        ['icon' => '<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="20" x2="12" y2="10"/><line x1="18" y1="20" x2="18" y2="4"/><line x1="6" y1="20" x2="6" y2="16"/></svg>', 'label' => 'Laporan & Ekspor', 'url' => BASE_URL . '/sekretariat/laporan', 'id' => 'laporan'],
    ];
} else {
    $menus = [
        ['is_header' => true, 'label' => 'Menu Utama'],
        ['icon' => '<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>', 'label' => 'Dashboard', 'url' => BASE_URL . '/mahasiswa/dashboard', 'id' => 'dashboard'],
        ['icon' => '<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>', 'label' => 'Pengajuan', 'url' => BASE_URL . '/mahasiswa/pengajuan', 'id' => 'pengajuan'],
        ['icon' => '<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>', 'label' => 'Status Pengajuan', 'url' => BASE_URL . '/mahasiswa/status', 'id' => 'status'],
        ['icon' => '<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>', 'label' => 'Dokumen', 'url' => BASE_URL . '/mahasiswa/dokumen', 'id' => 'dokumen'],
        ['icon' => '<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>', 'label' => 'Profil', 'url' => BASE_URL . '/mahasiswa/profil', 'id' => 'profil'],
    ];
}
?>

<aside class="admin-sidebar">
    <!-- Logo -->
    <div class="sidebar-header">
        <a href="<?= BASE_URL ?>/" class="sidebar-brand">
            <div class="brand-logo">S</div>
            <div>
                <div class="brand-text-primary">SIMAGANG</div>
                <div class="brand-text-secondary">BAPPEDA LAMPUNG</div>
            </div>
        </a>

        <!-- Admin badge -->
        <div class="user-badge">
            <div class="user-avatar">
                <?= $initial ?>
            </div>
            <div>
                <div class="user-name"><?= htmlspecialchars($nama) ?></div>
                <div class="user-role"><?= ucfirst(htmlspecialchars($role)) ?></div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        
        <?php foreach ($menus as $menu): ?>
            <?php if (isset($menu['is_header'])): ?>
                <div class="nav-label" style="<?= $menu === reset($menus) ? '' : 'margin-top: 16px;' ?>">
                    <?= htmlspecialchars($menu['label']) ?>
                </div>
            <?php else: ?>
                <a href="<?= $menu['url'] ?>" class="nav-item <?= $currentPage === $menu['id'] ? 'active' : '' ?>">
                    <?= $menu['icon'] ?>
                    <?= htmlspecialchars($menu['label']) ?>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>
    </nav>

    <!-- Footer -->
    <div style="padding: 0 12px; border-top: 1px solid rgba(255,255,255,0.08); padding-top: 16px;">
        <a href="<?= BASE_URL ?>/logout" style="display: flex; align-items: center; gap: 10px; width: 100%; padding: 9px 10px; background: none; border: none; cursor: pointer; font-family: var(--font-body); font-size: 13px; color: rgba(255,255,255,0.38); text-align: left; text-decoration: none; transition: all 0.15s;" onmouseover="this.style.color='rgba(255,255,255,0.8)'" onmouseout="this.style.color='rgba(255,255,255,0.38)'">
            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg> Keluar
        </a>
    </div>
</aside>
