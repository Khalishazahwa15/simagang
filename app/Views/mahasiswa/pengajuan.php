<div class="mb-6">
    <div class="d-flex align-center gap-2 mb-2">
        <a href="<?= BASE_URL ?>/mahasiswa/dashboard" class="text-muted" style="text-decoration: none;">
            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        </a>
        <div class="text-overline" style="margin: 0;">Pengajuan Magang</div>
    </div>
</div>

<div class="grid grid-3">
    <!-- Left: Form Stepper -->
    <div style="grid-column: span 2;">
        
        <!-- Step Indicators -->
        <div class="card fade-up interactive-card fade-up interactive-card" style="padding: 20px 28px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between;">
            <div class="step-indicator active" id="indicator-0" style="flex: 1; display: flex; align-items: center;">
                <div style="display: flex; flex-direction: column; align-items: center; flex: 1;">
                    <div class="step-circle" style="width: 32px; height: 32px; border-radius: 50%; background: var(--accent); border: 2px solid var(--accent); display: flex; align-items: center; justify-content: center; margin-bottom: 6px; font-family: var(--font-mono); font-size: 12px; font-weight: 600; color: var(--primary-dark);">01</div>
                    <div class="step-label" style="font-family: var(--font-body); font-size: 12px; font-weight: 700; color: var(--text-primary); text-align: center;">Preferensi Divisi</div>
                </div>
                <div class="step-line" style="height: 1px; width: 30px; background: var(--border); margin-bottom: 18px;"></div>
            </div>
            
            <div class="step-indicator" id="indicator-1" style="flex: 1; display: flex; align-items: center;">
                <div style="display: flex; flex-direction: column; align-items: center; flex: 1;">
                    <div class="step-circle" style="width: 32px; height: 32px; border-radius: 50%; background: transparent; border: 2px solid var(--border); display: flex; align-items: center; justify-content: center; margin-bottom: 6px; font-family: var(--font-mono); font-size: 12px; font-weight: 600; color: var(--text-secondary);">02</div>
                    <div class="step-label" style="font-family: var(--font-body); font-size: 12px; font-weight: 400; color: var(--text-secondary); text-align: center;">Dokumen Wajib</div>
                </div>
                <div class="step-line" style="height: 1px; width: 30px; background: var(--border); margin-bottom: 18px;"></div>
            </div>
            
            <div class="step-indicator" id="indicator-2" style="flex: 1; display: flex; align-items: center;">
                <div style="display: flex; flex-direction: column; align-items: center; flex: 1;">
                    <div class="step-circle" style="width: 32px; height: 32px; border-radius: 50%; background: transparent; border: 2px solid var(--border); display: flex; align-items: center; justify-content: center; margin-bottom: 6px; font-family: var(--font-mono); font-size: 12px; font-weight: 600; color: var(--text-secondary);">03</div>
                    <div class="step-label" style="font-family: var(--font-body); font-size: 12px; font-weight: 400; color: var(--text-secondary); text-align: center;">Review & Kirim</div>
                </div>
            </div>
        </div>

        <div class="card fade-up interactive-card fade-up interactive-card" style="padding: 28px;">
            <form id="pengajuanForm" action="<?= BASE_URL ?>/mahasiswa/pengajuan/submit" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= \App\Core\Session::get('csrf_token') ?? '' ?>">
                
                <!-- Step 0: Preferensi Divisi -->
                <div id="step-0" class="form-step">
                    <h2 style="font-family: var(--font-display); font-size: 22px; font-weight: 400; color: var(--text-primary); margin-bottom: 8px; margin-top: 0;">Preferensi Divisi / Bidang</h2>
                    <p style="font-family: var(--font-body); font-size: 13.5px; color: var(--text-secondary); margin-bottom: 24px; line-height: 1.6;">
                        Pilih divisi yang sesuai dengan minat dan program studi Anda. Preferensi ini <strong>tidak mengikat</strong> — penempatan final adalah keputusan Sekretariat Bappeda berdasarkan kebutuhan dan kapasitas divisi.
                    </p>
                    
                    <div class="form-group">
                        <label for="app-views-mahasiswa-pengajuan-divisi" class="form-label required">Preferensi Divisi / Bidang</label>
                        <select id="app-views-mahasiswa-pengajuan-divisi" name="divisi" class="form-control" required>
                            <option value="">Pilih divisi...</option>
                            <?php foreach ($divisi as $div): ?>
                            <option value="<?= $div['id'] ?>">
                                <?= htmlspecialchars($div['nama_divisi']) ?> (Sisa Kuota: <?= $div['kapasitas'] - ($div['terisi'] ?? 0) ?>)
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-help">Pilih satu pilihan yang paling sesuai.</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="app-views-mahasiswa-pengajuan-start_date" class="form-label required">Rencana Periode Magang</label>
                        <div class="grid grid-2" style="gap: 12px;">
                            <input id="app-views-mahasiswa-pengajuan-start_date" type="date" name="start_date" class="form-control" required>
                            <input type="date" name="end_date" class="form-control" required>
                        </div>
                        <div class="form-help">Perkiraan tanggal mulai dan selesai yang Anda harapkan.</div>
                    </div>
                </div>

                <div id="step-1" class="form-step" style="display: none;">
                    <h2 style="font-family: var(--font-display); font-size: 22px; font-weight: 400; color: var(--text-primary); margin-bottom: 8px; margin-top: 0;">Unggah Dokumen</h2>
                    <p style="font-family: var(--font-body); font-size: 13.5px; color: var(--text-secondary); margin-bottom: 24px;">
                        Pastikan seluruh dokumen berformat PDF dan tidak melebihi batas ukuran 2MB.
                    </p>
                    
                    <div class="form-group">
                        <label class="form-label required">Surat Lamaran / Pernyataan</label>
                        <label style="display: block; border: 1px dashed var(--border); border-radius: var(--radius-lg); padding: 24px; text-align: center; background: var(--bg-soft); cursor: pointer; transition: all 0.2s ease;">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--text-secondary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 12px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                            <div class="text-value" style="font-size: 13px; margin-bottom: 4px;">Klik untuk mengunggah file</div>
                            <div class="text-muted" style="font-size: 12px; font-family: var(--font-body);">Format PDF, Maksimal 2MB</div>
                            <input type="file" name="surat_lamaran" accept="application/pdf" style="opacity: 0; position: absolute; z-index: -1;" required>
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label required">Curriculum Vitae (CV)</label>
                        <label style="display: block; border: 1px dashed var(--border); border-radius: var(--radius-lg); padding: 24px; text-align: center; background: var(--bg-soft); cursor: pointer; transition: all 0.2s ease;">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--text-secondary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 12px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                            <div class="text-value" style="font-size: 13px; margin-bottom: 4px;">Klik untuk mengunggah file</div>
                            <div class="text-muted" style="font-size: 12px; font-family: var(--font-body);">Format PDF, Maksimal 2MB</div>
                            <input type="file" name="cv" accept="application/pdf" style="opacity: 0; position: absolute; z-index: -1;" required>
                        </label>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Transkrip Nilai</label>
                        <label style="display: block; border: 1px dashed var(--border); border-radius: var(--radius-lg); padding: 24px; text-align: center; background: var(--bg-soft); cursor: pointer; transition: all 0.2s ease;">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--text-secondary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 12px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                            <div class="text-value" style="font-size: 13px; margin-bottom: 4px;">Klik untuk mengunggah file</div>
                            <div class="text-muted" style="font-size: 12px; font-family: var(--font-body);">Format PDF, Maksimal 2MB</div>
                            <input type="file" name="transkrip" accept="application/pdf" style="opacity: 0; position: absolute; z-index: -1;" required>
                        </label>
                    </div>

                    <div class="form-group mb-0">
                        <label class="form-label">Dokumen Tambahan (Opsional)</label>
                        <label style="display: block; border: 1px dashed var(--border); border-radius: var(--radius-lg); padding: 24px; text-align: center; background: var(--bg-soft); cursor: pointer; transition: all 0.2s ease;">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--text-secondary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 12px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                            <div class="text-value" style="font-size: 13px; margin-bottom: 4px;">Klik untuk mengunggah file</div>
                            <div class="text-muted" style="font-size: 12px; font-family: var(--font-body);">Format PDF, Maksimal 2MB, misal: Portofolio</div>
                            <input type="file" name="tambahan" accept="application/pdf" style="opacity: 0; position: absolute; z-index: -1;">
                        </label>
                    </div>
                </div>

                <!-- Step 2: Review & Kirim -->
                <div id="step-2" class="form-step" style="display: none;">
                    <h2 style="font-family: var(--font-display); font-size: 22px; font-weight: 400; color: var(--text-primary); margin-bottom: 20px; margin-top: 0;">Tinjau & Kirimkan Pengajuan</h2>
                    
                    <div style="background: var(--bg-soft); border-radius: 8px; padding: 16px 20px; margin-bottom: 14px;">
                        <div style="font-family: var(--font-body); font-size: 12px; font-weight: 700; letter-spacing: 0.10em; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 12px;">Pernyataan Validitas</div>
                        <p style="font-family: var(--font-body); font-size: 13px; color: var(--text-primary); line-height: 1.6; margin: 0;">
                            Dengan menekan tombol kirim, saya menyatakan bahwa seluruh data dan dokumen yang saya unggah adalah benar dan dapat dipertanggungjawabkan. Saya bersedia mengikuti seluruh proses seleksi dan aturan magang di Bappeda Provinsi Lampung.
                        </p>
                    </div>

                    <div style="background: var(--accent-soft); border: 1px solid rgba(217, 165, 29, 0.25); border-radius: 8px; padding: 14px 16px; display: flex; gap: 10px; margin-bottom: 24px;">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 1px;"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="16" y2="12"/><line x1="12" x2="12.01" y1="8" y2="8"/></svg>
                        <p style="font-family: var(--font-body); font-size: 13px; color: var(--color-warning-ink); line-height: 1.55; margin: 0;">
                            Pastikan seluruh dokumen sudah benar sebelum mengirimkan. Pengajuan yang sudah dikirim akan masuk antrean pemeriksaan Sekretariat.
                        </p>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 15px; padding: 14px;">
                        Kirimkan Pengajuan
                    </button>
                </div>

                <!-- Navigation Buttons -->
                <div id="step-navigation" style="display: flex; justify-content: space-between; margin-top: 32px; padding-top: 24px; border-top: 1px solid var(--border);">
                    <button type="button" id="btnPrev" class="btn btn-outline" style="opacity: 0.5; cursor: not-allowed;" disabled>Kembali</button>
                    <div style="display: flex; gap: 12px;">
                        <button type="button" id="btnNext" class="btn btn-primary">
                            Lanjutkan 
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Right: Sidebar Information -->
    <div>
        <div style="display: flex; flex-direction: column; gap: 14px;">
            <div style="background: var(--bg-green-soft); border: 1px solid var(--primary-light); border-radius: 10px; padding: 18px 20px;">
                <div style="font-family: var(--font-body); font-size: 12px; font-weight: 700; letter-spacing: 0.10em; color: var(--primary); text-transform: uppercase; margin-bottom: 8px;">Dokumen Wajib</div>
                <div style="display: flex; align-items: flex-start; gap: 8px; margin-bottom: 6px;">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                    <span style="font-family: var(--font-body); font-size: 12.5px; color: var(--primary); line-height: 1.4;">Surat Lamaran / Pernyataan</span>
                </div>
                <div style="display: flex; align-items: flex-start; gap: 8px; margin-bottom: 6px;">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                    <span style="font-family: var(--font-body); font-size: 12.5px; color: var(--primary); line-height: 1.4;">Curriculum Vitae (CV)</span>
                </div>
                <div style="display: flex; align-items: flex-start; gap: 8px; margin-bottom: 6px;">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                    <span style="font-family: var(--font-body); font-size: 12.5px; color: var(--primary); line-height: 1.4;">Transkrip Nilai</span>
                </div>
                <div style="font-family: var(--font-body); font-size: 12px; color: var(--primary); margin-top: 10px; padding-top: 10px; border-top: 1px solid var(--color-success-border);">
                    Persiapkan file berformat PDF sebelum mengisi formulir.
                </div>
            </div>
            
            <div class="card fade-up interactive-card fade-up interactive-card" style="padding: 14px 18px; margin-bottom: 0;">
                <div style="font-family: var(--font-body); font-size: 12px; font-weight: 700; letter-spacing: 0.10em; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 8px;">Catatan Penting</div>
                <p style="font-family: var(--font-body); font-size: 12.5px; color: var(--text-secondary); line-height: 1.6; margin: 0;">
                    Pengajuan bersifat <strong>rolling</strong> — tidak ada batas waktu pendaftaran. Preferensi divisi bersifat non-mengikat; penempatan final adalah keputusan Sekretariat Bappeda.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const totalSteps = 3;
        let currentStep = 0;
        
        const btnPrev = document.getElementById('btnPrev');
        const btnNext = document.getElementById('btnNext');
        
        // Simple file input display
        const fileInputs = document.querySelectorAll('input[type="file"]');
        fileInputs.forEach(input => {
            input.addEventListener('change', function(e) {
                const fileName = e.target.files[0]?.name;
                if (fileName) {
                    const textValue = this.parentElement.querySelector('.text-value');
                    textValue.textContent = fileName;
                    textValue.style.color = 'var(--primary)';
                    this.parentElement.style.borderColor = 'var(--primary)';
                    this.parentElement.style.background = 'var(--bg-green-soft)';
                }
            });
        });

        function updateUI() {
            // Update forms visibility
            for (let i = 0; i < totalSteps; i++) {
                document.getElementById('step-' + i).style.display = (i === currentStep) ? 'block' : 'none';
            }
            
            // Update indicators
            for (let i = 0; i < totalSteps; i++) {
                const indicator = document.getElementById('indicator-' + i);
                const circle = indicator.querySelector('.step-circle');
                const label = indicator.querySelector('.step-label');
                const line = indicator.querySelector('.step-line');
                
                if (i < currentStep) {
                    // Completed
                    circle.style.background = 'var(--primary)';
                    circle.style.borderColor = 'var(--primary)';
                    circle.innerHTML = '<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--bg-main)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>';
                    label.style.fontWeight = '400';
                    label.style.color = 'var(--text-secondary)';
                    if (line) line.style.background = 'var(--primary)';
                } else if (i === currentStep) {
                    // Active
                    circle.style.background = 'var(--accent)';
                    circle.style.borderColor = 'var(--accent)';
                    circle.innerHTML = '0' + (i + 1);
                    circle.style.color = 'var(--primary-dark)';
                    label.style.fontWeight = '700';
                    label.style.color = 'var(--text-primary)';
                    if (line) line.style.background = 'var(--border)';
                } else {
                    // Pending
                    circle.style.background = 'transparent';
                    circle.style.borderColor = 'var(--border)';
                    circle.innerHTML = '0' + (i + 1);
                    circle.style.color = 'var(--text-secondary)';
                    label.style.fontWeight = '400';
                    label.style.color = 'var(--text-secondary)';
                    if (line) line.style.background = 'var(--border)';
                }
            }
            
            // Update buttons
            if (currentStep === 0) {
                btnPrev.style.opacity = '0.5';
                btnPrev.style.cursor = 'not-allowed';
                btnPrev.disabled = true;
            } else {
                btnPrev.style.opacity = '1';
                btnPrev.style.cursor = 'pointer';
                btnPrev.disabled = false;
            }
            
            if (currentStep === totalSteps - 1) {
                btnNext.style.display = 'none';
            } else {
                btnNext.style.display = 'inline-flex';
            }
        }
        
        btnPrev.addEventListener('click', function() {
            if (currentStep > 0) {
                currentStep--;
                updateUI();
            }
        });
        
        btnNext.addEventListener('click', function() {
            // Basic HTML5 validation before next step
            const currentFormStep = document.getElementById('step-' + currentStep);
            const inputs = currentFormStep.querySelectorAll('input, select, textarea');
            let allValid = true;
            
            inputs.forEach(input => {
                if (!input.checkValidity()) {
                    allValid = false;
                    input.reportValidity();
                }
            });
            
            if (allValid && currentStep < totalSteps - 1) {
                currentStep++;
                updateUI();
            }
        });
    });
</script>



