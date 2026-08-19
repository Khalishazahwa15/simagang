<?php
// Extracted and derived from Figma Make CSS and PRD v4.1

$categories = [
    [
        'id' => 'umum',
        'label' => 'Umum',
        'items' => [
            [
                'q' => 'Apa itu SIMAGANG?',
                'a' => 'SIMAGANG (Sistem Informasi Pengelolaan Magang Mahasiswa) adalah portal digital resmi Bappeda Provinsi Lampung untuk mengelola pengajuan magang mahasiswa. Sistem ini menggantikan proses manual berbasis kertas dan memungkinkan mahasiswa mengajukan, memantau, dan menerima dokumen magang secara daring.',
            ],
            [
                'q' => 'Apakah SIMAGANG hanya untuk mahasiswa dari Lampung?',
                'a' => 'Tidak. SIMAGANG terbuka untuk mahasiswa aktif dari perguruan tinggi terakreditasi di seluruh Indonesia, tidak terbatas pada institusi di Lampung. Namun, pelaksanaan magang berlangsung secara langsung (tatap muka) di kantor Bappeda Provinsi Lampung.',
            ],
            [
                'q' => 'Kapan pendaftaran magang dibuka?',
                'a' => 'Sistem SIMAGANG menggunakan pendaftaran bergulir (rolling) — tidak ada periode pendaftaran tetap. Pengajuan dapat dilakukan kapan saja sepanjang tahun, bergantung pada ketersediaan kapasitas divisi yang dituju.',
            ],
            [
                'q' => 'Berapa lama proses pengajuan hingga mendapat keputusan?',
                'a' => 'Estimasi proses: pemeriksaan berkas memerlukan 1–3 hari kerja, ditambah 1–2 hari kerja untuk konfirmasi kebutuhan divisi. Total proses biasanya 3–7 hari kerja sejak pengajuan dikirim, asalkan dokumen lengkap dan tidak memerlukan revisi.',
            ],
        ],
    ],
    [
        'id' => 'akun',
        'label' => 'Akun & Registrasi',
        'items' => [
            [
                'q' => 'Dokumen apa yang diperlukan untuk mendaftar akun?',
                'a' => 'Pendaftaran akun tidak memerlukan dokumen — cukup email aktif dan NIM/NPM. Dokumen-dokumen resmi (surat lamaran, CV, transkrip) diunggah saat mengisi formulir pengajuan magang, bukan saat registrasi akun.',
            ],
            [
                'q' => 'Bisakah satu akun digunakan untuk beberapa pengajuan?',
                'a' => 'Ya. Satu akun mahasiswa dapat digunakan untuk mengajukan magang berkali-kali — misalnya jika pengajuan sebelumnya ditolak atau sudah selesai. Namun, hanya satu pengajuan aktif yang diperbolehkan dalam satu waktu.',
            ],
            [
                'q' => 'Bagaimana jika saya lupa kata sandi?',
                'a' => 'Gunakan fitur "Lupa kata sandi?" di halaman masuk. Tautan reset kata sandi akan dikirim ke email yang terdaftar. Jika mengalami kesulitan, hubungi Sekretariat Bappeda Provinsi Lampung melalui email resmi.',
            ],
        ],
    ],
    [
        'id' => 'pengajuan',
        'label' => 'Pengajuan & Dokumen',
        'items' => [
            [
                'q' => 'Dokumen apa saja yang wajib diunggah?',
                'a' => 'Tiga dokumen wajib: (1) Surat Lamaran Magang ditujukan kepada Kepala Bappeda Provinsi Lampung, (2) Curriculum Vitae (CV) terbaru, dan (3) Transkrip Nilai akademik resmi. Selain itu, terdapat dokumen opsional seperti surat pengantar dari kampus atau portofolio.',
            ],
            [
                'q' => 'Format file apa yang diterima?',
                'a' => 'Dokumen diterima dalam format PDF (untuk semua dokumen) dan JPG/PNG (untuk transkrip nilai atau sertifikat). Ukuran maksimum per file umumnya 2–5 MB tergantung jenis dokumennya. Detail format tersedia di halaman Persyaratan.',
            ],
            [
                'q' => 'Apakah dokumen perlu dilegalisir?',
                'a' => 'Tidak. Transkrip nilai tidak perlu dilegalisir — cukup salinan yang terbaca jelas dan mencantumkan identitas resmi institusi. Untuk surat lamaran, disarankan menggunakan kop surat perguruan tinggi jika tersedia.',
            ],
            [
                'q' => 'Bisakah saya mengubah formulir setelah dikirim?',
                'a' => 'Tidak bisa secara langsung. Setelah formulir dikirim (status "Diajukan"), formulir tidak dapat diedit. Jika Sekretariat meminta revisi dokumen (status "Perlu Revisi"), Anda dapat mengunggah ulang dokumen yang diminta melalui portal.',
            ],
            [
                'q' => 'Bagaimana jika saya ingin membatalkan pengajuan?',
                'a' => 'Anda dapat membatalkan pengajuan selama masih berstatus Draft atau Diajukan (sebelum diproses). Setelah memasuki tahap pemeriksaan, pembatalan tidak dapat dilakukan mandiri — hubungi Sekretariat. Jika sudah Sedang Magang dan ingin berhenti, gunakan fitur Pengunduran Diri di dashboard.',
            ],
        ],
    ],
    [
        'id' => 'proses',
        'label' => 'Proses & Status',
        'items' => [
            [
                'q' => 'Apa artinya status "Perlu Revisi"?',
                'a' => 'Status "Perlu Revisi" berarti Sekretariat menemukan ketidaklengkapan atau ketidaksesuaian pada dokumen yang diunggah. Anda akan menerima notifikasi beserta catatan dari Sekretariat yang menjelaskan apa yang perlu diperbaiki. Unggah ulang dokumen yang diminta melalui panel di dashboard.',
            ],
            [
                'q' => 'Apa artinya status "Cek Kebutuhan Divisi"?',
                'a' => 'Status ini menunjukkan bahwa berkas Anda sudah dinyatakan lengkap dan valid, dan Sekretariat sedang mengkonfirmasi ketersediaan kapasitas divisi yang Anda pilih. Jika divisi tersedia, status akan berubah menjadi "Diterima". Jika divisi penuh atau tidak menerima saat ini, pengajuan dapat ditolak pada tahap ini.',
            ],
            [
                'q' => 'Apakah ada surat penolakan resmi jika pengajuan ditolak?',
                'a' => 'Tidak ada surat penolakan formal yang diterbitkan. Jika pengajuan ditolak, Anda akan menerima notifikasi melalui sistem beserta alasan penolakan yang wajib dicantumkan oleh Sekretariat. Anda dapat mengajukan kembali setelah memenuhi persyaratan atau memilih divisi yang berbeda.',
            ],
            [
                'q' => 'Apakah saya perlu mengkonfirmasi setelah diterima?',
                'a' => 'Tidak diperlukan konfirmasi dari mahasiswa. Begitu status berubah menjadi "Diterima", Anda tinggal menunggu Sekretariat mengunggah surat penerimaan dan menandai mulainya pelaksanaan magang.',
            ],
        ],
    ],
    [
        'id' => 'pelaksanaan',
        'label' => 'Pelaksanaan & Selesai',
        'items' => [
            [
                'q' => 'Bagaimana cara mengundurkan diri saat sedang magang?',
                'a' => 'Jika Anda perlu mengundurkan diri saat berstatus "Sedang Magang", gunakan fitur Pengunduran Diri di dashboard mahasiswa. Anda wajib mengisi alasan pengunduran diri. Status akan berubah menjadi "Mengundurkan Diri". Segera hubungi supervisor di divisi dan informasikan secara langsung.',
            ],
            [
                'q' => 'Kapan dokumen akhir (sertifikat/surat keterangan) tersedia?',
                'a' => 'Dokumen akhir diunggah oleh Sekretariat setelah menandai status "Selesai". Biasanya tersedia dalam 1–3 hari kerja setelah periode magang berakhir. Anda dapat mengunduhnya melalui tab "Dokumen" di dashboard.',
            ],
            [
                'q' => 'Apakah saya bisa melihat riwayat magang sebelumnya?',
                'a' => 'Ya. Semua riwayat pengajuan dan magang yang pernah Anda lakukan tersimpan di sistem dan dapat dilihat di dashboard mahasiswa, termasuk dokumen yang pernah diproses.',
            ],
        ],
    ],
];
?>

