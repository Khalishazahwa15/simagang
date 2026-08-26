<div class="mb-6">
    <div class="d-flex align-center justify-end">
        <button onclick="document.getElementById('modalTambahUser').style.display='flex'" style="display: flex; align-items: center; gap: 8px; padding: 10px 18px; background: var(--primary); color: var(--bg-main); border: none; border-radius: 8px; cursor: pointer; font-family: var(--font-body); font-size: var(--text-body-sm); font-weight: 500;">
            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
            Tambah Pengguna
        </button>
    </div>
</div>

<form method="GET" action="<?= BASE_URL ?>/admin/users">
    <div class="d-flex align-center" style="margin-bottom: 20px; flex-wrap: wrap; gap: 12px; width: 100%;">
        <div style="position: relative; flex: 1 1 200px; min-width: 0;">
            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-secondary);"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            <input type="text" name="q" placeholder="Cari nama, email, atau NPM..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" style="width: 100%; padding: 9px 14px 9px 36px; background: var(--bg-main); border: 1px solid var(--border); border-radius: 8px; font-family: var(--font-body); font-size: var(--text-body-sm); color: var(--text-primary); outline: none;">
        </div>
        <select name="role" style="flex: 1 1 150px; min-width: 0; padding: 9px 14px; background: var(--bg-main); border: 1px solid var(--border); border-radius: 8px; font-family: var(--font-body); font-size: var(--text-body-sm); color: var(--text-primary); outline: none; cursor: pointer;">
            <option value="">Semua Peran</option>
            <option value="admin" <?= ($_GET['role'] ?? '') == 'admin' ? 'selected' : '' ?>>Administrator</option>
            <option value="sekretariat" <?= ($_GET['role'] ?? '') == 'sekretariat' ? 'selected' : '' ?>>Sekretariat</option>
            <option value="mahasiswa" <?= ($_GET['role'] ?? '') == 'mahasiswa' ? 'selected' : '' ?>>Mahasiswa</option>
        </select>
        <select name="status" style="flex: 1 1 150px; min-width: 0; padding: 9px 14px; background: var(--bg-main); border: 1px solid var(--border); border-radius: 8px; font-family: var(--font-body); font-size: var(--text-body-sm); color: var(--text-secondary); outline: none; cursor: pointer;">
            <option value="">Semua Status</option>
            <option value="aktif" <?= ($_GET['status'] ?? '') == 'aktif' ? 'selected' : '' ?>>Aktif</option>
            <option value="nonaktif" <?= ($_GET['status'] ?? '') == 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
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
                    <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 12px; font-weight: 500; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">Nama Lengkap</th>
                    <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 12px; font-weight: 500; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">Email / Kontak</th>
                    <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 12px; font-weight: 500; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">Peran (Role)</th>
                    <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 12px; font-weight: 500; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">Status</th>
                    <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 12px; font-weight: 500; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">Terakhir Aktif</th>
                    <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 12px; font-weight: 500; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                <tr>
                    <td colspan="6" style="padding: 40px; text-align: center; color: var(--text-secondary); font-size: var(--text-body-sm);">Belum ada data pengguna.</td>
                </tr>
                <?php else: ?>
                    <?php foreach ($users as $u): 
                        $isAktif = $u['status'] === 'aktif';
                        // Style for role badges
                        $roleStyles = [
                            'admin' => 'background: var(--text-primary); color: var(--bg-main);',
                            'sekretariat' => 'background: var(--accent); color: var(--text-primary);',
                            'mahasiswa' => 'background: var(--border); color: var(--text-primary);' // Assuming draft style
                        ];
                        $roleLabel = [
                            'admin' => 'Administrator',
                            'sekretariat' => 'Sekretariat',
                            'mahasiswa' => 'Mahasiswa'
                        ];
                        $rStyle = $roleStyles[$u['role']] ?? $roleStyles['mahasiswa'];
                        $rLabel = $roleLabel[$u['role']] ?? ucfirst($u['role']);
                    ?>
                <tr style="border-bottom: 1px solid var(--border); transition: background 0.15s; opacity: <?= $isAktif ? '1' : '0.6' ?>;">
                    <td style="padding: 13px 16px;">
                        <div style="font-family: var(--font-body); font-size: var(--text-body-sm); font-weight: 500; color: var(--text-primary);"><?= htmlspecialchars($u['nama']) ?></div>
                        <div style="font-family: var(--font-mono); font-size: 12px; color: var(--text-secondary);">ID: <?= $u['id'] ?></div>
                    </td>
                    <td style="padding: 13px 16px;">
                        <div style="font-family: var(--font-body); font-size: var(--text-body-sm); font-weight: 500; color: var(--text-primary);"><?= htmlspecialchars($u['email']) ?></div>
                    </td>
                    <td style="padding: 13px 16px;"><span class="badge" style="<?= $rStyle ?>"><?= $rLabel ?></span></td>
                    <td style="padding: 13px 16px;">
                        <?php if ($isAktif): ?>
                            <span class="badge badge-disetujui">Aktif</span>
                        <?php else: ?>
                            <span class="badge" style="background: var(--bg-soft); color: var(--text-secondary);">Nonaktif</span>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 13px 16px; font-family: var(--font-body); font-size: 12.5px; color: var(--text-secondary);"><?= empty($u['last_login_at']) ? 'Belum pernah' : date('d M Y H:i', strtotime($u['last_login_at'])) ?></td>
                    <td style="padding: 13px 16px;">
                        <div class="d-flex gap-2">
                            <?php if ($u['id'] != \App\Core\Auth::id()): ?>
                            <button onclick="editUser(<?= htmlspecialchars(json_encode([
                                'id' => $u['id'],
                                'nama' => $u['nama'],
                                'email' => $u['email'],
                                'role' => $u['role']
                            ])) ?>)" style="display: flex; align-items: center; justify-content: center; width: 28px; height: 28px; background: var(--bg-soft); border: 0.666667px solid var(--border); border-radius: 5px; cursor: pointer;" title="Edit Pengguna">
                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                            </button>
                            <form action="<?= BASE_URL ?>/admin/users/toggle/<?= $u['id'] ?>" method="POST" style="margin: 0;">
                                <input type="hidden" name="csrf_token" value="<?= \App\Core\Session::get('csrf_token') ?? '' ?>">
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin <?= $isAktif ? 'menonaktifkan' : 'mengaktifkan' ?> pengguna ini?')" style="display: flex; align-items: center; justify-content: center; width: 28px; height: 28px; background: <?= $isAktif ? 'var(--color-danger-soft)' : 'var(--color-success-soft)' ?>; border: 0.666667px solid <?= $isAktif ? 'var(--color-danger-border)' : 'var(--primary-light)' ?>; border-radius: 5px; cursor: pointer;" title="<?= $isAktif ? 'Nonaktifkan' : 'Aktifkan' ?>">
                                    <?php if ($isAktif): ?>
                                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--color-danger)" stroke-width="1.16667" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="9" y1="9" x2="15" y2="15"/><line x1="15" y1="9" x2="9" y2="15"/></svg>
                                    <?php else: ?>
                                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.16667" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                    <?php endif; ?>
                                </button>
                            </form>
                            <?php else: ?>
                                <span style="font-size: 12px; color: var(--text-secondary);">Anda</span>
                            <?php endif; ?>
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
        Halaman <?= $page ?> dari <?= $totalPages ?> (Total: <?= $totalRows ?> pengguna)
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

