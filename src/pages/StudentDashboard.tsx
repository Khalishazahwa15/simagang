import { useState } from 'react'
import {
  LayoutDashboard, FileText, FolderOpen, User, LogOut,
  CheckCircle2, Clock, XCircle, AlertCircle, ArrowRight,
  Upload, Download, Eye, ChevronRight, Bell, RotateCcw, Info,
} from 'lucide-react'
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

// PRD Status Lifecycle
export type AppStatus =
  | 'Draft' | 'Diajukan' | 'Diperiksa'
  | 'Perlu Revisi' | 'Cek Kebutuhan Divisi'
  | 'Diterima' | 'Ditolak'
  | 'Sedang Magang' | 'Mengundurkan Diri' | 'Selesai'
  | 'Dibatalkan (Draft)' | 'Dibatalkan (Belum Diproses)'

interface Props {
  navigate: (p: Page) => void
  onLogout: () => void
}

type View = 'overview' | 'form' | 'status' | 'dokumen' | 'profil'

// Demo: switch application status to test different views
const DEMO_STATUSES: AppStatus[] = [
  'Draft', 'Diajukan', 'Diperiksa', 'Perlu Revisi',
  'Cek Kebutuhan Divisi', 'Diterima', 'Ditolak',
  'Sedang Magang', 'Selesai',
]

export default function StudentDashboard({ navigate, onLogout }: Props) {
  const [view, setView] = useState<View>('overview')
  const [demoStatus, setDemoStatus] = useState<AppStatus>('Diteruskan' as any)
  // Use 'Diterima' as default to show document download
  const [appStatus, setAppStatus] = useState<AppStatus>('Diterima')

  return (
    <div style={{ minHeight: '100vh', display: 'grid', gridTemplateColumns: '240px 1fr', background: '#F4F5F0' }}>
      <StudentSidebar view={view} setView={setView} onLogout={onLogout} />
      <div style={{ minHeight: '100vh', overflow: 'auto' }}>
        {/* Demo status switcher */}
        <DemoBar appStatus={appStatus} setAppStatus={setAppStatus} />
        {view === 'overview' && <OverviewView appStatus={appStatus} setView={setView} />}
        {view === 'form' && <FormPengajuan setView={setView} />}
        {view === 'status' && <StatusView appStatus={appStatus} />}
        {view === 'dokumen' && <DokumenView appStatus={appStatus} />}
        {view === 'profil' && <ProfilView />}
      </div>
    </div>
  )
}

function DemoBar({ appStatus, setAppStatus }: { appStatus: AppStatus; setAppStatus: (s: AppStatus) => void }) {
  return (
    <div style={{ background: C.greenDark, padding: '8px 24px', display: 'flex', alignItems: 'center', gap: 12, flexWrap: 'wrap' }}>
      <span style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.08em', color: C.gold, textTransform: 'uppercase' }}>Demo Status:</span>
      {DEMO_STATUSES.map(s => (
        <button key={s} onClick={() => setAppStatus(s)} style={{ padding: '3px 10px', background: appStatus === s ? C.gold : 'rgba(255,255,255,0.1)', border: 'none', borderRadius: 4, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 600, color: appStatus === s ? C.greenDark : 'rgba(255,255,255,0.65)', transition: 'all 0.15s' }}>
          {s}
        </button>
      ))}
    </div>
  )
}

function StudentSidebar({ view, setView, onLogout }: { view: View; setView: (v: View) => void; onLogout: () => void }) {
  const items = [
    { icon: <LayoutDashboard size={17} />, label: 'Beranda', v: 'overview' as View },
    { icon: <FileText size={17} />, label: 'Ajukan Magang', v: 'form' as View },
    { icon: <Clock size={17} />, label: 'Status Pengajuan', v: 'status' as View },
    { icon: <FolderOpen size={17} />, label: 'Dokumen', v: 'dokumen' as View },
    { icon: <User size={17} />, label: 'Profil', v: 'profil' as View },
  ]

  return (
    <aside style={{ background: C.green, display: 'flex', flexDirection: 'column', padding: '0 0 24px', position: 'sticky', top: 0, height: '100vh' }}>
      <div style={{ padding: '24px 20px 20px', borderBottom: '1px solid rgba(255,255,255,0.1)' }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 20 }}>
          <div style={{ width: 32, height: 32, background: C.gold, borderRadius: 6, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
            <span style={{ fontFamily: 'var(--font-display)', fontSize: 16, color: C.greenDark }}>S</span>
          </div>
          <div>
            <div style={{ fontFamily: 'var(--font-body)', fontWeight: 800, fontSize: 13, color: C.offwhite }}>SIMAGANG</div>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 9, color: 'rgba(255,255,255,0.4)', letterSpacing: '0.06em', textTransform: 'uppercase' }}>Mahasiswa</div>
          </div>
        </div>
        <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
          <div style={{ width: 36, height: 36, borderRadius: '50%', background: 'rgba(255,255,255,0.15)', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
            <span style={{ fontFamily: 'var(--font-body)', fontWeight: 700, fontSize: 14, color: C.offwhite }}>N</span>
          </div>
          <div>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 600, color: C.offwhite }}>Najwa Ramadhani</div>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, color: 'rgba(255,255,255,0.5)' }}>Teknik Informatika — Unila</div>
          </div>
        </div>
      </div>

      <nav style={{ flex: 1, padding: '16px 12px' }}>
        {items.map(({ icon, label, v }) => (
          <button key={v} onClick={() => setView(v)} style={{ display: 'flex', alignItems: 'center', gap: 10, width: '100%', padding: '10px 12px', background: view === v ? 'rgba(255,255,255,0.12)' : 'none', border: 'none', borderRadius: 8, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: view === v ? 600 : 500, color: view === v ? C.offwhite : 'rgba(255,255,255,0.6)', textAlign: 'left', marginBottom: 2, borderLeft: view === v ? `3px solid ${C.gold}` : '3px solid transparent' }}>
            {icon} {label}
          </button>
        ))}
      </nav>

      <div style={{ padding: '0 12px', borderTop: '1px solid rgba(255,255,255,0.08)', paddingTop: 16 }}>
        <button onClick={onLogout} style={{ display: 'flex', alignItems: 'center', gap: 10, width: '100%', padding: '10px 12px', background: 'none', border: 'none', cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13, color: 'rgba(255,255,255,0.4)', textAlign: 'left' }}>
          <LogOut size={16} /> Keluar
        </button>
      </div>
    </aside>
  )
}