<style>
    .faq-layout {
        display: grid;
        grid-template-columns: 220px 1fr;
        gap: 48px;
    }
    
    .cat-btn {
        display: block;
        width: 100%;
        text-align: left;
        padding: 10px 14px;
        background: transparent;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-family: var(--font-body);
        font-size: 14px;
        font-weight: 500;
        color: var(--text-secondary);
        margin-bottom: 2px;
        transition: all 0.15s;
    }
    
    .cat-btn.active {
        background: var(--bg-green-soft);
        font-weight: 700;
        color: var(--primary);
    }
    
    .cat-count {
        float: right;
        font-family: var(--font-mono);
        font-size: 11px;
        color: var(--text-muted);
        background: var(--border);
        border-radius: 99px;
        padding: 1px 7px;
    }
    
    .cat-btn.active .cat-count {
        color: var(--primary);
        background: var(--bg-main);
    }
    
    .faq-accordion {
        border: 1px solid var(--border);
        border-radius: 12px;
        margin-bottom: 10px;
        overflow: hidden;
        transition: border-color 0.2s;
    }
    
    .faq-accordion.active {
        border-color: rgba(11, 79, 71, 0.25); /* C.green + 40 opacity approx */
    }
    
    .faq-header {
        width: 100%;
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 20px 24px;
        background: var(--bg-soft);
        border: none;
        cursor: pointer;
        text-align: left;
    }
    
    .faq-accordion.active .faq-header {
        background: var(--bg-green-soft);
    }
    
    .faq-chevron {
        flex-shrink: 0;
        margin-top: 3px;
        transition: transform 0.2s;
    }
    
    .faq-accordion.active .faq-chevron {
        transform: rotate(180deg);
    }
    
    .faq-content {
        padding: 0 24px 24px 64px;
        display: none;
    }
    
    .faq-accordion.active .faq-content {
        display: block;
    }
    
    @media (max-width: 768px) {
        .faq-layout {
            grid-template-columns: 1fr;
            gap: 32px;
        }
        .faq-content {
            padding-left: 24px;
        }
    }
