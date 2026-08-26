<?php
function getDistCount($dist, $status) {
    return isset($dist[$status]) ? $dist[$status] : 0;
}

$maxDist = max(1, array_sum($distribusi));

$distItems = [
    ['label' => 'Diajukan', 'status' => 'diajukan', 'color' => 'var(--color-info-ink)'],
    ['label' => 'Diperiksa', 'status' => 'dalam_verifikasi', 'color' => 'var(--color-warning-ink)'],
    ['label' => 'Perlu Revisi', 'status' => 'revisi', 'color' => 'var(--color-danger-ink)'],
    ['label' => 'Cek Kebutuhan Divisi', 'status' => 'cek_divisi', 'color' => 'var(--accent)'], // Dummy for UI presentation
    ['label' => 'Diterima', 'status' => 'diterima', 'color' => 'var(--primary)'],
    ['label' => 'Sedang Magang', 'status' => 'sedang_magang', 'color' => 'var(--primary-dark)'],
    ['label' => 'Selesai', 'status' => 'selesai', 'color' => 'var(--primary)']
];

function getBadgeStyle($status) {
    switch ($status) {
        case 'diajukan': return 'background: var(--color-info-soft); color: var(--color-info-ink);';
        case 'dalam_verifikasi': return 'background: var(--color-warning-soft); color: var(--color-warning-ink);';
        case 'revisi': return 'background: var(--color-danger-soft); color: var(--color-danger-ink);';
        default: return 'background: var(--bg-soft); color: var(--text-secondary);';
    }
}

function getBadgeLabel($status) {
    switch ($status) {
        case 'diajukan': return 'DIAJUKAN';
        case 'dalam_verifikasi': return 'DIPERIKSA';
        case 'revisi': return 'REVISI';
        default: return strtoupper(str_replace('_', ' ', $status));
    }
}
?>

