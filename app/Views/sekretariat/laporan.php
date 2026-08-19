<?php
$statusMapping = [
    'diajukan' => ['label' => 'Diajukan', 'color' => '#1D4ED8'],
    'dalam_verifikasi' => ['label' => 'Diperiksa', 'color' => '#7A5A00'],
    'revisi' => ['label' => 'Perlu Revisi', 'color' => '#92400E'],
    'diterima' => ['label' => 'Diterima', 'color' => 'var(--primary)'],
    'ditolak' => ['label' => 'Ditolak', 'color' => '#991B1B'],
    'sedang_magang' => ['label' => 'Sedang Magang', 'color' => 'var(--primary)'],
    'selesai' => ['label' => 'Selesai', 'color' => '#065F46']
];

$maxStatus = 0;
foreach ($distribusi_status as $count) {
    if ($count > $maxStatus) $maxStatus = $count;
}
$maxStatus = max($maxStatus, 1);

$maxDivisi = 0;
foreach ($distribusi_divisi as $div) {
    if ($div['count'] > $maxDivisi) $maxDivisi = $div['count'];
}
$maxDivisi = max($maxDivisi, 1);
?>
<div style="font-family: 'Plus Jakarta Sans', sans-serif; max-width: 1040px; display: flex; flex-direction: column; gap: 20px;">
    
    <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 20px;">
        
        <!-- Distribusi Status Pengajuan -->
        <div style="background: var(--bg-card); border: 0.666667px solid var(--border); border-radius: 10px;">
            <div style="padding: 13px 20px; border-bottom: 0.666667px solid var(--border);">
                <div style="font-weight: 700; font-size: 11.5px; line-height: 17px; letter-spacing: 1.15px; text-transform: uppercase; color: var(--text-secondary);">
                    Distribusi Status Pengajuan
                </div>
            </div>
            <div style="padding: 18px 20px 28px; display: flex; flex-direction: column; gap: 10px;">
                <?php foreach ($statusMapping as $key => $meta): 
                    $count = $distribusi_status[$key] ?? 0;
                    $pct = ($count / $maxStatus) * 100;
                ?>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 10px; height: 10px; background: <?= $meta['color'] ?>; border-radius: 5px;"></div>
                    <div style="flex-grow: 1; font-weight: 400; font-size: 13px; line-height: 20px; color: var(--text-primary);">
                        <?= $meta['label'] ?>
                    </div>
                    <div style="width: 80px; height: 5px; background: var(--border); border-radius: 2px; position: relative;">
                        <div style="position: absolute; left: 0; top: 0; bottom: 0; width: <?= $pct ?>%; background: <?= $meta['color'] ?>; border-radius: 2px;"></div>
                    </div>
                    <div style="width: 24px; text-align: right; font-family: 'JetBrains Mono', monospace; font-weight: 600; font-size: 13px; line-height: 20px; color: var(--text-primary);">
                        <?= $count ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Pengajuan per Divisi -->
        <div style="background: var(--bg-card); border: 0.666667px solid var(--border); border-radius: 10px;">
            <div style="padding: 13px 20px; border-bottom: 0.666667px solid var(--border);">
                <div style="font-weight: 700; font-size: 11.5px; line-height: 17px; letter-spacing: 1.15px; text-transform: uppercase; color: var(--text-secondary);">
                    Pengajuan per Divisi (Preferensi)
                </div>
            </div>
            <div style="padding: 18px 20px 28px; display: flex; flex-direction: column; gap: 10px;">
                <?php if (empty($distribusi_divisi)): ?>
                    <div style="font-size: 13px; color: var(--text-secondary); text-align: center; padding: 20px 0;">Belum ada data pengajuan</div>
                <?php else: ?>
                    <?php foreach ($distribusi_divisi as $idx => $div): 
                        if ($idx >= 7) break; // limit to top 7 matching UI length roughly
                        $pct = ($div['count'] / $maxDivisi) * 100;
                    ?>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="flex-grow: 1; font-weight: 400; font-size: 12.5px; line-height: 19px; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            <?= htmlspecialchars($div['nama_divisi']) ?>
                        </div>
                        <div style="width: 80px; height: 5px; background: var(--border); border-radius: 2px; position: relative;">
                            <div style="position: absolute; left: 0; top: 0; bottom: 0; width: <?= $pct ?>%; background: var(--primary); border-radius: 2px;"></div>
                        </div>
                        <div style="width: 24px; text-align: right; font-family: 'JetBrains Mono', monospace; font-weight: 600; font-size: 13px; line-height: 20px; color: var(--text-primary);">
                            <?= $div['count'] ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <!-- Ekspor Data -->
    <div style="background: var(--bg-card); border: 0.666667px solid var(--border); border-radius: 10px; padding: 20px 24px;">
        <div style="font-weight: 700; font-size: 12px; line-height: 18px; letter-spacing: 1.2px; text-transform: uppercase; color: var(--text-secondary); margin-bottom: 14px;">
            Ekspor Data
        </div>
        <div style="display: flex; flex-wrap: wrap; gap: 12px;">
            <a href="<?= BASE_URL ?>/sekretariat/laporan/export" style="text-decoration: none; padding: 10px 18px; background: #F7F4EC; border: 0.666667px solid var(--border); border-radius: 8px; display: inline-flex; flex-direction: column; justify-content: center;">
                <div style="display: flex; align-items: center; gap: 7px; margin-bottom: 3px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.33333" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    <div style="font-weight: 700; font-size: 13px; line-height: 20px; color: var(--text-primary);">Ekspor Semua Pengajuan (.csv)</div>
                </div>
                <div style="font-weight: 400; font-size: 11.5px; line-height: 17px; color: var(--text-secondary);">
                    Semua data pengajuan dengan status terkini
                </div>
            </a>

        </div>
    </div>
</div>

