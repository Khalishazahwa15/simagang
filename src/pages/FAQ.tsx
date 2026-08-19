import { useState } from 'react'
import { ChevronDown, MessageCircle } from 'lucide-react'
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

interface FAQ { q: string; a: string }
interface Category { id: string; label: string; items: FAQ[] }

const CATEGORIES: Category[] = [
  {
    id: 'umum',
    label: 'Umum',
    items: [
      {
        q: 'Apa itu SIMAGANG?',
        a: 'SIMAGANG (Sistem Informasi Pengelolaan Magang Mahasiswa) adalah portal digital resmi Bappeda Provinsi Lampung untuk mengelola pengajuan magang mahasiswa. Sistem ini menggantikan proses manual berbasis kertas dan memungkinkan mahasiswa mengajukan, memantau, dan menerima dokumen magang secara daring.',
      },
      {
        q: 'Apakah SIMAGANG hanya untuk mahasiswa dari Lampung?',
        a: 'Tidak. SIMAGANG terbuka untuk mahasiswa aktif dari perguruan tinggi terakreditasi di seluruh Indonesia, tidak terbatas pada institusi di Lampung. Namun, pelaksanaan magang berlangsung secara langsung (tatap muka) di kantor Bappeda Provinsi Lampung.',
      },
      {
        q: 'Kapan pendaftaran magang dibuka?',
        a: 'Sistem SIMAGANG menggunakan pendaftaran bergulir (rolling) — tidak ada periode pendaftaran tetap. Pengajuan dapat dilakukan kapan saja sepanjang tahun, bergantung pada ketersediaan kapasitas divisi yang dituju.',
      },
      {
        q: 'Berapa lama proses pengajuan hingga mendapat keputusan?',
        a: 'Estimasi proses: pemeriksaan berkas memerlukan 1–3 hari kerja, ditambah 1–2 hari kerja untuk konfirmasi kebutuhan divisi. Total proses biasanya 3–7 hari kerja sejak pengajuan dikirim, asalkan dokumen lengkap dan tidak memerlukan revisi.',
      },
    ],
  },
  {
    id: 'akun',
    label: 'Akun & Registrasi',
    items: [
      {
        q: 'Dokumen apa yang diperlukan untuk mendaftar akun?',
        a: 'Pendaftaran akun tidak memerlukan dokumen — cukup email aktif dan NIM/NPM. Dokumen-dokumen resmi (surat lamaran, CV, transkrip) diunggah saat mengisi formulir pengajuan magang, bukan saat registrasi akun.',
      },
      {
        q: 'Bisakah satu akun digunakan untuk beberapa pengajuan?',
        a: 'Ya. Satu akun mahasiswa dapat digunakan untuk mengajukan magang berkali-kali — misalnya jika pengajuan sebelumnya ditolak atau sudah selesai. Namun, hanya satu pengajuan aktif yang diperbolehkan dalam satu waktu.',
      },
      {
        q: 'Bagaimana jika saya lupa kata sandi?',
        a: 'Gunakan fitur "Lupa kata sandi?" di halaman masuk. Tautan reset kata sandi akan dikirim ke email yang terdaftar. Jika mengalami kesulitan, hubungi Sekretariat Bappeda Provinsi Lampung melalui email resmi.',
      },
    ],
  },
  {
    id: 'pengajuan',
    label: 'Pengajuan & Dokumen',
    items: [
      {
        q: 'Dokumen apa saja yang wajib diunggah?',
        a: 'Tiga dokumen wajib: (1) Surat Lamaran Magang ditujukan kepada Kepala Bappeda Provinsi Lampung, (2) Curriculum Vitae (CV) terbaru, dan (3) Transkrip Nilai akademik resmi. Selain itu, terdapat dokumen opsional seperti surat pengantar dari kampus atau portofolio.',
      },
      {
        q: 'Format file apa yang diterima?',
        a: 'Dokumen diterima dalam format PDF (untuk semua dokumen) dan JPG/PNG (untuk transkrip nilai atau sertifikat). Ukuran maksimum per file umumnya 2–5 MB tergantung jenis dokumennya. Detail format tersedia di halaman Persyaratan.',
      },
      {
        q: 'Apakah dokumen perlu dilegalisir?',
        a: 'Tidak. Transkrip nilai tidak perlu dilegalisir — cukup salinan yang terbaca jelas dan mencantumkan identitas resmi institusi. Untuk surat lamaran, disarankan menggunakan kop surat perguruan tinggi jika tersedia.',
      },
      {
        q: 'Bisakah saya mengubah formulir setelah dikirim?',
        a: 'Tidak bisa secara langsung. Setelah formulir dikirim (status "Diajukan"), formulir tidak dapat diedit. Jika Sekretariat meminta revisi dokumen (status "Perlu Revisi"), Anda dapat mengunggah ulang dokumen yang diminta melalui portal.',
      },
      {
        q: 'Bagaimana jika saya ingin membatalkan pengajuan?',
        a: 'Anda dapat membatalkan pengajuan selama masih berstatus Draft atau Diajukan (sebelum diproses). Setelah memasuki tahap pemeriksaan, pembatalan tidak dapat dilakukan mandiri — hubungi Sekretariat. Jika sudah Sedang Magang dan ingin berhenti, gunakan fitur Pengunduran Diri di dashboard.',
      },
    ],
  },
  {
    id: 'proses',
    label: 'Proses & Status',
    items: [
      {
        q: 'Apa artinya status "Perlu Revisi"?',
        a: 'Status "Perlu Revisi" berarti Sekretariat menemukan ketidaklengkapan atau ketidaksesuaian pada dokumen yang diunggah. Anda akan menerima notifikasi beserta catatan dari Sekretariat yang menjelaskan apa yang perlu diperbaiki. Unggah ulang dokumen yang diminta melalui panel di dashboard.',
      },
      {
        q: 'Apa artinya status "Cek Kebutuhan Divisi"?',
        a: 'Status ini menunjukkan bahwa berkas Anda sudah dinyatakan lengkap dan valid, dan Sekretariat sedang mengkonfirmasi ketersediaan kapasitas divisi yang Anda pilih. Jika divisi tersedia, status akan berubah menjadi "Diterima". Jika divisi penuh atau tidak menerima saat ini, pengajuan dapat ditolak pada tahap ini.',
      },
      {
        q: 'Apakah ada surat penolakan resmi jika pengajuan ditolak?',
        a: 'Tidak ada surat penolakan formal yang diterbitkan. Jika pengajuan ditolak, Anda akan menerima notifikasi melalui sistem beserta alasan penolakan yang wajib dicantumkan oleh Sekretariat. Anda dapat mengajukan kembali setelah memenuhi persyaratan atau memilih divisi yang berbeda.',
      },
      {
        q: 'Apakah saya perlu mengkonfirmasi setelah diterima?',
        a: 'Tidak diperlukan konfirmasi dari mahasiswa. Begitu status berubah menjadi "Diterima", Anda tinggal menunggu Sekretariat mengunggah surat penerimaan dan menandai mulainya pelaksanaan magang.',
      },
    ],
  },
  {
    id: 'pelaksanaan',
    label: 'Pelaksanaan & Selesai',
    items: [
      {
        q: 'Bagaimana cara mengundurkan diri saat sedang magang?',
        a: 'Jika Anda perlu mengundurkan diri saat berstatus "Sedang Magang", gunakan fitur Pengunduran Diri di dashboard mahasiswa. Anda wajib mengisi alasan pengunduran diri. Status akan berubah menjadi "Mengundurkan Diri". Segera hubungi supervisor di divisi dan informasikan secara langsung.',
      },
      {
        q: 'Kapan dokumen akhir (sertifikat/surat keterangan) tersedia?',
        a: 'Dokumen akhir diunggah oleh Sekretariat setelah menandai status "Selesai". Biasanya tersedia dalam 1–3 hari kerja setelah periode magang berakhir. Anda dapat mengunduhnya melalui tab "Dokumen" di dashboard.',
      },
      {
        q: 'Apakah saya bisa melihat riwayat magang sebelumnya?',
        a: 'Ya. Semua riwayat pengajuan dan magang yang pernah Anda lakukan tersimpan di sistem dan dapat dilihat di dashboard mahasiswa, termasuk dokumen yang pernah diproses.',
      },
    ],
  },
]

