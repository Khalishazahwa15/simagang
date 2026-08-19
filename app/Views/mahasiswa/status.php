<?php
function getStatusBadgeStyle($status) {
    switch($status) {
        case 'dalam_verifikasi': 
        case 'revisi':
            return 'background: #FEF3C7; color: #7A5A00;';
        case 'diterima': return 'background: #E7F2EF; color: var(--primary);';
        case 'ditolak': return 'background: #FEE2E2; color: #991B1B;';
        case 'sedang_magang': return 'background: #E7F2EF; color: var(--primary-dark);';
        case 'selesai': return 'background: #E7F2EF; color: var(--primary);';
        default: return 'background: #F7F4EC; color: var(--text-secondary);'; // Draft / Diajukan
    }
}
function getStatusBadgeLabel($status) {
    switch($status) {
        case 'dalam_verifikasi': return 'DIPERIKSA';
        case 'revisi': return 'REVISI';
        default: return strtoupper(str_replace('_', ' ', $status));
    }
}

// 0: Diajukan, 1: Diperiksa, 2: Cek Divisi, 3: Keputusan (Diterima/Ditolak), 4: Sedang Magang, 5: Selesai
function getStepProgress($status) {
    switch($status) {
        case 'diajukan': return 0;
        case 'dalam_verifikasi': 
        case 'revisi': return 1;
        case 'diterima': 
        case 'ditolak': return 3;
        case 'sedang_magang': return 4;
        case 'selesai': return 5;
        default: return -1; // Draft
    }
}

$stepNames = [
    'Diajukan',
    'Diperiksa',
    'Cek Divisi',
    ($pengajuan && $pengajuan['status'] === 'ditolak') ? 'Ditolak' : 'Diterima',
    'Sedang Magang',
    'Selesai'
];
?>

