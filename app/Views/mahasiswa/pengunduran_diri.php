<!-- Note: Topbar already contains Title and Subtitle -->

<div style="display: flex; flex-direction: column; gap: 24px; max-width: 800px;">
    
    <div style="background: var(--bg-soft); border: 1px solid var(--border); border-radius: 10px; overflow: hidden;">
        <div style="padding: 24px; border-bottom: 1px solid var(--border); background: var(--color-danger-soft);">
            <h3 style="font-family: var(--font-display); font-size: 18px; font-weight: 600; color: var(--color-danger); margin: 0 0 8px 0;">Pengajuan Pengunduran Diri</h3>
            <p style="font-family: var(--font-body); font-size: var(--text-body-sm); color: var(--color-danger-ink); margin: 0;">Proses ini bersifat final. Setelah diajukan dan disetujui oleh Bappeda, Anda tidak dapat membatalkan pengunduran diri atau melanjutkan program magang.</p>
        </div>
        <div style="padding: 24px;">
            <form action="<?= BASE_URL ?>/mahasiswa/pengunduran-diri" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= \App\Core\Session::get('csrf_token') ?? '' ?>">
                <input type="hidden" name="pengajuan_id" value="<?= htmlspecialchars($pengajuan['id']) ?>">
                
                <div class="form-group mb-4">
                    <label for="app-views-mahasiswa-pengunduran-diri-alasan" class="form-label required">Alasan Pengunduran Diri</label>
                    <textarea id="app-views-mahasiswa-pengunduran-diri-alasan" name="alasan" class="form-control" rows="4" required placeholder="Jelaskan alasan secara singkat..." style="background: var(--bg-main);"></textarea>
                </div>
                
                <div class="form-group mb-4">
                    <label for="app-views-mahasiswa-pengunduran-diri-surat_pengunduran_diri" class="form-label required">Surat Keterangan Kampus (PDF)</label>
                    <div style="margin-bottom: 8px; font-family: var(--font-body); font-size: 12px; color: var(--text-secondary);">Unggah surat keterangan resmi dari perguruan tinggi yang menyatakan persetujuan pengunduran diri magang Anda.</div>
                    <input id="app-views-mahasiswa-pengunduran-diri-surat_pengunduran_diri" type="file" name="surat_pengunduran_diri" class="form-control" accept=".pdf" required style="background: var(--bg-main);">
                </div>
                
                <div style="padding: 16px; background: var(--bg-main); border: 1px dashed var(--border); border-radius: 6px; margin-bottom: 24px;">
                    <div style="display: flex; gap: 12px;">
                        <input type="checkbox" required id="confirm" style="margin-top: 4px;">
                        <label for="confirm" style="font-family: var(--font-body); font-size: var(--text-body-sm); color: var(--text-primary); cursor: pointer; line-height: 1.5;">Saya menyatakan bahwa pengunduran diri ini diajukan dengan sadar, telah disetujui oleh pihak kampus, dan saya memahami bahwa proses magang di Bappeda akan segera dihentikan.</label>
                    </div>
                </div>
                
                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <a href="<?= BASE_URL ?>/mahasiswa/dashboard" class="btn btn-outline">Batal</a>
                    <button type="submit" class="btn btn-primary" style="background: var(--color-danger); border-color: var(--color-danger);">Ajukan Pengunduran Diri</button>
                </div>
            </form>
        </div>
    </div>
</div>

