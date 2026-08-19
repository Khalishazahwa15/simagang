<!-- Note: Topbar already contains Title and Subtitle -->

<div style="display: flex; flex-direction: column; gap: 32px; max-width: 1028px;">
    
    <!-- Table: Dokumen Pengajuan Saya -->
    <div>
        <div style="margin-bottom: 16px;">
            <h3 style="font-family: var(--font-display); font-size: 18px; font-weight: 600; color: var(--text-primary); margin: 0 0 4px 0;">Dokumen Pengajuan Saya</h3>
            <p style="font-family: var(--font-body); font-size: 13.5px; color: var(--text-secondary); margin: 0;">Versi lama disimpan sebagai histori — tidak dihapus saat diperbarui.</p>
        </div>
        
        <div style="background: var(--bg-main); border: 1px solid var(--border); border-radius: 10px; overflow: hidden;">
            <?php if (!empty($dokumen)): ?>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; min-width: 700px;">
                    <thead>
                        <tr style="background: var(--bg-soft);">
                            <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 10.5px; font-weight: 700; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">DOKUMEN</th>
                            <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 10.5px; font-weight: 700; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">VERSI</th>
                            <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 10.5px; font-weight: 700; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">TANGGAL</th>
                            <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 10.5px; font-weight: 700; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">STATUS</th>
                            <th style="padding: 12px 16px; text-align: right; font-family: var(--font-body); font-size: 10.5px; font-weight: 700; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $docTypes = [
                            'surat_lamaran' => 'Surat Lamaran / Surat Pengantar',
                            'cv' => 'Curriculum Vitae',
                            'transkrip' => 'Transkrip Nilai',
                            'laporan' => 'Laporan Akhir'
                        ];
                        
                        foreach ($dokumen as $dok): 
                            // Skip official documents in this table
                            if (in_array($dok['jenis_dokumen'], ['surat_penerimaan_final', 'dokumen_akhir_magang', 'surat_pengunduran_diri'])) continue;
                            
                            $label = $docTypes[$dok['jenis_dokumen']] ?? ucfirst($dok['jenis_dokumen']);
                            // Check extension
                            $ext = strtoupper(pathinfo($dok['original_filename'], PATHINFO_EXTENSION));
                            if (!$ext) $ext = 'PDF';
                            
                            $dateStr = date('d M Y', strtotime($dok['created_at']));
                            
                            // Status mapping (since schema doesn't have status on dokumen, we assume it's valid if it exists, or check pengajuan status)
                            $statusBadge = '<span class="badge" style="background: #E8F5E9; color: var(--primary);">TERUNGGAH</span>';
                        ?>
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 13px 16px;">
                                <div style="font-family: var(--font-body); font-size: 13.5px; font-weight: 600; color: var(--text-primary); margin-bottom: 2px;"><?= $label ?></div>
                                <div style="font-family: var(--font-body); font-size: 11.5px; color: var(--text-secondary);"><?= $ext ?></div>
                            </td>
                            <td style="padding: 13px 16px;">
                                <span style="font-family: var(--font-body); font-size: 13px; color: var(--text-primary);">v<?= $dok['version'] ?? '1' ?></span>
                            </td>
                            <td style="padding: 13px 16px;">
                                <span style="font-family: var(--font-body); font-size: 12.5px; color: var(--text-primary);"><?= $dateStr ?></span>
                            </td>
                            <td style="padding: 13px 16px;">
                                <?= $statusBadge ?>
                            </td>
                            <td style="padding: 13px 16px; text-align: right;">
                                <div style="display: flex; justify-content: flex-end; gap: 12px;">
                                    <a href="<?= BASE_URL ?>/mahasiswa/dokumen/download/<?= $dok['id'] ?>" target="_blank" style="display: flex; align-items: center; justify-content: center; padding: 6px; background: var(--bg-soft); border: 1px solid var(--border); border-radius: 6px; cursor: pointer; color: var(--text-secondary); text-decoration: none;" title="Unduh Dokumen">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>
                                    <?php if ($pengajuan && $pengajuan['status'] === 'revisi'): ?>
                                    <a href="<?= BASE_URL ?>/mahasiswa/pengajuan/revisi" style="display: flex; align-items: center; justify-content: center; padding: 6px; background: var(--bg-soft); border: 1px solid var(--border); border-radius: 6px; cursor: pointer; color: var(--text-secondary); text-decoration: none;" title="Perbarui Dokumen">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
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
                <p style="font-family: var(--font-body); font-size: 13.5px; color: var(--text-secondary);">Belum ada dokumen yang diunggah.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Dokumen Resmi dari Bappeda -->
    <div>
        <div style="margin-bottom: 16px;">
            <h3 style="font-family: var(--font-display); font-size: 18px; font-weight: 600; color: var(--text-primary); margin: 0 0 4px 0;">Dokumen Resmi dari Bappeda</h3>
            <p style="font-family: var(--font-body); font-size: 13.5px; color: var(--text-secondary); margin: 0;">Diterbitkan dan diunggah oleh Sekretariat setelah selesai proses administrasi.</p>
        </div>
        
        <div style="background: var(--bg-main); border: 1px solid var(--border); border-radius: 10px; overflow: hidden; display: flex; flex-direction: column;">
            
            <!-- Item 1: Surat Balasan / Penerimaan -->
            <div style="padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 16px; background: var(--bg-main);">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div style="width: 36px; height: 36px; border-radius: 8px; background: var(--bg-soft); display: flex; align-items: center; justify-content: center; border: 1px solid var(--border); flex-shrink: 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--text-secondary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><line x1="10" y1="9" x2="8" y2="9"/></svg>
                    </div>
                    <div>
                        <div style="font-family: var(--font-body); font-size: 13.5px; font-weight: 600; color: var(--text-primary); margin-bottom: 2px;">Surat Penerimaan Magang</div>
                        <?php if ($pengajuan && in_array($pengajuan['status'], ['diterima', 'sedang_magang', 'selesai'])): ?>
                            <div style="font-family: var(--font-body); font-size: 12.5px; color: var(--text-secondary);">Tersedia</div>
                        <?php else: ?>
                            <div style="font-family: var(--font-body); font-size: 12.5px; color: var(--text-secondary);">Belum tersedia — sedang diproses</div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php 
                $suratPenerimaan = array_filter($dokumen, fn($d) => $d['jenis_dokumen'] === 'surat_penerimaan_final');
                $suratPenerimaan = reset($suratPenerimaan);
                if ($pengajuan && in_array($pengajuan['status'], ['diterima', 'sedang_magang', 'selesai']) && $suratPenerimaan): 
                ?>
                    <a href="<?= BASE_URL ?>/mahasiswa/dokumen/download/<?= $suratPenerimaan['id'] ?>" style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: var(--primary); color: white; border: none; border-radius: 6px; font-family: var(--font-body); font-size: 13px; font-weight: 600; text-decoration: none;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Unduh
                    </a>
                <?php else: ?>
                    <div style="display: inline-flex; align-items: center; gap: 6px; font-family: var(--font-body); font-size: 12.5px; color: var(--text-secondary);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Menunggu
                    </div>
                <?php endif; ?>
            </div>

            <!-- Item 2: Surat Keterangan Selesai Magang -->
            <div style="padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 16px; background: var(--bg-main);">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div style="width: 36px; height: 36px; border-radius: 8px; background: var(--bg-soft); display: flex; align-items: center; justify-content: center; border: 1px solid var(--border); flex-shrink: 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--text-secondary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><line x1="10" y1="9" x2="8" y2="9"/></svg>
                    </div>
                    <div>
                        <div style="font-family: var(--font-body); font-size: 13.5px; font-weight: 600; color: var(--text-primary); margin-bottom: 2px;">Surat Keterangan Selesai Magang</div>
                        <?php if ($pengajuan && $pengajuan['status'] === 'selesai'): ?>
                            <div style="font-family: var(--font-body); font-size: 12.5px; color: var(--text-secondary);">Tersedia</div>
                        <?php else: ?>
                            <div style="font-family: var(--font-body); font-size: 12.5px; color: var(--text-secondary);">Belum tersedia — sedang diproses</div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php 
                $suratAkhir = array_filter($dokumen, fn($d) => $d['jenis_dokumen'] === 'dokumen_akhir_magang');
                $suratAkhir = reset($suratAkhir);
                if ($pengajuan && $pengajuan['status'] === 'selesai' && $suratAkhir): 
                ?>
                    <a href="<?= BASE_URL ?>/mahasiswa/dokumen/download/<?= $suratAkhir['id'] ?>" style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: var(--primary); color: white; border: none; border-radius: 6px; font-family: var(--font-body); font-size: 13px; font-weight: 600; text-decoration: none;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Unduh
                    </a>
                <?php else: ?>
                    <div style="display: inline-flex; align-items: center; gap: 6px; font-family: var(--font-body); font-size: 12.5px; color: var(--text-secondary);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Menunggu
                    </div>
                <?php endif; ?>
            </div>

            <!-- Item 3: Sertifikat Magang -->
            <div style="padding: 20px 24px; display: flex; align-items: center; justify-content: space-between; gap: 16px; background: var(--bg-main);">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div style="width: 36px; height: 36px; border-radius: 8px; background: var(--bg-soft); display: flex; align-items: center; justify-content: center; border: 1px solid var(--border); flex-shrink: 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--text-secondary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="8" width="18" height="12" rx="2" ry="2"/><path d="M7 8V6a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2"/><line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/></svg>
                    </div>
                    <div>
                        <div style="font-family: var(--font-body); font-size: 13.5px; font-weight: 600; color: var(--text-primary); margin-bottom: 2px;">Sertifikat Magang</div>
                        <?php if ($pengajuan && $pengajuan['status'] === 'selesai'): ?>
                            <div style="font-family: var(--font-body); font-size: 12.5px; color: var(--text-secondary);">Tersedia</div>
                        <?php else: ?>
                            <div style="font-family: var(--font-body); font-size: 12.5px; color: var(--text-secondary);">Belum tersedia — sedang diproses</div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if ($pengajuan && $pengajuan['status'] === 'selesai'): ?>
                    <button style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: var(--primary); color: white; border: none; border-radius: 6px; font-family: var(--font-body); font-size: 13px; font-weight: 600; text-decoration: none; cursor:not-allowed;" disabled title="Belum Terintegrasi">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Unduh
                    </button>
                <?php else: ?>
                    <div style="display: inline-flex; align-items: center; gap: 6px; font-family: var(--font-body); font-size: 12.5px; color: var(--text-secondary);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Menunggu
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>


