
<!-- Filters -->
<form method="GET" action="<?= BASE_URL ?>/admin/audit-log">
    <div class="d-flex align-center" style="margin-bottom: 20px; flex-wrap: wrap; gap: 12px; width: 100%;">
        <div style="position: relative; flex: 1 1 200px; min-width: 0;">
            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-secondary);"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            <input type="text" name="q" placeholder="Cari pengguna, aktivitas, atau detail..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" style="width: 100%; padding: 9px 14px 9px 36px; background: var(--bg-main); border: 1px solid var(--border); border-radius: 8px; font-family: var(--font-body); font-size: 13.5px; color: var(--text-primary); outline: none;">
        </div>
        
        <select name="action" style="flex: 1 1 150px; min-width: 0; padding: 9px 14px; background: var(--bg-main); border: 1px solid var(--border); border-radius: 8px; font-family: var(--font-body); font-size: 13.5px; color: var(--text-primary); outline: none; cursor: pointer;">
            <option value="">Semua Aksi</option>
            <option value="update_status" <?= ($actionFilter ?? '') === 'update_status' ? 'selected' : '' ?>>Update Status</option>
            <option value="CREATE_DIVISI" <?= ($actionFilter ?? '') === 'CREATE_DIVISI' ? 'selected' : '' ?>>Buat Divisi</option>
            <option value="UPDATE_DIVISI" <?= ($actionFilter ?? '') === 'UPDATE_DIVISI' ? 'selected' : '' ?>>Update Divisi</option>
            <option value="TOGGLE_STATUS_DIVISI" <?= ($actionFilter ?? '') === 'TOGGLE_STATUS_DIVISI' ? 'selected' : '' ?>>Ubah Status Divisi</option>
            <option value="CREATE_USER" <?= ($actionFilter ?? '') === 'CREATE_USER' ? 'selected' : '' ?>>Buat User</option>
            <option value="UPDATE_USER" <?= ($actionFilter ?? '') === 'UPDATE_USER' ? 'selected' : '' ?>>Update User</option>
            <option value="TOGGLE_STATUS_USER" <?= ($actionFilter ?? '') === 'TOGGLE_STATUS_USER' ? 'selected' : '' ?>>Ubah Status User</option>
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
                    <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 12px; font-weight: 700; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">Waktu</th>
                    <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 12px; font-weight: 700; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">Pengguna</th>
                    <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 12px; font-weight: 700; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">Aksi</th>
                    <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 12px; font-weight: 700; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">Entitas</th>
                    <th style="padding: 12px 16px; text-align: left; font-family: var(--font-body); font-size: 12px; font-weight: 700; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; white-space: nowrap; border-bottom: 1px solid var(--border);">Detail</th>
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

<!-- Pagination -->
<?php if (isset($totalPages) && $totalPages > 1): ?>
<div class="d-flex align-center justify-between mt-4">
    <span class="text-muted" style="font-size: 12.5px;">
        Halaman <?= $page ?> dari <?= $totalPages ?> (Total: <?= $totalRows ?> log)
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
</div>

