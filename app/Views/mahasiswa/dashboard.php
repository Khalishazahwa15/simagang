<?php
$statusLabel = 'Belum Ada Pengajuan';
$statusDesc = 'Mulai pengajuan baru';
if ($pengajuan) {
    switch ($pengajuan['status']) {
        case 'draft': $statusLabel = 'Draft'; $statusDesc = 'Belum dikirim'; break;
        case 'diajukan': $statusLabel = 'Diajukan'; $statusDesc = 'Menunggu antrean'; break;
        case 'dalam_verifikasi': $statusLabel = 'Diperiksa'; $statusDesc = 'Pemeriksaan berkas'; break;
        case 'revisi': $statusLabel = 'Revisi'; $statusDesc = 'Perbaikan dokumen'; break;
        case 'diterima': $statusLabel = 'Diterima'; $statusDesc = 'Menunggu mulai magang'; break;
        case 'ditolak': $statusLabel = 'Ditolak'; $statusDesc = 'Tidak memenuhi kriteria'; break;
        case 'sedang_magang': $statusLabel = 'Sedang Magang'; $statusDesc = 'Periode magang aktif'; break;
        case 'selesai': $statusLabel = 'Selesai'; $statusDesc = 'Program selesai'; break;
    }
}

$durasi = '-';
$durasiDesc = 'Waktu pelaksanaan';
if ($pengajuan && $pengajuan['tanggal_mulai_rencana'] && $pengajuan['tanggal_selesai_rencana']) {
    $start = new DateTime($pengajuan['tanggal_mulai_rencana']);
    $end = new DateTime($pengajuan['tanggal_selesai_rencana']);
    $diff = $start->diff($end);
    $durasi = $diff->m . ' Bulan';
    if ($diff->m == 0 && $diff->d > 0) $durasi = $diff->d . ' Hari';
}

$bidang = '-';
$bidangDesc = 'Unit kerja penempatan';
if ($pengajuan) {
    $bidang = $pengajuan['nama_divisi_final'] ?? $pengajuan['nama_divisi_preferensi'] ?? '-';
    // Truncate if too long
    if (strlen($bidang) > 25) {
        $bidang = substr($bidang, 0, 22) . '...';
    }
}

$dokCount = count($dokumen ?? []);
$dokDesc = 'Berkas terunggah';
?>
<?php if (!empty($profilKurang)): ?>
<div style="background: var(--color-warning-soft); border: 1px solid var(--color-warning-border); border-radius: 10px; padding: 16px 20px; margin-bottom: 24px; display: flex; align-items: flex-start; gap: 14px;">
    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--color-warning-ink)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; margin-top:2px"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
    <div style="flex: 1;">
        <div style="font-family: var(--font-body); font-weight: 700; font-size: 14px; color: var(--color-warning-ink); margin-bottom: 4px;">Lengkapi profil dulu sebelum mengajukan magang</div>
        <div style="font-family: var(--font-body); font-size: 13px; color: var(--color-warning-ink); line-height: 1.6;">
            Data berikut belum terisi: <strong><?= htmlspecialchars(implode(', ', $profilKurang)) ?></strong>.
            Formulir pengajuan magang baru bisa dibuka setelah semuanya lengkap.
        </div>
        <a href="<?= BASE_URL ?>/mahasiswa/profil" style="display: inline-block; margin-top: 12px; padding: 8px 16px; background: var(--color-warning-ink); color: var(--color-warning-soft); text-decoration: none; border-radius: 6px; font-family: var(--font-body); font-size: 13px; font-weight: 600;">Lengkapi Profil</a>
    </div>
</div>
<?php endif; ?>


