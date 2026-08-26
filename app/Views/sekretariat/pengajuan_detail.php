<?php
// Divisi yang tampil terpilih pada daftar penempatan final:
// tawaran yang sudah disetujui mahasiswa lebih diutamakan daripada preferensi awal.
$divisiTerpilih = $pengajuan['divisi_id_final']
    ?: ($pengajuan['divisi_id_tawaran'] ?: $pengajuan['divisi_id_preferensi']);

$namaDivisiTawaran = '';
foreach ($divisi as $d) {
    if ($d['id'] == ($pengajuan['divisi_id_tawaran'] ?? null)) {
        $namaDivisiTawaran = $d['nama_divisi'];
        break;
    }
}
?>
<div class="mb-6">
    <div class="d-flex align-center gap-2 mb-2">
        <a href="<?= BASE_URL ?>/sekretariat/pengajuan" class="text-muted" style="text-decoration: none; display: flex; align-items: center; gap: 6px;">
            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Kembali ke Daftar
        </a>
    </div>
    <div class="d-flex align-center justify-between">
        <div class="d-flex align-center gap-3">
            <h1 class="card-title" style="font-family: var(--font-display); font-size: 28px; font-weight: 400; text-transform: none; letter-spacing: -0.01em; margin: 0;">Verifikasi Pengajuan</h1>
            <div style="width: 1px; height: 24px; background: var(--border);"></div>
            <span class="text-mono" style="font-size: 16px; color: var(--primary); font-weight: 600;"><?= htmlspecialchars($pengajuan['nomor_pengajuan']) ?></span>
        </div>
        <div class="badge badge-<?= htmlspecialchars($pengajuan['status']) === 'diajukan' ? 'menunggu_verifikasi' : 'disetujui' ?>" style="font-size: var(--text-body-sm); padding: 6px 14px; text-transform: uppercase;">
            <?= htmlspecialchars(str_replace('_', ' ', $pengajuan['status'])) ?>
        </div>
    </div>
</div>

