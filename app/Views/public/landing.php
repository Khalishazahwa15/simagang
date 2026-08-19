<?php
// Extracted and derived from Figma Make CSS and PRD v4.1
?>
<style>
    /* Document Panel Timeline Styles */
    .doc-panel {
        background: var(--bg-main);
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
    }
    .doc-panel-header {
        background: var(--primary);
        padding: 20px 28px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    .doc-panel-badge {
        background: var(--accent);
        color: var(--primary-dark);
        padding: 4px 12px;
        border-radius: 6px;
        font-family: var(--font-body);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.06em;
    }
    .doc-panel-body {
        padding: 24px 28px;
    }
    
    .timeline-stepper {
        display: flex;
        align-items: center;
        gap: 0;
    }
    .timeline-step {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
    }
    .timeline-circle {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        border: 2px solid var(--border);
        margin-bottom: 8px;
        position: relative;
        z-index: 1;
    }
    .timeline-circle.done {
        background: var(--primary);
        border-color: var(--primary);
    }
    .timeline-circle.active {
        background: var(--accent);
        border-color: var(--accent);
    }
    .timeline-num {
        font-family: var(--font-mono);
        font-size: 10px;
        font-weight: 600;
        color: var(--text-secondary);
    }
    .timeline-circle.active .timeline-num {
        color: var(--primary-dark);
    }
    .timeline-label {
        font-family: var(--font-body);
        font-size: 10.5px;
        font-weight: 500;
        color: var(--text-secondary);
        text-align: center;
    }
    .timeline-step.active .timeline-label {
        color: var(--text-primary);
        font-weight: 700;
    }
    .timeline-line {
        position: absolute;
        top: 14px;
        left: -50%;
        right: 50%;
        height: 2px;
        background: var(--border);
        z-index: 0;
    }
    .timeline-line.done {
        background: var(--primary);
    }
    
    .trust-strip {
        background: var(--primary);
        padding: 32px;
    }
    .trust-grid {
        max-width: 1240px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0;
    }
    .trust-item {
        padding: 20px 28px;
        display: flex;
        align-items: flex-start;
        gap: 16px;
    }
    .trust-item + .trust-item {
        border-left: 1px solid rgba(255,255,255,0.12);
    }

    .why-section {
        padding: 96px 32px;
        background: var(--bg-main);
    }
    
    .alur-section {
        padding: 96px 32px;
        background: var(--bg-soft);
    }
    .alur-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 24px;
        position: relative;
        z-index: 1;
    }
    .alur-circle {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: var(--bg-main);
        border: 2px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }
    .alur-circle.active {
        background: var(--primary);
        border-color: var(--primary);
    }
    .alur-circle span {
        font-family: var(--font-mono);
        font-size: 14px;
        font-weight: 600;
        color: var(--text-secondary);
    }
    .alur-circle.active span {
        color: var(--bg-main);
    }

    .cta-section {
        background: var(--primary-dark);
        padding: 80px 32px;
    }

    .footer-section {
        background: var(--text-primary);
        padding: 48px 32px 32px;
        border-top: 1px solid rgba(255,255,255,0.06);
    }

    .hero-eyebrow {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
    }
    .hero-eyebrow-line {
        width: 32px;
        height: 2px;
        background: var(--accent);
    }
    .hero-eyebrow-text {
        font-family: var(--font-body);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: var(--text-secondary);
    }
    .hero-headline {
        font-family: var(--font-display);
        font-size: clamp(42px, 5vw, 64px);
        font-weight: 500;
        color: var(--text-primary);
        line-height: 1.1;
        letter-spacing: -0.02em;
        margin-bottom: 24px;
    }
    .hero-subtitle {
        font-family: var(--font-body);
        font-size: 16.5px;
        color: var(--text-secondary);
        line-height: 1.6;
        max-width: 480px;
        margin-bottom: 40px;
    }
    .section-eyebrow {
        font-family: var(--font-body);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: var(--accent);
        margin-bottom: 16px;
    }
    .section-title {
        font-family: var(--font-display);
        font-size: clamp(32px, 4vw, 44px);
        font-weight: 500;
        color: var(--text-primary);
        line-height: 1.15;
        letter-spacing: -0.02em;
        margin: 0;
    }

    @media (max-width: 900px) {
        .hero-grid { grid-template-columns: 1fr !important; gap: 48px !important; }
        .doc-panel { display: none; }
        .trust-grid, .alur-grid { grid-template-columns: 1fr; }
    }
