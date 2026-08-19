<?php
// Extract title and subtitle from the global scope if set
$headerTitle = $title ?? 'Dashboard';
$headerSubtitle = $subtitle ?? '';

// Fetch notifications
$userId = \App\Core\Auth::id();
$unreadCount = 0;
$notifications = [];
if ($userId) {
    $notifService = new \App\Services\NotificationService();
    $unreadCount = $notifService->getUnreadCount($userId);
    $notifications = $notifService->getByUser($userId, 5); // top 5
}
?>
<div class="admin-header">
    <div>
        <h1 class="admin-header-title">
            <?= htmlspecialchars($headerTitle) ?>
        </h1>
        <?php if ($headerSubtitle): ?>
            <p class="admin-header-subtitle"><?= htmlspecialchars($headerSubtitle) ?></p>
        <?php endif; ?>
    </div>
    <div style="display: flex; align-items: center; gap: 12px; position: relative;">
        <!-- Notification Dropdown -->
        <div class="notification-dropdown" style="position: relative;">
            <button onclick="document.getElementById('notif-menu').classList.toggle('show')" style="position: relative; background: none; border: none; cursor: pointer; color: var(--text-secondary); padding: 8px;">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                <?php if ($unreadCount > 0): ?>
                    <span style="position: absolute; top: 4px; right: 4px; background: var(--accent); color: var(--primary-dark); font-size: 10px; font-weight: bold; border-radius: 50%; width: 16px; height: 16px; display: flex; align-items: center; justify-content: center;"><?= $unreadCount > 9 ? '9+' : $unreadCount ?></span>
                <?php endif; ?>
            </button>

            <!-- Dropdown Menu -->
            <div id="notif-menu" style="display: none; position: absolute; right: 0; top: 100%; width: 320px; background: var(--bg-card); box-shadow: 0 10px 25px rgba(0,0,0,0.1); border-radius: 8px; border: 1px solid var(--border-light); z-index: var(--z-dropdown); margin-top: 8px;">
                <div style="padding: 12px 16px; border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-weight: 600; font-size: 14px; color: var(--text-primary);">Notifikasi</span>
                    <?php if ($unreadCount > 0): ?>
                        <form action="<?= BASE_URL ?>/notifikasi/tandai-terbaca" method="POST" style="margin:0;">
                            <input type="hidden" name="csrf_token" value="<?= \App\Core\Session::get('csrf_token') ?>">
                            <button type="submit" style="background: none; border: none; color: var(--primary); font-size: 12px; font-weight: 600; cursor: pointer; padding: 0;">Tandai semua dibaca</button>
                        </form>
                    <?php endif; ?>
                </div>
                <div style="max-height: 300px; overflow-y: auto;">
                    <?php if (empty($notifications)): ?>
                        <div style="padding: 24px; text-align: center; color: var(--text-secondary); font-size: 13px;">Belum ada notifikasi.</div>
                    <?php else: ?>
                        <?php foreach ($notifications as $n): ?>
                            <div style="padding: 12px 16px; border-bottom: 1px solid var(--bg-soft); <?= !$n['is_read'] ? 'background-color: var(--bg-soft);' : '' ?>">
                                <div style="font-size: 13px; color: var(--text-primary); line-height: 1.4; margin-bottom: 4px;">
                                    <?= htmlspecialchars($n['pesan']) ?>
                                </div>
                                <div style="font-size: 11px; color: var(--text-secondary);">
                                    <?= date('d M Y H:i', strtotime($n['created_at'])) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.querySelector('.notification-dropdown');
    const menu = document.getElementById('notif-menu');
    if (menu && dropdown && !dropdown.contains(event.target)) {
        menu.classList.remove('show');
    }
});
</script>
<style>
#notif-menu.show { display: block !important; }
</style>

