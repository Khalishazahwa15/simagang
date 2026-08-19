import { useState } from 'react'
import { Check, FileText, Upload, AlertCircle } from 'lucide-react'
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

const ELIGIBLE = [
  'Mahasiswa aktif terdaftar di perguruan tinggi negeri atau swasta yang terakreditasi di Indonesia',
  'Minimal semester 3 (enam SKS semester sebelumnya terpenuhi) atau sesuai kebijakan program studi',
  'Tidak sedang menjalani magang, PKL, atau kerja praktik di instansi lain pada periode yang sama',
  'Memiliki nilai akademik yang memenuhi standar minimum (IPK ≥ 2,75 atau sesuai kebijakan program studi)',
  'Mendapat persetujuan atau rekomendasi dari Dosen Pembimbing / Dosen Wali',
  'Bersedia mematuhi tata tertib dan aturan kerja di lingkungan Bappeda Provinsi Lampung',
]

const DOCS_WAJIB = [
  {
    title: 'Surat Lamaran Magang',
    code: 'DOC-01',
    desc: 'Surat lamaran yang ditujukan kepada Kepala Bappeda Provinsi Lampung. Ditandatangani mahasiswa dan mencantumkan rencana periode magang.',
    format: 'PDF',
    maxSize: '2 MB',
    notes: 'Gunakan kop surat perguruan tinggi jika tersedia',
  },
  {
    title: 'Curriculum Vitae (CV)',
    code: 'DOC-02',
    desc: 'CV terbaru yang memuat data diri, riwayat pendidikan, pengalaman organisasi, dan keterampilan relevan.',
    format: 'PDF',
    maxSize: '2 MB',
    notes: 'Cantumkan nomor telepon dan email aktif',
  },
  {
    title: 'Transkrip Nilai',
    code: 'DOC-03',
    desc: 'Transkrip nilai akademik resmi yang dikeluarkan oleh institusi perguruan tinggi. Harus menampilkan IPK terkini.',
    format: 'PDF / JPG',
    maxSize: '3 MB',
    notes: 'Transkrip tidak perlu dilegalisir, namun harus terbaca jelas',
  },
]

const DOCS_OPSIONAL = [
  {
    title: 'Surat Pengantar dari Kampus',
    code: 'OPT-01',
    desc: 'Surat pengantar resmi dari Dekan, Ketua Program Studi, atau pejabat berwenang di perguruan tinggi.',
    format: 'PDF',
    maxSize: '2 MB',
  },
  {
    title: 'Portofolio Karya / Proposal Riset',
    code: 'OPT-02',
    desc: 'Untuk magang di divisi perencanaan atau riset, portofolio atau proposal dapat memperkuat pengajuan.',
    format: 'PDF',
    maxSize: '5 MB',
  },
  {
    title: 'Sertifikat Pelatihan / Penghargaan',
    code: 'OPT-03',
    desc: 'Sertifikat kompetensi, pelatihan, atau penghargaan yang relevan dengan divisi yang dituju.',
    format: 'PDF / JPG',
    maxSize: '2 MB',
  },
]

const DIVISI = [
  { nama: 'Perencanaan Pembangunan Daerah', fokus: 'Analisis kebijakan, RPJMD, Renstra' },
  { nama: 'Monitoring dan Evaluasi', fokus: 'Monev program, pelaporan kinerja' },
  { nama: 'Pemerintahan dan Pembangunan Manusia', fokus: 'Sosial, pendidikan, kesehatan, SDM' },
  { nama: 'Perekonomian dan SDA', fokus: 'Pertanian, pariwisata, ekonomi daerah' },
  { nama: 'Infrastruktur dan Kewilayahan', fokus: 'Tata ruang, infrastruktur, lingkungan' },
  { nama: 'Data dan Informasi', fokus: 'Statistik, sistem informasi, GIS' },
  { nama: 'Sekretariat', fokus: 'Administrasi, kearsipan, persuratan' },
]

