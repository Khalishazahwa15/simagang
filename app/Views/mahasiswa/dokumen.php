<?php
// Dideklarasikan di luar seluruh percabangan: kedua tabel di halaman ini
// memakainya, termasuk saat mahasiswa belum mengunggah satu dokumen pun.
$dokumen = $dokumen ?? [];

$docTypes = [
    'surat_lamaran' => 'Surat Lamaran / Surat Pengantar',
    'cv' => 'Curriculum Vitae',
    'transkrip' => 'Transkrip Nilai',
    'laporan' => 'Laporan Akhir'
];

$officialDocs = ['surat_balasan', 'surat_penerimaan_final', 'surat_tugas', 'surat_keterangan', 'dokumen_akhir_magang', 'laporan_akhir'];
?>
<!-- Note: Topbar already contains Title and Subtitle -->

<div style="display: flex; flex-direction: column; gap: 32px;">
    
    <!-- Table: Dokumen Pengajuan Saya -->
    <div>
        <div style="margin-bottom: 16px;">
            <h3 style="font-family: var(--font-display); font-size: 18px; font-weight: 600; color: var(--text-primary); margin: 0 0 4px 0;">Dokumen Pengajuan Saya</h3>
            <p style="font-family: var(--font-body); font-size: var(--text-body-sm); color: var(--text-secondary); margin: 0;">Versi lama disimpan sebagai histori — tidak dihapus saat diperbarui.</p>
        </div>
        
        <div style="background: var(--bg-main); border: 1px solid var(--border); border-radius: 10px; overflow: hidden;">
            <?php if (!empty($dokumen)): ?>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; min-width: 700px;">
                    <thead>
                        <tr style="background: var(--bg-soft);">
                            <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 12px; font-weight: 500; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">DOKUMEN</th>
                            <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 12px; font-weight: 500; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">VERSI</th>
                            <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 12px; font-weight: 500; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">TANGGAL</th>
                            <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 12px; font-weight: 500; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">STATUS</th>
                            <th style="padding: 12px 16px; text-align: right; font-family: var(--font-body); font-size: 12px; font-weight: 500; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($dokumen as $dok): 
                            // Skip official documents in this table
                            if (in_array($dok['jenis_dokumen'], $officialDocs)) continue;
                            
                            $label = $docTypes[$dok['jenis_dokumen']] ?? ucfirst($dok['jenis_dokumen']);
                            // Check extension
                            $ext = strtoupper(pathinfo($dok['original_filename'], PATHINFO_EXTENSION));
                            if (!$ext) $ext = 'PDF';
                            
                            $dateStr = date('d M Y', strtotime($dok['created_at']));
                            
                            // Status mapping (since schema doesn't have status on dokumen, we assume it's valid if it exists, or check pengajuan status)
                            $statusBadge = '<span class="badge" style="background: var(--color-success-soft); color: var(--primary);">TERUNGGAH</span>';
                        ?>
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 13px 16px;">
                                <div style="font-family: var(--font-body); font-size: var(--text-body-sm); font-weight: 500; color: var(--text-primary); margin-bottom: 2px;"><?= $label ?></div>
                                <div style="font-family: var(--font-body); font-size: 12px; color: var(--text-secondary);"><?= $ext ?></div>
                            </td>
                            <td style="padding: 13px 16px;">
                                <span style="font-family: var(--font-body); font-size: var(--text-body-sm); color: var(--text-primary);">v<?= $dok['version'] ?? '1' ?></span>
                            </td>
                            <td style="padding: 13px 16px;">
                                <span style="font-family: var(--font-body); font-size: 12.5px; color: var(--text-primary);"><?= $dateStr ?></span>
                            </td>
                            <td style="padding: 13px 16px;">
                                <?= $statusBadge ?>
                            </td>
                            <td style="padding: 13px 16px; text-align: right;">
                                <div style="display: flex; justify-content: flex-end; gap: 12px;">
                                    <a href="<?= BASE_URL ?>/mahasiswa/dokumen/view/<?= $dok['id'] ?>" target="_blank" style="display: flex; align-items: center; justify-content: center; padding: 6px; background: var(--bg-soft); border: 1px solid var(--border); border-radius: 6px; cursor: pointer; color: var(--primary); text-decoration: none;" title="Lihat Dokumen">
                                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>
                                    <a href="<?= BASE_URL ?>/mahasiswa/dokumen/download/<?= $dok['id'] ?>" style="display: flex; align-items: center; justify-content: center; padding: 6px; background: var(--bg-soft); border: 1px solid var(--border); border-radius: 6px; cursor: pointer; color: var(--text-secondary); text-decoration: none;" title="Unduh Dokumen">
                                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                    </a>
                                    <?php if ($pengajuan && $pengajuan['status'] === 'revisi'): ?>
                                    <a href="<?= BASE_URL ?>/mahasiswa/pengajuan/revisi" style="display: flex; align-items: center; justify-content: center; padding: 6px; background: var(--bg-soft); border: 1px solid var(--border); border-radius: 6px; cursor: pointer; color: var(--text-secondary); text-decoration: none;" title="Perbarui Dokumen">
                                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div style="padding: 48px 24px; text-align: center;">
                <p style="font-family: var(--font-body); font-size: var(--text-body-sm); color: var(--text-secondary);">Belum ada dokumen yang diunggah.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Dokumen Resmi dari Bappeda -->
    <div>
        <div style="margin-bottom: 16px;">
            <h3 style="font-family: var(--font-display); font-size: 18px; font-weight: 600; color: var(--text-primary); margin: 0 0 4px 0;">Dokumen Resmi dari Bappeda</h3>
            <p style="font-family: var(--font-body); font-size: var(--text-body-sm); color: var(--text-secondary); margin: 0;">Diterbitkan dan diunggah oleh Sekretariat setelah selesai proses administrasi.</p>
        </div>
        
        <div style="background: var(--bg-main); border: 1px solid var(--border); border-radius: 10px; overflow: hidden; display: flex; flex-direction: column;">
            
            <?php 
            $bappedaDokumen = array_filter($dokumen, function($dok) use ($officialDocs) {
                return in_array($dok['jenis_dokumen'], $officialDocs);
            });
            ?>
            
            <?php if (!empty($bappedaDokumen)): ?>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; min-width: 700px;">
                    <thead>
                        <tr style="background: var(--bg-soft);">
                            <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 12px; font-weight: 500; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">DOKUMEN</th>
                            <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 12px; font-weight: 500; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">VERSI</th>
                            <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 12px; font-weight: 500; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">TANGGAL</th>
                            <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 12px; font-weight: 500; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">STATUS</th>
                            <th style="padding: 12px 16px; text-align: right; font-family: var(--font-body); font-size: 12px; font-weight: 500; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $bappedaDocTypes = [
                            'surat_balasan' => 'Surat Balasan / Jawaban Magang',
                            'surat_penerimaan_final' => 'Surat Penerimaan Final',
                            'surat_tugas' => 'Surat Tugas Pembimbing',
                            'surat_keterangan' => 'Surat Keterangan',
                            'dokumen_akhir_magang' => 'Sertifikat / Surat Selesai',
                            'laporan_penilaian' => 'Laporan Penilaian Magang'
                        ];
                        
                        foreach ($bappedaDokumen as $dok): 
                            $label = $bappedaDocTypes[$dok['jenis_dokumen']] ?? ucfirst(str_replace('_', ' ', $dok['jenis_dokumen']));
                            $ext = strtoupper(pathinfo($dok['original_filename'], PATHINFO_EXTENSION));
                            if (!$ext) $ext = 'PDF';
                            
                            $dateStr = date('d M Y', strtotime($dok['created_at']));
                            $statusBadge = '<span class="badge" style="background: var(--color-success-soft); color: var(--primary);">RESMI BAPPEDA</span>';
                        ?>
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 13px 16px;">
                                <div style="font-family: var(--font-body); font-size: var(--text-body-sm); font-weight: 500; color: var(--text-primary); margin-bottom: 2px;"><?= $label ?></div>
                                <div style="font-family: var(--font-body); font-size: 12px; color: var(--text-secondary);"><?= $ext ?></div>
                            </td>
                            <td style="padding: 13px 16px;">
                                <span style="font-family: var(--font-body); font-size: var(--text-body-sm); color: var(--text-primary);">v<?= $dok['version'] ?? '1' ?></span>
                            </td>
                            <td style="padding: 13px 16px;">
                                <span style="font-family: var(--font-body); font-size: 12.5px; color: var(--text-primary);"><?= $dateStr ?></span>
                            </td>
                            <td style="padding: 13px 16px;">
                                <?= $statusBadge ?>
                            </td>
                            <td style="padding: 13px 16px; text-align: right;">
                                <div style="display: flex; justify-content: flex-end; gap: 12px;">
                                    <a href="<?= BASE_URL ?>/mahasiswa/dokumen/view/<?= $dok['id'] ?>" target="_blank" style="display: flex; align-items: center; justify-content: center; padding: 6px; background: var(--bg-soft); border: 1px solid var(--border); border-radius: 6px; cursor: pointer; color: var(--primary); text-decoration: none;" title="Lihat Dokumen">
                                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>
                                    <a href="<?= BASE_URL ?>/mahasiswa/dokumen/download/<?= $dok['id'] ?>" style="display: flex; align-items: center; justify-content: center; padding: 6px; background: var(--bg-soft); border: 1px solid var(--border); border-radius: 6px; cursor: pointer; color: var(--text-secondary); text-decoration: none;" title="Unduh Dokumen">
                                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div style="padding: 48px 24px; text-align: center;">
                <p style="font-family: var(--font-body); font-size: var(--text-body-sm); color: var(--text-secondary);">Belum ada dokumen resmi yang diterbitkan.</p>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>