</style>

<!-- Hero Section -->
<section style="background: var(--bg-main); border-bottom: 1px solid var(--border); padding: 72px 32px 80px;">
    <div class="hero-grid grid-2" style="max-width: 1240px; margin: 0 auto; gap: 80px; align-items: center">
        <!-- Left: Editorial content -->
        <div>
            <!-- Eyebrow -->
            <div class="hero-eyebrow">
                <div class="hero-eyebrow-line"></div>
                <span class="hero-eyebrow-text">Layanan Digital BAPPEDA Provinsi Lampung</span>
            </div>

            <!-- Headline -->
            <h1 class="hero-headline">
                Ajukan Magang di<br>
                <em style="color: var(--primary); font-style: normal;">Bappeda Provinsi</em><br>
                Lampung.
            </h1>

            <!-- Subtitle -->
            <p class="hero-subtitle">
                Ajukan, lengkapi dokumen, dan pantau proses magang mahasiswa secara online dalam satu layanan terpadu.
            </p>

            <!-- CTAs -->
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <a href="<?= BASE_URL ?>/login" class="btn btn-primary" style="padding: 14px 28px; font-size: 14px; border-radius: 8px;">
                    Ajukan Magang
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 8px;"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
                <a href="<?= BASE_URL ?>/login" class="btn btn-outline" style="padding: 14px 28px; font-size: 14px; border-radius: 8px; font-weight: 600;">
                    Cek Status Pengajuan
                </a>
            </div>
        </div>

        <!-- Right: Document composition -->
        <div class="doc-panel">
            <div class="doc-panel-header">
                <div>
                    <div style="font-family: var(--font-body); font-size: 10px; font-weight: 700; letter-spacing: 0.12em; color: rgba(255,255,255,0.6); text-transform: uppercase; margin-bottom: 4px;">Surat Keterangan Pengajuan</div>
                    <div style="font-family: var(--font-mono); font-size: 20px; font-weight: 600; color: var(--bg-main); letter-spacing: 0.02em;">PGJ-1002</div>
                </div>
                <div class="doc-panel-badge">
                    DITERUSKAN
                </div>
            </div>
            
            <div class="doc-panel-body">
                <div class="grid-2" style="gap: 12px 24px; margin-bottom: 24px">
                    <div>
                        <div style="font-family: var(--font-body); font-size: 10px; font-weight: 700; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 2px;">Nama Mahasiswa</div>
                        <div style="font-family: var(--font-body); font-size: 13px; font-weight: 600; color: var(--text-primary);">Mahasiswa (Data Contoh)</div>
                    </div>
                    <div>
                        <div style="font-family: var(--font-body); font-size: 10px; font-weight: 700; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 2px;">NIM</div>
                        <div style="font-family: var(--font-body); font-size: 13px; font-weight: 600; color: var(--text-primary);">12345678</div>
                    </div>
                    <div>
                        <div style="font-family: var(--font-body); font-size: 10px; font-weight: 700; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 2px;">Perguruan Tinggi</div>
                        <div style="font-family: var(--font-body); font-size: 13px; font-weight: 600; color: var(--text-primary);">Universitas Lampung</div>
                    </div>
                    <div>
                        <div style="font-family: var(--font-body); font-size: 10px; font-weight: 700; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 2px;">Program Studi</div>
                        <div style="font-family: var(--font-body); font-size: 13px; font-weight: 600; color: var(--text-primary);">Sistem Informasi</div>
                    </div>
                </div>
                
                <hr style="border: none; border-top: 1px solid var(--border); margin-bottom: 20px;">
                
                <div style="font-family: var(--font-body); font-size: 11px; font-weight: 700; letter-spacing: 0.10em; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 16px;">Tahapan Pengajuan</div>
                
                <div class="timeline-stepper">
                    <div class="timeline-step">
                        <div class="timeline-circle done">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--bg-main)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
                        </div>
                        <div class="timeline-label">Pengajuan</div>
                    </div>
                    <div class="timeline-step">
                        <div class="timeline-line done"></div>
                        <div class="timeline-circle done">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--bg-main)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
                        </div>
                        <div class="timeline-label">Verifikasi</div>
                    </div>
                    <div class="timeline-step active">
                        <div class="timeline-line"></div>
                        <div class="timeline-circle active">
                            <span class="timeline-num">03</span>
                        </div>
                        <div class="timeline-label">Persetujuan</div>
                    </div>
                    <div class="timeline-step">
                        <div class="timeline-line"></div>
                        <div class="timeline-circle">
                            <span class="timeline-num">04</span>
                        </div>
                        <div class="timeline-label">Surat</div>
                    </div>
                </div>
                
                <hr style="border: none; border-top: 1px solid var(--border); margin: 20px 0;">
                
                <div style="background: var(--bg-green-soft); border: 1px solid var(--primary-light); border-left: 3px solid var(--primary); border-radius: 6px; padding: 10px 14px;">
                    <div style="font-family: var(--font-body); font-size: 12.5px; color: var(--primary); font-weight: 500; line-height: 1.5;">
                        Berkas dinyatakan lengkap dan diteruskan ke tahap persetujuan substansi.
                    </div>
                    <div style="font-family: var(--font-body); font-size: 11px; color: var(--text-secondary); margin-top: 4px;">
                        07 Agustus 2026 &middot; 09:42 WIB
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Trust Strip -->
<section class="trust-strip">
    <div class="trust-grid">
        <!-- Item 1 -->
        <div class="trust-item">
            <div class="icon-gold">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
            </div>
            <div>
                <div style="font-family: var(--font-body); font-size: 14px; font-weight: 700; color: var(--bg-main); margin-bottom: 3px;">Pengajuan Online</div>
                <div style="font-family: var(--font-body); font-size: 12.5px; color: rgba(255,255,255,0.6); line-height: 1.5;">Tidak perlu datang ke kantor</div>
            </div>
        </div>
        <!-- Item 2 -->
        <div class="trust-item">
            <div class="icon-gold">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div>
                <div style="font-family: var(--font-body); font-size: 14px; font-weight: 700; color: var(--bg-main); margin-bottom: 3px;">Status Transparan</div>
                <div style="font-family: var(--font-body); font-size: 12.5px; color: rgba(255,255,255,0.6); line-height: 1.5;">Pantau setiap tahapan secara real-time</div>
            </div>
        </div>
        <!-- Item 3 -->
        <div class="trust-item">
            <div class="icon-gold">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <div>
                <div style="font-family: var(--font-body); font-size: 14px; font-weight: 700; color: var(--bg-main); margin-bottom: 3px;">Dokumen Tersimpan</div>
                <div style="font-family: var(--font-body); font-size: 12.5px; color: rgba(255,255,255,0.6); line-height: 1.5;">Arsip digital aman dan tertelusuri</div>
            </div>
        </div>
        <!-- Item 4 -->
        <div class="trust-item">
            <div class="icon-gold">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
            </div>
            <div>
                <div style="font-family: var(--font-body); font-size: 14px; font-weight: 700; color: var(--bg-main); margin-bottom: 3px;">Proses Terstruktur</div>
                <div style="font-family: var(--font-body); font-size: 12.5px; color: rgba(255,255,255,0.6); line-height: 1.5;">Alur kerja yang jelas dan terstandar</div>
            </div>
        </div>
    </div>