<div style="display: flex; flex-direction: column; gap: 24px; max-width: 968px;">
    
    <?php if (!$pengajuan): ?>
    <div style="background: var(--bg-soft); border: 1px dashed var(--border); border-radius: 10px; padding: 64px 32px; text-align: center;">
        <h2 style="font-family: var(--font-display); font-size: 20px; font-weight: 600; color: var(--text-primary); margin: 0 0 12px 0;">Belum ada pengajuan</h2>
        <p style="font-family: var(--font-body); font-size: 14px; color: var(--text-secondary); margin: 0 auto 24px;">Anda belum mengirimkan pengajuan magang.</p>
        <a href="<?= BASE_URL ?>/mahasiswa/pengajuan" class="btn btn-primary" style="padding: 10px 24px;">Ajukan Magang</a>
    </div>
    <?php else: ?>

    <!-- NOMOR PENGAJUAN -->
    <div style="background: var(--bg-soft); border: 1px solid var(--border); border-radius: 10px; padding: 24px; display: flex; justify-content: space-between; align-items: flex-start;">
        <div>
            <div style="font-family: var(--font-body); font-size: 11px; font-weight: 700; color: var(--text-secondary); letter-spacing: 1.1px; text-transform: uppercase; margin-bottom: 8px;">
                Nomor Pengajuan
            </div>
            <div style="font-family: var(--font-mono); font-size: 26px; font-weight: 600; color: var(--primary); line-height: 1.5;">
                PGJ-<?= str_pad($pengajuan['id'], 4, '0', STR_PAD_LEFT) ?>
            </div>
        </div>
        
        <!-- STATUS BADGE -->
        <div style="padding: 6px 16px; border-radius: 4px; font-family: var(--font-body); font-size: 13px; font-weight: 700; letter-spacing: 0.65px; text-transform: uppercase; <?= getStatusBadgeStyle($pengajuan['status']) ?>">
            <?= getStatusBadgeLabel($pengajuan['status']) ?>
        </div>
    </div>

    <!-- TAHAPAN PROSES -->
    <div style="background: var(--bg-soft); border: 1px solid var(--border); border-radius: 10px; padding: 24px;">
        <div style="font-family: var(--font-body); font-size: 12px; font-weight: 700; color: var(--text-secondary); letter-spacing: 1.2px; text-transform: uppercase; margin-bottom: 20px;">
            Tahapan Proses
        </div>
        
        <?php $step = getStepProgress($pengajuan['status']); ?>
        <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 8px;">
            <?php foreach ($stepNames as $i => $name): ?>
            <div style="flex: 1; display: flex; flex-direction: column; align-items: center; position: relative;">
                
                <!-- Connecting Line -->
                <?php if ($i > 0): ?>
                <div style="position: absolute; top: 16px; right: 50%; left: -50%; height: 2px; background: <?= $step >= $i ? 'var(--primary)' : 'var(--border)' ?>; z-index: 0;"></div>
                <?php endif; ?>
                
                <!-- Step Circle -->
                <?php if ($step > $i): ?>
                <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--primary); border: 2px solid var(--primary); display: flex; align-items: center; justify-content: center; z-index: 1; position: relative; margin-bottom: 12px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--bg-soft)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                </div>
                <?php else: ?>
                <div style="width: 32px; height: 32px; border-radius: 50%; background: <?= $step == $i ? 'var(--accent)' : 'transparent' ?>; border: 2px solid <?= $step == $i ? 'var(--accent)' : 'var(--border)' ?>; display: flex; align-items: center; justify-content: center; z-index: 1; position: relative; margin-bottom: 12px;">
                    <span style="font-family: var(--font-mono); font-size: 11px; font-weight: 600; color: <?= $step == $i ? 'var(--primary-dark)' : 'var(--text-secondary)' ?>;"><?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></span>
                </div>
                <?php endif; ?>
                
                <!-- Step Label -->
                <div style="font-family: var(--font-body); font-size: 10.5px; font-weight: <?= $step == $i ? '700' : '400' ?>; color: <?= $step == $i ? 'var(--text-primary)' : 'var(--text-secondary)' ?>; text-align: center; max-width: 90px; line-height: 1.4;">
                    <?= $name ?>
                </div>
                
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- RIWAYAT AKTIVITAS -->
    <div style="background: var(--bg-soft); border: 1px solid var(--border); border-radius: 10px;">
        <div style="padding: 16px 24px; border-bottom: 1px solid var(--border);">
            <div style="font-family: var(--font-body); font-size: 13px; font-weight: 700; color: var(--text-primary);">Riwayat Aktivitas</div>
        </div>
        <div style="padding: 0;">
            <?php if (empty($riwayat)): ?>
                <div style="padding: 24px; font-family: var(--font-body); font-size: 13px; color: var(--text-secondary); text-align: center;">Belum ada riwayat aktivitas.</div>
            <?php else: ?>
                <?php foreach (array_reverse($riwayat) as $index => $item): ?>
                <div style="display: flex; gap: 16px; padding: 14px 24px; border-bottom: <?= $index < count($riwayat) - 1 ? '1px solid var(--border)' : 'none' ?>; align-items: flex-start;">
                    
                    <div style="margin-top: 2px; width: 15px; height: 15px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; <?= $index === 0 ? 'background: var(--primary);' : 'border: 1.25px solid var(--primary);' ?>">
                        <?php if ($index === 0): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="var(--bg-soft)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                        <?php endif; ?>
                    </div>
                    
                    <div>
                        <div style="font-family: var(--font-body); font-size: 13.5px; font-weight: 600; color: var(--text-primary); margin-bottom: 2px;">
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
                        <div style="font-family: var(--font-body); font-size: 13px; color: var(--text-secondary); margin-bottom: 4px; line-height: 1.5;">
                            <?php
                                if ($item['status_baru'] === 'diajukan') echo 'Pengajuan berhasil dikirimkan dan masuk antrean pemeriksaan.';
                                elseif ($item['status_baru'] === 'dalam_verifikasi') echo 'Berkas dinyatakan lengkap. Sekretariat sedang memproses ketersediaan divisi.';
                                elseif ($item['status_baru'] === 'revisi') echo htmlspecialchars($item['catatan']);
                                elseif ($item['status_baru'] === 'diterima') echo 'Anda diterima di ' . htmlspecialchars($pengajuan['nama_divisi_final'] ?? $pengajuan['nama_divisi_preferensi'] ?? 'Bappeda Provinsi Lampung') . '.';
                                elseif ($item['status_baru'] === 'ditolak') echo htmlspecialchars($item['catatan'] ?? 'Pengajuan ditolak.');
                                else echo htmlspecialchars($item['catatan']);
                            ?>
                        </div>
                        <div style="font-family: var(--font-body); font-size: 11px; color: var(--text-secondary);">
                            <?= date('d M Y', strtotime($item['created_at'])) ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <?php if ($pengajuan['status'] === 'revisi'): ?>
    <div style="background: var(--bg-soft); border: 1px solid var(--border); border-radius: 10px; margin-top: 8px;">
        <div style="padding: 16px 24px; border-bottom: 1px solid var(--border); background: rgba(217,165,29,0.05);">
            <div style="font-family: var(--font-body); font-size: 13px; font-weight: 700; color: var(--text-primary);">Formulir Perbaikan Dokumen</div>
        </div>
        <div style="padding: 24px;">
            <form action="<?= BASE_URL ?>/mahasiswa/pengajuan/revisi" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= \App\Core\Session::get('csrf_token') ?? '' ?>">
                <input type="hidden" name="pengajuan_id" value="<?= htmlspecialchars($pengajuan['id']) ?>">
                
                <div class="form-group mb-4">
                    <label class="form-label required">Jenis Dokumen yang Direvisi</label>
                    <select name="jenis_dokumen" class="form-control" required style="background: var(--bg-main);">
                        <option value="">-- Pilih Jenis Dokumen --</option>
                        <option value="surat_lamaran">Surat Lamaran / Surat Pengantar</option>
                        <option value="cv">Curriculum Vitae (CV)</option>
                        <option value="transkrip">Transkrip Nilai</option>
                    </select>
                </div>
                
                <div class="form-group mb-4">
                    <label class="form-label required">Pilih File Baru (PDF)</label>
                    <input type="file" name="file_dokumen" class="form-control" accept=".pdf" required style="background: var(--bg-main);">
                    <div class="form-help mt-1">Maksimal ukuran file: 2 MB. File baru akan otomatis di-versioning, file lama tetap tersimpan.</div>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    Kirim Revisi
                </button>
            </form>
        </div>
    </div>
    <?php endif; ?>
    
    <?php endif; ?>
</div>