<div style="display: flex; flex-direction: column; gap: 24px; font-family: var(--font-body);">
    
    <!-- 4 Summary Cards -->
    <div class="grid-4 dashboard-stats">
        <!-- Card 1 -->
        <div class="surface-card">
            <span class="metric-icon metric-icon-amber" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m12 3 9 5-9 5-9-5 9-5Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9 5 9-5M3 16l9 5 9-5"/></svg></span>
            <div class="metric-label">PERLU DITINDAKLANJUTI</div>
            <div class="metric-value metric-value-warning"><?= $stats['tindak_lanjut'] ?></div>
            <div class="metric-description">Menunggu tindakan</div>
        </div>
        <!-- Card 2 -->
        <div class="surface-card">
            <span class="metric-icon metric-icon-red" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M9 9l6 6m0-6-6 6"/></svg></span>
            <div class="metric-label">PERLU REVISI</div>
            <div class="metric-value metric-value-danger"><?= $stats['revisi'] ?></div>
            <div class="metric-description">Menunggu mahasiswa</div>
        </div>
        <!-- Card 3 -->
        <div class="surface-card">
            <span class="metric-icon metric-icon-green" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="m8 12 3 3 5-6"/></svg></span>
            <div class="metric-label">SEDANG MAGANG</div>
            <div class="metric-value metric-value-success"><?= $stats['aktif'] ?></div>
            <div class="metric-description">Aktif menjalani magang</div>
        </div>
        <!-- Card 4 -->
        <div class="surface-card">
            <span class="metric-icon metric-icon-blue" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6 3h12v18H6zM9 7h6M9 11h6M9 15h4"/></svg></span>
            <div class="metric-label">TOTAL PENGAJUAN</div>
            <div class="metric-value"><?= $stats['total'] ?></div>
            <div class="metric-description"><?= $stats['selesai'] ?> selesai &middot; <?= $stats['diterima'] ?> diterima</div>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid layout-split" style="gap: 24px; align-items: stretch">
        
        <!-- Left Column: Pending List -->
        <div class="surface-card" style="display: flex; flex-direction: column; height: 100%;">
            <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 0.666667px solid var(--border); padding: 16px 20px;">
                <div style="font-weight: 500; font-size: var(--text-body-sm); line-height: 20px; color: var(--text-primary);">Pengajuan Terbaru</div>
                <a href="<?= BASE_URL ?>/sekretariat/pengajuan" style="font-weight: 500; font-size: 12.5px; line-height: 19px; color: var(--primary); text-decoration: none;">Lihat Semua &rarr;</a>
            </div>
            
            <div style="padding: 0; flex-grow: 1; display: flex; flex-direction: column;">
                <?php if (empty($pengajuan)): ?>
                    <div class="empty-state"><span class="empty-state-icon"><svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M22 12h-6l-2 3h-4l-2-3H2"/><path stroke-linecap="round" stroke-linejoin="round" d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11Z"/></svg></span><span>Belum ada pengajuan yang perlu ditindaklanjuti.</span></div>
                <?php else: ?>
                    <?php foreach ($pengajuan as $item): ?>
                    <a href="<?= BASE_URL ?>/sekretariat/pengajuan/detail/<?= $item['id'] ?>" style="display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; border-bottom: 0.666667px solid var(--border); text-decoration: none; transition: background 0.15s;" onmouseover="this.style.background='var(--bg-main)'" onmouseout="this.style.background='transparent'">
                        <div>
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                <div style="font-family: var(--font-mono); font-size: 12px; font-weight: 500; color: var(--primary);"><?= htmlspecialchars($item['nomor_pengajuan']) ?></div>
                                <div style="padding: 2px 8px; border-radius: 4px; font-size: 12px; font-weight: 500; letter-spacing: 0.5px; <?= getBadgeStyle($item['status']) ?>">
                                    <?= getBadgeLabel($item['status']) ?>
                                </div>
                            </div>
                            <div style="font-size: var(--text-body-sm); font-weight: 500; color: var(--text-primary); margin-bottom: 2px;"><?= htmlspecialchars($item['mahasiswa_nama']) ?></div>
                            <div style="font-size: 12px; color: var(--text-secondary);">
                                <?php
                                    $univ = !empty($item['universitas']) ? $item['universitas'] : 'Profil belum lengkap';
                                    $prodi = !empty($item['program_studi']) ? ' - ' . $item['program_studi'] : '';
                                    echo htmlspecialchars($univ . $prodi) . ' &middot; ' . date('d M Y', strtotime($item['created_at']));
                                ?>
                            </div>
                        </div>
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--text-secondary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                    </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right Column: Status Distribution -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <div class="surface-card">
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; border-bottom: 0.666667px solid var(--border);">
                    <div style="font-weight: 500; font-size: var(--text-body-sm); line-height: 20px; color: var(--text-primary);">Distribusi Status</div>
                </div>
                <div style="padding: 12px 0; max-height: 300px; overflow-y: auto;">
                    <?php foreach ($distItems as $dItem): ?>
                    <?php 
                        $count = getDistCount($distribusi, $dItem['status']); 
                        $percentage = $maxDist > 0 ? ($count / $maxDist) * 100 : 0;
                        $width = $count > 0 ? max(5, $percentage) . '%' : '4px';
                    ?>
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px 20px;">
                        <div style="display: flex; align-items: center; gap: 10px; flex: 1;">
                            <div style="width: 8px; height: 8px; border-radius: 50%; background: <?= $dItem['color'] ?>;"></div>
                            <div style="font-weight: 500; font-size: 12.5px; color: var(--text-primary); width: 130px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= $dItem['label'] ?></div>
                            
                            <!-- Progress bar -->
                            <div style="flex: 1; height: 4px; background: var(--border); border-radius: 2px; position: relative; max-width: 60px;">
                                <div style="position: absolute; top: 0; left: 0; height: 100%; background: <?= $dItem['color'] ?>; border-radius: 2px; width: <?= $width ?>;"></div>
                            </div>
                        </div>
                        <div style="font-family: 'JetBrains Mono', monospace; font-size: 12px; font-weight: 500; color: var(--text-primary); width: 24px; text-align: right;">
                            <?= $count ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </div>
</div>



