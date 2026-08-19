<?php
/**
 * Alat uji konfigurasi email.
 *
 *   php tools/kirim-email-uji.php tujuan@contoh.com
 *
 * Membaca .env yang sama dengan aplikasi, lalu mencoba mengirim satu email.
 * Kegagalan ditampilkan lengkap dengan percakapan SMTP-nya, supaya penyebabnya
 * kelihatan tanpa perlu menebak dari halaman web.
 */

define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');

require_once APP_PATH . '/Core/Env.php';

spl_autoload_register(function ($class) {
    $prefix = 'PHPMailer\\PHPMailer\\';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $file = ROOT_PATH . '/lib/PHPMailer/' . substr($class, $len) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

\App\Core\Env::load(ROOT_PATH . '/.env');

$tujuan = $argv[1] ?? '';
if ($tujuan === '') {
    fwrite(STDERR, "Penggunaan: php tools/kirim-email-uji.php tujuan@contoh.com\n");
    exit(1);
}

$host = \App\Core\Env::get('SMTP_HOST', '');
$port = \App\Core\Env::get('SMTP_PORT', '587');
$user = \App\Core\Env::get('SMTP_USER', '');
$pass = \App\Core\Env::get('SMTP_PASS', '');
$enkripsi = strtolower(\App\Core\Env::get('SMTP_ENCRYPTION', 'tls'));
$dari = \App\Core\Env::get('MAIL_FROM_ADDRESS', $user);
$namaDari = \App\Core\Env::get('MAIL_FROM_NAME', 'SIMAGANG Bappeda Lampung');

echo "Konfigurasi terbaca dari .env\n";
echo "  SMTP_HOST          : " . ($host === '' ? '(kosong)' : $host) . "\n";
echo "  SMTP_PORT          : {$port}\n";
echo "  SMTP_USER          : " . ($user === '' ? '(kosong)' : $user) . "\n";
echo "  SMTP_PASS          : " . ($pass === '' ? '(kosong)' : str_repeat('*', strlen($pass)) . ' (' . strlen($pass) . " karakter, disembunyikan)") . "\n";
echo "  SMTP_ENCRYPTION    : {$enkripsi}\n";
echo "  MAIL_FROM_ADDRESS  : " . ($dari === '' ? '(kosong)' : $dari) . "\n";
echo "\n";

if (trim($host) === '') {
    echo "SMTP_HOST masih kosong.\n";
    echo "Aplikasi tidak akan mengirim email, melainkan menulisnya ke storage/logs/mail.log.\n";
    echo "Isi dulu kredensial SMTP di .env, lalu jalankan ulang perintah ini.\n";
    exit(1);
}

if ($dari !== '' && $user !== '' && strcasecmp($dari, $user) !== 0) {
    echo "Peringatan: MAIL_FROM_ADDRESS berbeda dengan SMTP_USER.\n";
    echo "Gmail akan menimpa alamat pengirim dengan {$user} kecuali alamat tersebut\n";
    echo "sudah didaftarkan sebagai \"Send mail as\" di akun Gmail Anda.\n\n";
}

$mail = new PHPMailer\PHPMailer\PHPMailer(true);

// Tampilkan percakapan SMTP supaya kegagalan bisa ditelusuri
$mail->SMTPDebug = 2;
$mail->Debugoutput = function ($pesan, $level) {
    echo '  smtp> ' . rtrim($pesan) . "\n";
};

try {
    $mail->isSMTP();
    $mail->Host = $host;
    $mail->Port = (int)$port;
    $mail->CharSet = 'UTF-8';
    $mail->Timeout = 20;

    if (trim($user) !== '') {
        $mail->SMTPAuth = true;
        $mail->Username = $user;
        $mail->Password = $pass;
    }

    if ($enkripsi === 'ssl') {
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
    } elseif ($enkripsi === 'tls') {
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
    } else {
        $mail->SMTPSecure = false;
        $mail->SMTPAutoTLS = false;
    }

    $mail->setFrom($dari, $namaDari);
    $mail->addAddress($tujuan);

    $mail->isHTML(true);
    $mail->Subject = 'Uji Konfigurasi Email SIMAGANG';
    $mail->Body = '<p>Konfigurasi SMTP SIMAGANG sudah benar.</p>'
        . '<p>Email ini dikirim oleh <code>tools/kirim-email-uji.php</code> pada '
        . date('d F Y H:i:s') . '.</p>';
    $mail->AltBody = "Konfigurasi SMTP SIMAGANG sudah benar.\n"
        . "Email ini dikirim oleh tools/kirim-email-uji.php pada " . date('d F Y H:i:s') . ".";

    $mail->send();

    echo "\nBERHASIL. Email uji terkirim ke {$tujuan}.\n";
    echo "Periksa kotak masuk (dan folder Spam) alamat tersebut.\n";
    exit(0);
} catch (\Throwable $e) {
    echo "\nGAGAL mengirim email.\n";
    echo "Pesan: " . ($mail->ErrorInfo !== '' ? $mail->ErrorInfo : $e->getMessage()) . "\n\n";
    echo "Penyebab yang paling sering:\n";
    echo "  - 535-5.7.8 Username and Password not accepted\n";
    echo "      SMTP_PASS bukan App Password, atau App Password sudah dicabut.\n";
    echo "  - Could not connect to SMTP host\n";
    echo "      Port diblokir jaringan/antivirus, atau SMTP_PORT tidak cocok dengan\n";
    echo "      SMTP_ENCRYPTION (587 untuk tls, 465 untuk ssl).\n";
    echo "  - SMTP connect() failed / certificate verify failed\n";
    echo "      Jam sistem meleset jauh, atau ekstensi openssl PHP belum aktif.\n";
    exit(1);
}