</section>

<!-- Mengapa SIMAGANG -->
<section class="why-section">
    <div style="max-width: 1240px; margin: 0 auto;">
        <div style="display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: 56px;">
            <div>
                <div class="section-eyebrow">Mengapa SIMAGANG</div>
                <h2 class="section-title">
                    Layanan yang dirancang<br>
                    untuk <em style="color: var(--primary); font-style: normal;">kemudahan mahasiswa.</em>
                </h2>
            </div>
        </div>
        
        <div style="height: 1px; background: var(--border); margin-bottom: 0;"></div>
        
        <div>
            <?php
            $benefits = [
                ['num' => '01', 'title' => 'Pengajuan Digital', 'body' => 'Tidak perlu datang ke kantor untuk memulai proses pengajuan magang. Seluruh administrasi dilakukan secara daring.'],
                ['num' => '02', 'title' => 'Status Terpantau', 'body' => 'Mahasiswa dapat mengetahui posisi pengajuan pada setiap tahapan proses secara transparan dan real-time.'],
                ['num' => '03', 'title' => 'Dokumen Terarsip', 'body' => 'Dokumen pengajuan tersimpan secara digital dan dapat ditelusuri kapan saja oleh mahasiswa maupun administrator.'],
            ];
            foreach ($benefits as $b): ?>
            <div style="display: grid; grid-template-columns: 80px 1fr 1fr; gap: 0 48px; padding: 40px 0; border-bottom: 1px solid var(--border); align-items: start;">
                <div style="font-family: var(--font-mono); font-size: 36px; font-weight: 600; color: var(--accent); line-height: 1; padding-top: 4px;"><?= $b['num'] ?></div>
                <div><h3 style="font-family: var(--font-display); font-size: 26px; font-weight: 400; color: var(--text-primary); line-height: 1.2; letter-spacing: -0.01em; margin: 0;"><?= $b['title'] ?></h3></div>
                <div><p style="font-family: var(--font-body); font-size: 15px; color: var(--text-secondary); line-height: 1.7; margin: 0;"><?= $b['body'] ?></p></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Alur Pengajuan -->