interface Props { navigate: (p: Page) => void }

export default function Persyaratan({ navigate }: Props) {
  const [openSection, setOpenSection] = useState<string | null>('eligible')

  const Section = ({ id, title, children }: { id: string; title: string; children: React.ReactNode }) => {
    const open = openSection === id
    return (
      <div style={{ border: `1px solid ${C.rule}`, borderRadius: 12, marginBottom: 12, overflow: 'hidden' }}>
        <button onClick={() => setOpenSection(open ? null : id)} style={{ width: '100%', display: 'flex', alignItems: 'center', justifyContent: 'space-between', padding: '20px 24px', background: open ? C.greenSoft : C.ivory, border: 'none', cursor: 'pointer', textAlign: 'left' }}>
          <span style={{ fontFamily: 'var(--font-display)', fontSize: 20, fontWeight: 400, color: C.ink, letterSpacing: '-0.01em' }}>{title}</span>
          <span style={{ fontFamily: 'var(--font-mono)', fontSize: 18, color: open ? C.green : C.muted, transition: 'transform 0.2s', display: 'inline-block', transform: open ? 'rotate(45deg)' : 'none' }}>+</span>
        </button>
        {open && <div style={{ padding: '0 24px 24px' }}>{children}</div>}
      </div>
    )
  }

  return (
    <div style={{ background: C.offwhite, minHeight: '100vh' }}>
      {/* Header */}
      <div style={{ background: C.green, paddingTop: 72, paddingBottom: 64, position: 'relative', overflow: 'hidden' }}>
        <div style={{ position: 'absolute', inset: 0, backgroundImage: `repeating-linear-gradient(90deg,transparent,transparent 80px,rgba(255,255,255,0.018) 80px,rgba(255,255,255,0.018) 81px)` }} />
        <div style={{ position: 'absolute', bottom: 0, left: 0, right: 0, height: 3, background: C.gold }} />
        <div style={{ maxWidth: 900, margin: '0 auto', padding: '0 40px', position: 'relative', zIndex: 1 }}>
          <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.14em', color: C.gold, textTransform: 'uppercase', marginBottom: 16 }}>Informasi Resmi</div>
          <h1 style={{ fontFamily: 'var(--font-display)', fontSize: 'clamp(36px,5vw,56px)', fontWeight: 400, color: C.offwhite, lineHeight: 1.1, letterSpacing: '-0.02em', marginBottom: 16 }}>
            Persyaratan Magang
          </h1>
          <p style={{ fontFamily: 'var(--font-body)', fontSize: 17, color: 'rgba(255,255,255,0.6)', lineHeight: 1.7, maxWidth: 580 }}>
            Ketentuan eligibilitas, dokumen yang diperlukan, dan informasi divisi tersedia untuk magang di Bappeda Provinsi Lampung.
          </p>
        </div>
      </div>

      <div style={{ maxWidth: 900, margin: '0 auto', padding: '64px 40px' }}>
        {/* Alert */}
        <div style={{ display: 'flex', gap: 14, background: C.goldSoft, border: `1px solid ${C.gold}40`, borderRadius: 10, padding: '16px 20px', marginBottom: 48 }}>
          <AlertCircle size={18} color={C.gold} style={{ flexShrink: 0, marginTop: 2 }} />
          <div style={{ fontFamily: 'var(--font-body)', fontSize: 14, color: '#78350F', lineHeight: 1.7 }}>
            <strong>Pendaftaran terbuka sepanjang tahun.</strong> Tidak ada periode pendaftaran tetap — pengajuan dapat dilakukan kapan saja, tergantung ketersediaan kapasitas divisi.
          </div>
        </div>

        {/* Quick summary */}
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3,1fr)', gap: 20, marginBottom: 48 }}>
          {[
            { icon: <Check size={20} color={C.green} />, label: '6 Kriteria Eligibilitas', sub: 'yang harus dipenuhi' },
            { icon: <FileText size={20} color={C.green} />, label: '3 Dokumen Wajib', sub: '+ opsional untuk penunjang' },
            { icon: <Upload size={20} color={C.green} />, label: 'Unggah Mandiri', sub: 'melalui portal SIMAGANG' },
          ].map(item => (
            <div key={item.label} style={{ background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 12, padding: '20px 24px', display: 'flex', alignItems: 'flex-start', gap: 14 }}>
              <div style={{ flexShrink: 0, marginTop: 2 }}>{item.icon}</div>
              <div>
                <div style={{ fontFamily: 'var(--font-body)', fontSize: 15, fontWeight: 700, color: C.ink, marginBottom: 2 }}>{item.label}</div>
                <div style={{ fontFamily: 'var(--font-body)', fontSize: 13, color: C.muted }}>{item.sub}</div>
              </div>
            </div>
          ))}
        </div>

        {/* Accordion sections */}
        <Section id="eligible" title="Kriteria Eligibilitas">
          <div style={{ paddingTop: 16, display: 'flex', flexDirection: 'column', gap: 10 }}>
            {ELIGIBLE.map((e, i) => (
              <div key={i} style={{ display: 'flex', gap: 14, alignItems: 'flex-start' }}>
                <div style={{ width: 22, height: 22, borderRadius: '50%', background: C.greenSoft, border: `1px solid ${C.green}30`, display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0, marginTop: 2 }}>
                  <Check size={12} color={C.green} />
                </div>
                <p style={{ fontFamily: 'var(--font-body)', fontSize: 14.5, color: '#3D4844', lineHeight: 1.7, margin: 0 }}>{e}</p>
              </div>
            ))}
          </div>
        </Section>

        <Section id="wajib" title="Dokumen Wajib">
          <div style={{ paddingTop: 16, display: 'flex', flexDirection: 'column', gap: 16 }}>
            {DOCS_WAJIB.map(doc => (
              <div key={doc.code} style={{ background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 10, padding: '20px 24px' }}>
                <div style={{ display: 'flex', alignItems: 'center', gap: 12, marginBottom: 8 }}>
                  <span style={{ fontFamily: 'var(--font-mono)', fontSize: 11, color: C.green, background: C.greenSoft, padding: '3px 8px', borderRadius: 5 }}>{doc.code}</span>
                  <h4 style={{ fontFamily: 'var(--font-body)', fontSize: 15, fontWeight: 700, color: C.ink, margin: 0 }}>{doc.title}</h4>
                  <span style={{ marginLeft: 'auto', background: '#FEF2F2', color: '#9B2C2C', fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, padding: '3px 10px', borderRadius: 99, letterSpacing: '0.06em' }}>WAJIB</span>
                </div>
                <p style={{ fontFamily: 'var(--font-body)', fontSize: 14, color: C.muted, lineHeight: 1.7, marginBottom: 10 }}>{doc.desc}</p>
                <div style={{ display: 'flex', gap: 16, flexWrap: 'wrap' }}>
                  <span style={{ fontFamily: 'var(--font-body)', fontSize: 12, color: C.muted }}><strong style={{ color: C.ink }}>Format:</strong> {doc.format}</span>
                  <span style={{ fontFamily: 'var(--font-body)', fontSize: 12, color: C.muted }}><strong style={{ color: C.ink }}>Maks. ukuran:</strong> {doc.maxSize}</span>
                </div>
                {doc.notes && (
                  <div style={{ marginTop: 10, paddingTop: 10, borderTop: `1px solid ${C.ruleSoft}`, fontFamily: 'var(--font-body)', fontSize: 12.5, color: C.muted }}>
                    <em>Catatan: {doc.notes}</em>
                  </div>
                )}
              </div>
            ))}
          </div>
        </Section>

        <Section id="opsional" title="Dokumen Opsional (Penunjang)">
          <div style={{ paddingTop: 8 }}>
            <p style={{ fontFamily: 'var(--font-body)', fontSize: 14, color: C.muted, lineHeight: 1.7, marginBottom: 20 }}>
              Dokumen berikut tidak wajib, namun dapat memperkuat pengajuan Anda, terutama untuk divisi yang kompetitif atau memiliki spesialisasi.
            </p>
            <div style={{ display: 'flex', flexDirection: 'column', gap: 12 }}>
              {DOCS_OPSIONAL.map(doc => (
                <div key={doc.code} style={{ background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 10, padding: '16px 20px', display: 'flex', gap: 16, alignItems: 'flex-start' }}>
                  <span style={{ fontFamily: 'var(--font-mono)', fontSize: 11, color: C.muted, background: '#F3F5F3', padding: '3px 8px', borderRadius: 5, flexShrink: 0, marginTop: 2 }}>{doc.code}</span>
                  <div>
                    <div style={{ fontFamily: 'var(--font-body)', fontSize: 14, fontWeight: 700, color: C.ink, marginBottom: 4 }}>{doc.title}</div>
                    <div style={{ fontFamily: 'var(--font-body)', fontSize: 13.5, color: C.muted, lineHeight: 1.65, marginBottom: 6 }}>{doc.desc}</div>
                    <div style={{ fontFamily: 'var(--font-body)', fontSize: 12, color: C.muted }}>
                      <strong style={{ color: C.ink }}>Format:</strong> {doc.format} · <strong style={{ color: C.ink }}>Maks:</strong> {doc.maxSize}
                    </div>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </Section>

        <Section id="divisi" title="Divisi yang Tersedia">
          <div style={{ paddingTop: 12 }}>
            <p style={{ fontFamily: 'var(--font-body)', fontSize: 14, color: C.muted, lineHeight: 1.7, marginBottom: 20 }}>
              Ketersediaan kapasitas setiap divisi bersifat dinamis. Status kapasitas ditampilkan saat pengisian formulir pengajuan.
            </p>
            <div style={{ display: 'flex', flexDirection: 'column', gap: 8 }}>
              {DIVISI.map((div, i) => (
                <div key={i} style={{ display: 'flex', alignItems: 'center', gap: 16, padding: '14px 18px', background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 8 }}>
                  <div style={{ width: 32, height: 32, borderRadius: 8, background: C.greenSoft, display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                    <span style={{ fontFamily: 'var(--font-mono)', fontSize: 12, color: C.green, fontWeight: 700 }}>{String(i + 1).padStart(2, '0')}</span>
                  </div>
                  <div>
                    <div style={{ fontFamily: 'var(--font-body)', fontSize: 14.5, fontWeight: 700, color: C.ink }}>{div.nama}</div>
                    <div style={{ fontFamily: 'var(--font-body)', fontSize: 13, color: C.muted }}>{div.fokus}</div>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </Section>

        {/* CTA */}
        <div style={{ marginTop: 48, background: C.green, borderRadius: 16, padding: '40px 48px', display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: 32 }}>
          <div>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.12em', color: C.gold, textTransform: 'uppercase', marginBottom: 8 }}>Sudah Siap?</div>
            <h3 style={{ fontFamily: 'var(--font-display)', fontSize: 26, fontWeight: 400, color: C.offwhite, lineHeight: 1.2, margin: 0 }}>Ajukan magang Anda sekarang</h3>
          </div>
          <div style={{ display: 'flex', gap: 12, flexShrink: 0 }}>
            <button onClick={() => navigate('alur')} style={{ padding: '12px 24px', background: 'rgba(255,255,255,0.1)', border: '1px solid rgba(255,255,255,0.25)', borderRadius: 8, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 14, fontWeight: 600, color: C.offwhite }}>
              Lihat Alur
            </button>
            <button onClick={() => navigate('register')} style={{ padding: '12px 24px', background: C.gold, border: 'none', borderRadius: 8, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 14, fontWeight: 700, color: C.greenDark }}>
              Daftar Akun
            </button>
          </div>
        </div>
      </div>
    </div>
  )
}
