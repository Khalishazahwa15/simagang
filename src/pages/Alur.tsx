import type { Page } from '../App'

const C = {
  green: '#0F5C4D',
  greenDark: '#08483D',
  greenMid: '#0C4E41',
  greenSoft: '#EAF4EF',
  greenPale: '#F2F8F6',
  gold: '#E9B949',
  goldSoft: '#FFF4D8',
  goldPale: '#FFF9EB',
  ivory: '#FAF9F5',
  offwhite: '#F3F6F1',
  ink: '#18211F',
  muted: '#66706C',
  rule: '#E5E8E3',
  ruleSoft: '#F0F2F0',
}

const STEPS = [
  {
    num: '01',
    title: 'Buat Akun & Masuk',
    subtitle: 'Registrasi & Autentikasi',
    desc: 'Daftarkan akun menggunakan email aktif dan Nomor Induk Mahasiswa (NIM/NPM). Setelah verifikasi email, Anda dapat masuk ke portal SIMAGANG.',
    detail: [
      'Email aktif yang dapat dihubungi',
      'NIM/NPM dari institusi Anda',
      'Kata sandi minimal 8 karakter',
    ],
    badge: 'Publik',
    duration: '5 menit',
  },
  {
    num: '02',
    title: 'Isi Formulir Pengajuan',
    subtitle: 'Input Data & Dokumen',
    desc: 'Lengkapi formulir empat-langkah: Data Diri, Preferensi Divisi, Unggah Dokumen, dan Review. Pengajuan hanya dapat dikirim setelah semua tahap selesai.',
    detail: [
      'Data diri dan institusi asal',
      'Rencana periode magang (awal–akhir)',
      'Pilihan divisi yang diminati',
      'Tiga dokumen wajib diunggah (PDF/JPG)',
    ],
    badge: 'Mahasiswa',
    duration: '15–30 menit',
  },
  {
    num: '03',
    title: 'Pemeriksaan Berkas',
    subtitle: 'Verifikasi Sekretariat',
    desc: 'Tim Sekretariat memeriksa kelengkapan dan keabsahan dokumen yang diunggah. Jika berkas perlu diperbaiki, Anda akan mendapat notifikasi untuk mengunggah ulang.',
    detail: [
      'Pemeriksaan kelengkapan dokumen',
      'Validasi data institusi dan periode',
      'Notifikasi dikirim jika revisi diperlukan',
      'Anda dapat mengunggah ulang dokumen yang diminta',
    ],
    badge: 'Sekretariat',
    duration: '1–3 hari kerja',
  },
  {
    num: '04',
    title: 'Pengecekan Kebutuhan Divisi',
    subtitle: 'Konfirmasi Kapasitas',
    desc: 'Setelah berkas dinyatakan lengkap, Sekretariat mengkonfirmasi ketersediaan kapasitas divisi yang dipilih. Jika divisi penuh atau tidak menerima, pengajuan dapat ditolak pada tahap ini.',
    detail: [
      'Pengecekan kapasitas divisi yang dipilih',
      'Jika diterima: langsung ke status "Diterima"',
      'Jika ditolak: alasan wajib dicantumkan',
      'Notifikasi dikirim ke mahasiswa',
    ],
    badge: 'Sekretariat',
    duration: '1–2 hari kerja',
  },
  {
    num: '05',
    title: 'Pelaksanaan Magang',
    subtitle: 'Status Aktif Magang',
    desc: 'Setelah diterima, Sekretariat menandai dimulainya pelaksanaan magang. Anda berstatus "Sedang Magang" selama periode berlangsung. Tersedia mekanisme pengunduran diri jika diperlukan.',
    detail: [
      'Surat penerimaan diunggah oleh Sekretariat',
      'Status berubah menjadi "Sedang Magang"',
      'Pengunduran diri dapat dilakukan melalui portal',
      'Alasan pengunduran diri wajib diisi',
    ],
    badge: 'Aktif',
    duration: 'Sesuai periode',
  },
  {
    num: '06',
    title: 'Penyelesaian & Dokumen Akhir',
    subtitle: 'Arsip & Penutupan',
    desc: 'Setelah periode magang berakhir, Sekretariat menandai status "Selesai" dan mengunggah dokumen akhir (sertifikat, surat keterangan). Dokumen tersedia untuk diunduh melalui portal.',
    detail: [
      'Sekretariat menandai selesai',
      'Dokumen akhir diunggah (sertifikat, SK, dll.)',
      'Mahasiswa dapat mengunduh dokumen resmi',
      'Riwayat magang tersimpan dalam sistem',
    ],
    badge: 'Selesai',
    duration: '1–3 hari kerja',
  },
]