<section class="alur-section">
    <div style="max-width: 1240px; margin: 0 auto;">
        <div style="margin-bottom: 56px; display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 24px;">
            <div>
                <div class="section-eyebrow">Alur Pengajuan</div>
                <h2 class="section-title">
                    Lima langkah mudah<br>menuju magang di Bappeda.
                </h2>
            </div>
            <a href="<?= BASE_URL ?>/login" class="btn btn-outline" style="padding: 12px 24px; border-color: var(--primary); color: var(--primary); font-size: 13.5px; border-radius: 8px; align-self: flex-end; font-weight: 600;">
                Mulai Sekarang 
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 8px;"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </a>
        </div>

        <div style="position: relative;">
            <div style="position: absolute; top: 28px; left: 28px; right: 28px; height: 1px; background: var(--border); z-index: 0;"></div>
            
            <div class="alur-grid">
                <?php
                $alurSteps = [
                    ['num' => '01', 'title' => 'Registrasi', 'desc' => 'Buat akun dengan email perguruan tinggi aktif.'],
                    ['num' => '02', 'title' => 'Lengkapi Profil', 'desc' => 'Isi data diri, akademik, dan informasi institusi.'],
                    ['num' => '03', 'title' => 'Ajukan Magang', 'desc' => 'Pilih bidang, periode, dan unggah dokumen persyaratan.'],
                    ['num' => '04', 'title' => 'Verifikasi & Persetujuan', 'desc' => 'Tim Bappeda memverifikasi berkas dan memberikan persetujuan.'],
                    ['num' => '05', 'title' => 'Surat Diterbitkan', 'desc' => 'Surat penerimaan magang diterbitkan secara digital.'],
                ];
                foreach ($alurSteps as $i => $step): 
                    $isActive = $i === 0;
                ?>
                <div style="display: flex; flex-direction: column; align-items: flex-start;">
                    <div class="alur-circle <?= $isActive ? 'active' : '' ?>">
                        <span><?= $step['num'] ?></span>
                    </div>
                    <div style="font-family: var(--font-body); font-size: 15px; font-weight: 700; color: var(--text-primary); margin-bottom: 8px; line-height: 1.3;"><?= $step['title'] ?></div>
                    <div style="font-family: var(--font-body); font-size: 13px; color: var(--text-secondary); line-height: 1.6;"><?= $step['desc'] ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div style="max-width: 1240px; margin: 0 auto; display: grid; grid-template-columns: 1fr auto; gap: 40px; align-items: center;">
        <div>
            <div class="section-eyebrow">Siap Memulai?</div>
            <h2 style="font-family: var(--font-display); font-size: clamp(28px, 3vw, 42px); font-weight: 400; color: var(--bg-main); line-height: 1.15; letter-spacing: -0.02em; margin-bottom: 12px;">Ajukan magang Anda hari ini.</h2>
            <p style="font-family: var(--font-body); font-size: 15px; color: rgba(255,255,255,0.65); line-height: 1.6; margin: 0;">Daftarkan diri dan mulai proses pengajuan magang di Bappeda Provinsi Lampung.</p>
        </div>
        <div style="display: flex; flex-direction: column; gap: 12px; flex-shrink: 0;">
            <a href="<?= BASE_URL ?>/register" class="btn btn-secondary" style="padding: 14px 32px; background: var(--accent); color: var(--primary-dark); border-radius: 8px; border: none; font-weight: 700; font-size: 14px; white-space: nowrap;">Daftar Sekarang</a>
            <a href="<?= BASE_URL ?>/login" class="btn btn-outline" style="padding: 14px 32px; background: transparent; color: rgba(255,255,255,0.8); border-radius: 8px; border: 1px solid rgba(255,255,255,0.25); font-weight: 600; font-size: 14px; white-space: nowrap;">Sudah Punya Akun</a>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer-section">
    <div style="max-width: 1240px; margin: 0 auto;">
        <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 48px; margin-bottom: 40px;">
            <div>
                <div style="font-family: var(--font-body); font-weight: 800; font-size: 16px; color: var(--bg-main); margin-bottom: 4px;">SIMAGANG</div>
                <div style="font-family: var(--font-body); font-size: 11px; color: rgba(255,255,255,0.4); letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 16px;">Sistem Informasi Pengelolaan Magang Mahasiswa</div>
                <p style="font-family: var(--font-body); font-size: 13.5px; color: rgba(255,255,255,0.5); line-height: 1.7; max-width: 360px; margin: 0;">
                    Layanan digital untuk pengelolaan magang mahasiswa pada Badan Perencanaan Pembangunan Daerah (Bappeda) Provinsi Lampung.
                </p>
            </div>
            <div>
                <div style="font-family: var(--font-body); font-size: 11px; font-weight: 700; letter-spacing: 0.12em; color: rgba(255,255,255,0.4); text-transform: uppercase; margin-bottom: 16px;">Tautan</div>
                <?php foreach (['Beranda' => '/', 'Alur Pengajuan' => '/alur', 'Persyaratan' => '/persyaratan', 'FAQ' => '/faq'] as $label => $url): ?>
                <div style="margin-bottom: 10px;">
                    <a href="<?= BASE_URL . $url ?>" style="background: none; border: none; font-family: var(--font-body); font-size: 13.5px; color: rgba(255,255,255,0.6); padding: 0; text-decoration: none;"><?= $label ?></a>
                </div>
                <?php endforeach; ?>
            </div>
            <div>
                <div style="font-family: var(--font-body); font-size: 11px; font-weight: 700; letter-spacing: 0.12em; color: rgba(255,255,255,0.4); text-transform: uppercase; margin-bottom: 16px;">Kontak</div>
                <p style="font-family: var(--font-body); font-size: 13.5px; color: rgba(255,255,255,0.5); line-height: 1.7; margin: 0;">
                    Bappeda Provinsi Lampung<br>
                    Jl. Cut Meutia No.4, Bandar Lampung<br>
                    simagang@bappeda.lampungprov.go.id
                </p>
            </div>
        </div>
        
        <hr style="border: none; border-top: 1px solid rgba(255,255,255,0.08); margin-bottom: 24px;">
        
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div style="font-family: var(--font-body); font-size: 12px; color: rgba(255,255,255,0.3);">
                &copy; <?= date('Y') ?> Bappeda Provinsi Lampung. Hak cipta dilindungi.
            </div>
            <div style="font-family: var(--font-body); font-size: 12px; color: rgba(255,255,255,0.3);">
                SIMAGANG v1.0.0
            </div>
        </div>
    </div>
</footer>


