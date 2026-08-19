import { ArrowRight, CheckCircle2, Clock, FileText, Shield } from 'lucide-react'
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

interface Props {
  navigate: (p: Page) => void
}

export default function Beranda({ navigate }: Props) {
  return (
    <main style={{ background: C.offwhite }}>
      <HeroSection navigate={navigate} />
      <TrustStrip />
      <WhySection />
      <AlurSection navigate={navigate} />
      <CtaSection navigate={navigate} />
      <Footer navigate={navigate} />
    </main>
  )
}

function HeroSection({ navigate }: Props) {
  return (
    <section
      style={{
        background: C.ivory,
        borderBottom: `1px solid ${C.rule}`,
        padding: '72px 32px 80px',
      }}
    >
      <div
        style={{
          maxWidth: 1240,
          margin: '0 auto',
          display: 'grid',
          gridTemplateColumns: '1fr 1fr',
          gap: 80,
          alignItems: 'center',
        }}
      >
        {/* Left: Editorial content */}
        <div>
          {/* Eyebrow */}
          <div style={{ display: 'flex', alignItems: 'center', gap: 12, marginBottom: 28 }}>
            <div style={{ width: 24, height: 2, background: C.gold }} />
            <span
              style={{
                fontFamily: 'var(--font-body)',
                fontSize: 11,
                fontWeight: 700,
                letterSpacing: '0.14em',
                color: C.muted,
                textTransform: 'uppercase',
              }}
            >
              Layanan Digital BAPPEDA Provinsi Lampung
            </span>
          </div>

          {/* Headline */}
          <h1
            style={{
              fontFamily: 'var(--font-display)',
              fontSize: 'clamp(40px, 5vw, 60px)',
              fontWeight: 400,
              color: C.ink,
              lineHeight: 1.12,
              letterSpacing: '-0.02em',
              marginBottom: 24,
            }}
          >
            Ajukan Magang di
            <br />
            <em style={{ color: C.green }}>Bappeda Provinsi</em>
            <br />
            Lampung.
          </h1>

          {/* Subtitle */}
          <p
            style={{
              fontFamily: 'var(--font-body)',
              fontSize: 17,
              color: C.muted,
              lineHeight: 1.7,
              marginBottom: 40,
              maxWidth: 480,
            }}
          >
            Ajukan, lengkapi dokumen, dan pantau proses magang mahasiswa secara online dalam satu layanan terpadu.
          </p>

          {/* CTAs */}
          <div style={{ display: 'flex', gap: 12, flexWrap: 'wrap' }}>
            <button
              onClick={() => navigate('login')}
              style={{
                padding: '14px 28px',
                background: C.green,
                color: C.offwhite,
                border: 'none',
                borderRadius: 8,
                cursor: 'pointer',
                fontFamily: 'var(--font-body)',
                fontSize: 14,
                fontWeight: 700,
                display: 'flex',
                alignItems: 'center',
                gap: 8,
                transition: 'background 0.15s',
              }}
            >
              Ajukan Magang
              <ArrowRight size={16} />
            </button>
            <button
              onClick={() => navigate('login')}
              style={{
                padding: '14px 28px',
                background: 'none',
                color: C.ink,
                border: `1px solid ${C.rule}`,
                borderRadius: 8,
                cursor: 'pointer',
                fontFamily: 'var(--font-body)',
                fontSize: 14,
                fontWeight: 600,
                display: 'flex',
                alignItems: 'center',
                gap: 8,
                transition: 'border-color 0.15s',
              }}
            >
              Cek Status Pengajuan
            </button>
          </div>
        </div>

        {/* Right: Document composition */}
        <DocumentPanel />
      </div>

      <style>{`
        @media (max-width: 900px) {
          .hero-grid { grid-template-columns: 1fr !important; gap: 48px !important; }
          .doc-panel { display: none; }
        }
      `}</style>
    </section>
  )
}