function PageHeader({ title, subtitle, action }: { title: string; subtitle?: string; action?: React.ReactNode }) {
  return (
    <div style={{ background: C.offwhite, borderBottom: `1px solid ${C.rule}`, padding: '28px 36px', display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between' }}>
      <div>
        <h1 style={{ fontFamily: 'var(--font-display)', fontSize: 26, fontWeight: 400, color: C.ink, marginBottom: 2, letterSpacing: '-0.01em' }}>{title}</h1>
        {subtitle && <p style={{ fontFamily: 'var(--font-body)', fontSize: 14, color: C.muted }}>{subtitle}</p>}
      </div>
      <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
        {action}
        <button style={{ position: 'relative', background: 'none', border: 'none', cursor: 'pointer', color: C.muted, padding: 8 }}>
          <Bell size={20} />
          <span style={{ position: 'absolute', top: 6, right: 6, width: 8, height: 8, background: C.gold, borderRadius: '50%' }} />
        </button>
      </div>
    </div>
  )
}

// ─── Status badge ──────────────────────────────────────────────────────────────
const STATUS_COLORS: Record<string, { bg: string; color: string; border?: string }> = {
  'Draft':                     { bg: C.ivory, color: C.muted },
  'Diajukan':                  { bg: '#EFF6FF', color: '#1D4ED8' },
  'Diperiksa':                 { bg: C.goldSoft, color: '#7A5A00' },
  'Perlu Revisi':              { bg: '#FEF3C7', color: '#92400E', border: '#F59E0B' },
  'Cek Kebutuhan Divisi':      { bg: C.goldSoft, color: '#7A5A00' },
  'Diterima':                  { bg: C.greenSoft, color: C.greenMid },
  'Ditolak':                   { bg: '#FEE2E2', color: '#991B1B' },
  'Sedang Magang':             { bg: C.greenSoft, color: C.green },
  'Mengundurkan Diri':         { bg: C.ivory, color: C.muted },
  'Selesai':                   { bg: '#D1FAE5', color: '#065F46' },
  'Dibatalkan (Draft)':        { bg: C.ivory, color: C.muted },
  'Dibatalkan (Belum Diproses)': { bg: C.ivory, color: C.muted },
}

function StatusBadge({ status, size = 'normal' }: { status: AppStatus | string; size?: 'small' | 'normal' | 'large' }) {
  const s = STATUS_COLORS[status] ?? { bg: C.ivory, color: C.muted }
  const pad = size === 'large' ? '6px 16px' : size === 'small' ? '2px 8px' : '4px 10px'
  const fs = size === 'large' ? 13 : size === 'small' ? 10 : 11
  return (
    <span style={{ fontFamily: 'var(--font-body)', fontSize: fs, fontWeight: 700, letterSpacing: '0.05em', textTransform: 'uppercase', background: s.bg, color: s.color, border: s.border ? `1px solid ${s.border}` : `1px solid ${s.bg}`, padding: pad, borderRadius: 4, display: 'inline-block', whiteSpace: 'nowrap' }}>
      {status}
    </span>
  )
}

// ─── Status lifecycle stepper ──────────────────────────────────────────────────
const LIFECYCLE_STEPS = [
  { key: 'Diajukan', label: 'Diajukan' },
  { key: 'Diperiksa', label: 'Diperiksa' },
  { key: 'Cek Kebutuhan Divisi', label: 'Cek Divisi' },
  { key: 'Diterima', label: 'Diterima / Ditolak' },
  { key: 'Sedang Magang', label: 'Sedang Magang' },
  { key: 'Selesai', label: 'Selesai' },
]

const STATUS_STEP_IDX: Record<string, number> = {
  'Diajukan': 0, 'Diperiksa': 1, 'Perlu Revisi': 1,
  'Cek Kebutuhan Divisi': 2, 'Diterima': 3, 'Ditolak': 3,
  'Sedang Magang': 4, 'Mengundurkan Diri': 4, 'Selesai': 5,
}

function LifecycleStepper({ status }: { status: AppStatus }) {
  const cur = STATUS_STEP_IDX[status] ?? -1
  const rejected = status === 'Ditolak' || status === 'Mengundurkan Diri'

  return (
    <div style={{ display: 'flex', alignItems: 'center' }}>
      {LIFECYCLE_STEPS.map((step, i) => (
        <div key={step.key} style={{ flex: 1, display: 'flex', flexDirection: 'column', alignItems: 'center', position: 'relative' }}>
          {i > 0 && (
            <div style={{ position: 'absolute', top: 16, right: '50%', left: '-50%', height: 2, background: i <= cur ? C.green : C.rule, zIndex: 0 }} />
          )}
          <div style={{ width: 32, height: 32, borderRadius: '50%', background: i < cur ? C.green : i === cur ? (rejected && i === cur ? '#9B2C2C' : C.gold) : 'transparent', border: `2px solid ${i < cur ? C.green : i === cur ? (rejected && i === cur ? '#9B2C2C' : C.gold) : C.rule}`, display: 'flex', alignItems: 'center', justifyContent: 'center', zIndex: 1, position: 'relative', marginBottom: 8 }}>
            {i < cur ? <CheckCircle2 size={15} color={C.offwhite} /> : (
              <span style={{ fontFamily: 'var(--font-mono)', fontSize: 11, fontWeight: 600, color: i === cur ? (rejected ? C.offwhite : C.greenDark) : C.muted }}>{String(i + 1).padStart(2, '0')}</span>
            )}
          </div>
          <div style={{ fontFamily: 'var(--font-body)', fontSize: 10.5, fontWeight: i === cur ? 700 : 400, color: i === cur ? C.ink : C.muted, textAlign: 'center', lineHeight: 1.3 }}>{step.label}</div>
          {i === cur && status === 'Perlu Revisi' && <div style={{ fontFamily: 'var(--font-body)', fontSize: 9, color: '#92400E', fontWeight: 700, marginTop: 2, textTransform: 'uppercase', letterSpacing: '0.05em' }}>Revisi</div>}
        </div>
      ))}
    </div>
  )
}

// ─── Overview ──────────────────────────────────────────────────────────────────
function OverviewView({ appStatus, setView }: { appStatus: AppStatus; setView: (v: View) => void }) {
  const noApp = appStatus === 'Draft'

  return (
    <div>
      <PageHeader title="Selamat datang, Najwa." subtitle="Pantau proses pengajuan magangmu dari satu tempat." />
      <div style={{ padding: '32px 36px' }}>
        {noApp ? (
          <NoApplicationCard setView={setView} />
        ) : (
          <ApplicationCard appStatus={appStatus} setView={setView} />
        )}
      </div>
    </div>
  )
}

function NoApplicationCard({ setView }: { setView: (v: View) => void }) {
  return (
    <div style={{ background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 10, padding: '56px', textAlign: 'center' }}>
      <div style={{ width: 56, height: 56, background: C.greenSoft, borderRadius: '50%', display: 'flex', alignItems: 'center', justifyContent: 'center', margin: '0 auto 20px' }}>
        <FileText size={26} color={C.greenMid} />
      </div>
      <h2 style={{ fontFamily: 'var(--font-display)', fontSize: 24, fontWeight: 400, color: C.ink, marginBottom: 10 }}>Belum ada pengajuan magang.</h2>
      <p style={{ fontFamily: 'var(--font-body)', fontSize: 14, color: C.muted, lineHeight: 1.7, marginBottom: 28, maxWidth: 400, margin: '0 auto 28px' }}>
        Ajukan magang di Bappeda Provinsi Lampung dengan melengkapi data diri dan mengunggah dokumen yang dipersyaratkan.
      </p>
      <button onClick={() => setView('form')} style={{ padding: '12px 28px', background: C.green, color: C.offwhite, border: 'none', borderRadius: 8, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 14, fontWeight: 700, display: 'inline-flex', alignItems: 'center', gap: 8 }}>
        Ajukan Magang Sekarang <ArrowRight size={15} />
      </button>
    </div>
  )
}

function ApplicationCard({ appStatus, setView }: { appStatus: AppStatus; setView: (v: View) => void }) {
  return (
    <div style={{ display: 'flex', flexDirection: 'column', gap: 20 }}>
      {/* Status alert banners based on status */}
      {appStatus === 'Perlu Revisi' && (
        <div style={{ background: '#FEF3C7', border: '1px solid #F59E0B', borderLeft: '4px solid #D97706', borderRadius: 8, padding: '14px 18px', display: 'flex', gap: 12 }}>
          <AlertCircle size={18} color="#D97706" style={{ flexShrink: 0, marginTop: 1 }} />
          <div>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 700, color: '#92400E', marginBottom: 4 }}>Berkas Perlu Diperbaiki</div>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 13, color: '#92400E', lineHeight: 1.6 }}>
              Catatan Sekretariat: "Transkrip nilai yang diunggah belum mencantumkan stempel resmi perguruan tinggi. Mohon ganti dengan yang asli."
            </div>
            <button onClick={() => setView('form')} style={{ marginTop: 10, padding: '7px 16px', background: '#D97706', color: '#FFFBEB', border: 'none', borderRadius: 6, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 700 }}>
              Perbaiki & Kirim Ulang
            </button>
          </div>
        </div>
      )}
      {appStatus === 'Diterima' && (
        <div style={{ background: C.greenSoft, border: `1px solid #A7D4CB`, borderLeft: `4px solid ${C.green}`, borderRadius: 8, padding: '14px 18px', display: 'flex', gap: 12 }}>
          <CheckCircle2 size={18} color={C.green} style={{ flexShrink: 0, marginTop: 1 }} />
          <div>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 700, color: C.greenDark, marginBottom: 4 }}>Pengajuan Diterima</div>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 13, color: C.greenMid, lineHeight: 1.6 }}>
              Anda diterima magang di Bidang Perencanaan & Evaluasi Pembangunan. Surat penerimaan sedang diproses oleh Bappeda dan akan tersedia untuk diunduh.
            </div>
          </div>
        </div>
      )}
      {appStatus === 'Ditolak' && (
        <div style={{ background: '#FEE2E2', border: '1px solid #FECACA', borderLeft: '4px solid #DC2626', borderRadius: 8, padding: '14px 18px', display: 'flex', gap: 12, alignItems: 'flex-start' }}>
          <XCircle size={18} color="#DC2626" style={{ flexShrink: 0, marginTop: 1 }} />
          <div style={{ flex: 1 }}>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 700, color: '#991B1B', marginBottom: 4 }}>Pengajuan Ditolak</div>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 13, color: '#991B1B', lineHeight: 1.6, marginBottom: 10 }}>
              Alasan: "Kuota bidang yang dipilih sudah penuh untuk periode ini. Silakan mengajukan kembali pada kesempatan berikutnya."
            </div>
            <button onClick={() => setView('form')} style={{ padding: '7px 16px', background: C.green, color: C.offwhite, border: 'none', borderRadius: 6, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 700, display: 'inline-flex', alignItems: 'center', gap: 6 }}>
              <RotateCcw size={13} /> Ajukan Kembali
            </button>
          </div>
        </div>
      )}

      {/* Application card */}
      <div style={{ background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 10, overflow: 'hidden' }}>
        <div style={{ padding: '20px 24px', borderBottom: `1px solid ${C.rule}`, display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
          <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
            <span style={{ fontFamily: 'var(--font-body)', fontSize: 12, fontWeight: 700, letterSpacing: '0.10em', color: C.muted, textTransform: 'uppercase' }}>Pengajuan Aktif</span>
            <span style={{ fontFamily: 'var(--font-mono)', fontSize: 13, fontWeight: 600, color: C.greenMid, background: C.greenSoft, padding: '2px 10px', borderRadius: 4 }}>PGJ-2026-0047</span>
          </div>
          <StatusBadge status={appStatus} />
        </div>
        <div style={{ padding: '24px' }}>
          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: '12px 24px', marginBottom: 28 }}>
            {[
              { label: 'Preferensi Bidang', value: 'Perencanaan & Evaluasi Pembangunan' },
              { label: 'Penempatan Final', value: appStatus === 'Diterima' || appStatus === 'Sedang Magang' || appStatus === 'Selesai' ? 'Perencanaan & Evaluasi Pembangunan' : '—' },
              { label: 'Tanggal Pengajuan', value: '05 Agustus 2026' },
            ].map(({ label, value }) => (
              <div key={label}>
                <div style={{ fontFamily: 'var(--font-body)', fontSize: 10.5, fontWeight: 700, letterSpacing: '0.08em', color: C.muted, textTransform: 'uppercase', marginBottom: 3 }}>{label}</div>
                <div style={{ fontFamily: 'var(--font-body)', fontSize: 14, fontWeight: 500, color: C.ink }}>{value}</div>
              </div>
            ))}
          </div>
          <div style={{ marginBottom: 20 }}>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.10em', color: C.muted, textTransform: 'uppercase', marginBottom: 16 }}>Tahapan Proses</div>
            <LifecycleStepper status={appStatus} />
          </div>
          <button onClick={() => setView('status')} style={{ display: 'inline-flex', alignItems: 'center', gap: 6, background: 'none', border: 'none', cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 600, color: C.green, padding: 0 }}>
            Lihat riwayat lengkap <ArrowRight size={14} />
          </button>
        </div>
      </div>

      {/* Document download (Diterima / Selesai) */}
      {(appStatus === 'Diterima' || appStatus === 'Sedang Magang' || appStatus === 'Selesai') && (
        <DocAvailableCard appStatus={appStatus} setView={setView} />
      )}

      {/* Undurkan diri (Diterima / Sedang Magang) */}
      {(appStatus === 'Diterima' || appStatus === 'Sedang Magang') && (
        <ResignationCard />
      )}

      {/* Activity */}
      <ActivityTimeline appStatus={appStatus} />
    </div>
  )
}

