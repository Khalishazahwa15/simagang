<?php
$statusMapping = [
    'diajukan' => ['label' => 'Diajukan', 'color' => 'var(--color-info-ink)'],
    'dalam_verifikasi' => ['label' => 'Diperiksa', 'color' => 'var(--color-warning-ink)'],
    'revisi' => ['label' => 'Perlu Revisi', 'color' => 'var(--color-warning-ink)'],
    'diterima' => ['label' => 'Diterima', 'color' => 'var(--primary)'],
    'ditolak' => ['label' => 'Ditolak', 'color' => 'var(--color-danger-ink)'],
    'sedang_magang' => ['label' => 'Sedang Magang', 'color' => 'var(--primary)'],
    'selesai' => ['label' => 'Selesai', 'color' => 'var(--color-success-ink)']
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

$filterAktif = [];
foreach (['dari', 'sampai', 'divisi'] as $kunci) {
    if (trim($_GET[$kunci] ?? '') !== '') {
        $filterAktif[$kunci] = $_GET[$kunci];
    }
}
$tautanEkspor = function ($preset) use ($filterAktif) {
    return BASE_URL . '/sekretariat/laporan/export?' . http_build_query(array_merge(['filter' => $preset], $filterAktif));
};
?>
<div style="font-family: 'Plus Jakarta Sans', sans-serif; max-width: 1040px; display: flex; flex-direction: column; gap: 20px;">

    <form method="GET" action="<?= BASE_URL ?>/sekretariat/laporan">
        <div class="d-flex align-center" style="flex-wrap: wrap; gap: 12px; width: 100%;">
            <div>
                <label for="app-views-sekretariat-laporan-dari" class="form-label" style="display: block; margin-bottom: 4px;">Dari Tanggal</label>
                <input id="app-views-sekretariat-laporan-dari" type="date" name="dari" value="<?= htmlspecialchars($_GET['dari'] ?? '') ?>" style="padding: 9px 14px; background: var(--bg-main); border: 1px solid var(--border); border-radius: 8px; font-family: var(--font-body); font-size: 13.5px; color: var(--text-primary); outline: none;">
            </div>
            <div>
                <label for="app-views-sekretariat-laporan-sampai" class="form-label" style="display: block; margin-bottom: 4px;">Sampai Tanggal</label>
                <input id="app-views-sekretariat-laporan-sampai" type="date" name="sampai" value="<?= htmlspecialchars($_GET['sampai'] ?? '') ?>" style="padding: 9px 14px; background: var(--bg-main); border: 1px solid var(--border); border-radius: 8px; font-family: var(--font-body); font-size: 13.5px; color: var(--text-primary); outline: none;">
            </div>
            <div style="flex: 1; min-width: 180px;">
                <label for="app-views-sekretariat-laporan-divisi" class="form-label" style="display: block; margin-bottom: 4px;">Divisi Preferensi</label>
                <select id="app-views-sekretariat-laporan-divisi" name="divisi" style="width: 100%; padding: 9px 14px; background: var(--bg-main); border: 1px solid var(--border); border-radius: 8px; font-family: var(--font-body); font-size: 13.5px; color: var(--text-primary); outline: none; cursor: pointer;">
                    <option value="">Semua Divisi</option>
                    <?php foreach (($divisiList ?? []) as $d): ?>
                        <option value="<?= $d['id'] ?>" <?= ($_GET['divisi'] ?? '') == $d['id'] ? 'selected' : '' ?>><?= htmlspecialchars($d['nama_divisi']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="align-self: flex-end; display: flex; gap: 8px;">
                <button type="submit" class="btn btn-primary" style="padding: 9px 16px;">Terapkan</button>
                <?php if (!empty($filterAktif)): ?>
                    <a href="<?= BASE_URL ?>/sekretariat/laporan" class="btn btn-outline" style="padding: 9px 16px;">Reset</a>
                <?php endif; ?>
            </div>
        </div>
        <p class="text-muted" style="font-family: var(--font-body); font-size: 13px; margin: 10px 0 0 0;">
            <?= (int)($totalRows ?? 0) ?> pengajuan termasuk dalam ringkasan dan ekspor di bawah ini.
        </p>
    </form>

    
    <div class="grid grid-2" style="gap: 20px">
        
        <!-- Distribusi Status Pengajuan -->
        <div class="surface-card">
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
        <div class="surface-card">
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
    <div class="surface-card" style="padding: 20px 24px;">
        <div style="font-weight: 700; font-size: 12px; line-height: 18px; letter-spacing: 1.2px; text-transform: uppercase; color: var(--text-secondary); margin-bottom: 14px;">
            Pilihan Ekspor Data (CSV)
        </div>
        <div style="display: flex; flex-wrap: wrap; gap: 12px;">
            <a href="<?= $tautanEkspor('semua') ?>" style="text-decoration: none; padding: 12px 18px; background: var(--color-surface-soft); border: 1px solid var(--border); border-radius: 8px; display: inline-flex; flex-direction: column; justify-content: center; min-width: 280px; flex: 1;">
                <div style="display: flex; align-items: center; gap: 7px; margin-bottom: 4px;">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.33333" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    <div style="font-weight: 700; font-size: 13.5px; line-height: 20px; color: var(--text-primary);">Ekspor Semua Data</div>
                </div>
                <div style="font-weight: 400; font-size: 12px; line-height: 17px; color: var(--text-secondary);">
                    Seluruh riwayat pengajuan magang
                </div>
            </a>
            
            <a href="<?= $tautanEkspor('baru') ?>" style="text-decoration: none; padding: 12px 18px; background: var(--bg-main); border: 1px solid var(--border); border-radius: 8px; display: inline-flex; flex-direction: column; justify-content: center; min-width: 280px; flex: 1;">
                <div style="display: flex; align-items: center; gap: 7px; margin-bottom: 4px;">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--color-info)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    <div style="font-weight: 700; font-size: 13.5px; line-height: 20px; color: var(--text-primary);">Pendaftar Baru</div>
                </div>
                <div style="font-weight: 400; font-size: 12px; line-height: 17px; color: var(--text-secondary);">
                    Pengajuan yang sedang diproses (belum magang)
                </div>
            </a>
            
            <a href="<?= $tautanEkspor('aktif') ?>" style="text-decoration: none; padding: 12px 18px; background: var(--bg-main); border: 1px solid var(--border); border-radius: 8px; display: inline-flex; flex-direction: column; justify-content: center; min-width: 280px; flex: 1;">
                <div style="display: flex; align-items: center; gap: 7px; margin-bottom: 4px;">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--color-success)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    <div style="font-weight: 700; font-size: 13.5px; line-height: 20px; color: var(--text-primary);">Peserta Aktif</div>
                </div>
                <div style="font-weight: 400; font-size: 12px; line-height: 17px; color: var(--text-secondary);">
                    Mahasiswa yang sedang magang (Sedang Magang)
                </div>
            </a>
            
            <a href="<?= $tautanEkspor('selesai') ?>" style="text-decoration: none; padding: 12px 18px; background: var(--bg-main); border: 1px solid var(--border); border-radius: 8px; display: inline-flex; flex-direction: column; justify-content: center; min-width: 280px; flex: 1;">
                <div style="display: flex; align-items: center; gap: 7px; margin-bottom: 4px;">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--color-warning-ink)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <div style="font-weight: 700; font-size: 13.5px; line-height: 20px; color: var(--text-primary);">Alumni (Selesai)</div>
                </div>
                <div style="font-weight: 400; font-size: 12px; line-height: 17px; color: var(--text-secondary);">
                    Mahasiswa yang telah menyelesaikan program
                </div>
            </a>
            
            <a href="<?= $tautanEkspor('ditolak') ?>" style="text-decoration: none; padding: 12px 18px; background: var(--bg-main); border: 1px solid var(--border); border-radius: 8px; display: inline-flex; flex-direction: column; justify-content: center; min-width: 280px; flex: 1;">
                <div style="display: flex; align-items: center; gap: 7px; margin-bottom: 4px;">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--color-danger)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    <div style="font-weight: 700; font-size: 13.5px; line-height: 20px; color: var(--text-primary);">Ditolak / Mundur</div>
                </div>
                <div style="font-weight: 400; font-size: 12px; line-height: 17px; color: var(--text-secondary);">
                    Pengajuan yang dibatalkan, ditolak, atau mundur
                </div>
            </a>
        </div>
    </div>
</div>