<!-- Modal Tambah Pengguna -->
<div id="modalTambahUser" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: var(--z-modal); align-items: center; justify-content: center;">
    <div class="modal-content" style="background: var(--bg-card); width: 100%; max-width: 500px; border-radius: 12px; margin: 10vh auto; box-shadow: 0 4px 20px rgba(0,0,0,0.15);">
        <div style="padding: 20px 24px; border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-family: var(--font-display); font-size: 18px; color: var(--text-primary);">Tambah Pengguna Internal</h3>
            <button aria-label="Tutup dialog" onclick="document.getElementById('modalTambahUser').style.display='none'" style="background: none; border: none; cursor: pointer; color: var(--text-secondary);">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form action="<?= BASE_URL ?>/admin/users/store" method="POST">
            <input type="hidden" name="csrf_token" value="<?= \App\Core\Session::get('csrf_token') ?? '' ?>">
            <div style="padding: 24px;">
                <div style="margin-bottom: 16px;">
                    <label for="app-views-admin-users-nama" style="display: block; font-size: var(--text-body-sm); font-weight: 500; color: var(--text-primary); margin-bottom: 8px;">Nama Lengkap <span style="color: red;">*</span></label>
                    <input id="app-views-admin-users-nama" type="text" name="nama" required style="width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 6px; font-family: var(--font-body); font-size: 14px;">
                </div>
                <div style="margin-bottom: 16px;">
                    <label for="app-views-admin-users-email" style="display: block; font-size: var(--text-body-sm); font-weight: 500; color: var(--text-primary); margin-bottom: 8px;">Alamat Email <span style="color: red;">*</span></label>
                    <input id="app-views-admin-users-email" type="email" name="email" required style="width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 6px; font-family: var(--font-body); font-size: 14px;">
                </div>
                <div style="margin-bottom: 16px;">
                    <label for="app-views-admin-users-role" style="display: block; font-size: var(--text-body-sm); font-weight: 500; color: var(--text-primary); margin-bottom: 8px;">Role Akses <span style="color: red;">*</span></label>
                    <select id="app-views-admin-users-role" name="role" required style="width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 6px; font-family: var(--font-body); font-size: 14px;">
                        <option value="">-- Pilih Role --</option>
                        <option value="sekretariat">Sekretariat</option>
                        <option value="admin">Administrator</option>
                        <option value="mahasiswa">Mahasiswa</option>
                    </select>
                </div>
                <div style="margin-bottom: 16px;">
                    <label for="app-views-admin-users-password" style="display: block; font-size: var(--text-body-sm); font-weight: 500; color: var(--text-primary); margin-bottom: 8px;">Password Default <span style="color: red;">*</span></label>
                    <input id="app-views-admin-users-password" type="password" name="password" required style="width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 6px; font-family: var(--font-body); font-size: 14px;">
                </div>
            </div>
            <div style="padding: 16px 24px; border-top: 1px solid var(--border-light); display: flex; justify-content: flex-end; gap: 12px; background: var(--bg-soft); border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                <button type="button" onclick="document.getElementById('modalTambahUser').style.display='none'" style="padding: 8px 16px; background: white; border: 1px solid var(--border); border-radius: 6px; cursor: pointer; font-weight: 600;">Batal</button>
                <button type="submit" style="padding: 8px 16px; background: var(--primary); color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">Simpan Pengguna</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Pengguna -->