function DocAvailableCard({ appStatus, setView }: { appStatus: AppStatus; setView: (v: View) => void }) {
  const suratPenerimaanAvailable = appStatus === 'Diterima' || appStatus === 'Sedang Magang' || appStatus === 'Selesai'
  const dokumenAkhirAvailable = appStatus === 'Selesai'

  return (
    <div style={{ background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 10, overflow: 'hidden' }}>
      <div style={{ padding: '16px 24px', borderBottom: `1px solid ${C.rule}` }}>
        <div style={{ fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 700, color: C.ink }}>Dokumen Resmi</div>
        <div style={{ fontFamily: 'var(--font-body)', fontSize: 12, color: C.muted }}>Dokumen yang diterbitkan Bappeda — diproses di luar sistem, diunggah oleh Sekretariat.</div>
      </div>
      <div>
        {[
          { nama: 'Surat Penerimaan Magang', tersedia: suratPenerimaanAvailable, uploadedDate: '09 Agu 2026' },
          { nama: 'Surat Keterangan Selesai Magang', tersedia: dokumenAkhirAvailable, uploadedDate: '15 Okt 2026' },
          { nama: 'Sertifikat Magang', tersedia: dokumenAkhirAvailable, uploadedDate: '15 Okt 2026' },
        ].map((doc, i, arr) => (
          <div key={doc.nama} style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', padding: '14px 24px', borderBottom: i < arr.length - 1 ? `1px solid ${C.rule}` : 'none' }}>
            <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
              <div style={{ width: 34, height: 34, background: doc.tersedia ? C.greenSoft : C.ivory, borderRadius: 6, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                <FileText size={16} color={doc.tersedia ? C.greenMid : C.muted} />
              </div>
              <div>
                <div style={{ fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 600, color: C.ink }}>{doc.nama}</div>
                <div style={{ fontFamily: 'var(--font-body)', fontSize: 11.5, color: C.muted }}>
                  {doc.tersedia ? `Tersedia · diunggah ${doc.uploadedDate}` : 'Sedang diproses oleh Bappeda'}
                </div>
              </div>
            </div>
            {doc.tersedia ? (
              <button style={{ display: 'flex', alignItems: 'center', gap: 7, padding: '8px 16px', background: C.green, color: C.offwhite, border: 'none', borderRadius: 7, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 600 }}>
                <Download size={14} /> Unduh
              </button>
            ) : (
              <div style={{ display: 'flex', alignItems: 'center', gap: 6, fontFamily: 'var(--font-body)', fontSize: 12.5, color: C.muted }}>
                <Clock size={13} /> Belum tersedia
              </div>
            )}
          </div>
        ))}
      </div>
    </div>
  )
}

function ResignationCard() {
  const [show, setShow] = useState(false)
  return (
    <div style={{ background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 10, padding: '16px 24px' }}>
      <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', gap: 16 }}>
        <div>
          <div style={{ fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 700, color: C.ink, marginBottom: 4 }}>Mengundurkan Diri</div>
          <p style={{ fontFamily: 'var(--font-body)', fontSize: 12.5, color: C.muted, lineHeight: 1.6, maxWidth: 480 }}>
            Jika Anda perlu mengundurkan diri, unggah surat resmi pengunduran diri dari perguruan tinggi (dengan alasan yang jelas dan profesional). Sekretariat akan memverifikasinya.
          </p>
        </div>
        <button onClick={() => setShow(!show)} style={{ flexShrink: 0, padding: '8px 16px', background: 'none', border: `1px solid ${C.rule}`, borderRadius: 7, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 600, color: C.muted }}>
          {show ? 'Tutup' : 'Proses Pengunduran Diri'}
        </button>
      </div>
      {show && (
        <div style={{ marginTop: 16, paddingTop: 16, borderTop: `1px solid ${C.rule}` }}>
          <div style={{ fontFamily: 'var(--font-body)', fontSize: 12.5, fontWeight: 700, color: C.ink, marginBottom: 8 }}>
            Unggah Surat Pengunduran Diri Resmi (dari Perguruan Tinggi)
            <span style={{ color: '#9B2C2C', marginLeft: 4 }}>*</span>
          </div>
          <div style={{ border: `2px dashed ${C.rule}`, borderRadius: 8, padding: '20px', textAlign: 'center', background: C.offwhite, marginBottom: 12 }}>
            <Upload size={20} color={C.muted} style={{ margin: '0 auto 8px', display: 'block' }} />
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 13, color: C.muted }}>PDF · Maks 5 MB</div>
            <button style={{ marginTop: 10, padding: '7px 16px', background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 6, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13, color: C.ink }}>Pilih File</button>
          </div>
          <button style={{ padding: '10px 20px', background: '#9B2C2C', color: C.offwhite, border: 'none', borderRadius: 7, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 700 }}>
            Kirim Permohonan Pengunduran Diri
          </button>
        </div>
      )}
    </div>
  )
}

