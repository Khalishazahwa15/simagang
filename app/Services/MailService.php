<?php
namespace App\Services;

use App\Core\Env;
use App\Core\Logger;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class MailService {
    /**
     * Kirim email lewat SMTP bila kredensial tersedia di .env.
     * Bila SMTP_HOST kosong, isi email ditulis ke storage/logs/mail.log
     * supaya alur tetap dapat diuji di lingkungan pengembangan.
     */
    public function kirim($tujuan, $namaTujuan, $subjek, $isiHtml, $isiTeks) {
        $host = Env::get('SMTP_HOST', '');

        if (trim($host) === '') {
            $this->tulisKeLog($tujuan, $subjek, $isiTeks);
            return true;
        }

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = $host;
            $mail->Port = (int)Env::get('SMTP_PORT', 587);
            $mail->CharSet = 'UTF-8';
            $mail->Timeout = 10;

            $pengguna = Env::get('SMTP_USER', '');
            if (trim($pengguna) !== '') {
                $mail->SMTPAuth = true;
                $mail->Username = $pengguna;
                $mail->Password = Env::get('SMTP_PASS', '');
            }

            $enkripsi = strtolower(Env::get('SMTP_ENCRYPTION', 'tls'));
            if ($enkripsi === 'ssl') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } elseif ($enkripsi === 'tls') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            } else {
                $mail->SMTPSecure = false;
                $mail->SMTPAutoTLS = false;
            }

            $mail->setFrom(
                Env::get('MAIL_FROM_ADDRESS', $pengguna),
                Env::get('MAIL_FROM_NAME', 'SIMAGANG Bappeda Lampung')
            );
            $mail->addAddress($tujuan, $namaTujuan);

            $mail->isHTML(true);
            $mail->Subject = $subjek;
            $mail->Body = $isiHtml;
            $mail->AltBody = $isiTeks;

            $mail->send();
            return true;
        } catch (PHPMailerException $e) {
            Logger::error('EMAIL GAGAL', $mail->ErrorInfo ?: $e->getMessage());
            return false;
        } catch (\Exception $e) {
            Logger::error('EMAIL GAGAL', $e->getMessage());
            return false;
        }
    }

    private function tulisKeLog($tujuan, $subjek, $isiTeks) {
        $logDir = ROOT_PATH . '/storage/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }

        $pesan = "[" . date('Y-m-d H:i:s') . "] SMTP belum dikonfigurasi. Email untuk {$tujuan}\n";
        $pesan .= "Subjek: {$subjek}\n";
        $pesan .= $isiTeks . "\n";
        $pesan .= "--------------------------------------------------\n";

        file_put_contents($logDir . '/mail.log', $pesan, FILE_APPEND);
    }
}
