<?php
function getDistCount($dist, $status) {
    return isset($dist[$status]) ? $dist[$status] : 0;
}

$maxDist = max(1, array_sum($distribusi));

$distItems = [
    ['label' => 'Diajukan', 'status' => 'diajukan', 'color' => '#1D4ED8'],
    ['label' => 'Diperiksa', 'status' => 'dalam_verifikasi', 'color' => '#7A5A00'],
    ['label' => 'Perlu Revisi', 'status' => 'revisi', 'color' => '#991B1B'],
    ['label' => 'Cek Kebutuhan Divisi', 'status' => 'cek_divisi', 'color' => 'var(--accent)'], // Dummy for UI presentation
    ['label' => 'Diterima', 'status' => 'diterima', 'color' => 'var(--primary)'],
    ['label' => 'Sedang Magang', 'status' => 'sedang_magang', 'color' => 'var(--primary-dark)'],
    ['label' => 'Selesai', 'status' => 'selesai', 'color' => 'var(--primary)']
];

function getBadgeStyle($status) {
    switch ($status) {
        case 'diajukan': return 'background: #EFF6FF; color: #1D4ED8;';
        case 'dalam_verifikasi': return 'background: #FEF3C7; color: #7A5A00;';
        case 'revisi': return 'background: #FEE2E2; color: #991B1B;';
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

<div style="display: flex; flex-direction: column; gap: 24px; max-width: 1040px; font-family: 'Plus Jakarta Sans', sans-serif;">
    
    <!-- 4 Summary Cards -->
    <div style="background: var(--bg-card); border: 0.666667px solid var(--border); border-radius: 10px; display: grid; grid-template-columns: repeat(4, 1fr);">
        <!-- Card 1 -->
        <div style="padding: 20px; display: flex; flex-direction: column;">
            <div style="font-weight: 700; font-size: 11px; line-height: 16px; letter-spacing: 0.88px; text-transform: uppercase; color: var(--text-secondary); margin-bottom: 8px;">PERLU DITINDAKLANJUTI</div>
            <div style="font-family: 'JetBrains Mono', monospace; font-size: 32px; font-weight: 600; color: var(--primary); line-height: 1; margin-bottom: 4px;"><?= $stats['tindak_lanjut'] ?></div>
            <div style="font-weight: 400; font-size: 11.5px; line-height: 17px; color: var(--text-secondary);">Menunggu tindakan</div>
        </div>
        <!-- Card 2 -->
        <div style="padding: 20px; border-left: 0.666667px solid var(--border); display: flex; flex-direction: column;">
            <div style="font-weight: 700; font-size: 11px; line-height: 16px; letter-spacing: 0.88px; text-transform: uppercase; color: var(--text-secondary); margin-bottom: 8px;">PERLU REVISI</div>
            <div style="font-family: 'JetBrains Mono', monospace; font-size: 32px; font-weight: 600; color: var(--primary); line-height: 1; margin-bottom: 4px;"><?= $stats['revisi'] ?></div>
            <div style="font-weight: 400; font-size: 11.5px; line-height: 17px; color: var(--text-secondary);">Menunggu mahasiswa</div>
        </div>
        <!-- Card 3 -->
        <div style="padding: 20px; border-left: 0.666667px solid var(--border); display: flex; flex-direction: column;">
            <div style="font-weight: 700; font-size: 11px; line-height: 16px; letter-spacing: 0.88px; text-transform: uppercase; color: var(--text-secondary); margin-bottom: 8px;">SEDANG MAGANG</div>
            <div style="font-family: 'JetBrains Mono', monospace; font-size: 32px; font-weight: 600; color: var(--accent); line-height: 1; margin-bottom: 4px;"><?= $stats['aktif'] ?></div>
            <div style="font-weight: 400; font-size: 11.5px; line-height: 17px; color: var(--text-secondary);">Aktif menjalani magang</div>
        </div>
        <!-- Card 4 -->
        <div style="padding: 20px; border-left: 0.666667px solid var(--border); display: flex; flex-direction: column;">
            <div style="font-weight: 700; font-size: 11px; line-height: 16px; letter-spacing: 0.88px; text-transform: uppercase; color: var(--text-secondary); margin-bottom: 8px;">TOTAL PENGAJUAN</div>
            <div style="font-family: 'JetBrains Mono', monospace; font-size: 32px; font-weight: 600; color: var(--text-primary); line-height: 1; margin-bottom: 4px;"><?= $stats['total'] ?></div>
            <div style="font-weight: 400; font-size: 11.5px; line-height: 17px; color: var(--text-secondary);"><?= $stats['selesai'] ?> selesai &middot; <?= $stats['diterima'] ?> diterima</div>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid" style="grid-template-columns: 3fr 2fr; gap: 24px; align-items: stretch;">
        
        <!-- Left Column: Pending List -->
        <div style="background: var(--bg-card); border: 0.666667px solid var(--border); border-radius: 10px; display: flex; flex-direction: column; height: 100%;">
            <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 0.666667px solid var(--border); padding: 16px 20px;">
                <div style="font-weight: 700; font-size: 13px; line-height: 20px; color: var(--text-primary);">Pengajuan Terbaru</div>
                <a href="<?= BASE_URL ?>/sekretariat/pengajuan" style="font-weight: 600; font-size: 12.5px; line-height: 19px; color: var(--primary); text-decoration: none;">Lihat Semua &rarr;</a>
            </div>
            
            <div style="padding: 0; flex-grow: 1; display: flex; flex-direction: column;">
                <?php if (empty($pengajuan)): ?>
                    <div style="padding: 32px 24px; text-align: center; color: var(--text-secondary); font-size: 13px;">Belum ada pengajuan yang perlu ditindaklanjuti.</div>
                <?php else: ?>
                    <?php foreach ($pengajuan as $item): ?>
                    <a href="<?= BASE_URL ?>/sekretariat/pengajuan/detail/<?= $item['id'] ?>" style="display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; border-bottom: 0.666667px solid var(--border); text-decoration: none; transition: background 0.15s;" onmouseover="this.style.background='var(--bg-main)'" onmouseout="this.style.background='transparent'">
                        <div>
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                <div style="font-family: var(--font-mono); font-size: 12px; font-weight: 600; color: var(--primary);">PGJ-<?= str_pad($item['id'], 4, '0', STR_PAD_LEFT) ?></div>
                                <div style="padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 700; letter-spacing: 0.5px; <?= getBadgeStyle($item['status']) ?>">
                                    <?= getBadgeLabel($item['status']) ?>
                                </div>
                            </div>
                            <div style="font-size: 13.5px; font-weight: 600; color: var(--text-primary); margin-bottom: 2px;"><?= htmlspecialchars($item['mahasiswa_nama']) ?></div>
                            <div style="font-size: 12px; color: var(--text-secondary);">
                                <?php
                                    $univ = !empty($item['universitas']) ? $item['universitas'] : 'Profil belum lengkap';
                                    $prodi = !empty($item['program_studi']) ? ' - ' . $item['program_studi'] : '';
                                    echo htmlspecialchars($univ . $prodi) . ' &middot; ' . date('d M Y', strtotime($item['created_at']));
                                ?>
                            </div>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--text-secondary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                    </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right Column: Status Distribution -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <div style="background: var(--bg-card); border: 0.666667px solid var(--border); border-radius: 10px;">
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; border-bottom: 0.666667px solid var(--border);">
                    <div style="font-weight: 700; font-size: 13px; line-height: 20px; color: var(--text-primary);">Distribusi Status</div>
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
                        <div style="font-family: 'JetBrains Mono', monospace; font-size: 12px; font-weight: 600; color: var(--text-primary); width: 24px; text-align: right;">
                            <?= $count ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </div>
</div>