interface Props { navigate: (p: Page) => void }

export default function FAQ({ navigate }: Props) {
  const [activeCategory, setActiveCategory] = useState('umum')
  const [openIdx, setOpenIdx] = useState<number | null>(0)

  const category = CATEGORIES.find(c => c.id === activeCategory)!

  return (
    <div style={{ background: C.offwhite, minHeight: '100vh' }}>
      {/* Header */}
      <div style={{ background: C.green, paddingTop: 72, paddingBottom: 64, position: 'relative', overflow: 'hidden' }}>
        <div style={{ position: 'absolute', inset: 0, backgroundImage: `repeating-linear-gradient(90deg,transparent,transparent 80px,rgba(255,255,255,0.018) 80px,rgba(255,255,255,0.018) 81px)` }} />
        <div style={{ position: 'absolute', bottom: 0, left: 0, right: 0, height: 3, background: C.gold }} />
        <div style={{ maxWidth: 960, margin: '0 auto', padding: '0 40px', position: 'relative', zIndex: 1 }}>
          <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.14em', color: C.gold, textTransform: 'uppercase', marginBottom: 16 }}>Bantuan & Informasi</div>
          <h1 style={{ fontFamily: 'var(--font-display)', fontSize: 'clamp(36px,5vw,56px)', fontWeight: 400, color: C.offwhite, lineHeight: 1.1, letterSpacing: '-0.02em', marginBottom: 16 }}>
            Pertanyaan yang Sering Diajukan
          </h1>
          <p style={{ fontFamily: 'var(--font-body)', fontSize: 17, color: 'rgba(255,255,255,0.6)', lineHeight: 1.7, maxWidth: 580 }}>
            Jawaban atas pertanyaan umum seputar pengajuan magang, persyaratan, dan penggunaan sistem SIMAGANG.
          </p>
        </div>
      </div>

      <div style={{ maxWidth: 960, margin: '0 auto', padding: '64px 40px', display: 'grid', gridTemplateColumns: '220px 1fr', gap: 48 }}>
        {/* Sidebar */}
        <div>
          <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.10em', color: C.muted, textTransform: 'uppercase', marginBottom: 12 }}>Kategori</div>
          {CATEGORIES.map(cat => (
            <button key={cat.id} onClick={() => { setActiveCategory(cat.id); setOpenIdx(null) }} style={{ display: 'block', width: '100%', textAlign: 'left', padding: '10px 14px', background: activeCategory === cat.id ? C.greenSoft : 'transparent', border: 'none', borderRadius: 8, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 14, fontWeight: activeCategory === cat.id ? 700 : 500, color: activeCategory === cat.id ? C.green : C.muted, marginBottom: 2, transition: 'all 0.15s' }}>
              {cat.label}
              <span style={{ float: 'right', fontFamily: 'var(--font-mono)', fontSize: 11, color: activeCategory === cat.id ? C.greenMid : '#B0B8B5', background: activeCategory === cat.id ? C.offwhite : C.ruleSoft, borderRadius: 99, padding: '1px 7px' }}>{cat.items.length}</span>
            </button>
          ))}

          {/* Contact card */}
          <div style={{ marginTop: 32, background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 12, padding: '20px 18px' }}>
            <MessageCircle size={20} color={C.green} style={{ marginBottom: 10 }} />
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 700, color: C.ink, marginBottom: 6 }}>Belum menemukan jawaban?</div>
            <p style={{ fontFamily: 'var(--font-body)', fontSize: 12.5, color: C.muted, lineHeight: 1.65, marginBottom: 14 }}>
              Hubungi Sekretariat Bappeda Provinsi Lampung melalui email resmi atau kunjungi kantor pada jam kerja.
            </p>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.08em', color: C.muted, textTransform: 'uppercase', marginBottom: 4 }}>Email</div>
            <div style={{ fontFamily: 'var(--font-mono)', fontSize: 12, color: C.green }}>bappeda@lampungprov.go.id</div>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.08em', color: C.muted, textTransform: 'uppercase', marginTop: 10, marginBottom: 4 }}>Jam Kerja</div>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 12, color: C.muted }}>Senin–Jumat, 08.00–16.00 WIB</div>
          </div>
        </div>

        {/* FAQ list */}
        <div>
          <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.10em', color: C.muted, textTransform: 'uppercase', marginBottom: 20 }}>
            {category.label} · {category.items.length} pertanyaan
          </div>
          {category.items.map((item, i) => {
            const open = openIdx === i
            return (
              <div key={i} style={{ border: `1px solid ${open ? C.green + '40' : C.rule}`, borderRadius: 12, marginBottom: 10, overflow: 'hidden', transition: 'border-color 0.2s' }}>
                <button onClick={() => setOpenIdx(open ? null : i)} style={{ width: '100%', display: 'flex', alignItems: 'flex-start', gap: 16, padding: '20px 24px', background: open ? C.greenSoft : C.ivory, border: 'none', cursor: 'pointer', textAlign: 'left' }}>
                  <span style={{ fontFamily: 'var(--font-mono)', fontSize: 12, color: C.gold, flexShrink: 0, marginTop: 3, fontWeight: 700 }}>Q</span>
                  <span style={{ fontFamily: 'var(--font-body)', fontSize: 15.5, fontWeight: 600, color: C.ink, lineHeight: 1.5, flex: 1 }}>{item.q}</span>
                  <ChevronDown size={18} color={C.muted} style={{ flexShrink: 0, marginTop: 3, transform: open ? 'rotate(180deg)' : 'none', transition: 'transform 0.2s' }} />
                </button>
                {open && (
                  <div style={{ padding: '0 24px 24px 64px' }}>
                    <p style={{ fontFamily: 'var(--font-body)', fontSize: 14.5, color: '#3D4844', lineHeight: 1.8, margin: 0 }}>{item.a}</p>
                  </div>
                )}
              </div>
            )
          })}
        </div>
      </div>

      {/* CTA */}
      <div style={{ maxWidth: 960, margin: '0 auto 80px', padding: '0 40px' }}>
        <div style={{ background: C.green, borderRadius: 16, padding: '36px 48px', display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 32 }}>
          <div>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.12em', color: C.gold, textTransform: 'uppercase', marginBottom: 8 }}>Portal SIMAGANG</div>
            <h3 style={{ fontFamily: 'var(--font-display)', fontSize: 26, fontWeight: 400, color: C.offwhite, lineHeight: 1.2, margin: 0 }}>Ajukan magang Anda secara digital</h3>
          </div>
          <div style={{ display: 'flex', gap: 12, flexShrink: 0 }}>
            <button onClick={() => navigate('alur')} style={{ padding: '12px 24px', background: 'rgba(255,255,255,0.1)', border: '1px solid rgba(255,255,255,0.25)', borderRadius: 8, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 14, fontWeight: 600, color: C.offwhite }}>
              Lihat Alur
            </button>
            <button onClick={() => navigate('register')} style={{ padding: '12px 24px', background: C.gold, border: 'none', borderRadius: 8, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 14, fontWeight: 700, color: C.greenDark }}>
              Daftar Sekarang
            </button>
          </div>
        </div>
      </div>
    </div>
  )
}
