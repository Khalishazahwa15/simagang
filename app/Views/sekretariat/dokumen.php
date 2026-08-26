
<!-- Filters -->
<form method="GET" action="<?= BASE_URL ?>/sekretariat/dokumen">
    <div class="d-flex align-center" style="margin-bottom: 20px; flex-wrap: wrap; gap: 12px; width: 100%;">
        <div style="position: relative; flex: 1 1 200px; min-width: 0;">
            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-secondary);"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            <input type="text" name="q" value="<?= htmlspecialchars($q ?? '') ?>" placeholder="Cari nama mahasiswa atau nomor pengajuan..." style="width: 100%; padding: 9px 14px 9px 36px; background: var(--bg-main); border: 1px solid var(--border); border-radius: 8px; font-family: var(--font-body); font-size: var(--text-body-sm); color: var(--text-primary); outline: none;">
        </div>
        <button type="submit" class="btn btn-primary" style="padding: 9px 16px;">Cari</button>
    </div>
</form>

<!-- Main Table -->
<div style="background: var(--bg-main); border: 1px solid var(--border); border-radius: 10px; overflow: hidden; margin-bottom: 24px;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; min-width: 760px;">
            <thead>
                <tr style="background: var(--bg-soft);">
                    <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 12px; font-weight: 700; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">Peserta Magang</th>
                    <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 12px; font-weight: 700; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">Nomor Pengajuan</th>
                    <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 12px; font-weight: 700; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">File Laporan</th>
                    <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 12px; font-weight: 700; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">Tgl Unggah</th>
                    <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 12px; font-weight: 700; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);"></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($dokumenList)): ?>
                <tr>
                    <td colspan="5" style="padding: 32px; text-align: center;">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--border)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 12px; display: block;"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
                        <div style="font-family: var(--font-body); font-size: var(--text-body-sm); color: var(--text-secondary);">Tidak ada laporan akhir yang ditemukan.</div>
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($dokumenList as $d): ?>
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="padding: 13px 16px;">
                            <div style="font-family: var(--font-body); font-size: var(--text-body-sm); font-weight: 600; color: var(--text-primary);"><?= htmlspecialchars($d['mahasiswa_nama']) ?></div>
                        </td>
                        <td style="padding: 13px 16px; font-family: var(--font-body); font-size: var(--text-body-sm); color: var(--text-secondary);"><?= htmlspecialchars($d['nomor_pengajuan']) ?></td>
                        <td style="padding: 13px 16px;">
                            <div style="font-family: var(--font-body); font-size: var(--text-body-sm); font-weight: 600; color: var(--text-primary);"><?= htmlspecialchars($d['original_filename']) ?></div>
                        </td>
                        <td style="padding: 13px 16px; font-family: var(--font-body); font-size: 12.5px; color: var(--text-secondary); white-space: nowrap;"><?= date('d M Y', strtotime($d['created_at'])) ?></td>
                        <td style="padding: 13px 16px;">
                            <div style="display: flex; gap: 8px;">
                                <a href="<?= BASE_URL ?>/sekretariat/dokumen/view/<?= $d['id'] ?>" target="_blank" style="display: flex; align-items: center; justify-content: center; padding: 6px; background: var(--bg-soft); border: 1px solid var(--border); border-radius: 6px; cursor: pointer; color: var(--primary); text-decoration: none;" title="Lihat Dokumen">
                                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>
                                <a href="<?= BASE_URL ?>/sekretariat/dokumen/download/<?= $d['id'] ?>" style="display: flex; align-items: center; justify-content: center; padding: 6px; background: var(--bg-soft); border: 1px solid var(--border); border-radius: 6px; cursor: pointer; color: var(--text-secondary); text-decoration: none;" title="Unduh Dokumen">
                                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                </a>
                            </div>
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
        Halaman <?= $page ?> dari <?= $totalPages ?> (Total: <?= $totalRows ?> dokumen)
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