function ActivityTimeline({ appStatus }: { appStatus: AppStatus }) {
  const events: { date: string; title: string; desc: string; type: 'ok' | 'warn' | 'err' | 'neutral' }[] = []

  if (appStatus === 'Selesai') {
    events.push({ date: '15 Okt 2026', title: 'Magang Selesai', desc: 'Periode magang telah berakhir. Dokumen akhir sedang disiapkan oleh Bappeda.', type: 'ok' })
    events.push({ date: '10 Jan 2027', title: 'Mulai Magang', desc: 'Pelaksanaan magang dimulai. Pembina: Ibu Sari Dewi.', type: 'ok' })
  }
  if (['Diterima', 'Sedang Magang', 'Selesai'].includes(appStatus))
    events.push({ date: '09 Agu 2026', title: 'Pengajuan Diterima', desc: 'Anda diterima di Bidang Perencanaan & Evaluasi Pembangunan.', type: 'ok' })
  if (['Cek Kebutuhan Divisi', 'Diterima', 'Ditolak', 'Sedang Magang', 'Selesai'].includes(appStatus))
    events.push({ date: '08 Agu 2026', title: 'Berkas Lengkap — Cek Kebutuhan Divisi', desc: 'Berkas dinyatakan lengkap. Sekretariat sedang mengecek ketersediaan divisi.', type: 'ok' })
  if (appStatus === 'Perlu Revisi')
    events.push({ date: '07 Agu 2026', title: 'Perlu Revisi Berkas', desc: 'Sekretariat meminta perbaikan: Transkrip nilai belum berstempel resmi.', type: 'warn' })
  if (appStatus === 'Ditolak')
    events.push({ date: '08 Agu 2026', title: 'Pengajuan Ditolak', desc: 'Alasan: Kuota bidang yang dipilih sudah penuh.', type: 'err' })
  events.push({ date: '05 Agu 2026', title: 'Pengajuan Dikirimkan', desc: 'Pengajuan berhasil dikirimkan dan masuk antrean pemeriksaan.', type: 'ok' })

  const iconMap = { ok: <CheckCircle2 size={15} color={C.green} />, warn: <AlertCircle size={15} color="#D97706" />, err: <XCircle size={15} color="#DC2626" />, neutral: <div style={{ width: 15, height: 15, borderRadius: '50%', border: `2px solid ${C.rule}` }} /> }

  return (
    <div style={{ background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 10, overflow: 'hidden' }}>
      <div style={{ padding: '16px 24px', borderBottom: `1px solid ${C.rule}` }}>
        <div style={{ fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 700, color: C.ink }}>Riwayat Aktivitas</div>
      </div>
      <div>
        {events.map((ev, i) => (
          <div key={i} style={{ display: 'flex', gap: 16, padding: '14px 24px', borderBottom: i < events.length - 1 ? `1px solid ${C.rule}` : 'none' }}>
            <div style={{ flexShrink: 0, marginTop: 2 }}>{iconMap[ev.type]}</div>
            <div style={{ flex: 1 }}>
              <div style={{ fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 600, color: C.ink, marginBottom: 2 }}>{ev.title}</div>
              <div style={{ fontFamily: 'var(--font-body)', fontSize: 13, color: C.muted, lineHeight: 1.5, marginBottom: 3 }}>{ev.desc}</div>
              <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, color: C.muted }}>{ev.date}</div>
            </div>
          </div>
        ))}
      </div>
    </div>
  )
}

