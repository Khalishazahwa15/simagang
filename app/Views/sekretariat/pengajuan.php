<div class="mb-6">
    <div class="d-flex align-center justify-between">
        <div>
            <h1 class="card-title" style="font-family: var(--font-display); font-size: 28px; font-weight: 400; text-transform: none; letter-spacing: -0.01em; margin: 0 0 4px 0;">Daftar Pengajuan</h1>
            <p class="text-muted" style="font-family: var(--font-body); font-size: 13.5px; margin: 0;">47 total &middot; 4 ditampilkan</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="d-flex" style="gap: 12px; margin-bottom: 20px; flex-wrap: wrap;">
    <div style="position: relative; flex: 1 1 240px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-secondary);"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
        <input type="text" placeholder="Cari nama / nomor pengajuan..." style="width: 100%; padding: 9px 14px 9px 36px; background: var(--bg-main); border: 1px solid var(--border); border-radius: 8px; font-family: var(--font-body); font-size: 13.5px; color: var(--text-primary); outline: none;">
    </div>
    
    <select style="padding: 9px 14px; background: var(--bg-main); border: 1px solid var(--border); border-radius: 8px; font-family: var(--font-body); font-size: 13.5px; color: var(--text-primary); outline: none; cursor: pointer;">
        <option value="">Semua Status</option>
        <option value="diajukan">Diajukan</option>
        <option value="diperiksa">Diperiksa</option>
        <option value="perlu_revisi">Perlu Revisi</option>
        <option value="cek_divisi">Cek Kebutuhan Divisi</option>
        <option value="diterima">Diterima</option>
        <option value="ditolak">Ditolak</option>
        <option value="sedang_magang">Sedang Magang</option>
        <option value="mengundurkan_diri">Mengundurkan Diri</option>
        <option value="selesai">Selesai</option>
    </select>
    
    <select style="padding: 9px 14px; background: var(--bg-main); border: 1px solid var(--border); border-radius: 8px; font-family: var(--font-body); font-size: 13.5px; color: var(--text-secondary); outline: none; cursor: pointer;">
        <option value="">Semua Divisi</option>
        <option value="perencanaan">Perencanaan & Evaluasi</option>
        <option value="penelitian">Penelitian & Pengembangan</option>
        <option value="infrastruktur">Infrastruktur & Tata Ruang</option>
        <option value="ti">Teknologi Informasi</option>
        <option value="keuangan">Keuangan & Administrasi</option>
    </select>
    
    <button style="padding: 9px 16px; background: var(--bg-soft); border: 1px solid var(--border); border-radius: 8px; cursor: pointer; font-family: var(--font-body); font-size: 13.5px; color: var(--text-secondary); display: flex; align-items: center; gap: 6px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
        Ekspor
    </button>
</div>

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

<!-- Pagination (Optional) -->
<div class="d-flex align-center justify-between">
    <span class="text-muted" style="font-size: 12.5px;">Menampilkan 1-4 dari 47 pengajuan</span>
    <div class="d-flex gap-2">
        <button class="btn btn-outline btn-sm" disabled style="padding: 6px 12px; border-radius: 6px;">Sebelumnya</button>
        <button class="btn btn-outline btn-sm" style="padding: 6px 12px; border-radius: 6px;">Selanjutnya</button>
    </div>
</div>


