<div class="mb-6">
    <p class="text-muted" style="font-family: var(--font-body); font-size: 13.5px; margin: 0;"><?= isset($totalRows) ? $totalRows : '0' ?> total pengajuan ditemukan</p>
</div>

<form method="GET" action="<?= BASE_URL ?>/sekretariat/pengajuan">
    <div class="d-flex align-center" style="margin-bottom: 20px; flex-wrap: wrap; gap: 12px; width: 100%;">
        <div style="position: relative; flex: 1; min-width: 240px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-secondary);"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            <input type="text" name="q" placeholder="Cari nama / nomor pengajuan..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" style="width: 100%; padding: 9px 14px 9px 36px; background: var(--bg-main); border: 1px solid var(--border); border-radius: 8px; font-family: var(--font-body); font-size: 13.5px; color: var(--text-primary); outline: none;">
        </div>
        
        <select name="divisi" style="width: 160px; padding: 9px 14px; background: var(--bg-main); border: 1px solid var(--border); border-radius: 8px; font-family: var(--font-body); font-size: 13.5px; color: var(--text-secondary); outline: none; cursor: pointer;">
            <option value="">Semua Divisi</option>
            <?php foreach ($divisiList as $d): ?>
                <option value="<?= $d['id'] ?>" <?= ($_GET['divisi'] ?? '') == $d['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($d['nama_divisi']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <select name="status" style="width: 160px; padding: 9px 14px; background: var(--bg-main); border: 1px solid var(--border); border-radius: 8px; font-family: var(--font-body); font-size: 13.5px; color: var(--text-primary); outline: none; cursor: pointer;">
            <option value="">Semua Status</option>
            <option value="diajukan" <?= ($_GET['status'] ?? '') == 'diajukan' ? 'selected' : '' ?>>Diajukan</option>
            <option value="dalam_verifikasi" <?= ($_GET['status'] ?? '') == 'dalam_verifikasi' ? 'selected' : '' ?>>Dalam Verifikasi</option>
            <option value="revisi" <?= ($_GET['status'] ?? '') == 'revisi' ? 'selected' : '' ?>>Revisi</option>
            <option value="menunggu_konfirmasi_tawaran" <?= ($_GET['status'] ?? '') == 'menunggu_konfirmasi_tawaran' ? 'selected' : '' ?>>Menunggu Konfirmasi</option>
            <option value="menunggu_finalisasi_sekretariat" <?= ($_GET['status'] ?? '') == 'menunggu_finalisasi_sekretariat' ? 'selected' : '' ?>>Menunggu Finalisasi</option>
            <option value="diterima" <?= ($_GET['status'] ?? '') == 'diterima' ? 'selected' : '' ?>>Diterima</option>
            <option value="ditolak" <?= ($_GET['status'] ?? '') == 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
            <option value="sedang_magang" <?= ($_GET['status'] ?? '') == 'sedang_magang' ? 'selected' : '' ?>>Sedang Magang</option>
            <option value="mengundurkan_diri" <?= ($_GET['status'] ?? '') == 'mengundurkan_diri' ? 'selected' : '' ?>>Mengundurkan Diri</option>
            <option value="selesai" <?= ($_GET['status'] ?? '') == 'selesai' ? 'selected' : '' ?>>Selesai</option>
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
                    <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 10.5px; font-weight: 700; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">No. Pengajuan</th>
                    <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 10.5px; font-weight: 700; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">Mahasiswa</th>
                    <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 10.5px; font-weight: 700; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">Perguruan Tinggi</th>
                    <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 10.5px; font-weight: 700; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">Divisi Preferensi</th>
                    <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 10.5px; font-weight: 700; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">Status</th>
                    <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 10.5px; font-weight: 700; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">Tanggal</th>
                    <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 10.5px; font-weight: 700; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);"></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($pengajuan)): ?>
                <tr>
                    <td colspan="7" style="padding: 24px; text-align: center; color: var(--text-secondary); font-family: var(--font-body); font-size: 13.5px;">Tidak ada data pengajuan.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($pengajuan as $p): ?>
                <tr style="border-bottom: 1px solid var(--border);">
                    <td style="padding: 13px 16px;">
                        <span style="font-family: var(--font-mono); font-size: 12.5px; color: var(--primary); font-weight: 600;"><?= htmlspecialchars($p['nomor_pengajuan']) ?></span>
                    </td>
                    <td style="padding: 13px 16px;">
                        <div style="font-family: var(--font-body); font-size: 13.5px; font-weight: 600; color: var(--text-primary);"><?= htmlspecialchars($p['nama'] ?? 'Mahasiswa') ?></div>
                        <div style="font-family: var(--font-body); font-size: 11.5px; color: var(--text-secondary);">NPM. <?= htmlspecialchars($p['nim'] ?? '-') ?> &middot; <?= htmlspecialchars($p['program_studi'] ?? '-') ?></div>
                    </td>
                    <td style="padding: 13px 16px; font-family: var(--font-body); font-size: 13px; color: var(--text-secondary);"><?= htmlspecialchars($p['universitas'] ?? '-') ?></td>
                    <td style="padding: 13px 16px; font-family: var(--font-body); font-size: 13px; color: var(--text-secondary);"><?= htmlspecialchars($p['nama_divisi_preferensi'] ?? '-') ?></td>
                    <td style="padding: 13px 16px;"><span class="badge badge-<?= htmlspecialchars($p['status']) ?>"><?= ucfirst(str_replace('_', ' ', htmlspecialchars($p['status']))) ?></span></td>
                    <td style="padding: 13px 16px; font-family: var(--font-body); font-size: 12.5px; color: var(--text-secondary); white-space: nowrap;"><?= date('d M Y', strtotime($p['created_at'])) ?></td>
                    <td style="padding: 13px 16px;">
                        <a href="<?= BASE_URL ?>/sekretariat/pengajuan/detail/<?= $p['id'] ?>" style="display: flex; align-items: center; gap: 5px; padding: 6px 14px; background: var(--bg-green-soft); border: 1px solid var(--primary-light); border-radius: 6px; cursor: pointer; font-family: var(--font-body); font-size: 12px; font-weight: 600; color: var(--primary); white-space: nowrap; text-decoration: none;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            Buka
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
        Halaman <?= $page ?> dari <?= $totalPages ?> (Total: <?= $totalRows ?> pengajuan)
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


