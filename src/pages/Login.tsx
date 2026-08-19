import { useState } from 'react'
import { Eye, EyeOff, ArrowLeft } from 'lucide-react'
import type { Page, UserRole } from '../App'

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
  mode: 'login' | 'register'
  onLogin: (role: UserRole) => void
}

export default function Login({ navigate, mode, onLogin }: Props) {
  const [showPw, setShowPw] = useState(false)
  const [tab, setTab] = useState<'login' | 'register'>(mode)

  return (
    <div style={{ minHeight: '100vh', display: 'grid', gridTemplateColumns: '2fr 3fr' }}>
      {/* Left institutional panel */}
      <div style={{ background: C.green, padding: '48px 40px', display: 'flex', flexDirection: 'column', justifyContent: 'space-between', position: 'relative', overflow: 'hidden' }}>
        <div style={{ position: 'absolute', inset: 0, backgroundImage: `repeating-linear-gradient(0deg,transparent,transparent 40px,rgba(255,255,255,0.025) 40px,rgba(255,255,255,0.025) 41px)` }} />
        <div style={{ position: 'relative', zIndex: 1 }}>
          <button onClick={() => navigate('beranda')} style={{ display: 'flex', alignItems: 'center', gap: 8, background: 'none', border: 'none', cursor: 'pointer', color: 'rgba(255,255,255,0.55)', fontFamily: 'var(--font-body)', fontSize: 13, padding: 0, marginBottom: 56 }}>
            <ArrowLeft size={14} /> Kembali ke Beranda
          </button>

          <div style={{ display: 'flex', alignItems: 'center', gap: 12, marginBottom: 32 }}>
            <div style={{ width: 42, height: 42, background: C.gold, borderRadius: 8, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
              <span style={{ fontFamily: 'var(--font-display)', fontSize: 22, color: C.greenDark }}>S</span>
            </div>
          </div>

          <h1 style={{ fontFamily: 'var(--font-display)', fontSize: 'clamp(32px,3.5vw,48px)', fontWeight: 400, color: C.offwhite, lineHeight: 1.1, letterSpacing: '-0.02em', marginBottom: 12 }}>SIMAGANG</h1>
          <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.12em', color: C.gold, textTransform: 'uppercase', marginBottom: 20 }}>BAPPEDA Provinsi Lampung</div>
          <hr style={{ border: 'none', borderTop: '1px solid rgba(217,165,29,0.25)', marginBottom: 20 }} />
          <p style={{ fontFamily: 'var(--font-body)', fontSize: 15, color: 'rgba(255,255,255,0.6)', lineHeight: 1.7, maxWidth: 300 }}>
            Layanan pengajuan magang mahasiswa secara digital — transparan, terstruktur, dan terarsip.
          </p>
        </div>

        <div style={{ position: 'relative', zIndex: 1 }}>
          <div style={{ background: 'rgba(255,255,255,0.06)', border: '1px solid rgba(255,255,255,0.1)', borderRadius: 10, padding: '16px 20px' }}>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.10em', color: 'rgba(255,255,255,0.35)', textTransform: 'uppercase', marginBottom: 8 }}>Akses Sistem</div>
            <p style={{ fontFamily: 'var(--font-body)', fontSize: 13, color: 'rgba(255,255,255,0.5)', lineHeight: 1.6 }}>
              Mahasiswa aktif perguruan tinggi — ajukan magang melalui akun terdaftar.
            </p>
          </div>
        </div>
      </div>

      {/* Right form panel */}
      <div style={{ background: C.offwhite, display: 'flex', alignItems: 'center', justifyContent: 'center', padding: '48px' }}>
        <div style={{ width: '100%', maxWidth: 440 }}>
          <div style={{ display: 'flex', marginBottom: 40, borderBottom: `1px solid ${C.rule}` }}>
            {(['login', 'register'] as const).map(t => (
              <button key={t} onClick={() => setTab(t)} style={{ padding: '12px 0', marginRight: 32, background: 'none', border: 'none', cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 15, fontWeight: tab === t ? 700 : 500, color: tab === t ? C.ink : C.muted, borderBottom: tab === t ? `2px solid ${C.gold}` : '2px solid transparent', marginBottom: -1, transition: 'all 0.15s' }}>
                {t === 'login' ? 'Masuk' : 'Daftar'}
              </button>
            ))}
          </div>

          {tab === 'login' ? (
            <LoginForm onLogin={onLogin} showPw={showPw} setShowPw={setShowPw} />
          ) : (
            <RegisterForm onLogin={onLogin} showPw={showPw} setShowPw={setShowPw} />
          )}
        </div>
      </div>
    </div>
  )
}

function Field({ label, type = 'text', placeholder, required, helper, right }: { label: string; type?: string; placeholder?: string; required?: boolean; helper?: string; right?: React.ReactNode }) {
  return (
    <div style={{ marginBottom: 18 }}>
      <label style={{ display: 'block', fontFamily: 'var(--font-body)', fontSize: 12.5, fontWeight: 700, color: C.ink, marginBottom: 6 }}>
        {label}{required && <span style={{ color: '#9B2C2C', marginLeft: 3 }}>*</span>}
      </label>
      <div style={{ position: 'relative' }}>
        <input type={type} placeholder={placeholder} style={{ width: '100%', padding: right ? '10px 44px 10px 14px' : '10px 14px', background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 8, fontFamily: 'var(--font-body)', fontSize: 14, color: C.ink, outline: 'none' }}
          onFocus={e => { e.target.style.borderColor = C.green; e.target.style.boxShadow = `0 0 0 3px ${C.greenSoft}` }}
          onBlur={e => { e.target.style.borderColor = C.rule; e.target.style.boxShadow = 'none' }}
        />
        {right && <div style={{ position: 'absolute', right: 12, top: '50%', transform: 'translateY(-50%)' }}>{right}</div>}
      </div>
      {helper && <div style={{ fontFamily: 'var(--font-body)', fontSize: 11.5, color: C.muted, marginTop: 5 }}>{helper}</div>}
    </div>
  )
}

function DemoBtn({ label, color, onClick }: { label: string; color: string; onClick: () => void }) {
  return (
    <button onClick={onClick} style={{ width: '100%', padding: '11px', background: 'transparent', border: `1px solid ${color}`, borderRadius: 8, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 600, color: color, marginBottom: 8, transition: 'all 0.15s' }}
      onMouseEnter={e => { (e.target as HTMLElement).style.background = color + '12' }}
      onMouseLeave={e => { (e.target as HTMLElement).style.background = 'transparent' }}
    >{label}</button>
  )
}

function LoginForm({ onLogin, showPw, setShowPw }: { onLogin: (r: UserRole) => void; showPw: boolean; setShowPw: (v: boolean) => void }) {
  return (
    <div>
      <h2 style={{ fontFamily: 'var(--font-display)', fontSize: 28, fontWeight: 400, color: C.ink, marginBottom: 6 }}>Masuk ke Akun Anda</h2>
      <p style={{ fontFamily: 'var(--font-body)', fontSize: 14, color: C.muted, marginBottom: 28 }}>Gunakan email yang terdaftar pada sistem SIMAGANG.</p>

      <Field label="Alamat Email" type="email" placeholder="nama@universitas.ac.id" required />
      <Field label="Kata Sandi" type={showPw ? 'text' : 'password'} placeholder="Masukkan kata sandi" required
        right={<button type="button" onClick={() => setShowPw(!showPw)} style={{ background: 'none', border: 'none', cursor: 'pointer', color: C.muted, display: 'flex', alignItems: 'center' }}>{showPw ? <EyeOff size={16} /> : <Eye size={16} />}</button>}
      />

      <div style={{ display: 'flex', justifyContent: 'flex-end', marginBottom: 24 }}>
        <button style={{ background: 'none', border: 'none', cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13, color: C.greenMid, fontWeight: 600 }}>Lupa kata sandi?</button>
      </div>

      <button onClick={() => onLogin('mahasiswa')} style={{ width: '100%', padding: '13px', background: C.green, color: C.offwhite, border: 'none', borderRadius: 8, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 14.5, fontWeight: 700, marginBottom: 20 }}>
        Masuk sebagai Mahasiswa
      </button>

      <hr style={{ border: 'none', borderTop: `1px solid ${C.rule}`, marginBottom: 20 }} />
      <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.10em', color: C.muted, textTransform: 'uppercase', marginBottom: 10 }}>Demo Akses Internal</div>
      <DemoBtn label="Masuk sebagai Sekretariat" color={C.greenMid} onClick={() => onLogin('sekretariat')} />
      <DemoBtn label="Masuk sebagai Admin Sistem" color={C.muted} onClick={() => onLogin('admin_sistem')} />
    </div>
  )
}

function RegisterForm({ onLogin, showPw, setShowPw }: { onLogin: (r: UserRole) => void; showPw: boolean; setShowPw: (v: boolean) => void }) {
  return (
    <div>
      <h2 style={{ fontFamily: 'var(--font-display)', fontSize: 28, fontWeight: 400, color: C.ink, marginBottom: 6 }}>Daftar Akun Mahasiswa</h2>
      <p style={{ fontFamily: 'var(--font-body)', fontSize: 14, color: C.muted, marginBottom: 28 }}>Buat akun untuk mengajukan magang di Bappeda Provinsi Lampung.</p>

      <Field label="Nama Lengkap" placeholder="Nama sesuai KTP" required />
      <Field label="Alamat Email" type="email" placeholder="nama@universitas.ac.id" required helper="Gunakan email aktif — akan digunakan untuk verifikasi." />
      <Field label="NIM / NPM" placeholder="Nomor Induk Mahasiswa" required />
      <Field label="Kata Sandi" type={showPw ? 'text' : 'password'} placeholder="Minimal 8 karakter" required
        right={<button type="button" onClick={() => setShowPw(!showPw)} style={{ background: 'none', border: 'none', cursor: 'pointer', color: C.muted, display: 'flex', alignItems: 'center' }}>{showPw ? <EyeOff size={16} /> : <Eye size={16} />}</button>}
      />
      <Field label="Konfirmasi Kata Sandi" type="password" placeholder="Ulangi kata sandi" required />

      <button onClick={() => onLogin('mahasiswa')} style={{ width: '100%', padding: '13px', background: C.green, color: C.offwhite, border: 'none', borderRadius: 8, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 14.5, fontWeight: 700, marginTop: 8 }}>
        Daftar & Masuk
      </button>
    </div>
  )
}
