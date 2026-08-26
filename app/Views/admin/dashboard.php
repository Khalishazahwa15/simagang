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
    
    <!-- Info Banner -->
    <div style="background: var(--accent-light); border-width: 0.666667px 0.666667px 0.666667px 4px; border-style: solid; border-color: var(--accent); border-radius: 8px; padding: 14px 18px;">
        <div style="font-weight: 500; font-size: 12px; line-height: 18px; letter-spacing: 0.96px; text-transform: uppercase; color: var(--color-warning-ink); margin-bottom: 4px;">
            Ruang Lingkup Admin Sistem
        </div>
        <div style="font-weight: 400; font-size: var(--text-body-sm); line-height: 1.6; color: var(--color-warning-ink);">
            Anda memiliki akses penuh untuk mengelola operasional magang sekaligus administrasi sistem (divisi dan pengguna).
        </div>
    </div>

    <!-- 4 Summary Cards - Sekretariat Metrics -->
    <div class="grid-4 dashboard-stats">
                <div class="surface-card">
                    <span class="metric-icon metric-icon-amber" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m12 3 9 5-9 5-9-5 9-5Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9 5 9-5M3 16l9 5 9-5"/></svg></span>
                    <div class="metric-label">PERLU DITINDAKLANJUTI</div>
                    <div class="metric-value metric-value-warning"><?= $stats['tindak_lanjut'] ?></div>
                    <div class="metric-description">Menunggu tindakan</div>
        </div>
                <div class="surface-card">
                    <span class="metric-icon metric-icon-green" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="m8 12 3 3 5-6"/></svg></span>
                    <div class="metric-label">SEDANG MAGANG</div>
                    <div class="metric-value metric-value-success"><?= $stats['aktif'] ?></div>
                    <div class="metric-description">Aktif menjalani magang</div>
        </div>
                <div class="surface-card">
                    <span class="metric-icon metric-icon-blue" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6 3h12v18H6zM9 7h6M9 11h6M9 15h4"/></svg></span>
                    <div class="metric-label">TOTAL PENGAJUAN</div>
                    <div class="metric-value"><?= $stats['total'] ?></div>
                    <div class="metric-description"><?= $stats['selesai'] ?> selesai &middot; <?= $stats['diterima'] ?> diterima</div>
        </div>
                <div class="surface-card">
                    <span class="metric-icon metric-icon-blue" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 20V6l8-3 8 3v14M8 20v-5h8v5M8 9h.01M12 9h.01M16 9h.01"/></svg></span>
                    <div class="metric-label">KAPASITAS TOTAL</div>
                    <div class="metric-value"><?= $stats['kapasitas_total'] ?></div>
                    <div class="metric-description"><?= $stats['slot_terpakai'] ?> terpakai &middot; <?= $stats['divisi_total'] ?> divisi</div>
        </div>
    </div>

    <!-- Main Grid - Unified -->
    <div class="grid layout-split" style="gap: 24px; align-items: stretch">
        
        <!-- Left Column: Pending List (from Sekretariat) -->
        <div class="surface-card" style="display: flex; flex-direction: column; height: 100%;">
            <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 0.666667px solid var(--border); padding: 16px 20px;">
                <div style="font-weight: 500; font-size: var(--text-body-sm); line-height: 20px; color: var(--text-primary);">Pengajuan Terbaru</div>
                <a href="<?= BASE_URL ?>/sekretariat/pengajuan" style="font-weight: 500; font-size: 12.5px; line-height: 19px; color: var(--primary); text-decoration: none;">Lihat Semua &rarr;</a>
            </div>
            
            <div style="padding: 0; flex-grow: 1; display: flex; flex-direction: column;">
                <?php if (empty($pengajuan_terbaru)): ?>
                    <div class="empty-state"><span class="empty-state-icon"><svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M22 12h-6l-2 3h-4l-2-3H2"/><path stroke-linecap="round" stroke-linejoin="round" d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11Z"/></svg></span><span>Belum ada pengajuan yang perlu ditindaklanjuti.</span></div>
                <?php else: ?>
                    <?php foreach ($pengajuan_terbaru as $item): ?>
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

        <!-- Right Column -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <!-- Status Divisi -->
            <div class="surface-card">
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; border-bottom: 0.666667px solid var(--border);">
                    <div style="font-weight: 500; font-size: var(--text-body-sm); line-height: 20px; color: var(--text-primary);">
                        Status Divisi
                    </div>
                    <a href="<?= BASE_URL ?>/admin/bidang" style="font-weight: 500; font-size: 12.5px; line-height: 19px; color: var(--primary); text-decoration: none;">
                        Kelola &rarr;
                    </a>
                </div>
                
                <div style="padding: 0; max-height: 220px; overflow-y: auto;">
                    <?php if (empty($list_divisi)): ?>
                        <div class="empty-state"><span class="empty-state-icon"><svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M22 12h-6l-2 3h-4l-2-3H2"/><path stroke-linecap="round" stroke-linejoin="round" d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11Z"/></svg></span><span>Belum ada data divisi.</span></div>
                    <?php else: ?>
                        <?php foreach ($list_divisi as $index => $div): 
                            $isFull = $div['terisi'] >= $div['kapasitas'];
                            $pct = $div['kapasitas'] > 0 ? min(100, round(($div['terisi'] / $div['kapasitas']) * 100)) : 0;
                            
                            $badgeBg = $isFull ? 'var(--color-warning-soft)' : 'var(--color-success-soft)';
                            $badgeText = $isFull ? 'var(--color-warning-ink)' : 'var(--primary)';
                            $badgeLabel = $isFull ? 'PENUH' : 'DIBUTUHKAN';
                            
                            $barColor = $isFull ? 'var(--color-danger-ink)' : 'var(--primary)';
                        ?>
                        <div style="padding: 12px 20px; <?= $index < count($list_divisi) - 1 ? 'border-bottom: 0.666667px solid var(--border);' : '' ?>">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                                <div style="font-weight: 500; font-size: 12.5px; line-height: 18px; color: var(--text-primary);">
                                    <?= htmlspecialchars($div['nama_divisi']) ?>
                                </div>
                                <div style="background: <?= $badgeBg ?>; border-radius: 4px; padding: 2px 6px; font-weight: 500; font-size: 12px; line-height: 14px; letter-spacing: 0.5px; text-transform: uppercase; color: <?= $badgeText ?>;">
                                    <?= $badgeLabel ?>
                                </div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="flex-grow: 1; height: 5px; background: var(--border); border-radius: 3px; position: relative;">
                                    <div style="position: absolute; left: 0; top: 0; bottom: 0; width: <?= $pct ?>%; background: <?= $barColor ?>; border-radius: 3px;"></div>
                                </div>
                                <div style="font-family: 'JetBrains Mono', monospace; font-weight: 400; font-size: 12px; color: var(--text-secondary);">
                                    <?= $div['terisi'] ?>/<?= $div['kapasitas'] ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Pengguna Internal -->
            <div class="surface-card">
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; border-bottom: 0.666667px solid var(--border);">
                    <div style="font-weight: 500; font-size: var(--text-body-sm); line-height: 20px; color: var(--text-primary);">
                        Tim Internal
                    </div>
                    <a href="<?= BASE_URL ?>/admin/users" style="font-weight: 500; font-size: 12.5px; line-height: 19px; color: var(--primary); text-decoration: none;">
                        Semua Tim &rarr;
                    </a>
                </div>
                
                <div style="padding: 0; max-height: 200px; overflow-y: auto;">
                    <?php if (empty($list_internal)): ?>
                        <div class="empty-state"><span class="empty-state-icon"><svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M22 12h-6l-2 3h-4l-2-3H2"/><path stroke-linecap="round" stroke-linejoin="round" d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11Z"/></svg></span><span>Belum ada pengguna internal terdaftar.</span></div>
                    <?php else: ?>
                        <?php foreach (array_slice($list_internal, 0, 5) as $index => $user): 
                            $isAdmin = $user['role'] === 'admin';
                            $avatarBg = $isAdmin ? 'var(--text-primary)' : 'var(--color-success-soft)';
                            $avatarText = $isAdmin ? 'var(--bg-card)' : 'var(--primary)';
                            
                            $badgeBg = $isAdmin ? 'rgba(23, 32, 31, 0.063)' : 'var(--color-success-soft)';
                            $badgeText = $isAdmin ? 'var(--text-primary)' : 'var(--primary)';
                            $badgeLabel = $isAdmin ? 'ADMIN' : 'SEKRETARIAT';
                            
                            $initial = strtoupper(substr($user['nama'], 0, 1));
                        ?>
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px 20px; gap: 12px; <?= $index < min(5, count($list_internal)) - 1 ? 'border-bottom: 0.666667px solid var(--border);' : '' ?>">
                            <div style="display: flex; align-items: center; gap: 10px; overflow: hidden;">
                                <div style="width: 32px; height: 32px; border-radius: 16px; background: <?= $avatarBg ?>; color: <?= $avatarText ?>; display: flex; align-items: center; justify-content: center; font-weight: 500; font-size: var(--text-body-sm); flex-shrink: 0;">
                                    <?= $initial ?>
                                </div>
                                <div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    <div style="font-weight: 500; font-size: var(--text-body-sm); line-height: 18px; color: var(--text-primary); margin-bottom: 2px;">
                                        <?= htmlspecialchars($user['nama']) ?>
                                    </div>
                                    <div style="font-weight: 400; font-size: 12px; line-height: 15px; color: var(--text-secondary);">
                                        <?= htmlspecialchars($user['email']) ?>
                                    </div>
                                </div>
                            </div>
                            <div style="background: <?= $badgeBg ?>; border-radius: 4px; padding: 2px 6px; font-weight: 500; font-size: 12px; line-height: 14px; letter-spacing: 0.5px; text-transform: uppercase; color: <?= $badgeText ?>; flex-shrink: 0;">
                                <?= $badgeLabel ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>


