<div style="font-family: var(--font-body); max-width: 1040px;">
    
    <!-- Top Action -->
    <div style="display: flex; justify-content: flex-end; margin-bottom: 16px;">
        <button onclick="document.getElementById('modalTambahDivisi').style.display='block'" style="display: flex; align-items: center; justify-content: center; gap: 7px; padding: 9px 18px; background: var(--primary); color: var(--bg-card); border: none; border-radius: 8px; font-weight: 600; font-size: var(--text-body-sm); line-height: 20px; cursor: pointer; transition: background 0.15s;" onmouseover="this.style.background='var(--primary-dark)'" onmouseout="this.style.background='var(--primary)'">
            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tambah Divisi
        </button>
    </div>

    <!-- Table Container -->
    <div class="surface-card" style="margin-bottom: 24px;">
        <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; min-width: 860px; text-align: left;">
            <thead>
                <tr style="background: var(--color-surface-soft);">
                    <th style="padding: 12px 16px; border-bottom: 0.666667px solid var(--border); font-weight: 700; font-size: 12px; line-height: 16px; letter-spacing: 0.84px; text-transform: uppercase; color: var(--text-secondary); width: 30%;">DIVISI / BIDANG</th>
                    <th style="padding: 12px 16px; border-bottom: 0.666667px solid var(--border); font-weight: 700; font-size: 12px; line-height: 16px; letter-spacing: 0.84px; text-transform: uppercase; color: var(--text-secondary); width: 12%;">KAPASITAS</th>
                    <th style="padding: 12px 16px; border-bottom: 0.666667px solid var(--border); font-weight: 700; font-size: 12px; line-height: 16px; letter-spacing: 0.84px; text-transform: uppercase; color: var(--text-secondary); width: 15%;">TERPAKAI</th>
                    <th style="padding: 12px 16px; border-bottom: 0.666667px solid var(--border); font-weight: 700; font-size: 12px; line-height: 16px; letter-spacing: 0.84px; text-transform: uppercase; color: var(--text-secondary); width: 10%;">TERSEDIA</th>
                    <th style="padding: 12px 16px; border-bottom: 0.666667px solid var(--border); font-weight: 700; font-size: 12px; line-height: 16px; letter-spacing: 0.84px; text-transform: uppercase; color: var(--text-secondary); width: 15%;">STATUS</th>
                    <th style="padding: 12px 16px; border-bottom: 0.666667px solid var(--border); font-weight: 700; font-size: 12px; line-height: 16px; letter-spacing: 0.84px; text-transform: uppercase; color: var(--text-secondary); width: 18%;"></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($divisi)): ?>
                <tr>
                    <td colspan="6" style="padding: 40px; text-align: center; color: var(--text-secondary); font-size: var(--text-body-sm);">Belum ada data divisi.</td>
                </tr>
                <?php else: ?>
                    <?php foreach ($divisi as $d): 
                        $terisi = $d['terisi'];
                        $kapasitas = $d['kapasitas'];
                        $isFull = $terisi >= $kapasitas;
                        $tersedia = max(0, $kapasitas - $terisi);
                        $pct = $kapasitas > 0 ? min(100, round(($terisi / $kapasitas) * 100)) : 0;
                        
                        $tersediaColor = $isFull ? 'var(--color-danger-ink)' : 'var(--primary)';
                        $barColor = $isFull ? 'var(--color-danger-ink)' : 'var(--primary)';
                        $isAktif = ($d['status'] ?? 'aktif') === 'aktif';
                    ?>
                    <tr style="border-bottom: 0.666667px solid var(--border); transition: background 0.15s; opacity: <?= $isAktif ? '1' : '0.6' ?>;" onmouseover="this.style.background='rgba(11,79,71,0.02)'" onmouseout="this.style.background='transparent'">
                        <td style="padding: 16px;">
                            <div style="font-weight: 600; font-size: var(--text-body-sm); line-height: 20px; color: var(--text-primary);">
                                <?= htmlspecialchars($d['nama_divisi']) ?>
                            </div>
                        </td>
                        <td style="padding: 16px;">
                            <div style="font-family: 'JetBrains Mono', monospace; font-weight: 600; font-size: 14px; line-height: 21px; color: var(--text-primary);">
                                <?= $kapasitas ?>
                            </div>
                        </td>
                        <td style="padding: 16px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="font-family: 'JetBrains Mono', monospace; font-weight: 600; font-size: 14px; line-height: 21px; color: var(--text-primary); width: 16px;">
                                    <?= $terisi ?>
                                </div>
                                <div style="width: 56px; height: 5px; background: var(--border); border-radius: 3px; position: relative;">
                                    <div style="position: absolute; left: 0; top: 0; bottom: 0; width: <?= $pct ?>%; background: <?= $barColor ?>; border-radius: 3px;"></div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 16px;">
                            <div style="font-family: 'JetBrains Mono', monospace; font-weight: 600; font-size: 14px; line-height: 21px; color: <?= $tersediaColor ?>;">
                                <?= $tersedia ?>
                            </div>
                        </td>
                        <td style="padding: 16px;">
                            <?php if ($isAktif): ?>
                                <span style="display: inline-flex; padding: 4px 10px; background: var(--color-success-soft); color: var(--primary); font-size: 12px; font-weight: 600; border-radius: 4px;">AKTIF</span>
                            <?php else: ?>
                                <span style="display: inline-flex; padding: 4px 10px; background: var(--bg-soft); color: var(--text-secondary); font-size: 12px; font-weight: 600; border-radius: 4px;">NONAKTIF</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 16px; text-align: right;">
                            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 6px;">
                                <button onclick="editDivisi(<?= htmlspecialchars(json_encode($d)) ?>)" style="display: flex; align-items: center; justify-content: center; width: 28px; height: 28px; background: var(--color-surface-soft); border: 0.666667px solid var(--border); border-radius: 5px; cursor: pointer;" title="Edit Divisi">
                                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--text-secondary)" stroke-width="1.16667" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                                </button>
                                
                                <form action="<?= BASE_URL ?>/admin/bidang/toggle/<?= $d['id'] ?>" method="POST" style="margin: 0;">
                                    <input type="hidden" name="csrf_token" value="<?= \App\Core\Session::get('csrf_token') ?? '' ?>">
                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin <?= $isAktif ? 'menonaktifkan' : 'mengaktifkan' ?> divisi ini?')" style="display: flex; align-items: center; justify-content: center; width: 28px; height: 28px; background: <?= $isAktif ? 'var(--color-danger-soft)' : 'var(--color-success-soft)' ?>; border: 0.666667px solid <?= $isAktif ? 'var(--color-danger-border)' : 'var(--primary-light)' ?>; border-radius: 5px; cursor: pointer;" title="<?= $isAktif ? 'Nonaktifkan' : 'Aktifkan' ?>">
                                        <?php if ($isAktif): ?>
                                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--color-danger)" stroke-width="1.16667" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="9" y1="9" x2="15" y2="15"/><line x1="15" y1="9" x2="9" y2="15"/></svg>
                                        <?php else: ?>
                                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.16667" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        <?php endif; ?>
                                    </button>
                                </form>
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
    <div class="d-flex align-center justify-between mt-4 mb-4">
        <span class="text-muted" style="font-size: 12.5px;">
            Halaman <?= $page ?> dari <?= $totalPages ?> (Total: <?= $totalRows ?> divisi)
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

    <!-- Info Box -->
    <div style="background: var(--color-success-soft); border: 0.666667px solid var(--primary-light); border-radius: 8px; padding: 12px 16px;">
        <div style="font-weight: 400; font-size: 12.5px; line-height: 20px; color: var(--primary);">
            <b>Status Kebutuhan</b> adalah informasi operasional untuk Sekretariat saat menetapkan penempatan. Divisi yang nonaktif tidak dapat dipilih oleh mahasiswa saat registrasi maupun oleh sekretariat saat menetapkan penempatan.
        </div>
    </div>