const STATUS_MAP = [
  { key: 'Draft', color: C.muted, bg: '#F3F5F3', desc: 'Formulir belum dikirim' },
  { key: 'Diajukan', color: '#2563EB', bg: '#EFF6FF', desc: 'Menunggu pemeriksaan' },
  { key: 'Diperiksa', color: '#7C3AED', bg: '#F5F3FF', desc: 'Sedang diperiksa Sekretariat' },
  { key: 'Perlu Revisi', color: '#B45309', bg: '#FFFBEB', desc: 'Dokumen perlu diperbaiki' },
  { key: 'Cek Kebutuhan Divisi', color: '#0369A1', bg: '#F0F9FF', desc: 'Berkas lengkap, konfirmasi divisi' },
  { key: 'Diterima', color: C.greenMid, bg: C.greenSoft, desc: 'Pengajuan diterima' },
  { key: 'Ditolak', color: '#9B2C2C', bg: '#FEF2F2', desc: 'Pengajuan ditolak' },
  { key: 'Sedang Magang', color: C.green, bg: C.greenPale, desc: 'Periode magang aktif berjalan' },
  { key: 'Mengundurkan Diri', color: '#92400E', bg: '#FFFBEB', desc: 'Mengundurkan diri dari magang' },
  { key: 'Selesai', color: C.green, bg: C.greenSoft, desc: 'Magang selesai, dokumen tersedia' },
  { key: 'Dibatalkan', color: C.muted, bg: '#F3F5F3', desc: 'Pengajuan dibatalkan mahasiswa' },
]

const BADGE_COLORS: Record<string, { bg: string; text: string }> = {
  'Publik': { bg: C.goldSoft, text: '#92400E' },
  'Mahasiswa': { bg: C.greenSoft, text: C.green },
  'Sekretariat': { bg: '#EFF6FF', text: '#2563EB' },
  'Aktif': { bg: C.greenSoft, text: C.greenMid },
  'Selesai': { bg: '#F0F9FF', text: '#0369A1' },
}

interface Props { navigate: (p: Page) => void }

