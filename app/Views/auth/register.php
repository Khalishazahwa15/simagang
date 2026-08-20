<div style="display: flex; min-height: 100vh; width: 100%;">
    <!-- Left: Branding / Illustration (Hidden on mobile) -->
    <div style="flex: 1; background-color: var(--primary); display: none; flex-direction: column; justify-content: center; padding: 64px; position: relative; overflow: hidden;" class="auth-brand-side">
        <!-- Abstract shape -->
        <div style="position: absolute; top: -100px; right: -100px; width: 500px; height: 500px; border-radius: 50%; background: var(--primary-dark); opacity: 0.5;"></div>
        <div style="position: absolute; bottom: -50px; left: -50px; width: 300px; height: 300px; border-radius: 50%; border: 40px solid var(--primary-dark); opacity: 0.5;"></div>

        <div style="position: relative; z-index: 1;">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 40px;">
                <div style="width: 48px; height: 48px; background-color: var(--accent); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-family: var(--font-display); font-size: 24px; color: var(--primary-dark);">S</div>
                <div>
                    <div style="font-family: var(--font-body); font-weight: 800; font-size: 20px; color: var(--bg-main); line-height: 1.2;">SIMAGANG</div>
                    <div style="font-family: var(--font-body); font-size: 11px; color: rgba(255,255,255,0.6); letter-spacing: 0.1em; text-transform: uppercase;">BAPPEDA LAMPUNG</div>
                </div>
            </div>

            <h1 style="font-family: var(--font-display); font-size: 42px; color: var(--bg-main); line-height: 1.15; margin-bottom: 24px;">
                Bergabunglah untuk memulai<br>pengalaman magang terbaik.
            </h1>
            <p style="font-family: var(--font-body); font-size: 16px; color: rgba(255,255,255,0.7); line-height: 1.6; max-width: 480px;">
                Daftarkan akun dengan menggunakan email resmi instansi atau universitas Anda agar dapat mengajukan permohonan magang.
            </p>
        </div>
    </div>

    <!-- Right: Form -->
    <div style="flex: 1; display: flex; align-items: center; justify-content: center; background-color: var(--bg-main); padding: 32px;" class="auth-form-side">
        <div style="width: 100%; max-width: 440px;">
            <div class="mobile-logo-only" style="display: none; margin-bottom: 32px; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; background-color: var(--accent); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-family: var(--font-display); font-size: 20px; color: var(--primary-dark);">S</div>
                <div>
                    <div style="font-family: var(--font-body); font-weight: 800; font-size: 18px; color: var(--text-primary); line-height: 1.2;">SIMAGANG</div>
                </div>
            </div>

            <div style="margin-bottom: 24px;">
                <h2 style="font-family: var(--font-display); font-size: 28px; color: var(--text-primary); margin: 0 0 8px 0;">Buat Akun Baru</h2>
                <p style="font-family: var(--font-body); font-size: 14px; color: var(--text-secondary); margin: 0;">Pendaftaran terbagi dua tahap. Isian yang sudah diketik tidak hilang saat berpindah tahap.</p>
            </div>

            <!-- Penanda tahap -->
            <ol class="daftar-tahap" aria-label="Tahapan pendaftaran">
                <li class="tahap" id="penanda-tahap-1" aria-current="step">
                    <span class="tahap-nomor">1</span>
                    <span class="tahap-teks">
                        <span class="tahap-judul">Informasi Akun</span>
                        <span class="tahap-ket">Nama, email, kata sandi</span>
                    </span>
                </li>
                <li class="tahap" id="penanda-tahap-2">
                    <span class="tahap-nomor">2</span>
                    <span class="tahap-teks">
                        <span class="tahap-judul">Data Akademik</span>
                        <span class="tahap-ket">Kampus dan kontak</span>
                    </span>
                </li>
            </ol>

            <?php if ($flash = \App\Core\Session::getFlash('error')): ?>
                <div class="alert alert-danger">
                    <div class="alert-icon">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                    </div>
                    <div>
                        <div class="alert-title">Gagal Mendaftar</div>
                        <p class="alert-body"><?= htmlspecialchars($flash) ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>/register" method="POST" id="form-daftar">
                <input type="hidden" name="csrf_token" value="<?= \App\Core\Session::get('csrf_token') ?>">

                <!-- ============ Tahap 1: Informasi Akun ============ -->
                <fieldset class="tahap-panel" id="tahap-1">
                    <legend class="tahap-panel-judul">Informasi Akun</legend>

                    <div class="form-group">
                        <label for="app-views-auth-register-nama" class="form-label required">Nama Lengkap</label>
                        <input id="app-views-auth-register-nama" type="text" name="nama" class="form-control" placeholder="Sesuai KTP/KTM" autocomplete="name" required>
                    </div>

                    <div class="form-group">
                        <label for="app-views-auth-register-email" class="form-label required">Alamat Email</label>
                        <input id="app-views-auth-register-email" type="email" name="email" class="form-control" placeholder="nama@student.unila.ac.id" autocomplete="email" required>
                        <div class="form-help">Dipakai untuk masuk dan menerima pemberitahuan setiap perubahan status pengajuan.</div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label for="reg-password" class="form-label required">Kata Sandi</label>
                            <div style="position: relative;">
                                <input type="password" id="reg-password" name="password" class="form-control" placeholder="Minimal 8 karakter" autocomplete="new-password" required minlength="8" style="padding-right: 40px;">
                                <button aria-label="Tampilkan atau sembunyikan kata sandi" type="button" onclick="togglePassword('reg-password', this)" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; padding: 0; cursor: pointer; color: var(--text-secondary); display: flex; align-items: center; justify-content: center;">
                                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="reg-password-conf" class="form-label required">Konfirmasi Sandi</label>
                            <div style="position: relative;">
                                <input type="password" id="reg-password-conf" name="password_confirm" class="form-control" placeholder="Ketik ulang" autocomplete="new-password" required minlength="8" style="padding-right: 40px;">
                                <button aria-label="Tampilkan atau sembunyikan kata sandi" type="button" onclick="togglePassword('reg-password-conf', this)" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; padding: 0; cursor: pointer; color: var(--text-secondary); display: flex; align-items: center; justify-content: center;">
                                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="tahap-aksi" id="aksi-tahap-1" hidden>
                        <button type="button" class="btn btn-primary btn-lg tahap-tombol-utama" id="tombol-lanjut">
                            Lanjut ke Data Akademik
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 8px;"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </button>
                    </div>
                </fieldset>

                <!-- ============ Tahap 2: Data Akademik ============ -->
                <fieldset class="tahap-panel" id="tahap-2">
                    <legend class="tahap-panel-judul">Data Akademik</legend>
                    <p class="tahap-panel-ket">Dipakai Sekretariat untuk menilai permohonan magang Anda.</p>

                    <div class="grid-2">
                        <div class="form-group">
                            <label for="app-views-auth-register-nim" class="form-label required">NIM</label>
                            <input id="app-views-auth-register-nim" type="text" name="nim" class="form-control" placeholder="Nomor Induk Mahasiswa" inputmode="numeric" autocomplete="off" required>
                        </div>
                        <div class="form-group">
                            <label for="app-views-auth-register-semester" class="form-label required">Semester</label>
                            <input id="app-views-auth-register-semester" type="number" name="semester" class="form-control" placeholder="Contoh: 6" inputmode="numeric" min="1" max="14" required>
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label for="app-views-auth-register-tempat_lahir" class="form-label required">Tempat Lahir</label>
                            <input id="app-views-auth-register-tempat_lahir" type="text" name="tempat_lahir" class="form-control" placeholder="Contoh: Bandar Lampung" required>
                        </div>
                        <div class="form-group">
                            <label for="app-views-auth-register-tanggal_lahir" class="form-label required">Tanggal Lahir</label>
                            <input id="app-views-auth-register-tanggal_lahir" type="date" name="tanggal_lahir" class="form-control" max="<?= date('Y-m-d') ?>" autocomplete="bday" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="app-views-auth-register-universitas" class="form-label required">Universitas</label>
                        <input id="app-views-auth-register-universitas" type="text" name="universitas" class="form-control" placeholder="Contoh: Universitas Lampung" autocomplete="organization" required>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label for="app-views-auth-register-fakultas" class="form-label required">Fakultas</label>
                            <input id="app-views-auth-register-fakultas" type="text" name="fakultas" class="form-control" placeholder="Contoh: Teknik" required>
                        </div>
                        <div class="form-group">
                            <label for="app-views-auth-register-program_studi" class="form-label required">Program Studi</label>
                            <input id="app-views-auth-register-program_studi" type="text" name="program_studi" class="form-control" placeholder="Contoh: Teknik Informatika" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="app-views-auth-register-nomor_hp" class="form-label required">Nomor HP</label>
                        <input id="app-views-auth-register-nomor_hp" type="tel" name="nomor_hp" class="form-control" placeholder="Contoh: 081234567890" inputmode="tel" autocomplete="tel" required>
                    </div>

                    <div class="form-group">
                        <label for="app-views-auth-register-alamat" class="form-label required">Alamat</label>
                        <textarea id="app-views-auth-register-alamat" name="alamat" class="form-control" placeholder="Alamat tempat tinggal saat ini" rows="2" autocomplete="street-address" required></textarea>
                    </div>

                    <div class="form-group" style="margin-top: 8px;">
                        <label style="display: flex; align-items: flex-start; gap: 12px; cursor: pointer;">
                            <input type="checkbox" required style="margin-top: 4px; accent-color: var(--primary);">
                            <span style="font-family: var(--font-body); font-size: 12.5px; color: var(--text-secondary); line-height: 1.5;">
                                Saya menyetujui <a href="<?= BASE_URL ?>" style="color: var(--primary); font-weight: 600;">Syarat dan Ketentuan</a> serta <a href="<?= BASE_URL ?>" style="color: var(--primary); font-weight: 600;">Kebijakan Privasi</a> yang berlaku.
                            </span>
                        </label>
                    </div>

                    <div class="tahap-aksi">
                        <button type="button" class="btn btn-outline tahap-tombol-kembali" id="tombol-kembali" hidden>
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                            Kembali
                        </button>
                        <button type="submit" class="btn btn-primary btn-lg tahap-tombol-utama">Daftar Sekarang</button>
                    </div>
                </fieldset>
            </form>

            <div style="margin-top: 28px; text-align: center; font-family: var(--font-body); font-size: 13.5px; color: var(--text-secondary);">
                Sudah memiliki akun? <a href="<?= BASE_URL ?>/login" style="font-weight: 600; color: var(--primary);">Masuk di sini</a>
            </div>
        </div>
    </div>