</div>

<!-- Modal Tambah Divisi -->
    <div id="modalTambahDivisi" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: var(--z-modal); align-items: center; justify-content: center;">
    <div class="modal-content" style="background: var(--bg-card); width: 100%; max-width: 500px; border-radius: 12px; margin: 10vh auto; box-shadow: 0 4px 20px rgba(0,0,0,0.15);">
        <div style="padding: 20px 24px; border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-family: var(--font-display); font-size: 18px; color: var(--text-primary);">Tambah Divisi Baru</h3>
            <button aria-label="Tutup dialog" onclick="document.getElementById('modalTambahDivisi').style.display='none'" style="background: none; border: none; cursor: pointer; color: var(--text-secondary);">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form action="<?= BASE_URL ?>/admin/bidang/store" method="POST">
            <input type="hidden" name="csrf_token" value="<?= \App\Core\Session::get('csrf_token') ?? '' ?>">
            <div style="padding: 24px;">
                <div style="margin-bottom: 16px;">
                    <label for="app-views-admin-bidang-nama_divisi" style="display: block; font-size: var(--text-body-sm); font-weight: 600; color: var(--text-primary); margin-bottom: 8px;">Nama Divisi / Bidang <span style="color: red;">*</span></label>
                    <input id="app-views-admin-bidang-nama_divisi" type="text" name="nama_divisi" required style="width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 6px; font-family: var(--font-body); font-size: 14px;">
                </div>
                <div style="margin-bottom: 16px;">
                    <label for="app-views-admin-bidang-deskripsi" style="display: block; font-size: var(--text-body-sm); font-weight: 600; color: var(--text-primary); margin-bottom: 8px;">Deskripsi (Opsional)</label>
                    <textarea id="app-views-admin-bidang-deskripsi" name="deskripsi" rows="3" style="width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 6px; font-family: var(--font-body); font-size: 14px;"></textarea>
                </div>
                <div style="margin-bottom: 16px;">
                    <label for="app-views-admin-bidang-kapasitas" style="display: block; font-size: var(--text-body-sm); font-weight: 600; color: var(--text-primary); margin-bottom: 8px;">Kapasitas Magang <span style="color: red;">*</span></label>
                    <input id="app-views-admin-bidang-kapasitas" type="number" name="kapasitas" min="0" required value="0" style="width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 6px; font-family: var(--font-body); font-size: 14px;">
                </div>
            </div>
            <div style="padding: 16px 24px; border-top: 1px solid var(--border-light); display: flex; justify-content: flex-end; gap: 12px; background: var(--bg-soft); border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                <button type="button" onclick="document.getElementById('modalTambahDivisi').style.display='none'" style="padding: 8px 16px; background: white; border: 1px solid var(--border); border-radius: 6px; cursor: pointer; font-weight: 600;">Batal</button>
                <button type="submit" style="padding: 8px 16px; background: var(--primary); color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Divisi -->