// ─── Form Pengajuan ────────────────────────────────────────────────────────────
function FormPengajuan({ setView }: { setView: (v: View) => void }) {
  const [step, setStep] = useState(0)
  const steps = [
    { num: '01', label: 'Data Diri' },
    { num: '02', label: 'Preferensi Divisi' },
    { num: '03', label: 'Dokumen' },
    { num: '04', label: 'Review & Kirim' },
  ]

  return (
    <div>
      <PageHeader title="Form Pengajuan Magang" subtitle="Isi data dengan lengkap dan benar. Pengajuan bersifat rolling — dibuka sepanjang waktu." />
      <div style={{ padding: '32px 36px' }}>
        {/* Step indicator */}
        <div style={{ background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 10, padding: '20px 28px', marginBottom: 24, display: 'flex', alignItems: 'center' }}>
          {steps.map((s, i) => (
            <div key={s.num} style={{ flex: 1, display: 'flex', alignItems: 'center' }}>
              <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', flex: 1 }}>
                <div style={{ width: 32, height: 32, borderRadius: '50%', background: i < step ? C.green : i === step ? C.gold : 'transparent', border: `2px solid ${i < step ? C.green : i === step ? C.gold : C.rule}`, display: 'flex', alignItems: 'center', justifyContent: 'center', marginBottom: 6 }}>
                  {i < step ? <CheckCircle2 size={15} color={C.offwhite} /> : <span style={{ fontFamily: 'var(--font-mono)', fontSize: 11, fontWeight: 600, color: i === step ? C.greenDark : C.muted }}>{s.num}</span>}
                </div>
                <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: i === step ? 700 : 400, color: i === step ? C.ink : C.muted, textAlign: 'center' }}>{s.label}</div>
              </div>
              {i < steps.length - 1 && <div style={{ height: 1, width: 20, background: i < step ? C.green : C.rule, marginBottom: 18, flexShrink: 0 }} />}
            </div>
          ))}
        </div>

        <div style={{ display: 'grid', gridTemplateColumns: '1fr 300px', gap: 24 }}>
          <div style={{ background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 10, padding: '28px' }}>
            {step === 0 && <StepDataDiri />}
            {step === 1 && <StepDivisi />}
            {step === 2 && <StepDokumen />}
            {step === 3 && <StepReview setView={setView} />}

            {step < 3 && (
              <div style={{ display: 'flex', justifyContent: 'space-between', marginTop: 32, paddingTop: 24, borderTop: `1px solid ${C.rule}` }}>
                <button onClick={() => step > 0 && setStep(step - 1)} style={{ padding: '10px 24px', background: 'none', border: `1px solid ${C.rule}`, borderRadius: 8, cursor: step === 0 ? 'not-allowed' : 'pointer', fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 600, color: C.muted, opacity: step === 0 ? 0.4 : 1 }}>Kembali</button>
                <div style={{ display: 'flex', gap: 12 }}>
                  <button style={{ padding: '10px 20px', background: 'none', border: `1px solid ${C.rule}`, borderRadius: 8, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 600, color: C.muted }}>Simpan Draft</button>
                  <button onClick={() => setStep(step + 1)} style={{ padding: '10px 28px', background: C.green, border: 'none', borderRadius: 8, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 700, color: C.offwhite, display: 'flex', alignItems: 'center', gap: 8 }}>
                    Lanjutkan <ArrowRight size={14} />
                  </button>
                </div>
              </div>
            )}
          </div>

          <div style={{ display: 'flex', flexDirection: 'column', gap: 14 }}>
            <div style={{ background: C.greenSoft, border: '1px solid #C3DBD6', borderRadius: 10, padding: '18px 20px' }}>
              <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.10em', color: C.greenMid, textTransform: 'uppercase', marginBottom: 8 }}>Dokumen Wajib</div>
              {['Surat Lamaran / Surat Pengantar', 'Curriculum Vitae (CV)', 'Transkrip Nilai Terbaru'].map(d => (
                <div key={d} style={{ display: 'flex', alignItems: 'flex-start', gap: 8, marginBottom: 6 }}>
                  <CheckCircle2 size={13} color={C.greenMid} style={{ flexShrink: 0, marginTop: 2 }} />
                  <span style={{ fontFamily: 'var(--font-body)', fontSize: 12.5, color: C.greenMid, lineHeight: 1.4 }}>{d}</span>
                </div>
              ))}
              <div style={{ fontFamily: 'var(--font-body)', fontSize: 11.5, color: C.greenMid, marginTop: 10, paddingTop: 10, borderTop: '1px solid #A7D4CB' }}>+ Dokumen tambahan opsional dapat dilampirkan.</div>
            </div>
            <div style={{ background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 10, padding: '14px 18px' }}>
              <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.10em', color: C.muted, textTransform: 'uppercase', marginBottom: 8 }}>Catatan</div>
              <p style={{ fontFamily: 'var(--font-body)', fontSize: 12.5, color: C.muted, lineHeight: 1.6 }}>
                Pengajuan bersifat <strong>rolling</strong> — tidak ada batas waktu pendaftaran. Preferensi divisi bersifat non-mengikat; penempatan final adalah keputusan Sekretariat Bappeda.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}