<div id="modalEditUser" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: var(--z-modal); align-items: center; justify-content: center;">
    <div class="modal-content" style="background: var(--bg-card); width: 100%; max-width: 500px; border-radius: 12px; margin: 10vh auto; box-shadow: 0 4px 20px rgba(0,0,0,0.15);">
        <div style="padding: 20px 24px; border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-family: var(--font-display); font-size: 18px; color: var(--text-primary);">Edit Pengguna</h3>
            <button aria-label="Tutup dialog" onclick="document.getElementById('modalEditUser').style.display='none'" style="background: none; border: none; cursor: pointer; color: var(--text-secondary);">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form id="formEditUser" method="POST">
            <input type="hidden" name="csrf_token" value="<?= \App\Core\Session::get('csrf_token') ?? '' ?>">
            <div style="padding: 24px;">
                <div style="margin-bottom: 16px;">
                    <label for="editNama" style="display: block; font-size: var(--text-body-sm); font-weight: 500; color: var(--text-primary); margin-bottom: 8px;">Nama Lengkap <span style="color: red;">*</span></label>
                    <input type="text" name="nama" id="editNama" required style="width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 6px; font-family: var(--font-body); font-size: 14px;">
                </div>
                <div style="margin-bottom: 16px;">
                    <label for="editEmail" style="display: block; font-size: var(--text-body-sm); font-weight: 500; color: var(--text-primary); margin-bottom: 8px;">Alamat Email <span style="color: red;">*</span></label>
                    <input type="email" name="email" id="editEmail" required style="width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 6px; font-family: var(--font-body); font-size: 14px;">
                </div>
                <div style="margin-bottom: 16px;">
                    <label for="editRole" style="display: block; font-size: var(--text-body-sm); font-weight: 500; color: var(--text-primary); margin-bottom: 8px;">Role Akses <span style="color: red;">*</span></label>
                    <select name="role" id="editRole" required style="width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 6px; font-family: var(--font-body); font-size: 14px;">
                        <option value="sekretariat">Sekretariat</option>
                        <option value="admin">Administrator</option>
                        <option value="mahasiswa">Mahasiswa</option>
                    </select>
                </div>
                <div style="margin-bottom: 16px;">
                    <label for="app-views-admin-users-password-2" style="display: block; font-size: var(--text-body-sm); font-weight: 500; color: var(--text-primary); margin-bottom: 8px;">Password Baru (Kosongkan jika tidak ingin mengubah)</label>
                    <input id="app-views-admin-users-password-2" type="password" name="password" style="width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 6px; font-family: var(--font-body); font-size: 14px;" placeholder="***">
                </div>
            </div>
            <div style="padding: 16px 24px; border-top: 1px solid var(--border-light); display: flex; justify-content: flex-end; gap: 12px; background: var(--bg-soft); border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                <button type="button" onclick="document.getElementById('modalEditUser').style.display='none'" style="padding: 8px 16px; background: white; border: 1px solid var(--border); border-radius: 6px; cursor: pointer; font-weight: 600;">Batal</button>
                <button type="submit" style="padding: 8px 16px; background: var(--primary); color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
function editUser(user) {
    document.getElementById('editNama').value = user.nama;
    document.getElementById('editEmail').value = user.email;
    document.getElementById('editRole').value = user.role;
    document.getElementById('formEditUser').action = "<?= BASE_URL ?>/admin/users/update/" + user.id;
    document.getElementById('modalEditUser').style.display = 'flex';
}
</script>