<div class="grid layout-detail" style="gap: 24px">
    <!-- Left: Data & Riwayat -->
    <div class="d-flex flex-column gap-6">
        <!-- Informasi Pendaftar -->
        <div class="card fade-up interactive-card fade-up interactive-card" style="margin: 0;">
            <div class="card-header">
                <h3 class="card-title">Data Mahasiswa</h3>
            </div>
            <div class="card-body">
                <div class="grid-2">
                    <div>
                        <div class="text-overline">Nama Lengkap</div>
                        <div class="text-value mb-4"><?= htmlspecialchars($pengajuan['mahasiswa_nama'] ?? 'Data tidak tersedia') ?></div>
                    </div>
                    <div>
                        <div class="text-overline">NIM / NPM</div>
                        <div class="text-value mb-4"><?= htmlspecialchars($pengajuan['nim'] ?? 'Data tidak tersedia') ?></div>
                    </div>
                    <div>
                        <div class="text-overline">Perguruan Tinggi</div>
                        <div class="text-value mb-4"><?= htmlspecialchars($pengajuan['universitas'] ?? 'Data tidak tersedia') ?></div>
                    </div>
                    <div>
                        <div class="text-overline">Program Studi</div>
                        <div class="text-value mb-4"><?= htmlspecialchars($pengajuan['jurusan'] ?? 'Data tidak tersedia') ?></div>
                    </div>
                    <div>
                        <div class="text-overline">Kontak</div>
                        <div class="text-value mb-4"><?= htmlspecialchars($pengajuan['telepon'] ?? '-') ?></div>
                    </div>
                    <div>
                        <div class="text-overline">Tanggal Pengajuan</div>
                        <div class="text-value mb-4"><?= date('d M Y', strtotime($pengajuan['created_at'])) ?></div>
                    </div>
                </div>
                <div style="height: 32px;"></div>
            </div>
            <div class="card-header" style="border-top: 1px solid var(--border); background: var(--bg-main);">
                <h3 class="card-title">Detail Magang & Penempatan</h3>
            </div>
            <div class="card-body">
                <div class="grid-2">
                    <div>
                        <div class="text-overline">Bidang Preferensi</div>
                        <div class="text-value mb-4"><?= htmlspecialchars($pengajuan['nama_divisi'] ?? 'Belum ditentukan') ?></div>
                    </div>
                    <div>
                        <div class="text-overline">Periode Rencana Pelaksanaan</div>
                        <div class="text-value mb-4"><?= date('d M Y', strtotime($pengajuan['tanggal_mulai_rencana'])) ?> - <?= date('d M Y', strtotime($pengajuan['tanggal_selesai_rencana'])) ?></div>
                    </div>
                </div>

                <div class="gold-rule mb-4 mt-2"></div>

                <div class="text-overline">Dokumen Pengajuan Mahasiswa</div>
                <div class="d-flex flex-column gap-2 mt-2">
                    <?php
                    $dokMahasiswa = [];
                    $dokBappeda = [];
                    $bappedaTypes = ['surat_balasan', 'sertifikat'];
                    if (!empty($dokumen)) {
                        foreach ($dokumen as $doc) {
                            if (in_array($doc['jenis_dokumen'], $bappedaTypes)) {
                                $dokBappeda[] = $doc;
                            } else {
                                $dokMahasiswa[] = $doc;
                            }
                        }
                    }
                    ?>
                    
                    <?php if (empty($dokMahasiswa)): ?>
                        <div class="text-muted" style="font-size: var(--text-body-sm);">Belum ada dokumen mahasiswa yang diunggah.</div>
                    <?php else: ?>
                        <?php foreach ($dokMahasiswa as $doc): ?>
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid var(--border);">
                            <div class="d-flex align-center gap-3">
                                <div style="width: 32px; height: 32px; background: var(--bg-green-soft); border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                                </div>
                                <div>
                                    <div style="font-family: var(--font-body); font-size: var(--text-body-sm); font-weight: 600; color: var(--text-primary);"><?= htmlspecialchars($doc['jenis_dokumen']) ?></div>
                                    <div style="font-family: var(--font-body); font-size: 12px; color: var(--text-secondary);">PDF &middot; Diunggah</div>
                                </div>
                            </div>
                            <div style="display: flex; gap: 8px;">
                                <a href="<?= BASE_URL ?>/sekretariat/dokumen/view/<?= htmlspecialchars($doc['id']) ?>" target="_blank" style="display: flex; align-items: center; justify-content: center; padding: 6px; background: var(--bg-soft); border: 1px solid var(--border); border-radius: 6px; cursor: pointer; color: var(--primary); text-decoration: none;" title="Lihat Dokumen">
                                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>
                                <a href="<?= BASE_URL ?>/sekretariat/dokumen/download/<?= htmlspecialchars($doc['id']) ?>" style="display: flex; align-items: center; justify-content: center; padding: 6px; background: var(--bg-soft); border: 1px solid var(--border); border-radius: 6px; cursor: pointer; color: var(--text-secondary); text-decoration: none;" title="Unduh Dokumen">
                                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="card-body" style="border-top: 1px solid var(--border); padding-top: 24px; margin-top: 8px;">
                <div class="text-overline">DOKUMEN RESMI BAPPEDA</div>
                <div class="d-flex flex-column gap-2 mt-2">
                    <?php if (empty($dokBappeda)): ?>
                        <div class="text-muted" style="font-size: var(--text-body-sm);">Belum ada dokumen resmi dari Bappeda.</div>
                    <?php else: ?>
                        <?php foreach ($dokBappeda as $doc): ?>
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid var(--border);">
                            <div class="d-flex align-center gap-3">
                                <div style="width: 32px; height: 32px; background: var(--bg-green-soft); border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                                </div>
                                <div>
                                    <div style="font-family: var(--font-body); font-size: var(--text-body-sm); font-weight: 600; color: var(--text-primary);"><?= htmlspecialchars(str_replace('_', ' ', ucwords($doc['jenis_dokumen'], '_'))) ?></div>
                                    <div style="font-family: var(--font-body); font-size: 12px; color: var(--text-secondary);">PDF &middot; Terbit</div>
                                </div>
                            </div>
                            <div style="display: flex; gap: 8px;">
                                <a href="<?= BASE_URL ?>/sekretariat/dokumen/view/<?= htmlspecialchars($doc['id']) ?>" target="_blank" style="display: flex; align-items: center; justify-content: center; padding: 6px; background: var(--bg-soft); border: 1px solid var(--border); border-radius: 6px; cursor: pointer; color: var(--primary); text-decoration: none;" title="Lihat Dokumen">
                                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>
                                <a href="<?= BASE_URL ?>/sekretariat/dokumen/download/<?= htmlspecialchars($doc['id']) ?>" style="display: flex; align-items: center; justify-content: center; padding: 6px; background: var(--bg-soft); border: 1px solid var(--border); border-radius: 6px; cursor: pointer; color: var(--text-secondary); text-decoration: none;" title="Unduh Dokumen">
                                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Riwayat Status -->
        <div class="card fade-up interactive-card fade-up interactive-card" style="margin: 0;">
            <div class="card-header">
                <h3 class="card-title">Riwayat Status</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; gap: 10px; margin-bottom: 14px;">
                    <div style="display: flex; flex-direction: column; align-items: center; width: 18px;">
                        <div style="width: 12px; height: 12px; border-radius: 50%; background: var(--primary); flex-shrink: 0;"></div>
                        <div style="width: 1px; flex: 1; background: var(--border); margin-top: 4px;"></div>
                    </div>
                    <div style="flex: 1; padding-bottom: 14px;">
                        <span class="badge badge-menunggu_verifikasi" style="font-size: 12px; padding: 3px 9px;">Diperiksa</span>
                        <div style="font-family: var(--font-body); font-size: 12px; color: var(--text-secondary); margin-top: 4px;">Sistem &middot; Sedang diproses</div>
                    </div>
                </div>
                <div style="display: flex; gap: 10px;">
                    <div style="display: flex; flex-direction: column; align-items: center; width: 18px;">
                        <div style="width: 12px; height: 12px; border-radius: 50%; background: var(--primary); flex-shrink: 0;"></div>
                    </div>
                    <div style="flex: 1;">
                        <span class="badge badge-draft" style="font-size: 12px; padding: 3px 9px;">Diajukan</span>
                        <div style="font-family: var(--font-body); font-size: 12px; color: var(--text-secondary); margin-top: 4px;"><?= htmlspecialchars($pengajuan['mahasiswa_nama'] ?? 'Mahasiswa') ?> &middot; <?= date('d M Y', strtotime($pengajuan['created_at'])) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Action Form -->
    <div>
        <?php if ($pengajuan['status'] === 'menunggu_finalisasi_sekretariat'): ?>
        <div class="card fade-up interactive-card" style="margin: 0 0 24px 0;">
            <div class="card-header" style="background: rgba(217,165,29,0.05);">
                <h3 class="card-title">Finalisasi Penempatan</h3>
            </div>
            <div class="card-body">
                <p style="font-family: var(--font-body); font-size: var(--text-body-sm); color: var(--text-secondary); line-height: 1.6; margin-top: 0; margin-bottom: 16px;">
                    Mahasiswa telah <strong>menyetujui</strong> tawaran divisi
                    <strong><?= htmlspecialchars($namaDivisiTawaran ?: 'yang ditawarkan') ?></strong>.
                    Tekan tombol di bawah untuk menetapkannya sebagai penempatan final &mdash;
                    divisinya terisi otomatis, tidak perlu dipilih ulang.
                </p>
                <form action="<?= BASE_URL ?>/sekretariat/pengajuan/finalisasi-tawaran" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= \App\Core\Session::get('csrf_token') ?? '' ?>">
                    <input type="hidden" name="pengajuan_id" value="<?= htmlspecialchars($pengajuan['id']) ?>">
                    <button type="submit" class="btn btn-primary">Tetapkan <?= htmlspecialchars($namaDivisiTawaran ?: 'Divisi Tawaran') ?> sebagai Penempatan Final</button>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($pengajuan['status'] === 'diajukan'): ?>
        <div class="card fade-up interactive-card" style="margin: 0 0 24px 0;">
            <div class="card-header" style="background: rgba(217,165,29,0.05);">
                <h3 class="card-title">Mulai Verifikasi</h3>
            </div>
            <div class="card-body">
                <p style="font-family: var(--font-body); font-size: var(--text-body-sm); color: var(--text-secondary); line-height: 1.6; margin-top: 0; margin-bottom: 16px;">Berkas ini belum ditandai sedang diperiksa. Membuka halaman tidak mengubah statusnya. Tekan tombol di bawah untuk memindahkan status ke <strong>Dalam Verifikasi</strong>.</p>
                <form action="<?= BASE_URL ?>/sekretariat/pengajuan/detail/<?= $pengajuan['id'] ?>" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= \App\Core\Session::get('csrf_token') ?? '' ?>">
                    <input type="hidden" name="action" value="mulai_verifikasi">
                    <button type="submit" class="btn btn-primary">Mulai Verifikasi</button>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($pengajuan['status'] === 'menunggu_konfirmasi_tawaran'): ?>
        <div class="card fade-up interactive-card" style="margin: 0 0 24px 0;">
            <div class="card-header">
                <h3 class="card-title">Menunggu Jawaban Mahasiswa</h3>
            </div>
            <div class="card-body">
                <p style="font-family: var(--font-body); font-size: var(--text-body-sm); color: var(--text-secondary); line-height: 1.6; margin: 0;">
                    Divisi <strong><?= htmlspecialchars($pengajuan['nama_divisi_tawaran'] ?: 'alternatif') ?></strong>
                    sudah ditawarkan kepada mahasiswa. Keputusan tidak dapat diubah sampai yang bersangkutan
                    menerima atau menolak tawaran tersebut.
                </p>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($pengajuan['status'] === 'revisi'): ?>
        <div class="card fade-up interactive-card" style="margin: 0 0 24px 0;">
            <div class="card-header">
                <h3 class="card-title">Menunggu Perbaikan Berkas</h3>
            </div>
            <div class="card-body">
                <p style="font-family: var(--font-body); font-size: var(--text-body-sm); color: var(--text-secondary); line-height: 1.6; margin: 0;">
                    Pengajuan sudah dikembalikan kepada mahasiswa untuk diperbaiki. Panel keputusan akan
                    muncul kembali setelah berkas yang diperbaiki diajukan ulang dan diperiksa.
                </p>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($pengajuan['status'] === 'dalam_verifikasi'): ?>
        <div class="card fade-up interactive-card fade-up interactive-card" style="margin: 0;">
            <div class="card-header" style="background: rgba(217,165,29,0.05);">
                <h3 class="card-title">Tindakan Verifikasi & Keputusan</h3>
            </div>
            <div class="card-body">
                <form action="<?= BASE_URL ?>/sekretariat/pengajuan/detail/<?= $pengajuan['id'] ?>" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= \App\Core\Session::get('csrf_token') ?? '' ?>">
                    <input type="hidden" name="pengajuan_id" value="<?= htmlspecialchars($pengajuan['id']) ?>">
                    
                    <p style="font-family: var(--font-body); font-size: var(--text-body-sm); color: var(--text-secondary); line-height: 1.6; margin-top: 0; margin-bottom: 16px;">Tentukan hasil pemeriksaan kelengkapan berkas dan alokasi penempatan.</p>
                    
                    <div class="form-group">
                        <label for="verifikasiStatus" class="form-label required">Keputusan</label>
                        <select name="action" class="form-control" required id="verifikasiStatus" onchange="toggleFormFields()" style="background: var(--bg-soft);">
                            <option value="">-- Pilih Keputusan --</option>
                            <option value="diterima">Terima & Tetapkan Penempatan</option>
                            <option value="tawarkan">Tawarkan Divisi Alternatif</option>
                            <option value="revisi">Kembalikan untuk Revisi</option>
                            <option value="ditolak">Tolak Pengajuan</option>
                        </select>
                    </div>

                    <div id="diterimaFields" style="display: none; padding-top: 10px; border-top: 1px dashed var(--border); margin-top: 10px;">
                        <div class="form-group">
                            <label class="form-label">Bidang/Divisi Final</label>
                            <div class="form-control" style="background: var(--bg-soft); display: flex; align-items: center; justify-content: space-between; gap: 12px;">
                                <span><?= htmlspecialchars($pengajuan['nama_divisi'] ?: 'Belum ada preferensi') ?></span>
                                <span style="font-family: var(--font-body); font-size: 12px; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; color: var(--text-secondary);">Terkunci</span>
                            </div>
                            <div class="form-help">Mengikuti preferensi yang dipilih mahasiswa saat mendaftar. Untuk menempatkan di divisi lain, pilih keputusan <strong>Tawarkan Divisi Alternatif</strong> agar mahasiswa dapat menyetujuinya lebih dulu.</div>
                        </div>
                        <div class="form-group">
                            <label for="app-views-sekretariat-pengajuan-detail-pembina_lapangan" class="form-label">Pembina Lapangan (Opsional)</label>
                            <input id="app-views-sekretariat-pengajuan-detail-pembina_lapangan" type="text" name="pembina_lapangan" class="form-control" placeholder="Nama Pembina Lapangan" style="background: var(--bg-soft);">
                        </div>
                        <div class="form-group">
                            <label for="app-views-sekretariat-pengajuan-detail-tanggal_mulai_aktual" class="form-label required">Tanggal Mulai Aktual</label>
                            <input id="app-views-sekretariat-pengajuan-detail-tanggal_mulai_aktual" type="date" name="tanggal_mulai_aktual" class="form-control" value="<?= $pengajuan['tanggal_mulai_rencana'] ?>" style="background: var(--bg-soft);">
                        </div>
                        <div class="form-group">
                            <label for="app-views-sekretariat-pengajuan-detail-tanggal_selesai_aktual" class="form-label required">Tanggal Selesai Aktual</label>
                            <input id="app-views-sekretariat-pengajuan-detail-tanggal_selesai_aktual" type="date" name="tanggal_selesai_aktual" class="form-control" value="<?= $pengajuan['tanggal_selesai_rencana'] ?>" style="background: var(--bg-soft);">
                        </div>
                    </div>

                    <div id="alasanFields" style="display: none; padding-top: 10px; border-top: 1px dashed var(--border); margin-top: 10px;">
                        <div class="form-group">
                            <label for="app-views-sekretariat-pengajuan-detail-alasan_penolakan" class="form-label required">Alasan Penolakan</label>
                            <textarea id="app-views-sekretariat-pengajuan-detail-alasan_penolakan" name="alasan_penolakan" class="form-control" placeholder="Contoh: Kuota penuh..." rows="3" style="background: var(--bg-soft);"></textarea>
                        </div>
                    </div>

                    <div id="tawarkanFields" style="display: none; padding-top: 10px; border-top: 1px dashed var(--border); margin-top: 10px;">
                        <div class="form-group">
                            <label for="app-views-sekretariat-pengajuan-detail-divisi_id_tawaran" class="form-label required">Tawarkan ke Divisi Lain</label>
                            <select id="app-views-sekretariat-pengajuan-detail-divisi_id_tawaran" name="divisi_id_tawaran" class="form-control" style="background: var(--bg-soft);">
                                <option value="">-- Pilih Divisi Alternatif --</option>
                                <?php foreach($divisi as $d): ?>
                                    <?php if ($d['id'] == $pengajuan['divisi_id_preferensi']) continue; ?>
                                    <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['nama_divisi']) ?> (Sisa Kuota: <?= max(0, $d['kapasitas'] - ($d['terisi'] ?? 0)) ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label for="app-views-sekretariat-pengajuan-detail-catatan" class="form-label">Catatan Tambahan (Opsional)</label>
                        <textarea id="app-views-sekretariat-pengajuan-detail-catatan" name="catatan" class="form-control" placeholder="Beri catatan untuk mahasiswa terkait..." rows="3" style="background: var(--bg-soft);"></textarea>
                        <div class="form-help">Wajib diisi jika meminta revisi dokumen.</div>
                    </div>

                    <div class="gold-rule mt-4 mb-4"></div>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
                        Simpan Keputusan
                    </button>
                </form>
            </div>
        </div>
        <script>
            function toggleFormFields() {
                var val = document.getElementById('verifikasiStatus').value;
                document.getElementById('diterimaFields').style.display = (val === 'diterima') ? 'block' : 'none';
                document.getElementById('alasanFields').style.display = (val === 'ditolak') ? 'block' : 'none';
                document.getElementById('tawarkanFields').style.display = (val === 'tawarkan') ? 'block' : 'none';
                
                // toggle required attrs
                var diterimaInputs = document.getElementById('diterimaFields').querySelectorAll('input, select');
                diterimaInputs.forEach(function(input) {
                    if(input.name !== 'pembina_lapangan') {
                        input.required = (val === 'diterima');
                    }
                });

                var ditolakInputs = document.getElementById('alasanFields').querySelectorAll('textarea');
                ditolakInputs.forEach(function(input) {
                    input.required = (val === 'ditolak');
                });
                
                var tawarkanInputs = document.getElementById('tawarkanFields').querySelectorAll('select');
                tawarkanInputs.forEach(function(input) {
                    input.required = (val === 'tawarkan');
                });
            }
        </script>
        <?php else: ?>
        <div class="card fade-up interactive-card fade-up interactive-card" style="margin: 0;">
            <div class="card-body">
                <div style="text-align: center; padding: 20px;">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 16px;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
                    <h3 style="font-family: var(--font-display); font-size: 18px; color: var(--text-primary); margin-bottom: 8px;">Pengajuan Telah Diproses</h3>
                    <p style="font-size: var(--text-body-sm); color: var(--text-secondary);">Status saat ini: <?= htmlspecialchars(str_replace('_', ' ', strtoupper($pengajuan['status']))) ?></p>
                </div>
            </div>
        </div>
        
        <?php if (in_array($pengajuan['status'], ['diterima', 'sedang_magang', 'selesai'])): ?>
        <div class="card fade-up interactive-card fade-up interactive-card" style="margin: 0; margin-top: 16px;">
            <div class="card-header" style="background: rgba(217,165,29,0.05);">
                <h3 class="card-title">Aksi & Dokumen Resmi</h3>
            </div>
            <div class="card-body">
                <!-- Form Upload Dokumen -->
                <form action="<?= BASE_URL ?>/sekretariat/pengajuan/detail/<?= $pengajuan['id'] ?>/upload-final" method="POST" enctype="multipart/form-data" style="margin-bottom: 24px;">
                    <input type="hidden" name="csrf_token" value="<?= \App\Core\Session::get('csrf_token') ?? '' ?>">
                    <div class="form-group mb-3">
                        <label for="app-views-sekretariat-pengajuan-detail-jenis_dokumen" class="form-label required">Unggah Dokumen / Surat Resmi</label>
                        <select id="app-views-sekretariat-pengajuan-detail-jenis_dokumen" name="jenis_dokumen" class="form-control" required style="background: var(--bg-main);">
                            <option value="">-- Pilih Jenis Dokumen --</option>
                            <option value="surat_balasan">Surat Balasan / Jawaban Magang</option>
                            <option value="surat_penerimaan_final">Surat Penerimaan Final (Bappeda)</option>
                            <option value="surat_tugas">Surat Tugas Pembimbing</option>
                            <option value="surat_keterangan">Surat Keterangan</option>
                            <?php if ($pengajuan['status'] === 'sedang_magang' || $pengajuan['status'] === 'selesai'): ?>
                            <option value="dokumen_akhir_magang">Sertifikat / Surat Selesai</option>
                            <option value="laporan_penilaian">Laporan Penilaian Magang</option>
                            <?php endif; ?>
                            <option value="dokumen_administratif_lainnya">Dokumen Administratif Lainnya</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <input type="file" name="file_dokumen" class="form-control" accept=".pdf" required style="background: var(--bg-main);">
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Upload Dokumen</button>
                </form>

                <div class="gold-rule mb-4 mt-2"></div>

                <!-- Tombol Transisi Status Lanjutan -->
                <?php if ($pengajuan['status'] === 'diterima'): ?>
                <form action="<?= BASE_URL ?>/sekretariat/pengajuan/detail/<?= $pengajuan['id'] ?>/mulai-magang" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= \App\Core\Session::get('csrf_token') ?? '' ?>">
                    <button type="submit" class="btn btn-primary" style="width: 100%; background: var(--primary); color: white;" onclick="return confirm('Tandai mahasiswa ini telah memulai masa magang?')">Tandai Mulai Magang</button>
                </form>
                <?php elseif ($pengajuan['status'] === 'sedang_magang'): ?>
                <form action="<?= BASE_URL ?>/sekretariat/pengajuan/detail/<?= $pengajuan['id'] ?>/tandai-selesai" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= \App\Core\Session::get('csrf_token') ?? '' ?>">
                    <button type="submit" class="btn btn-primary" style="width: 100%; background: var(--primary); color: white;" onclick="return confirm('Tandai mahasiswa ini telah menyelesaikan magang?')">Tandai Magang Selesai</button>
                </form>
                <?php elseif ($pengajuan['status'] === 'selesai'): ?>
                <div style="display: flex; align-items: center; gap: 10px; padding: 12px 14px; background: var(--color-success-soft); border: 1px solid var(--color-success-border); border-radius: 8px; color: var(--color-success-ink); font-size: var(--text-body-sm);">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span>Magang sudah ditandai selesai. Anda tetap bisa mengunggah dokumen tambahan (sertifikat, laporan penilaian, dll) di atas kapan saja.</span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <?php
        // Cek jika ada pengajuan pengunduran diri dari mahasiswa
        $suratMundur = array_filter($dokumen ?? [], fn($d) => $d['jenis_dokumen'] === 'surat_pengunduran_diri');
        $suratMundur = reset($suratMundur);
        if ($suratMundur && !in_array($pengajuan['status'], ['mengundurkan_diri', 'ditolak', 'selesai'])):
        ?>
        <div class="card fade-up interactive-card fade-up interactive-card" style="margin: 0; margin-top: 16px; border: 1px solid var(--color-danger-border);">
            <div class="card-header" style="background: var(--color-danger-soft); border-bottom: 1px solid var(--color-danger-border);">
                <h3 class="card-title" style="color: var(--color-danger);">Verifikasi Pengunduran Diri</h3>
            </div>
            <div class="card-body">
                <p style="font-family: var(--font-body); font-size: var(--text-body-sm); color: var(--text-secondary); margin-bottom: 12px;">Mahasiswa ini telah mengajukan pengunduran diri. Silakan periksa surat pengunduran diri yang dilampirkan.</p>
                <a href="<?= BASE_URL ?>/sekretariat/dokumen/download/<?= $suratMundur['id'] ?>" target="_blank" class="btn btn-outline" style="width: 100%; margin-bottom: 16px; justify-content: center; border-color: var(--color-danger-border); color: var(--color-danger);">Unduh Surat Pengunduran Diri</a>
                
                <form action="<?= BASE_URL ?>/sekretariat/pengajuan/detail/<?= $pengajuan['id'] ?>/verifikasi-mundur" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= \App\Core\Session::get('csrf_token') ?? '' ?>">
                    <button type="submit" class="btn btn-primary" style="width: 100%; background: var(--color-danger); border-color: var(--color-danger);" onclick="return confirm('Setujui pengunduran diri? Status akan diubah dan tidak dapat dikembalikan.')">Setujui Pengunduran Diri</button>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <?php endif; ?>
    </div>
</div>