</div>

<style>
    @media (min-width: 900px) {
        .auth-brand-side { display: flex !important; }
    }
    @media (max-width: 899px) {
        .mobile-logo-only { display: flex !important; }
    }

    /* --- Penanda tahap --- */
    .daftar-tahap {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        list-style: none;
        margin: 0 0 24px 0;
        padding: 0;
    }

    .tahap {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
        padding-top: 10px;
        border-top: 3px solid var(--border);
        color: var(--text-secondary);
    }

    .tahap[aria-current="step"] {
        border-top-color: var(--primary);
        color: var(--text-primary);
    }

    .tahap.selesai {
        border-top-color: var(--primary);
    }

    .tahap-nomor {
        flex: 0 0 auto;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--bg-soft);
        color: var(--text-secondary);
        font-family: var(--font-mono);
        font-size: 11px;
        font-weight: 700;
    }

    .tahap[aria-current="step"] .tahap-nomor,
    .tahap.selesai .tahap-nomor {
        background: var(--primary);
        color: var(--bg-main);
    }

    .tahap-teks {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    .tahap-judul {
        font-family: var(--font-body);
        font-size: 12.5px;
        font-weight: 700;
        line-height: 1.3;
    }

    .tahap-ket {
        font-family: var(--font-body);
        font-size: 11px;
        color: var(--text-secondary);
        line-height: 1.3;
    }

    /* --- Panel tahap --- */
    .tahap-panel {
        border: none;
        margin: 0;
        padding: 0;
        min-width: 0;
    }

    .tahap-panel[hidden] {
        display: none;
    }

    /* Judul panel hanya diperlukan saat kedua tahap tampil sekaligus,
       yaitu ketika JavaScript tidak berjalan. */
    .tahap-panel-judul {
        font-family: var(--font-body);
        font-weight: 700;
        font-size: 13px;
        color: var(--text-primary);
        padding: 0;
        margin-bottom: 4px;
    }

    .tahap-panel-ket {
        font-family: var(--font-body);
        font-size: 12.5px;
        color: var(--text-secondary);
        margin: 0 0 16px 0;
    }

    #tahap-2 {
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid var(--border);
    }

    .berjalan #tahap-2 {
        margin-top: 0;
        padding-top: 0;
        border-top: none;
    }

    .berjalan .tahap-panel-judul {
        position: absolute;
        width: 1px;
        height: 1px;
        overflow: hidden;
        clip: rect(0 0 0 0);
        white-space: nowrap;
    }

    /* --- Tombol antar tahap --- */
    .tahap-aksi {
        display: flex;
        gap: 12px;
        margin-top: 20px;
    }

    .tahap-aksi[hidden] {
        display: none;
    }

    .tahap-tombol-utama {
        flex: 1 1 auto;
        justify-content: center;
    }

    .tahap-tombol-kembali {
        flex: 0 0 auto;
    }

    @media (max-width: 380px) {
        .tahap-ket { display: none; }
        .tahap-aksi { flex-direction: column-reverse; }
        .tahap-tombol-kembali { width: 100%; justify-content: center; }
    }
