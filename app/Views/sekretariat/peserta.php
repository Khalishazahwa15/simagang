
<!-- Filters -->
<form method="GET" action="<?= BASE_URL ?>/sekretariat/peserta">
    <div class="d-flex align-center" style="margin-bottom: 20px; flex-wrap: wrap; gap: 12px; width: 100%;">
        <div style="position: relative; flex: 1 1 200px; min-width: 0;">
            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-secondary);"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            <input type="text" name="q" value="<?= htmlspecialchars($q ?? '') ?>" placeholder="Cari nama, institusi, atau bidang..." style="width: 100%; padding: 9px 14px 9px 36px; background: var(--bg-main); border: 1px solid var(--border); border-radius: 8px; font-family: var(--font-body); font-size: var(--text-body-sm); color: var(--text-primary); outline: none;">
        </div>
        
        <select name="divisi" style="flex: 1 1 150px; min-width: 0; padding: 9px 14px; background: var(--bg-main); border: 1px solid var(--border); border-radius: 8px; font-family: var(--font-body); font-size: var(--text-body-sm); color: var(--text-secondary); outline: none; cursor: pointer;">
            <option value="">Semua Divisi</option>
            <?php foreach ($divisiList as $d): ?>
                <option value="<?= $d['id'] ?>" <?= ($_GET['divisi'] ?? '') == $d['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($d['nama_divisi']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <select name="status" style="flex: 1 1 150px; min-width: 0; padding: 9px 14px; background: var(--bg-main); border: 1px solid var(--border); border-radius: 8px; font-family: var(--font-body); font-size: var(--text-body-sm); color: var(--text-primary); outline: none; cursor: pointer;">
            <option value="">Semua Status</option>
            <option value="sedang_magang" <?= ($_GET['status'] ?? '') == 'sedang_magang' ? 'selected' : '' ?>>Aktif (Sedang Magang)</option>
            <option value="selesai" <?= ($_GET['status'] ?? '') == 'selesai' ? 'selected' : '' ?>>Selesai (Alumni)</option>
            <option value="diterima" <?= ($_GET['status'] ?? '') == 'diterima' ? 'selected' : '' ?>>Diterima (Menunggu)</option>
        </select>
        
        <button type="submit" class="btn btn-primary" style="padding: 9px 16px;">Cari</button>
    </div>
</form>

<!-- Main Table -->
<div style="background: var(--bg-main); border: 1px solid var(--border); border-radius: 10px; overflow: hidden; margin-bottom: 24px;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; min-width: 860px;">
            <thead>
                <tr style="background: var(--bg-soft);">
                    <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 12px; font-weight: 500; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">Mahasiswa</th>
                    <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 12px; font-weight: 500; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">Institusi & Prodi</th>
                    <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 12px; font-weight: 500; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">Bidang Penempatan</th>
                    <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 12px; font-weight: 500; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">Periode Magang</th>
                    <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 12px; font-weight: 500; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">Status</th>
                    <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 12px; font-weight: 500; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);"></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($peserta)): ?>
                <tr>
                    <td colspan="6" style="padding: 24px; text-align: center; color: var(--text-secondary); font-size: 14px;">Tidak ada peserta aktif.</td>
                </tr>
                <?php else: ?>
                    <?php foreach ($peserta as $p): 
                        $mulai = new DateTime($p['tanggal_mulai_rencana']);
                        $selesai = new DateTime($p['tanggal_selesai_rencana']);
                        $now = new DateTime();
                        
                        $sisaHari = 0;
                        if ($now < $selesai) {
                            $sisaHari = $now->diff($selesai)->days;
                        }
                    ?>
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="padding: 13px 16px;">
                            <div class="d-flex align-center gap-3">
                                <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--bg-green-soft); color: var(--primary); display: flex; align-items: center; justify-content: center; font-family: var(--font-display); font-size: 18px;">
                                    <?= strtoupper(substr($p['nama'], 0, 1)) ?>
                                </div>
                                <div>
                                    <div style="font-family: var(--font-body); font-size: var(--text-body-sm); font-weight: 500; color: var(--text-primary);"><?= htmlspecialchars($p['nama']) ?></div>
                                    <div style="font-family: var(--font-body); font-size: 12px; color: var(--text-secondary);"><?= htmlspecialchars($p['nomor_pengajuan']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 13px 16px;">
                            <div style="font-family: var(--font-body); font-size: var(--text-body-sm); font-weight: 500; color: var(--text-primary);"><?= htmlspecialchars($p['universitas'] ?? '-') ?></div>
                            <div style="font-family: var(--font-body); font-size: 12px; color: var(--text-secondary);"><?= htmlspecialchars($p['program_studi'] ?? '-') ?></div>
                        </td>
                        <td style="padding: 13px 16px; font-family: var(--font-body); font-size: var(--text-body-sm); color: var(--text-secondary);"><?= htmlspecialchars($p['divisi_nama'] ?? '-') ?></td>
                        <td style="padding: 13px 16px;">
                            <div style="font-family: var(--font-body); font-size: 12.5px; color: var(--text-secondary); white-space: nowrap;"><?= $mulai->format('d M') ?> - <?= $selesai->format('d M Y') ?></div>
                            <?php if ($sisaHari > 0): ?>
                                <div style="font-family: var(--font-body); font-size: 12px; color: var(--accent-dark); font-weight: 500;">Sisa <?= $sisaHari ?> hari</div>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 13px 16px;"><span class="badge badge-disetujui"><?= $p['status'] === 'sedang_magang' ? 'Aktif' : 'Diterima' ?></span></td>
                        <td style="padding: 13px 16px;">
                            <a href="<?= BASE_URL ?>/sekretariat/pengajuan/detail/<?= $p['id'] ?>" style="display: inline-flex; align-items: center; gap: 5px; padding: 6px 14px; background: var(--bg-soft); border: 1px solid var(--border); border-radius: 6px; cursor: pointer; font-family: var(--font-body); font-size: 12px; font-weight: 500; color: var(--text-primary); text-decoration: none; white-space: nowrap;">
                                Detail
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<?php if (isset($totalPages) && $totalPages > 1): ?>
<div class="d-flex align-center justify-between mt-4">
    <span class="text-muted" style="font-size: 12.5px;">
        Halaman <?= $page ?> dari <?= $totalPages ?> (Total: <?= $totalRows ?> peserta)
    </span>
    <div class="d-flex gap-2">
        <?php 
            $queryParams = $_GET;
            
            $queryParams['page'] = $page - 1;
            $prevUrl = '?' . http_build_query($queryParams);
            
            $queryParams['page'] = $page + 1;
            $nextUrl = '?' . http_build_query($queryParams);
        ?>
        <?php if ($page > 1): ?>
            <a href="<?= $prevUrl ?>" class="btn btn-outline btn-sm" style="padding: 6px 12px; border-radius: 6px; text-decoration: none;">Sebelumnya</a>
        <?php else: ?>
            <button class="btn btn-outline btn-sm" disabled style="padding: 6px 12px; border-radius: 6px; opacity: 0.5; cursor: not-allowed;">Sebelumnya</button>
        <?php endif; ?>
        
        <?php if ($page < $totalPages): ?>
            <a href="<?= $nextUrl ?>" class="btn btn-outline btn-sm" style="padding: 6px 12px; border-radius: 6px; text-decoration: none;">Selanjutnya</a>
        <?php else: ?>
            <button class="btn btn-outline btn-sm" disabled style="padding: 6px 12px; border-radius: 6px; opacity: 0.5; cursor: not-allowed;">Selanjutnya</button>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