function DocumentPanel() {
  const steps = [
    { num: '01', label: 'Pengajuan', done: true },
    { num: '02', label: 'Verifikasi', done: true },
    { num: '03', label: 'Persetujuan', done: false, active: true },
    { num: '04', label: 'Surat', done: false },
  ]

  return (
    <div
      style={{
        background: C.offwhite,
        border: `1px solid ${C.rule}`,
        borderRadius: 12,
        overflow: 'hidden',
      }}
    >
      {/* Document header */}
      <div
        style={{
          background: C.green,
          padding: '20px 28px',
          display: 'flex',
          justifyContent: 'space-between',
          alignItems: 'flex-start',
        }}
      >
        <div>
          <div style={{ fontFamily: 'var(--font-body)', fontSize: 10, fontWeight: 700, letterSpacing: '0.12em', color: 'rgba(255,255,255,0.6)', textTransform: 'uppercase', marginBottom: 4 }}>
            Surat Keterangan Pengajuan
          </div>
          <div style={{ fontFamily: 'var(--font-mono)', fontSize: 20, fontWeight: 600, color: C.offwhite, letterSpacing: '0.02em' }}>
            PGJ-1002
          </div>
        </div>
        <div
          style={{
            background: C.gold,
            color: C.greenDark,
            padding: '4px 12px',
            borderRadius: 6,
            fontFamily: 'var(--font-body)',
            fontSize: 11,
            fontWeight: 700,
            letterSpacing: '0.06em',
          }}
        >
          DITERUSKAN
        </div>
      </div>

      {/* Document body */}
      <div style={{ padding: '24px 28px' }}>
        {/* Applicant info */}
        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '12px 24px', marginBottom: 24 }}>
          {[
            { label: 'Nama Mahasiswa', value: 'Najwa Ramadhani' },
            { label: 'NIM', value: '2021010234' },
            { label: 'Perguruan Tinggi', value: 'Universitas Lampung' },
            { label: 'Program Studi', value: 'Teknik Informatika' },
          ].map(({ label, value }) => (
            <div key={label}>
              <div style={{ fontFamily: 'var(--font-body)', fontSize: 10, fontWeight: 700, letterSpacing: '0.08em', color: C.muted, textTransform: 'uppercase', marginBottom: 2 }}>
                {label}
              </div>
              <div style={{ fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 600, color: C.ink }}>
                {value}
              </div>
            </div>
          ))}
        </div>

        <hr style={{ border: 'none', borderTop: `1px solid ${C.rule}`, marginBottom: 20 }} />

        {/* Process timeline */}
        <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.10em', color: C.muted, textTransform: 'uppercase', marginBottom: 16 }}>
          Tahapan Pengajuan
        </div>

        <div style={{ display: 'flex', alignItems: 'center', gap: 0 }}>
          {[
            { num: '01', label: 'Pengajuan', done: true },
            { num: '02', label: 'Verifikasi', done: true },
            { num: '03', label: 'Persetujuan', done: false, active: true },
            { num: '04', label: 'Surat', done: false },
          ].map((step, i) => (
            <div key={step.num} style={{ flex: 1, display: 'flex', flexDirection: 'column', alignItems: 'center', position: 'relative' }}>
              {/* Connector line before */}
              {i > 0 && (
                <div
                  style={{
                    position: 'absolute',
                    top: 14,
                    right: '50%',
                    left: '-50%',
                    height: 2,
                    background: step.done ? C.green : C.rule,
                    zIndex: 0,
                  }}
                />
              )}
              {/* Node */}
              <div
                style={{
                  width: 28,
                  height: 28,
                  borderRadius: '50%',
                  background: step.done ? C.green : (step as any).active ? C.gold : 'transparent',
                  border: `2px solid ${step.done ? C.green : (step as any).active ? C.gold : C.rule}`,
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  zIndex: 1,
                  position: 'relative',
                  marginBottom: 8,
                }}
              >
                {step.done ? (
                  <CheckCircle2 size={14} color={C.offwhite} />
                ) : (
                  <span style={{ fontFamily: 'var(--font-mono)', fontSize: 10, fontWeight: 600, color: (step as any).active ? C.greenDark : C.muted }}>
                    {step.num}
                  </span>
                )}
              </div>
              <div style={{ fontFamily: 'var(--font-body)', fontSize: 10.5, fontWeight: (step as any).active ? 700 : 500, color: (step as any).active ? C.ink : C.muted, textAlign: 'center' }}>
                {step.label}
              </div>
            </div>
          ))}
        </div>

        <hr style={{ border: 'none', borderTop: `1px solid ${C.rule}`, margin: '20px 0' }} />

        {/* Status note */}
        <div
          style={{
            background: C.greenSoft,
            border: `1px solid #C3DBD6`,
            borderLeft: `3px solid ${C.green}`,
            borderRadius: 6,
            padding: '10px 14px',
          }}
        >
          <div style={{ fontFamily: 'var(--font-body)', fontSize: 12.5, color: C.greenMid, fontWeight: 500, lineHeight: 1.5 }}>
            Berkas dinyatakan lengkap dan diteruskan ke tahap persetujuan substansi.
          </div>
          <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, color: C.muted, marginTop: 4 }}>
            07 Agustus 2026 · 09:42 WIB
          </div>
        </div>
      </div>
    </div>
  )
}