function FInput({ label, required, type = 'text', placeholder, helper, children }: any) {
  return (
    <div style={{ marginBottom: 18 }}>
      <label style={{ display: 'block', fontFamily: 'var(--font-body)', fontSize: 12.5, fontWeight: 700, color: C.ink, marginBottom: 6 }}>
        {label}{required && <span style={{ color: '#9B2C2C', marginLeft: 3 }}>*</span>}
      </label>
      {children ?? <input type={type} placeholder={placeholder} style={{ width: '100%', padding: '10px 14px', background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 8, fontFamily: 'var(--font-body)', fontSize: 14, color: C.ink, outline: 'none' }} />}
      {helper && <div style={{ fontFamily: 'var(--font-body)', fontSize: 11.5, color: C.muted, marginTop: 5 }}>{helper}</div>}
    </div>
  )
}

function StepDataDiri() {
  return (
    <div>
      <h2 style={{ fontFamily: 'var(--font-display)', fontSize: 22, fontWeight: 400, color: C.ink, marginBottom: 20 }}>Data Diri Mahasiswa</h2>
      <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '0 20px' }}>
        <FInput label="Nama Lengkap" required />
        <FInput label="NIM / NPM" required />
        <FInput label="Perguruan Tinggi" required />
        <FInput label="Program Studi / Jurusan" required />
        <FInput label="Semester Aktif" required helper="Semester saat pengajuan diajukan" />
        <FInput label="Nomor HP Aktif" required type="tel" />
      </div>
      <FInput label="Alamat Email Aktif" required type="email" helper="Email untuk notifikasi status pengajuan." />
      <FInput label="Alamat Lengkap" required>
        <textarea rows={2} style={{ width: '100%', padding: '10px 14px', background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 8, fontFamily: 'var(--font-body)', fontSize: 14, color: C.ink, outline: 'none', resize: 'vertical' }} />
      </FInput>
    </div>
  )
}

function StepDivisi() {
  const divisis = ['Perencanaan & Evaluasi Pembangunan', 'Penelitian & Pengembangan', 'Infrastruktur & Tata Ruang', 'Teknologi Informasi', 'Keuangan & Administrasi Umum']
  return (
    <div>
      <h2 style={{ fontFamily: 'var(--font-display)', fontSize: 22, fontWeight: 400, color: C.ink, marginBottom: 8 }}>Preferensi Divisi / Bidang</h2>
      <p style={{ fontFamily: 'var(--font-body)', fontSize: 13.5, color: C.muted, marginBottom: 24, lineHeight: 1.6 }}>
        Pilih divisi yang sesuai dengan minat dan program studi Anda. Preferensi ini <strong>tidak mengikat</strong> — penempatan final adalah keputusan Sekretariat Bappeda berdasarkan kebutuhan dan kapasitas divisi.
      </p>
      <FInput label="Preferensi Divisi / Bidang" helper="Pilih satu pilihan yang paling sesuai.">
        <select style={{ width: '100%', padding: '10px 14px', background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 8, fontFamily: 'var(--font-body)', fontSize: 14, color: C.ink, outline: 'none' }}>
          <option value="">Pilih divisi...</option>
          {divisis.map(d => <option key={d}>{d}</option>)}
        </select>
      </FInput>
      <FInput label="Rencana Periode Magang" helper="Perkiraan tanggal mulai dan selesai yang Anda harapkan.">
        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 12 }}>
          <input type="date" style={{ width: '100%', padding: '10px 14px', background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 8, fontFamily: 'var(--font-body)', fontSize: 14, color: C.ink, outline: 'none' }} />
          <input type="date" style={{ width: '100%', padding: '10px 14px', background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 8, fontFamily: 'var(--font-body)', fontSize: 14, color: C.ink, outline: 'none' }} />
        </div>
      </FInput>
      <FInput label="Motivasi & Kompetensi Relevan" helper="Opsional — jelaskan mengapa Anda memilih divisi ini dan kompetensi yang Anda miliki.">
        <textarea rows={4} placeholder="Tuliskan motivasi dan kompetensi Anda..." style={{ width: '100%', padding: '10px 14px', background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 8, fontFamily: 'var(--font-body)', fontSize: 14, color: C.ink, outline: 'none', resize: 'vertical' }} />
      </FInput>
    </div>
  )
}

function StepDokumen() {
  const docs = [
    { label: 'Surat Lamaran / Surat Pengantar Kampus', required: true, format: 'PDF', note: 'Ditandatangani oleh pejabat berwenang di kampus.' },
    { label: 'Curriculum Vitae (CV)', required: true, format: 'PDF', note: '' },
    { label: 'Transkrip Nilai Terbaru', required: true, format: 'PDF', note: 'Harap menggunakan transkrip berlegalisir atau berstempel resmi.' },
    { label: 'Dokumen / Surat Tambahan', required: false, format: 'PDF / JPG / PNG', note: 'Opsional — lampirkan jika relevan (portofolio, sertifikat, dll.).' },
  ]

  return (
    <div>
      <h2 style={{ fontFamily: 'var(--font-display)', fontSize: 22, fontWeight: 400, color: C.ink, marginBottom: 8 }}>Unggah Dokumen</h2>
      <p style={{ fontFamily: 'var(--font-body)', fontSize: 13.5, color: C.muted, marginBottom: 24 }}>
        Dokumen lama tidak dihapus saat diganti — histori tersimpan otomatis oleh sistem.
      </p>
      <div style={{ border: `1px solid ${C.rule}`, borderRadius: 8, overflow: 'hidden' }}>
        <div style={{ display: 'grid', gridTemplateColumns: '2fr 80px 100px', padding: '10px 16px', background: C.ivory, borderBottom: `1px solid ${C.rule}` }}>
          {['Dokumen', 'Status', 'Aksi'].map(h => <div key={h} style={{ fontFamily: 'var(--font-body)', fontSize: 10.5, fontWeight: 700, letterSpacing: '0.08em', color: C.muted, textTransform: 'uppercase' }}>{h}</div>)}
        </div>
        {docs.map((doc, i) => (
          <div key={i} style={{ display: 'grid', gridTemplateColumns: '2fr 80px 100px', padding: '14px 16px', borderBottom: i < docs.length - 1 ? `1px solid ${C.rule}` : 'none', alignItems: 'center', gap: 8 }}>
            <div>
              <div style={{ fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 600, color: C.ink, marginBottom: 2 }}>
                {doc.label}{doc.required && <span style={{ color: '#9B2C2C', marginLeft: 4 }}>*</span>}
              </div>
              {doc.note && <div style={{ fontFamily: 'var(--font-body)', fontSize: 11.5, color: C.muted }}>{doc.note}</div>}
              <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, color: C.muted, marginTop: 2 }}>{doc.format}</div>
            </div>
            <div>
              {doc.required ? <span style={{ fontFamily: 'var(--font-body)', fontSize: 10, fontWeight: 700, letterSpacing: '0.06em', color: '#9B2C2C', background: '#FEE2E2', padding: '2px 7px', borderRadius: 4, textTransform: 'uppercase' }}>Wajib</span>
                : <span style={{ fontFamily: 'var(--font-body)', fontSize: 10, fontWeight: 700, color: C.muted, background: C.ivory, padding: '2px 7px', borderRadius: 4, textTransform: 'uppercase' }}>Opsional</span>}
            </div>
            <button style={{ display: 'flex', alignItems: 'center', gap: 6, padding: '7px 12px', background: C.greenSoft, border: '1px solid #C3DBD6', borderRadius: 6, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 12.5, fontWeight: 600, color: C.greenMid }}>
              <Upload size={13} /> Unggah
            </button>
          </div>
        ))}
      </div>
    </div>
  )
}