</style>

<script>
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    if (input.type === 'password') {
        input.type = 'text';
        btn.innerHTML = '<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>';
    } else {
        input.type = 'password';
        btn.innerHTML = '<svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>';
    }
}

/**
 * Pembagian formulir menjadi dua tahap.
 *
 * Tanpa JavaScript, kedua tahap tetap tampil sebagai satu formulir panjang
 * yang berfungsi penuh. Pembagiannya baru dipasang di sini, sehingga
 * pendaftaran tidak pernah bergantung pada skrip.
 */
(function () {
    const form = document.getElementById('form-daftar');
    if (!form) return;

    const panel = [document.getElementById('tahap-1'), document.getElementById('tahap-2')];
    const penanda = [document.getElementById('penanda-tahap-1'), document.getElementById('penanda-tahap-2')];
    const aksiSatu = document.getElementById('aksi-tahap-1');
    const tombolLanjut = document.getElementById('tombol-lanjut');
    const tombolKembali = document.getElementById('tombol-kembali');

    if (!panel[0] || !panel[1] || !tombolLanjut) return;

    form.classList.add('berjalan');
    aksiSatu.hidden = false;
    tombolKembali.hidden = false;

    // Isian yang sedang disembunyikan tidak boleh berstatus wajib: peramban
    // menolak mengirim formulir bila ada isian wajib yang tidak dapat
    // difokuskan, dan pesannya tidak terlihat pengguna.
    const wajib = panel.map(p => Array.from(p.querySelectorAll('[required]')));

    function tampilkan(ke) {
        panel.forEach((p, i) => {
            const aktif = i === ke;
            p.hidden = !aktif;
            wajib[i].forEach(el => { el.required = aktif; });
            penanda[i].toggleAttribute('aria-current', aktif);
            if (aktif) {
                penanda[i].setAttribute('aria-current', 'step');
            } else {
                penanda[i].removeAttribute('aria-current');
            }
            penanda[i].classList.toggle('selesai', i < ke);
        });

        const fokus = panel[ke].querySelector('input, textarea, select');
        if (fokus) fokus.focus({ preventScroll: true });
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    tombolLanjut.addEventListener('click', function () {
        // Periksa tahap pertama lebih dulu supaya kesalahan ketik tidak baru
        // ketahuan setelah seluruh data akademik terlanjur diisi.
        const belumSah = wajib[0].find(el => !el.checkValidity());
        if (belumSah) {
            belumSah.reportValidity();
            return;
        }

        const sandi = document.getElementById('reg-password');
        const ulang = document.getElementById('reg-password-conf');
        if (sandi && ulang && sandi.value !== ulang.value) {
            ulang.setCustomValidity('Konfirmasi kata sandi tidak sama.');
            ulang.reportValidity();
            return;
        }
        if (ulang) ulang.setCustomValidity('');

        tampilkan(1);
    });

    tombolKembali.addEventListener('click', function () {
        tampilkan(0);
    });

    const ulang = document.getElementById('reg-password-conf');
    if (ulang) {
        ulang.addEventListener('input', function () { ulang.setCustomValidity(''); });
    }

    tampilkan(0);
})();
</script>