function TrustStrip() {
  const items = [
    { icon: <FileText size={20} />, label: 'Pengajuan Online', desc: 'Tidak perlu datang ke kantor' },
    { icon: <Clock size={20} />, label: 'Status Transparan', desc: 'Pantau setiap tahapan secara real-time' },
    { icon: <Shield size={20} />, label: 'Dokumen Tersimpan', desc: 'Arsip digital aman dan tertelusuri' },
    { icon: <CheckCircle2 size={20} />, label: 'Proses Terstruktur', desc: 'Alur kerja yang jelas dan terstandar' },
  ]

  return (
    <section style={{ background: C.green, padding: '32px' }}>
      <div
        style={{
          maxWidth: 1240,
          margin: '0 auto',
          display: 'grid',
          gridTemplateColumns: 'repeat(4, 1fr)',
          gap: 0,
        }}
      >
        {items.map(({ icon, label, desc }, i) => (
          <div
            key={label}
            style={{
              padding: '20px 28px',
              borderLeft: i > 0 ? `1px solid rgba(255,255,255,0.12)` : 'none',
              display: 'flex',
              alignItems: 'flex-start',
              gap: 16,
            }}
          >
            <div style={{ color: C.gold, flexShrink: 0, marginTop: 2 }}>{icon}</div>
            <div>
              <div style={{ fontFamily: 'var(--font-body)', fontSize: 14, fontWeight: 700, color: C.offwhite, marginBottom: 3 }}>
                {label}
              </div>
              <div style={{ fontFamily: 'var(--font-body)', fontSize: 12.5, color: 'rgba(255,255,255,0.6)', lineHeight: 1.5 }}>
                {desc}
              </div>
            </div>
          </div>
        ))}
      </div>
    </section>
  )
}