function StepReview({ setView }: { setView: (v: View) => void }) {
  return (
    <div>
      <h2 style={{ fontFamily: 'var(--font-display)', fontSize: 22, fontWeight: 400, color: C.ink, marginBottom: 20 }}>Tinjau & Kirimkan Pengajuan</h2>
      {[
        { title: 'Data Diri Mahasiswa', items: [['Nama', 'Najwa Ramadhani'], ['NIM', '2021010234'], ['Perguruan Tinggi', 'Universitas Lampung'], ['Program Studi', 'Teknik Informatika'], ['Semester', '9']] },
        { title: 'Preferensi Divisi', items: [['Divisi / Bidang', 'Perencanaan & Evaluasi Pembangunan'], ['Rencana Periode', '10 Jan 2027 – 10 Apr 2027']] },
        { title: 'Dokumen', items: [['Surat Lamaran', '✓ Diunggah'], ['CV', '✓ Diunggah'], ['Transkrip Nilai', '✓ Diunggah']] },
      ].map(sec => (
        <div key={sec.title} style={{ background: C.ivory, borderRadius: 8, padding: '16px 20px', marginBottom: 14 }}>
          <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.10em', color: C.muted, textTransform: 'uppercase', marginBottom: 12 }}>{sec.title}</div>
          {sec.items.map(([k, v]) => (
            <div key={k} style={{ display: 'grid', gridTemplateColumns: '160px 1fr', gap: 8, marginBottom: 8 }}>
              <span style={{ fontFamily: 'var(--font-body)', fontSize: 13, color: C.muted }}>{k}</span>
              <span style={{ fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 600, color: C.ink }}>{v}</span>
            </div>
          ))}
        </div>
      ))}
      <div style={{ background: C.goldSoft, border: `1px solid ${C.gold}40`, borderRadius: 8, padding: '14px 16px', display: 'flex', gap: 10, marginBottom: 24 }}>
        <Info size={16} color={C.gold} style={{ flexShrink: 0, marginTop: 1 }} />
        <p style={{ fontFamily: 'var(--font-body)', fontSize: 13, color: '#7A5A00', lineHeight: 1.55 }}>
          Pastikan seluruh data dan dokumen sudah benar sebelum mengirimkan. Pengajuan yang sudah dikirim dapat direvisi jika diminta oleh Sekretariat.
        </p>
      </div>
      <button onClick={() => setView('status')} style={{ width: '100%', padding: '14px', background: C.green, color: C.offwhite, border: 'none', borderRadius: 8, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 15, fontWeight: 700 }}>
        Kirimkan Pengajuan
      </button>
    </div>
  )
}

