<div class="mb-6">
    <div class="d-flex align-center justify-between">
        <div>
            <h1 class="card-title" style="font-family: var(--font-display); font-size: 28px; font-weight: 400; text-transform: none; letter-spacing: -0.01em; margin: 0 0 4px 0;">Log Aktivitas Sistem</h1>
            <p class="text-muted" style="font-family: var(--font-body); font-size: 13.5px; margin: 0;">Pantau aktivitas administratif dan perubahan data penting dalam sistem.</p>
        </div>
    </div>
</div>

<!-- Filters -->
<form method="GET" action="<?= BASE_URL ?>/admin/audit-log">
    <div class="d-flex" style="gap: 12px; margin-bottom: 20px; flex-wrap: wrap;">
        <select name="action" style="padding: 9px 14px; background: var(--bg-main); border: 1px solid var(--border); border-radius: 8px; font-family: var(--font-body); font-size: 13.5px; color: var(--text-primary); outline: none; cursor: pointer;">
            <option value="">Semua Aksi</option>
            <option value="update_status" <?= ($actionFilter ?? '') === 'update_status' ? 'selected' : '' ?>>Update Status</option>
            <option value="CREATE_DIVISI" <?= ($actionFilter ?? '') === 'CREATE_DIVISI' ? 'selected' : '' ?>>Buat Divisi</option>
            <option value="UPDATE_DIVISI" <?= ($actionFilter ?? '') === 'UPDATE_DIVISI' ? 'selected' : '' ?>>Update Divisi</option>
            <option value="TOGGLE_STATUS_DIVISI" <?= ($actionFilter ?? '') === 'TOGGLE_STATUS_DIVISI' ? 'selected' : '' ?>>Ubah Status Divisi</option>
            <option value="CREATE_USER" <?= ($actionFilter ?? '') === 'CREATE_USER' ? 'selected' : '' ?>>Buat User</option>
            <option value="UPDATE_USER" <?= ($actionFilter ?? '') === 'UPDATE_USER' ? 'selected' : '' ?>>Update User</option>
            <option value="TOGGLE_STATUS_USER" <?= ($actionFilter ?? '') === 'TOGGLE_STATUS_USER' ? 'selected' : '' ?>>Ubah Status User</option>
        </select>
        <button type="submit" class="btn btn-primary" style="padding: 9px 16px;">Filter</button>
        <?php if (!empty($actionFilter)): ?>
            <a href="<?= BASE_URL ?>/admin/audit-log" class="btn btn-secondary" style="padding: 9px 16px; text-decoration:none; display:flex; align-items:center;">Reset</a>
        <?php endif; ?>
    </div>
</form>

<!-- Main Table -->
<div style="background: var(--bg-main); border: 1px solid var(--border); border-radius: 10px; overflow: hidden; margin-bottom: 24px;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; min-width: 860px;">
            <thead>
                <tr style="background: var(--bg-soft);">
                    <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 10.5px; font-weight: 700; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">Waktu</th>
                    <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 10.5px; font-weight: 700; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">Pengguna</th>
                    <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 10.5px; font-weight: 700; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">Aksi</th>
                    <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 10.5px; font-weight: 700; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">Entitas</th>
                    <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 10.5px; font-weight: 700; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">Detail</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)): ?>
                <tr>
                    <td colspan="5" style="padding: 24px; text-align: center; color: var(--text-secondary); font-size: 14px;">Tidak ada catatan log.</td>
                </tr>
                <?php else: ?>
                    <?php foreach ($logs as $log): ?>
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="padding: 13px 16px; font-family: var(--font-body); font-size: 12.5px; color: var(--text-secondary); white-space: nowrap;">
                            <?= date('d M Y H:i:s', strtotime($log['created_at'])) ?>
                        </td>
                        <td style="padding: 13px 16px; font-family: var(--font-body); font-size: 13px; font-weight: 600; color: var(--text-primary);">
                            <?= htmlspecialchars($log['user_name'] ?? 'System') ?>
                        </td>
                        <td style="padding: 13px 16px;">
                            <span class="badge" style="background: var(--bg-soft); border: 1px solid var(--border); color: var(--text-primary);"><?= htmlspecialchars($log['action']) ?></span>
                        </td>
                        <td style="padding: 13px 16px; font-family: var(--font-body); font-size: 13px; color: var(--text-secondary);">
                            <?= htmlspecialchars($log['entity']) ?> (ID: <?= htmlspecialchars($log['entity_id']) ?>)
                        </td>
                        <td style="padding: 13px 16px; font-family: var(--font-body); font-size: 13px; color: var(--text-secondary);">
                            <?= htmlspecialchars($log['details']) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