function WhySection() {
  const benefits = [
    {
      num: '01',
      title: 'Pengajuan Digital',
      body: 'Tidak perlu datang ke kantor untuk memulai proses pengajuan magang. Seluruh administrasi dilakukan secara daring.',
    },
    {
      num: '02',
      title: 'Status Terpantau',
      body: 'Mahasiswa dapat mengetahui posisi pengajuan pada setiap tahapan proses secara transparan dan real-time.',
    },
    {
      num: '03',
      title: 'Dokumen Terarsip',
      body: 'Dokumen pengajuan tersimpan secara digital dan dapat ditelusuri kapan saja oleh mahasiswa maupun administrator.',
    },
  ]

  return (
    <section style={{ padding: '96px 32px', background: C.ivory }}>
      <div style={{ maxWidth: 1240, margin: '0 auto' }}>
        {/* Section header */}
        <div style={{ display: 'flex', alignItems: 'flex-end', justifyContent: 'space-between', marginBottom: 56 }}>
          <div>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.14em', color: C.gold, textTransform: 'uppercase', marginBottom: 12 }}>
              Mengapa SIMAGANG
            </div>
            <h2
              style={{
                fontFamily: 'var(--font-display)',
                fontSize: 'clamp(32px, 3.5vw, 48px)',
                fontWeight: 400,
                color: C.ink,
                lineHeight: 1.15,
                letterSpacing: '-0.02em',
              }}
            >
              Layanan yang dirancang
              <br />
              untuk <em style={{ color: C.green }}>kemudahan mahasiswa.</em>
            </h2>
          </div>
        </div>

        {/* Gold divider */}
        <div style={{ height: 1, background: C.rule, marginBottom: 0 }} />

        {/* Benefits - editorial layout */}
        <div>
          {benefits.map(({ num, title, body }, i) => (
            <div
              key={num}
              style={{
                display: 'grid',
                gridTemplateColumns: '80px 1fr 1fr',
                gap: '0 48px',
                padding: '40px 0',
                borderBottom: `1px solid ${C.rule}`,
                alignItems: 'start',
              }}
            >
              {/* Number */}
              <div
                style={{
                  fontFamily: 'var(--font-mono)',
                  fontSize: 36,
                  fontWeight: 600,
                  color: C.gold,
                  lineHeight: 1,
                  paddingTop: 4,
                }}
              >
                {num}
              </div>

              {/* Title */}
              <div>
                <h3
                  style={{
                    fontFamily: 'var(--font-display)',
                    fontSize: 26,
                    fontWeight: 400,
                    color: C.ink,
                    lineHeight: 1.2,
                    letterSpacing: '-0.01em',
                  }}
                >
                  {title}
                </h3>
              </div>

              {/* Body */}
              <div>
                <p style={{ fontFamily: 'var(--font-body)', fontSize: 15, color: C.muted, lineHeight: 1.7 }}>
                  {body}
                </p>
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  )
}

function AlurSection({ navigate }: Props) {
  const steps = [
    { num: '01', title: 'Registrasi', desc: 'Buat akun dengan email perguruan tinggi aktif.' },
    { num: '02', title: 'Lengkapi Profil', desc: 'Isi data diri, akademik, dan informasi institusi.' },
    { num: '03', title: 'Ajukan Magang', desc: 'Pilih bidang, periode, dan unggah dokumen persyaratan.' },
    { num: '04', title: 'Verifikasi & Persetujuan', desc: 'Tim Bappeda memverifikasi berkas dan memberikan persetujuan.' },
    { num: '05', title: 'Surat Diterbitkan', desc: 'Surat penerimaan magang diterbitkan secara digital.' },
  ]

  return (
    <section style={{ padding: '96px 32px', background: C.offwhite }}>
      <div style={{ maxWidth: 1240, margin: '0 auto' }}>
        {/* Header */}
        <div style={{ marginBottom: 56, display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', flexWrap: 'wrap', gap: 24 }}>
          <div>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.14em', color: C.gold, textTransform: 'uppercase', marginBottom: 12 }}>
              Alur Pengajuan
            </div>
            <h2
              style={{
                fontFamily: 'var(--font-display)',
                fontSize: 'clamp(28px, 3vw, 42px)',
                fontWeight: 400,
                color: C.ink,
                lineHeight: 1.15,
                letterSpacing: '-0.02em',
              }}
            >
              Lima langkah mudah
              <br />
              menuju magang di Bappeda.
            </h2>
          </div>
          <button
            onClick={() => navigate('login')}
            style={{
              padding: '12px 24px',
              background: 'none',
              border: `1px solid ${C.green}`,
              borderRadius: 8,
              cursor: 'pointer',
              fontFamily: 'var(--font-body)',
              fontSize: 13.5,
              fontWeight: 600,
              color: C.green,
              alignSelf: 'flex-end',
              display: 'flex',
              alignItems: 'center',
              gap: 8,
            }}
          >
            Mulai Sekarang <ArrowRight size={14} />
          </button>
        </div>

        {/* Timeline */}
        <div style={{ position: 'relative' }}>
          {/* Connecting line */}
          <div
            style={{
              position: 'absolute',
              top: 28,
              left: 28,
              right: 28,
              height: 1,
              background: C.rule,
              zIndex: 0,
            }}
          />

          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(5, 1fr)', gap: 24, position: 'relative', zIndex: 1 }}>
            {steps.map((step, i) => (
              <div key={step.num} style={{ display: 'flex', flexDirection: 'column', alignItems: 'flex-start' }}>
                {/* Node */}
                <div
                  style={{
                    width: 56,
                    height: 56,
                    borderRadius: '50%',
                    background: i === 0 ? C.green : C.offwhite,
                    border: `2px solid ${i === 0 ? C.green : C.rule}`,
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    marginBottom: 20,
                  }}
                >
                  <span
                    style={{
                      fontFamily: 'var(--font-mono)',
                      fontSize: 14,
                      fontWeight: 600,
                      color: i === 0 ? C.offwhite : C.muted,
                    }}
                  >
                    {step.num}
                  </span>
                </div>

                <div
                  style={{
                    fontFamily: 'var(--font-body)',
                    fontSize: 15,
                    fontWeight: 700,
                    color: C.ink,
                    marginBottom: 8,
                    lineHeight: 1.3,
                  }}
                >
                  {step.title}
                </div>
                <div
                  style={{
                    fontFamily: 'var(--font-body)',
                    fontSize: 13,
                    color: C.muted,
                    lineHeight: 1.6,
                  }}
                >
                  {step.desc}
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </section>
  )
}

function CtaSection({ navigate }: Props) {
  return (
    <section style={{ background: C.green, padding: '80px 32px' }}>
      <div
        style={{
          maxWidth: 1240,
          margin: '0 auto',
          display: 'grid',
          gridTemplateColumns: '1fr auto',
          gap: 40,
          alignItems: 'center',
        }}
      >
        <div>
          <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.14em', color: C.gold, textTransform: 'uppercase', marginBottom: 12 }}>
            Siap Memulai?
          </div>
          <h2
            style={{
              fontFamily: 'var(--font-display)',
              fontSize: 'clamp(28px, 3vw, 42px)',
              fontWeight: 400,
              color: C.offwhite,
              lineHeight: 1.15,
              letterSpacing: '-0.02em',
              marginBottom: 12,
            }}
          >
            Ajukan magang Anda hari ini.
          </h2>
          <p style={{ fontFamily: 'var(--font-body)', fontSize: 15, color: 'rgba(255,255,255,0.65)', lineHeight: 1.6 }}>
            Daftarkan diri dan mulai proses pengajuan magang di Bappeda Provinsi Lampung.
          </p>
        </div>
        <div style={{ display: 'flex', flexDirection: 'column', gap: 12, flexShrink: 0 }}>
          <button
            onClick={() => navigate('register')}
            style={{
              padding: '14px 32px',
              background: C.gold,
              border: 'none',
              borderRadius: 8,
              cursor: 'pointer',
              fontFamily: 'var(--font-body)',
              fontSize: 14,
              fontWeight: 700,
              color: C.greenDark,
              whiteSpace: 'nowrap',
            }}
          >
            Daftar Sekarang
          </button>
          <button
            onClick={() => navigate('login')}
            style={{
              padding: '14px 32px',
              background: 'transparent',
              border: `1px solid rgba(255,255,255,0.25)`,
              borderRadius: 8,
              cursor: 'pointer',
              fontFamily: 'var(--font-body)',
              fontSize: 14,
              fontWeight: 600,
              color: 'rgba(255,255,255,0.8)',
              whiteSpace: 'nowrap',
            }}
          >
            Sudah Punya Akun
          </button>
        </div>
      </div>
    </section>
  )
}

function Footer({ navigate }: Props) {
  return (
    <footer style={{ background: C.ink, padding: '48px 32px 32px', borderTop: `1px solid rgba(255,255,255,0.06)` }}>
      <div style={{ maxWidth: 1240, margin: '0 auto' }}>
        <div style={{ display: 'grid', gridTemplateColumns: '2fr 1fr 1fr', gap: 48, marginBottom: 40 }}>
          <div>
            <div style={{ fontFamily: 'var(--font-body)', fontWeight: 800, fontSize: 16, color: C.offwhite, marginBottom: 4 }}>
              SIMAGANG
            </div>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, color: 'rgba(255,255,255,0.4)', letterSpacing: '0.08em', textTransform: 'uppercase', marginBottom: 16 }}>
              Sistem Informasi Pengelolaan Magang Mahasiswa
            </div>
            <p style={{ fontFamily: 'var(--font-body)', fontSize: 13.5, color: 'rgba(255,255,255,0.5)', lineHeight: 1.7, maxWidth: 360 }}>
              Layanan digital untuk pengelolaan magang mahasiswa pada Badan Perencanaan Pembangunan Daerah (Bappeda) Provinsi Lampung.
            </p>
          </div>
          <div>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.12em', color: 'rgba(255,255,255,0.4)', textTransform: 'uppercase', marginBottom: 16 }}>
              Tautan
            </div>
            {['Beranda', 'Alur Pengajuan', 'Persyaratan', 'FAQ'].map(link => (
              <div key={link} style={{ marginBottom: 10 }}>
                <button
                  style={{ background: 'none', border: 'none', cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13.5, color: 'rgba(255,255,255,0.6)', padding: 0 }}
                >
                  {link}
                </button>
              </div>
            ))}
          </div>
          <div>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.12em', color: 'rgba(255,255,255,0.4)', textTransform: 'uppercase', marginBottom: 16 }}>
              Kontak
            </div>
            <p style={{ fontFamily: 'var(--font-body)', fontSize: 13.5, color: 'rgba(255,255,255,0.5)', lineHeight: 1.7 }}>
              Bappeda Provinsi Lampung<br />
              Jl. Cut Meutia No.4, Bandar Lampung<br />
              simagang@bappeda.lampungprov.go.id
            </p>
          </div>
        </div>

        <hr style={{ border: 'none', borderTop: '1px solid rgba(255,255,255,0.08)', marginBottom: 24 }} />

        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
          <div style={{ fontFamily: 'var(--font-body)', fontSize: 12, color: 'rgba(255,255,255,0.3)' }}>
            © 2026 Bappeda Provinsi Lampung. Hak cipta dilindungi.
          </div>
          <div style={{ fontFamily: 'var(--font-body)', fontSize: 12, color: 'rgba(255,255,255,0.3)' }}>
            SIMAGANG v1.0.0
          </div>
        </div>
      </div>
    </footer>
  )
}