export default function Alur({ navigate }: Props) {
  return (
    <div style={{ background: C.offwhite, minHeight: '100vh' }}>
      {/* Page header */}
      <div style={{ background: C.green, paddingTop: 72, paddingBottom: 64, position: 'relative', overflow: 'hidden' }}>
        <div style={{ position: 'absolute', inset: 0, backgroundImage: `repeating-linear-gradient(90deg,transparent,transparent 80px,rgba(255,255,255,0.018) 80px,rgba(255,255,255,0.018) 81px)` }} />
        <div style={{ position: 'absolute', bottom: 0, left: 0, right: 0, height: 3, background: C.gold }} />
        <div style={{ maxWidth: 900, margin: '0 auto', padding: '0 40px', position: 'relative', zIndex: 1 }}>
          <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.14em', color: C.gold, textTransform: 'uppercase', marginBottom: 16 }}>Panduan Lengkap</div>
          <h1 style={{ fontFamily: 'var(--font-display)', fontSize: 'clamp(36px,5vw,56px)', fontWeight: 400, color: C.offwhite, lineHeight: 1.1, letterSpacing: '-0.02em', marginBottom: 16 }}>
            Alur Pengajuan Magang
          </h1>
          <p style={{ fontFamily: 'var(--font-body)', fontSize: 17, color: 'rgba(255,255,255,0.6)', lineHeight: 1.7, maxWidth: 580 }}>
            Panduan tahap demi tahap untuk mengajukan magang di Bappeda Provinsi Lampung melalui sistem SIMAGANG.
          </p>
        </div>
      </div>

      <div style={{ maxWidth: 900, margin: '0 auto', padding: '64px 40px' }}>
        {/* Quick overview */}
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3,1fr)', gap: 20, marginBottom: 64 }}>
          {[
            { label: 'Total Tahap', value: '6 Langkah', sub: 'dari daftar hingga selesai' },
            { label: 'Estimasi Proses', value: '5–10 Hari', sub: 'hari kerja sejak pengajuan' },
            { label: 'Sistem Buka', value: 'Sepanjang Tahun', sub: 'tidak ada periode pendaftaran tetap' },
          ].map(item => (
            <div key={item.label} style={{ background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 12, padding: '24px 28px' }}>
              <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.10em', color: C.muted, textTransform: 'uppercase', marginBottom: 8 }}>{item.label}</div>
              <div style={{ fontFamily: 'var(--font-display)', fontSize: 28, color: C.green, letterSpacing: '-0.01em', marginBottom: 4 }}>{item.value}</div>
              <div style={{ fontFamily: 'var(--font-body)', fontSize: 13, color: C.muted }}>{item.sub}</div>
            </div>
          ))}
        </div>

        {/* Steps */}
        <div style={{ marginBottom: 80 }}>
          <h2 style={{ fontFamily: 'var(--font-display)', fontSize: 30, fontWeight: 400, color: C.ink, letterSpacing: '-0.01em', marginBottom: 40 }}>Tahapan Proses</h2>
          <div style={{ display: 'flex', flexDirection: 'column', gap: 0 }}>
            {STEPS.map((step, i) => {
              const badge = BADGE_COLORS[step.badge] || { bg: C.goldSoft, text: '#92400E' }
              return (
                <div key={step.num} style={{ display: 'grid', gridTemplateColumns: '80px 1fr', gap: 0 }}>
                  {/* Left: number + connector */}
                  <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', paddingTop: 4 }}>
                    <div style={{ width: 48, height: 48, borderRadius: '50%', background: C.green, display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                      <span style={{ fontFamily: 'var(--font-mono)', fontSize: 13, color: C.gold, letterSpacing: '0.05em' }}>{step.num}</span>
                    </div>
                    {i < STEPS.length - 1 && (
                      <div style={{ width: 2, flex: 1, background: `linear-gradient(to bottom, ${C.green}, ${C.ruleSoft})`, minHeight: 40, marginTop: 4 }} />
                    )}
                  </div>

                  {/* Right: content */}
                  <div style={{ paddingLeft: 24, paddingBottom: i < STEPS.length - 1 ? 48 : 0 }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 12, marginBottom: 6 }}>
                      <h3 style={{ fontFamily: 'var(--font-display)', fontSize: 22, fontWeight: 400, color: C.ink, letterSpacing: '-0.01em', margin: 0 }}>{step.title}</h3>
                      <span style={{ background: badge.bg, color: badge.text, fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.08em', padding: '3px 10px', borderRadius: 99, textTransform: 'uppercase' }}>{step.badge}</span>
                    </div>
                    <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.10em', color: C.muted, textTransform: 'uppercase', marginBottom: 10 }}>{step.subtitle} · {step.duration}</div>
                    <p style={{ fontFamily: 'var(--font-body)', fontSize: 15, color: '#3D4844', lineHeight: 1.75, marginBottom: 16 }}>{step.desc}</p>
                    <div style={{ background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 10, padding: '16px 20px' }}>
                      {step.detail.map((d, di) => (
                        <div key={di} style={{ display: 'flex', gap: 10, alignItems: 'flex-start', marginBottom: di < step.detail.length - 1 ? 8 : 0 }}>
                          <div style={{ width: 6, height: 6, borderRadius: '50%', background: C.gold, flexShrink: 0, marginTop: 7 }} />
                          <span style={{ fontFamily: 'var(--font-body)', fontSize: 14, color: C.muted, lineHeight: 1.6 }}>{d}</span>
                        </div>
                      ))}
                    </div>
                  </div>
                </div>
              )
            })}
          </div>
        </div>

        {/* Status glossary */}
        <div style={{ marginBottom: 64 }}>
          <h2 style={{ fontFamily: 'var(--font-display)', fontSize: 30, fontWeight: 400, color: C.ink, letterSpacing: '-0.01em', marginBottom: 8 }}>Kamus Status Pengajuan</h2>
          <p style={{ fontFamily: 'var(--font-body)', fontSize: 15, color: C.muted, marginBottom: 28, lineHeight: 1.7 }}>
            Setiap pengajuan memiliki status yang mencerminkan posisinya dalam alur. Status ditampilkan secara real-time di dashboard Anda.
          </p>
          <div style={{ display: 'flex', flexDirection: 'column', gap: 2 }}>
            {STATUS_MAP.map(s => (
              <div key={s.key} style={{ display: 'flex', alignItems: 'center', gap: 16, padding: '12px 16px', background: C.ivory, borderRadius: 8, border: `1px solid ${C.ruleSoft}` }}>
                <span style={{ background: s.bg, color: s.color, fontFamily: 'var(--font-mono)', fontSize: 12, fontWeight: 700, padding: '4px 12px', borderRadius: 6, whiteSpace: 'nowrap', minWidth: 210, display: 'inline-block' }}>{s.key}</span>
                <span style={{ fontFamily: 'var(--font-body)', fontSize: 14, color: C.muted }}>{s.desc}</span>
              </div>
            ))}
          </div>
        </div>

        {/* CTA */}
        <div style={{ background: C.green, borderRadius: 16, padding: '40px 48px', display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 32 }}>
          <div>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.12em', color: C.gold, textTransform: 'uppercase', marginBottom: 8 }}>Siap Mengajukan?</div>
            <h3 style={{ fontFamily: 'var(--font-display)', fontSize: 26, fontWeight: 400, color: C.offwhite, lineHeight: 1.2, margin: 0 }}>Mulai proses pengajuan Anda sekarang</h3>
          </div>
          <div style={{ display: 'flex', gap: 12, flexShrink: 0 }}>
            <button onClick={() => navigate('persyaratan')} style={{ padding: '12px 24px', background: 'rgba(255,255,255,0.1)', border: '1px solid rgba(255,255,255,0.25)', borderRadius: 8, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 14, fontWeight: 600, color: C.offwhite }}>
              Lihat Persyaratan
            </button>
            <button onClick={() => navigate('login')} style={{ padding: '12px 24px', background: C.gold, border: 'none', borderRadius: 8, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 14, fontWeight: 700, color: C.greenDark }}>
              Ajukan Magang
            </button>
          </div>
        </div>
      </div>
    </div>
  )
}