<!-- Metric Cards -->
<div class="grid-4 dashboard-stats" style="margin-bottom: 24px; gap: 16px;">
    <!-- Card 1: Status -->
    <div class="surface-card" style="padding: 20px; display: flex; flex-direction: column;">
        <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; font-size: 12px; line-height: 16px; letter-spacing: 0.88px; text-transform: uppercase; color: var(--text-secondary); margin-bottom: 8px;">STATUS PENGAJUAN</div>
        <div style="font-family: var(--font-body); font-size: 20px; font-weight: 700; color: var(--primary); margin-bottom: 4px; line-height: 1.3; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= htmlspecialchars($statusLabel) ?>">
            <?= htmlspecialchars($statusLabel) ?>
        </div>
        <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 400; font-size: 12px; line-height: 17px; color: var(--text-secondary);">
            <?= htmlspecialchars($statusDesc) ?>
        </div>
    </div>
    
    <!-- Card 2: Bidang Tujuan -->
    <div class="surface-card" style="padding: 20px; display: flex; flex-direction: column;">
        <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; font-size: 12px; line-height: 16px; letter-spacing: 0.88px; text-transform: uppercase; color: var(--text-secondary); margin-bottom: 8px;">BIDANG / DIVISI</div>
        <div style="font-family: var(--font-body); font-size: 20px; font-weight: 700; color: var(--primary); margin-bottom: 4px; line-height: 1.3; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= htmlspecialchars($pengajuan['nama_divisi_final'] ?? $pengajuan['nama_divisi_preferensi'] ?? '-') ?>">
            <?= htmlspecialchars($bidang) ?>
        </div>
        <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 400; font-size: 12px; line-height: 17px; color: var(--text-secondary);">
            <?= htmlspecialchars($bidangDesc) ?>
        </div>
    </div>

    <!-- Card 3: Durasi -->
    <div class="surface-card" style="padding: 20px; display: flex; flex-direction: column;">
        <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; font-size: 12px; line-height: 16px; letter-spacing: 0.88px; text-transform: uppercase; color: var(--text-secondary); margin-bottom: 8px;">DURASI MAGANG</div>
        <div style="font-family: var(--font-body); font-size: 20px; font-weight: 700; color: var(--primary); margin-bottom: 4px; line-height: 1.3; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= htmlspecialchars($durasi) ?>">
            <?= htmlspecialchars($durasi) ?>
        </div>
        <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 400; font-size: 12px; line-height: 17px; color: var(--text-secondary);">
            <?= htmlspecialchars($durasiDesc) ?>
        </div>
    </div>

    <!-- Card 4: Dokumen -->
    <div class="surface-card" style="padding: 20px; display: flex; flex-direction: column;">
        <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; font-size: 12px; line-height: 16px; letter-spacing: 0.88px; text-transform: uppercase; color: var(--text-secondary); margin-bottom: 8px;">DOKUMEN</div>
        <div style="font-family: var(--font-mono); font-size: 32px; font-weight: 700; color: var(--text-primary); margin-bottom: 4px; line-height: 1;">
            <?= $dokCount ?>
        </div>
        <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 400; font-size: 12px; line-height: 17px; color: var(--text-secondary);">
            <?= htmlspecialchars($dokDesc) ?>
        </div>
    </div>
</div>

<!-- Warning/Alert for Revisions or Rejections -->
<?php if ($pengajuan && $pengajuan['status'] === 'revisi'): ?>
<div style="background: var(--color-warning-soft); border: 1px solid var(--color-warning-border); border-radius: 10px; padding: 16px 24px; margin-bottom: 24px; display: flex; gap: 16px; align-items: flex-start;">
    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--color-warning-ink)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
    <div>
        <div style="font-family: var(--font-body); font-size: 14px; font-weight: 700; color: var(--color-warning-ink); margin-bottom: 4px;">Terdapat Permintaan Revisi</div>
        <div style="font-family: var(--font-body); font-size: 13px; color: var(--color-warning-ink);">Mohon segera perbaiki dokumen atau data Anda sesuai dengan catatan yang diberikan pada halaman Status Pengajuan.</div>
    </div>
</div>
<?php elseif ($pengajuan && in_array($pengajuan['status'], ['diterima', 'sedang_magang'])): ?>
<div style="background: var(--bg-green-soft); border: 1px solid var(--border); border-radius: 10px; padding: 16px 24px; margin-bottom: 24px; display: flex; gap: 16px; align-items: flex-start;">
    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary-dark)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    <div>
        <div style="font-family: var(--font-body); font-size: 14px; font-weight: 700; color: var(--primary-dark); margin-bottom: 4px;">Selamat! Anda telah diterima.</div>
        <div style="font-family: var(--font-body); font-size: 13px; color: var(--primary-dark);">Silakan cek menu Dokumen untuk mengunduh Surat Penerimaan Final. Pastikan Anda hadir pada tanggal mulai magang.</div>
    </div>
</div>
<?php endif; ?>