</style>

<div style="background: var(--bg-main); min-height: 100vh;">
    <!-- Header -->
    <div class="page-header">
        <div class="page-header-bg"></div>
        <div class="page-header-line"></div>
        <div class="page-header-content">
            <div class="page-header-eyebrow">Bantuan & Informasi</div>
            <h1 class="page-header-title">Pertanyaan yang Sering Diajukan</h1>
            <p class="page-header-desc">
                Jawaban atas pertanyaan umum seputar pengajuan magang, persyaratan, dan penggunaan sistem SIMAGANG.
            </p>
        </div>
    </div>

    <div style="max-width: 960px; margin: 0 auto; padding: 64px 40px;">
        <div class="faq-layout">
            <!-- Sidebar -->
            <div>
                <div style="font-family: var(--font-body); font-size: 11px; font-weight: 700; letter-spacing: 0.10em; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 12px;">Kategori</div>
                <?php foreach ($categories as $i => $cat): ?>
                    <button class="cat-btn <?= $i === 0 ? 'active' : '' ?>" onclick="switchCategory('<?= $cat['id'] ?>', this)">
                        <?= $cat['label'] ?>
                        <span class="cat-count"><?= count($cat['items']) ?></span>
                    </button>
                <?php endforeach; ?>

                <!-- Contact card -->
                <div style="margin-top: 32px; background: var(--bg-soft); border: 1px solid var(--border); border-radius: 12px; padding: 20px 18px;">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 10px;"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
                    <div style="font-family: var(--font-body); font-size: 13.5px; font-weight: 700; color: var(--text-primary); margin-bottom: 6px;">Belum menemukan jawaban?</div>
                    <p style="font-family: var(--font-body); font-size: 12.5px; color: var(--text-secondary); line-height: 1.65; margin-bottom: 14px; margin-top: 0;">
                        Hubungi Sekretariat Bappeda Provinsi Lampung melalui email resmi atau kunjungi kantor pada jam kerja.
                    </p>
                    <div style="font-family: var(--font-body); font-size: 11px; font-weight: 700; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 4px;">Email</div>
                    <div style="font-family: var(--font-mono); font-size: 12px; color: var(--primary);">bappeda@lampungprov.go.id</div>
                    <div style="font-family: var(--font-body); font-size: 11px; font-weight: 700; letter-spacing: 0.08em; color: var(--text-secondary); text-transform: uppercase; margin-top: 10px; margin-bottom: 4px;">Jam Kerja</div>
                    <div style="font-family: var(--font-body); font-size: 12px; color: var(--text-secondary);">Senin–Jumat, 08.00–16.00 WIB</div>
                </div>
            </div>

            <!-- FAQ list -->
            <div>
                <?php foreach ($categories as $i => $cat): ?>
                    <div id="cat-content-<?= $cat['id'] ?>" class="faq-category-content" style="display: <?= $i === 0 ? 'block' : 'none' ?>;">
                        <div style="font-family: var(--font-body); font-size: 11px; font-weight: 700; letter-spacing: 0.10em; color: var(--text-secondary); text-transform: uppercase; margin-bottom: 20px;">
                            <?= $cat['label'] ?> &middot; <?= count($cat['items']) ?> pertanyaan
                        </div>
                        <?php foreach ($cat['items'] as $idx => $item): ?>
                            <div class="faq-accordion <?= $idx === 0 ? 'active' : '' ?>">
                                <button class="faq-header" onclick="toggleFaq(this)">
                                    <span style="font-family: var(--font-mono); font-size: 12px; color: var(--accent-dark); flex-shrink: 0; margin-top: 3px; font-weight: 700;">Q</span>
                                    <span style="font-family: var(--font-body); font-size: 15.5px; font-weight: 600; color: var(--text-primary); line-height: 1.5; flex: 1;"><?= $item['q'] ?></span>
                                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--text-secondary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="faq-chevron"><path d="m6 9 6 6 6-6"/></svg>
                                </button>
                                <div class="faq-content">
                                    <p style="font-family: var(--font-body); font-size: 14.5px; color: var(--text-primary); line-height: 1.8; margin: 0;"><?= $item['a'] ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- CTA -->
    <div style="max-width: 960px; margin: 0 auto 80px; padding: 0 40px;">
        <div class="bottom-cta" style="margin: 0;">
            <div>
                <div style="font-family: var(--font-body); font-size: 11px; font-weight: 700; letter-spacing: 0.12em; color: var(--accent-dark); text-transform: uppercase; margin-bottom: 8px;">Portal SIMAGANG</div>
                <h3 style="font-family: var(--font-display); font-size: 26px; font-weight: 400; color: var(--bg-main); line-height: 1.2; margin: 0;">Ajukan magang Anda secara digital</h3>
            </div>
            <div style="display: flex; gap: 12px; flex-shrink: 0;">
                <a href="<?= BASE_URL ?>/alur" class="btn" style="padding: 12px 24px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.25); border-radius: 8px; color: var(--bg-main); font-weight: 600; text-decoration: none;">
                    Lihat Alur
                </a>
                <a href="<?= BASE_URL ?>/register" class="btn" style="padding: 12px 24px; background: var(--accent); border: none; border-radius: 8px; color: var(--primary-dark); font-weight: 700; text-decoration: none;">
                    Daftar Sekarang
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function switchCategory(catId, btnEl) {
        // Reset buttons
        document.querySelectorAll('.cat-btn').forEach(btn => btn.classList.remove('active'));
        btnEl.classList.add('active');
        
        // Hide all contents
        document.querySelectorAll('.faq-category-content').forEach(content => {
            content.style.display = 'none';
        });
        
        // Show target content
        const target = document.getElementById('cat-content-' + catId);
        if (target) {
            target.style.display = 'block';
            
            // Optionally, reset all accordions in target and open the first one
            const accordions = target.querySelectorAll('.faq-accordion');
            accordions.forEach(acc => acc.classList.remove('active'));
            if (accordions.length > 0) {
                accordions[0].classList.add('active');
            }
        }
    }

    function toggleFaq(btnEl) {
        const item = btnEl.closest('.faq-accordion');
        const isActive = item.classList.contains('active');
        
        if (isActive) {
            item.classList.remove('active');
        } else {
            // Optional: close other accordions in the same category
            // const parent = item.closest('.faq-category-content');
            // parent.querySelectorAll('.faq-accordion').forEach(acc => acc.classList.remove('active'));
            
            item.classList.add('active');
        }
    }
</script>