// ─── Status View ───────────────────────────────────────────────────────────────
function StatusView({ appStatus }: { appStatus: AppStatus }) {
  return (
    <div>
      <PageHeader title="Status Pengajuan" subtitle="Detail tahapan dan riwayat lengkap pengajuan Anda." />
      <div style={{ padding: '32px 36px', display: 'flex', flexDirection: 'column', gap: 20 }}>
        <div style={{ background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 10, padding: '24px', display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', gap: 16 }}>
          <div>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.10em', color: C.muted, textTransform: 'uppercase', marginBottom: 4 }}>Nomor Pengajuan</div>
            <div style={{ fontFamily: 'var(--font-mono)', fontSize: 26, fontWeight: 600, color: C.green, marginBottom: 8 }}>PGJ-2026-0047</div>
            {appStatus === 'Ditolak' && (
              <div style={{ fontFamily: 'var(--font-body)', fontSize: 13, color: '#991B1B', background: '#FEE2E2', border: '1px solid #FECACA', borderRadius: 6, padding: '8px 12px', marginTop: 8, maxWidth: 440 }}>
                <strong>Alasan Penolakan:</strong> Kuota bidang yang dipilih sudah penuh untuk periode ini. Silakan mengajukan kembali pada kesempatan berikutnya.
              </div>
            )}
          </div>
          <StatusBadge status={appStatus} size="large" />
        </div>

        <div style={{ background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 10, padding: '24px' }}>
          <div style={{ fontFamily: 'var(--font-body)', fontSize: 12, fontWeight: 700, letterSpacing: '0.10em', color: C.muted, textTransform: 'uppercase', marginBottom: 20 }}>Tahapan Proses</div>
          <LifecycleStepper status={appStatus} />
        </div>

        <ActivityTimeline appStatus={appStatus} />
      </div>
    </div>
  )
}

// ─── Dokumen View ──────────────────────────────────────────────────────────────
function DokumenView({ appStatus }: { appStatus: AppStatus }) {
  const myDocs = [
    { nama: 'Surat Lamaran / Surat Pengantar', ver: 'v1', tanggal: '05 Agu 2026', format: 'PDF', status: 'Valid' },
    { nama: 'Curriculum Vitae', ver: 'v2', tanggal: '05 Agu 2026', format: 'PDF', status: 'Valid' },
    { nama: 'Transkrip Nilai', ver: 'v1', tanggal: '05 Agu 2026', format: 'PDF', status: appStatus === 'Perlu Revisi' ? 'Perlu Revisi' : 'Valid' },
  ]
  const resmiDocs = [
    { nama: 'Surat Penerimaan Magang', tersedia: ['Diterima','Sedang Magang','Selesai'].includes(appStatus), tanggal: '09 Agu 2026' },
    { nama: 'Surat Keterangan Selesai Magang', tersedia: appStatus === 'Selesai', tanggal: '15 Okt 2026' },
    { nama: 'Sertifikat Magang', tersedia: appStatus === 'Selesai', tanggal: '15 Okt 2026' },
  ]
  const docBadge = (s: string) => {
    const m: Record<string, { bg: string; color: string }> = { 'Valid': { bg: C.greenSoft, color: C.greenMid }, 'Perlu Revisi': { bg: '#FEF3C7', color: '#92400E' }, 'Belum Diperiksa': { bg: C.ivory, color: C.muted } }
    const st = m[s] ?? { bg: C.ivory, color: C.muted }
    return <span style={{ fontFamily: 'var(--font-body)', fontSize: 10.5, fontWeight: 700, letterSpacing: '0.05em', textTransform: 'uppercase', background: st.bg, color: st.color, padding: '3px 8px', borderRadius: 4 }}>{s}</span>
  }

  return (
    <div>
      <PageHeader title="Dokumen" subtitle="Dokumen yang Anda unggah dan dokumen resmi dari Bappeda." />
      <div style={{ padding: '32px 36px', display: 'flex', flexDirection: 'column', gap: 20 }}>
        {/* My uploaded docs */}
        <div style={{ background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 10, overflow: 'hidden' }}>
          <div style={{ padding: '16px 24px', borderBottom: `1px solid ${C.rule}` }}>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 700, color: C.ink }}>Dokumen Pengajuan Saya</div>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 12, color: C.muted }}>Versi lama disimpan sebagai histori — tidak dihapus saat diperbarui.</div>
          </div>
          <div style={{ display: 'grid', gridTemplateColumns: '2fr 80px 140px 110px 110px', padding: '10px 20px', background: C.ivory, borderBottom: `1px solid ${C.rule}` }}>
            {['Dokumen', 'Versi', 'Tanggal', 'Status', 'Aksi'].map(h => <div key={h} style={{ fontFamily: 'var(--font-body)', fontSize: 10.5, fontWeight: 700, letterSpacing: '0.08em', color: C.muted, textTransform: 'uppercase' }}>{h}</div>)}
          </div>
          {myDocs.map((doc, i) => (
            <div key={i} style={{ display: 'grid', gridTemplateColumns: '2fr 80px 140px 110px 110px', padding: '13px 20px', borderBottom: i < myDocs.length - 1 ? `1px solid ${C.rule}` : 'none', alignItems: 'center' }}>
              <div style={{ fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 600, color: C.ink }}>{doc.nama}<div style={{ fontSize: 11.5, color: C.muted, fontWeight: 400 }}>{doc.format}</div></div>
              <div style={{ fontFamily: 'var(--font-mono)', fontSize: 12.5, color: C.muted }}>{doc.ver}</div>
              <div style={{ fontFamily: 'var(--font-body)', fontSize: 12.5, color: C.muted }}>{doc.tanggal}</div>
              <div>{docBadge(doc.status)}</div>
              <div style={{ display: 'flex', gap: 8 }}>
                <button style={{ background: 'none', border: 'none', cursor: 'pointer', color: C.muted, padding: 4, display: 'flex', alignItems: 'center' }}><Eye size={15} /></button>
                <button style={{ background: 'none', border: 'none', cursor: 'pointer', color: C.muted, padding: 4, display: 'flex', alignItems: 'center' }}><Upload size={15} /></button>
              </div>
            </div>
          ))}
        </div>

        {/* Resmi docs */}
        <div style={{ background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 10, overflow: 'hidden' }}>
          <div style={{ padding: '16px 24px', borderBottom: `1px solid ${C.rule}` }}>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 700, color: C.ink }}>Dokumen Resmi dari Bappeda</div>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 12, color: C.muted }}>Diterbitkan dan diunggah oleh Sekretariat setelah selesai proses administrasi.</div>
          </div>
          {resmiDocs.map((doc, i) => (
            <div key={i} style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', padding: '14px 24px', borderBottom: i < resmiDocs.length - 1 ? `1px solid ${C.rule}` : 'none' }}>
              <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
                <div style={{ width: 34, height: 34, background: doc.tersedia ? C.greenSoft : C.ivory, borderRadius: 6, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                  <FileText size={16} color={doc.tersedia ? C.greenMid : C.muted} />
                </div>
                <div>
                  <div style={{ fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 600, color: C.ink }}>{doc.nama}</div>
                  <div style={{ fontFamily: 'var(--font-body)', fontSize: 11.5, color: C.muted }}>{doc.tersedia ? `Tersedia · ${doc.tanggal}` : 'Belum tersedia — sedang diproses'}</div>
                </div>
              </div>
              {doc.tersedia ? (
                <button style={{ display: 'flex', alignItems: 'center', gap: 7, padding: '8px 16px', background: C.green, color: C.offwhite, border: 'none', borderRadius: 7, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 600 }}>
                  <Download size={14} /> Unduh
                </button>
              ) : (
                <span style={{ fontFamily: 'var(--font-body)', fontSize: 12, color: C.muted, display: 'flex', alignItems: 'center', gap: 5 }}><Clock size={13} /> Menunggu</span>
              )}
            </div>
          ))}
        </div>
      </div>
    </div>
  )
}

// ─── Profil View ───────────────────────────────────────────────────────────────
function ProfilView() {
  return (
    <div>
      <PageHeader title="Profil Saya" subtitle="Data diri yang digunakan untuk pengajuan magang." />
      <div style={{ padding: '32px 36px' }}>
        <div style={{ background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 10, overflow: 'hidden' }}>
          <div style={{ padding: '24px', borderBottom: `1px solid ${C.rule}`, display: 'flex', alignItems: 'center', gap: 16 }}>
            <div style={{ width: 56, height: 56, borderRadius: '50%', background: C.greenSoft, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
              <span style={{ fontFamily: 'var(--font-display)', fontSize: 24, color: C.green }}>N</span>
            </div>
            <div>
              <div style={{ fontFamily: 'var(--font-body)', fontSize: 18, fontWeight: 700, color: C.ink }}>Najwa Ramadhani</div>
              <div style={{ fontFamily: 'var(--font-body)', fontSize: 13, color: C.muted }}>najwa.ramadhani@student.unila.ac.id</div>
            </div>
          </div>
          <div style={{ padding: '24px', display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '16px 32px' }}>
            {[
              ['NIM / NPM', '2021010234'], ['Perguruan Tinggi', 'Universitas Lampung'],
              ['Program Studi', 'Teknik Informatika'], ['Semester', '9'],
              ['Nomor HP', '+62 812 3456 7890'], ['Terdaftar Sejak', '01 Agustus 2026'],
            ].map(([k, v]) => (
              <div key={k}>
                <div style={{ fontFamily: 'var(--font-body)', fontSize: 10.5, fontWeight: 700, letterSpacing: '0.08em', color: C.muted, textTransform: 'uppercase', marginBottom: 4 }}>{k}</div>
                <div style={{ fontFamily: 'var(--font-body)', fontSize: 14, fontWeight: 500, color: C.ink }}>{v}</div>
              </div>
            ))}
          </div>
          <div style={{ padding: '20px 24px', borderTop: `1px solid ${C.rule}` }}>
            <button style={{ padding: '10px 20px', background: C.green, color: C.offwhite, border: 'none', borderRadius: 8, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 600 }}>Edit Profil</button>
          </div>
        </div>
      </div>
    </div>
  )
}