<!-- Main 2-Column Grid -->
<div class="layout-split" style="gap: 24px; align-items: start">
    <!-- Left: Detail Ringkasan -->
    <div class="surface-card" style="display: flex; flex-direction: column; height: 100%;">
        <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 0.666667px solid var(--border); padding: 16px 20px;">
            <div>
                <div style="font-family: var(--font-body); font-size: 13px; font-weight: 700; color: var(--text-primary); margin-bottom: 2px;">Informasi Magang</div>
                <div style="font-family: var(--font-body); font-size: 12px; color: var(--text-secondary);">Detail penempatan dan periode</div>
            </div>
            <a href="<?= BASE_URL ?>/mahasiswa/pengajuan" style="font-family: var(--font-body); font-size: 12px; font-weight: 600; color: var(--primary); text-decoration: none;">Kelola &rarr;</a>
        </div>
        <div style="padding: 0; flex-grow: 1; display: flex; flex-direction: column; overflow: hidden; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
            <?php if (!$pengajuan): ?>
                <div style="padding: 48px 24px; text-align: center;">
                    <p style="font-family: var(--font-body); font-size: 13px; color: var(--text-secondary); margin-bottom: 16px;">Anda belum memiliki pengajuan magang aktif.</p>
                    <a href="<?= BASE_URL ?>/mahasiswa/pengajuan" class="btn btn-primary" style="padding: 8px 16px;">Mulai Pengajuan Baru</a>
                </div>
            <?php else: ?>
                <div style="padding: 16px 20px; border-bottom: 1px solid var(--border);">
                    <div style="font-family: var(--font-body); font-size: 12px; font-weight: 700; letter-spacing: 0.10em; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 8px;">PERIODE PELAKSANAAN</div>
                    <div style="font-family: var(--font-body); font-size: 14px; font-weight: 600; color: var(--text-primary);">
                        <?= date('d M Y', strtotime($pengajuan['tanggal_mulai_rencana'])) ?> &mdash; <?= date('d M Y', strtotime($pengajuan['tanggal_selesai_rencana'])) ?>
                    </div>
                </div>
                
                <div style="padding: 16px 20px; border-bottom: 1px solid var(--border);">
                    <div style="font-family: var(--font-body); font-size: 12px; font-weight: 700; letter-spacing: 0.10em; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 8px;">PEMBINA LAPANGAN</div>
                    <div style="font-family: var(--font-body); font-size: 14px; font-weight: 600; color: var(--text-primary);">
                        <?= htmlspecialchars($pengajuan['pembina_lapangan'] ?? 'Belum ditentukan') ?>
                    </div>
                </div>

                <div style="padding: 16px 20px; border-bottom: 1px solid var(--border);">
                    <div style="font-family: var(--font-body); font-size: 12px; font-weight: 700; letter-spacing: 0.10em; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 8px;">DIVISI PENEMPATAN</div>
                    <div style="font-family: var(--font-body); font-size: 14px; font-weight: 600; color: var(--text-primary);">
                        <?= htmlspecialchars($pengajuan['nama_divisi_final'] ?? $pengajuan['nama_divisi_preferensi'] ?? 'Belum ditentukan') ?>
                    </div>
                </div>

                <div style="padding: 16px 20px; display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-family: var(--font-body); font-size: 12px; font-weight: 700; letter-spacing: 0.10em; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 8px;">NOMOR PENGAJUAN</div>
                        <div style="font-family: var(--font-mono); font-size: 14px; font-weight: 600; color: var(--text-primary);">
                            <?= htmlspecialchars($pengajuan['nomor_pengajuan']) ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Right: Aktivitas Terbaru -->
    <div class="surface-card" style="display: flex; flex-direction: column; height: 100%;">
        <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 0.666667px solid var(--border); padding: 16px 20px;">
            <div>
                <div style="font-family: var(--font-body); font-size: 13px; font-weight: 700; color: var(--text-primary); margin-bottom: 2px;">Aktivitas Terbaru</div>
                <div style="font-family: var(--font-body); font-size: 12px; color: var(--text-secondary);">Riwayat progres Anda</div>
            </div>
            <a href="<?= BASE_URL ?>/mahasiswa/status" style="font-family: var(--font-body); font-size: 12px; font-weight: 600; color: var(--primary); text-decoration: none;">Lihat Semua &rarr;</a>
        </div>
        <div style="padding: 0; flex-grow: 1; display: flex; flex-direction: column; overflow: hidden; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
            <?php if (empty($riwayat)): ?>
                <div style="padding: 32px 24px; text-align: center;">
                    <p style="font-family: var(--font-body); font-size: 13px; color: var(--text-secondary); margin: 0;">Belum ada aktivitas tercatat.</p>
                </div>
            <?php else: ?>
                <?php 
                // Only show top 3 history logs
                $recentHistory = array_slice(array_reverse($riwayat), 0, 3);
                foreach ($recentHistory as $index => $item): 
                ?>
                <div style="padding: 16px 20px; display: flex; gap: 16px; align-items: flex-start; <?= $index < count($recentHistory) - 1 ? 'border-bottom: 1px solid var(--border);' : '' ?>">
                    <div style="margin-top: 2px; width: 12px; height: 12px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; background: var(--bg-soft-dark); border: 1.25px solid <?= $index === 0 ? 'var(--primary)' : 'var(--text-secondary)' ?>;">
                    </div>
                    
                    <div>
                        <div style="font-family: var(--font-body); font-size: 13px; font-weight: 600; color: var(--text-primary); margin-bottom: 4px;">
                            <?php
                                if ($item['status_baru'] === 'diajukan') echo 'Pengajuan Dikirimkan';
                                elseif ($item['status_baru'] === 'dalam_verifikasi') echo 'Berkas Sedang Diperiksa';
                                elseif ($item['status_baru'] === 'revisi') echo 'Permintaan Revisi';
                                elseif ($item['status_baru'] === 'diterima') echo 'Pengajuan Diterima';
                                elseif ($item['status_baru'] === 'ditolak') echo 'Pengajuan Ditolak';
                                elseif ($item['status_baru'] === 'sedang_magang') echo 'Sedang Magang';
                                elseif ($item['status_baru'] === 'selesai') echo 'Magang Selesai';
                                else echo 'Status Diperbarui';
                            ?>
                        </div>
                        <div style="font-family: var(--font-body); font-size: 12px; color: var(--text-secondary);">
                            <?= date('d M Y · H:i', strtotime($item['created_at'])) ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>