<div id="modalEditDivisi" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: var(--z-modal); align-items: center; justify-content: center;">
    <div class="modal-content" style="background: var(--bg-card); width: 100%; max-width: 500px; border-radius: 12px; margin: 10vh auto; box-shadow: 0 4px 20px rgba(0,0,0,0.15);">
        <div style="padding: 20px 24px; border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-family: var(--font-display); font-size: 18px; color: var(--text-primary);">Edit Divisi</h3>
            <button aria-label="Tutup dialog" onclick="document.getElementById('modalEditDivisi').style.display='none'" style="background: none; border: none; cursor: pointer; color: var(--text-secondary);">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form id="formEditDivisi" method="POST">
            <input type="hidden" name="csrf_token" value="<?= \App\Core\Session::get('csrf_token') ?? '' ?>">
            <div style="padding: 24px;">
                <div style="margin-bottom: 16px;">
                    <label for="edit_nama_divisi" style="display: block; font-size: var(--text-body-sm); font-weight: 600; color: var(--text-primary); margin-bottom: 8px;">Nama Divisi / Bidang <span style="color: red;">*</span></label>
                    <input type="text" id="edit_nama_divisi" name="nama_divisi" required style="width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 6px; font-family: var(--font-body); font-size: 14px;">
                </div>
                <div style="margin-bottom: 16px;">
                    <label for="edit_deskripsi" style="display: block; font-size: var(--text-body-sm); font-weight: 600; color: var(--text-primary); margin-bottom: 8px;">Deskripsi (Opsional)</label>
                    <textarea id="edit_deskripsi" name="deskripsi" rows="3" style="width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 6px; font-family: var(--font-body); font-size: 14px;"></textarea>
                </div>
                <div style="margin-bottom: 16px;">
                    <label for="edit_kapasitas" style="display: block; font-size: var(--text-body-sm); font-weight: 600; color: var(--text-primary); margin-bottom: 8px;">Kapasitas Magang <span style="color: red;">*</span></label>
                    <input type="number" id="edit_kapasitas" name="kapasitas" min="0" required style="width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 6px; font-family: var(--font-body); font-size: 14px;">
                </div>
            </div>
            <div style="padding: 16px 24px; border-top: 1px solid var(--border-light); display: flex; justify-content: flex-end; gap: 12px; background: var(--bg-soft); border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                <button type="button" onclick="document.getElementById('modalEditDivisi').style.display='none'" style="padding: 8px 16px; background: white; border: 1px solid var(--border); border-radius: 6px; cursor: pointer; font-weight: 600;">Batal</button>
                <button type="submit" style="padding: 8px 16px; background: var(--primary); color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
function editDivisi(d) {
    document.getElementById('edit_nama_divisi').value = d.nama_divisi;
    document.getElementById('edit_deskripsi').value = d.deskripsi;
    document.getElementById('edit_kapasitas').value = d.kapasitas;
    document.getElementById('formEditDivisi').action = '<?= BASE_URL ?>/admin/bidang/update/' + d.id;
    document.getElementById('modalEditDivisi').style.display = 'block';
}
</script>


