import { useState } from 'react'
import {
  LayoutDashboard, FileText, FolderOpen, LogOut, Bell,
  CheckCircle2, Clock, AlertCircle, XCircle, Eye, Search,
  Upload, Download, ChevronRight, ArrowLeft, BarChart2, FileCheck,
} from 'lucide-react'
import type { Page } from '../App'
import type { AppStatus } from './StudentDashboard'

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

interface Props { navigate: (p: Page) => void; onLogout: () => void }
type View = 'dashboard' | 'list' | 'detail' | 'laporan'

// ─── Sample data ───────────────────────────────────────────────────────────────
interface Pengajuan {
  id: string; nama: string; nim: string; pt: string; prodi: string
  divisiPreferensi: string; divisiPenempatan?: string
  tglPengajuan: string; status: AppStatus; dokumenTersedia?: boolean
  catatan?: string; alasanTolak?: string
}

const PENGAJUANS: Pengajuan[] = [
  { id: 'PGJ-2026-0047', nama: 'Najwa Ramadhani', nim: '2021010234', pt: 'Unila', prodi: 'Teknik Informatika', divisiPreferensi: 'Perencanaan & Evaluasi', tglPengajuan: '05 Agu 2026', status: 'Diperiksa' },
  { id: 'PGJ-2026-0046', nama: 'Rizal Maulana', nim: '2020031456', pt: 'UBL', prodi: 'Manajemen', divisiPreferensi: 'Keuangan & Administrasi', tglPengajuan: '04 Agu 2026', status: 'Cek Kebutuhan Divisi' },
  { id: 'PGJ-2026-0045', nama: 'Siti Aisyah', nim: '2021054321', pt: 'Unila', prodi: 'Statistika', divisiPreferensi: 'Penelitian & Pengembangan', tglPengajuan: '03 Agu 2026', status: 'Perlu Revisi', catatan: 'KTP tidak terbaca jelas, harap unggah ulang.' },
  { id: 'PGJ-2026-0044', nama: 'Budi Santoso', nim: '2019078901', pt: 'IIB Darmajaya', prodi: 'Sistem Informasi', divisiPreferensi: 'Teknologi Informasi', divisiPenempatan: 'Teknologi Informasi', tglPengajuan: '01 Agu 2026', status: 'Diterima', dokumenTersedia: true },
  { id: 'PGJ-2026-0043', nama: 'Dewi Kurnia', nim: '2020092345', pt: 'Unila', prodi: 'Ekonomi Pembangunan', divisiPreferensi: 'Perencanaan & Evaluasi', tglPengajuan: '28 Jul 2026', status: 'Ditolak', alasanTolak: 'Kuota Bidang Perencanaan & Evaluasi sudah penuh untuk periode ini.' },
  { id: 'PGJ-2026-0042', nama: 'Ahmad Firdaus', nim: '2020015678', pt: 'Unila', prodi: 'Hukum', divisiPreferensi: 'Keuangan & Administrasi', divisiPenempatan: 'Keuangan & Administrasi', tglPengajuan: '25 Jul 2026', status: 'Sedang Magang' },
  { id: 'PGJ-2026-0041', nama: 'Rina Puspita', nim: '2019034567', pt: 'UBL', prodi: 'Akuntansi', divisiPreferensi: 'Keuangan & Administrasi', divisiPenempatan: 'Keuangan & Administrasi', tglPengajuan: '20 Jul 2026', status: 'Selesai', dokumenTersedia: true },
  { id: 'PGJ-2026-0040', nama: 'Hendra Wijaya', nim: '2020045678', pt: 'IIB Darmajaya', prodi: 'Teknik Komputer', divisiPreferensi: 'Teknologi Informasi', tglPengajuan: '18 Jul 2026', status: 'Diajukan' },
]

const STATUS_COLORS: Record<string, { bg: string; color: string }> = {
  'Draft': { bg: C.ivory, color: C.muted },
  'Diajukan': { bg: '#EFF6FF', color: '#1D4ED8' },
  'Diperiksa': { bg: C.goldSoft, color: '#7A5A00' },
  'Perlu Revisi': { bg: '#FEF3C7', color: '#92400E' },
  'Cek Kebutuhan Divisi': { bg: '#FEF3C7', color: '#7A5A00' },
  'Diterima': { bg: C.greenSoft, color: C.greenMid },
  'Ditolak': { bg: '#FEE2E2', color: '#991B1B' },
  'Sedang Magang': { bg: C.greenSoft, color: C.green },
  'Mengundurkan Diri': { bg: C.ivory, color: C.muted },
  'Selesai': { bg: '#D1FAE5', color: '#065F46' },
}

function StatusBadge({ status }: { status: string }) {
  const s = STATUS_COLORS[status] ?? { bg: C.ivory, color: C.muted }
  return (
    <span style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.05em', textTransform: 'uppercase', background: s.bg, color: s.color, padding: '3px 9px', borderRadius: 4, display: 'inline-block', whiteSpace: 'nowrap' }}>{status}</span>
  )
}

// ─── Component ─────────────────────────────────────────────────────────────────
export default function SekretariatDashboard({ navigate, onLogout }: Props) {
  const [view, setView] = useState<View>('dashboard')
  const [selectedId, setSelectedId] = useState<string>('PGJ-2026-0047')
  const [pengajuans, setPengajuans] = useState<Pengajuan[]>(PENGAJUANS)

  const selected = pengajuans.find(p => p.id === selectedId) ?? pengajuans[0]

  const openDetail = (id: string) => { setSelectedId(id); setView('detail') }

  const updateStatus = (id: string, newStatus: AppStatus, extra?: Partial<Pengajuan>) => {
    setPengajuans(prev => prev.map(p => p.id === id ? { ...p, status: newStatus, ...extra } : p))
  }

  return (
    <div style={{ minHeight: '100vh', display: 'grid', gridTemplateColumns: '252px 1fr', background: '#F1F3EE' }}>
      <SekSidebar view={view} setView={setView} onLogout={onLogout} />
      <div style={{ minHeight: '100vh', overflow: 'auto' }}>
        {view === 'dashboard' && <DashboardView pengajuans={pengajuans} openDetail={openDetail} />}
        {view === 'list' && <ListView pengajuans={pengajuans} openDetail={openDetail} />}
        {view === 'detail' && selected && (
          <DetailView
            p={selected}
            onBack={() => setView('list')}
            updateStatus={updateStatus}
          />
        )}
        {view === 'laporan' && <LaporanView pengajuans={pengajuans} />}
      </div>
    </div>
  )
}

function SekSidebar({ view, setView, onLogout }: { view: View; setView: (v: View) => void; onLogout: () => void }) {
  const items = [
    { icon: <LayoutDashboard size={16} />, label: 'Dashboard', v: 'dashboard' as View },
    { icon: <FileText size={16} />, label: 'Daftar Pengajuan', v: 'list' as View },
    { icon: <BarChart2 size={16} />, label: 'Laporan & Ekspor', v: 'laporan' as View },
  ]

  return (
    <aside style={{ background: C.greenDark, display: 'flex', flexDirection: 'column', padding: '0 0 24px', position: 'sticky', top: 0, height: '100vh' }}>
      <div style={{ padding: '24px 20px', borderBottom: '1px solid rgba(255,255,255,0.08)' }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 20 }}>
          <div style={{ width: 34, height: 34, background: C.gold, borderRadius: 6, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
            <span style={{ fontFamily: 'var(--font-display)', fontSize: 18, color: C.greenDark }}>S</span>
          </div>
          <div>
            <div style={{ fontFamily: 'var(--font-body)', fontWeight: 800, fontSize: 14, color: C.offwhite }}>SIMAGANG</div>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 9, color: 'rgba(255,255,255,0.35)', letterSpacing: '0.06em', textTransform: 'uppercase' }}>Sekretariat</div>
          </div>
        </div>
        <div style={{ background: 'rgba(217,165,29,0.12)', border: '1px solid rgba(217,165,29,0.2)', borderRadius: 7, padding: '10px 12px', display: 'flex', alignItems: 'center', gap: 10 }}>
          <div style={{ width: 28, height: 28, borderRadius: '50%', background: 'rgba(255,255,255,0.1)', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
            <span style={{ fontFamily: 'var(--font-body)', fontWeight: 700, fontSize: 13, color: C.offwhite }}>S</span>
          </div>
          <div>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 600, color: C.offwhite }}>Sari Dewi, S.IP</div>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 10, color: 'rgba(255,255,255,0.4)' }}>Kasubag Umum & Kepegawaian</div>
          </div>
        </div>
      </div>
      <nav style={{ flex: 1, padding: '16px 12px' }}>
        <div style={{ fontFamily: 'var(--font-body)', fontSize: 9, fontWeight: 700, letterSpacing: '0.14em', color: 'rgba(255,255,255,0.28)', textTransform: 'uppercase', padding: '0 8px', marginBottom: 8 }}>Menu</div>
        {items.map(({ icon, label, v }) => (
          <button key={v} onClick={() => setView(v)} style={{ display: 'flex', alignItems: 'center', gap: 10, width: '100%', padding: '9px 10px', background: view === v ? 'rgba(255,255,255,0.1)' : 'none', border: 'none', borderRadius: 7, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: view === v ? 600 : 400, color: view === v ? C.offwhite : 'rgba(255,255,255,0.55)', textAlign: 'left', marginBottom: 2, borderLeft: view === v ? `3px solid ${C.gold}` : '3px solid transparent' }}>
            <span style={{ opacity: view === v ? 1 : 0.7 }}>{icon}</span>{label}
          </button>
        ))}
      </nav>
      <div style={{ padding: '0 12px', borderTop: '1px solid rgba(255,255,255,0.08)', paddingTop: 16 }}>
        <button onClick={onLogout} style={{ display: 'flex', alignItems: 'center', gap: 10, width: '100%', padding: '9px 10px', background: 'none', border: 'none', cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13, color: 'rgba(255,255,255,0.38)', textAlign: 'left' }}>
          <LogOut size={15} /> Keluar
        </button>
      </div>
    </aside>
  )
}

function PageHeader({ title, subtitle }: { title: string; subtitle?: string }) {
  return (
    <div style={{ background: C.offwhite, borderBottom: `1px solid ${C.rule}`, padding: '24px 36px', display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between' }}>
      <div>
        <h1 style={{ fontFamily: 'var(--font-display)', fontSize: 26, fontWeight: 400, color: C.ink, letterSpacing: '-0.01em', marginBottom: 2 }}>{title}</h1>
        {subtitle && <p style={{ fontFamily: 'var(--font-body)', fontSize: 13.5, color: C.muted }}>{subtitle}</p>}
      </div>
      <button style={{ position: 'relative', background: 'none', border: 'none', cursor: 'pointer', color: C.muted, padding: 8 }}>
        <Bell size={18} />
        <span style={{ position: 'absolute', top: 6, right: 6, width: 7, height: 7, background: C.gold, borderRadius: '50%' }} />
      </button>
    </div>
  )
}

// ─── Dashboard View ────────────────────────────────────────────────────────────
function DashboardView({ pengajuans, openDetail }: { pengajuans: Pengajuan[]; openDetail: (id: string) => void }) {
  const counts = {
    diajukan: pengajuans.filter(p => p.status === 'Diajukan').length,
    diperiksa: pengajuans.filter(p => p.status === 'Diperiksa').length,
    perluRevisi: pengajuans.filter(p => p.status === 'Perlu Revisi').length,
    cekDivisi: pengajuans.filter(p => p.status === 'Cek Kebutuhan Divisi').length,
    diterima: pengajuans.filter(p => p.status === 'Diterima').length,
    sedangMagang: pengajuans.filter(p => p.status === 'Sedang Magang').length,
    selesai: pengajuans.filter(p => p.status === 'Selesai').length,
    total: pengajuans.length,
  }

  const needAction = pengajuans.filter(p => ['Diajukan', 'Diperiksa', 'Cek Kebutuhan Divisi'].includes(p.status))

  return (
    <div>
      <PageHeader title="Dashboard Sekretariat" subtitle={`Senin, 11 Agustus 2026 · Bappeda Provinsi Lampung`} />
      <div style={{ padding: '28px 36px' }}>
        {/* Stats */}
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(4, 1fr)', gap: 0, background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 10, overflow: 'hidden', marginBottom: 24 }}>
          {[
            { label: 'Perlu Ditindaklanjuti', value: counts.diajukan + counts.diperiksa + counts.cekDivisi, color: '#D97706', note: 'Diajukan + Diperiksa + Cek Divisi' },
            { label: 'Perlu Revisi', value: counts.perluRevisi, color: '#B45309', note: 'Menunggu mahasiswa memperbaiki' },
            { label: 'Sedang Magang', value: counts.sedangMagang, color: C.greenMid, note: 'Aktif menjalani magang' },
            { label: 'Total Pengajuan', value: counts.total, color: C.ink, note: `${counts.selesai} selesai · ${counts.diterima} diterima` },
          ].map(({ label, value, color, note }, i) => (
            <div key={label} style={{ padding: '20px', borderLeft: i > 0 ? `1px solid ${C.rule}` : 'none' }}>
              <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.08em', color: C.muted, textTransform: 'uppercase', marginBottom: 8 }}>{label}</div>
              <div style={{ fontFamily: 'var(--font-mono)', fontSize: 32, fontWeight: 600, color, lineHeight: 1, marginBottom: 4 }}>{value}</div>
              <div style={{ fontFamily: 'var(--font-body)', fontSize: 11.5, color: C.muted }}>{note}</div>
            </div>
          ))}
        </div>

        <div style={{ display: 'grid', gridTemplateColumns: '1fr 300px', gap: 24 }}>
          {/* Need action */}
          <div style={{ background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 10, overflow: 'hidden' }}>
            <div style={{ padding: '16px 24px', borderBottom: `1px solid ${C.rule}`, display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
              <div>
                <div style={{ fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 700, color: C.ink }}>Perlu Ditindaklanjuti</div>
                <div style={{ fontFamily: 'var(--font-body)', fontSize: 12, color: C.muted }}>Pengajuan yang menunggu tindakan Sekretariat</div>
              </div>
              <span style={{ fontFamily: 'var(--font-mono)', fontSize: 13, fontWeight: 700, color: '#D97706', background: '#FEF3C7', padding: '2px 10px', borderRadius: 4 }}>{needAction.length}</span>
            </div>
            {needAction.length === 0 ? (
              <div style={{ padding: '40px 24px', textAlign: 'center' }}>
                <CheckCircle2 size={32} color={C.greenMid} style={{ margin: '0 auto 12px', display: 'block' }} />
                <div style={{ fontFamily: 'var(--font-body)', fontSize: 14, color: C.muted }}>Tidak ada pengajuan yang perlu ditindaklanjuti.</div>
              </div>
            ) : (
              <div>
                {needAction.slice(0, 5).map((p, i) => (
                  <button key={p.id} onClick={() => openDetail(p.id)} style={{ display: 'flex', alignItems: 'center', gap: 16, width: '100%', padding: '14px 24px', borderBottom: i < Math.min(needAction.length, 5) - 1 ? `1px solid ${C.rule}` : 'none', background: 'none', border: 'none', cursor: 'pointer', textAlign: 'left' }}>
                    <div style={{ flex: 1 }}>
                      <div style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 3 }}>
                        <span style={{ fontFamily: 'var(--font-mono)', fontSize: 12, color: C.greenMid, fontWeight: 600 }}>{p.id}</span>
                        <StatusBadge status={p.status} />
                      </div>
                      <div style={{ fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 600, color: C.ink }}>{p.nama}</div>
                      <div style={{ fontFamily: 'var(--font-body)', fontSize: 12, color: C.muted }}>{p.pt} · {p.prodi} · {p.tglPengajuan}</div>
                    </div>
                    <ChevronRight size={16} color={C.muted} />
                  </button>
                ))}
              </div>
            )}
          </div>

          {/* Status summary */}
          <div style={{ background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 10, overflow: 'hidden' }}>
            <div style={{ padding: '16px 20px', borderBottom: `1px solid ${C.rule}` }}>
              <div style={{ fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 700, color: C.ink }}>Distribusi Status</div>
            </div>
            <div style={{ padding: '8px 0' }}>
              {([
                ['Diajukan', counts.diajukan],
                ['Diperiksa', counts.diperiksa],
                ['Perlu Revisi', counts.perluRevisi],
                ['Cek Kebutuhan Divisi', counts.cekDivisi],
                ['Diterima', counts.diterima],
                ['Sedang Magang', counts.sedangMagang],
                ['Selesai', counts.selesai],
              ] as [string, number][]).map(([s, n]) => {
                const pct = counts.total ? Math.round((n / counts.total) * 100) : 0
                const c = STATUS_COLORS[s] ?? { bg: C.ivory, color: C.muted }
                return (
                  <div key={s} style={{ padding: '10px 20px', display: 'flex', alignItems: 'center', gap: 10 }}>
                    <div style={{ width: 8, height: 8, borderRadius: '50%', background: c.color, flexShrink: 0 }} />
                    <div style={{ flex: 1, fontFamily: 'var(--font-body)', fontSize: 12.5, color: C.ink }}>{s}</div>
                    <div style={{ width: 60, height: 4, background: C.rule, borderRadius: 2, overflow: 'hidden' }}>
                      <div style={{ width: `${pct}%`, height: '100%', background: c.color, borderRadius: 2 }} />
                    </div>
                    <div style={{ fontFamily: 'var(--font-mono)', fontSize: 13, fontWeight: 600, color: C.ink, width: 20, textAlign: 'right' }}>{n}</div>
                  </div>
                )
              })}
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}

// ─── List View ─────────────────────────────────────────────────────────────────
function ListView({ pengajuans, openDetail }: { pengajuans: Pengajuan[]; openDetail: (id: string) => void }) {
  const [search, setSearch] = useState('')
  const [filterStatus, setFilterStatus] = useState('')

  const filtered = pengajuans.filter(p =>
    (search === '' || p.nama.toLowerCase().includes(search.toLowerCase()) || p.id.includes(search)) &&
    (filterStatus === '' || p.status === filterStatus)
  )

  return (
    <div>
      <PageHeader title="Daftar Pengajuan" subtitle={`${pengajuans.length} total · ${filtered.length} ditampilkan`} />
      <div style={{ padding: '24px 36px' }}>
        <div style={{ display: 'flex', gap: 12, marginBottom: 20, flexWrap: 'wrap' }}>
          <div style={{ position: 'relative', flex: '1 1 240px' }}>
            <Search size={15} style={{ position: 'absolute', left: 12, top: '50%', transform: 'translateY(-50%)', color: C.muted }} />
            <input value={search} onChange={e => setSearch(e.target.value)} placeholder="Cari nama / nomor pengajuan..." style={{ width: '100%', padding: '9px 14px 9px 36px', background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 8, fontFamily: 'var(--font-body)', fontSize: 13.5, color: C.ink, outline: 'none' }} />
          </div>
          <select value={filterStatus} onChange={e => setFilterStatus(e.target.value)} style={{ padding: '9px 14px', background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 8, fontFamily: 'var(--font-body)', fontSize: 13.5, color: filterStatus ? C.ink : C.muted, outline: 'none', cursor: 'pointer' }}>
            <option value="">Semua Status</option>
            {['Diajukan','Diperiksa','Perlu Revisi','Cek Kebutuhan Divisi','Diterima','Ditolak','Sedang Magang','Mengundurkan Diri','Selesai'].map(s => <option key={s}>{s}</option>)}
          </select>
          <select style={{ padding: '9px 14px', background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 8, fontFamily: 'var(--font-body)', fontSize: 13.5, color: C.muted, outline: 'none', cursor: 'pointer' }}>
            <option>Semua Divisi</option>
            <option>Perencanaan & Evaluasi</option>
            <option>Penelitian & Pengembangan</option>
            <option>Infrastruktur & Tata Ruang</option>
            <option>Teknologi Informasi</option>
            <option>Keuangan & Administrasi</option>
          </select>
          <button style={{ padding: '9px 16px', background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 8, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13.5, color: C.muted, display: 'flex', alignItems: 'center', gap: 6 }}>
            <Download size={14} /> Ekspor
          </button>
        </div>

        <div style={{ background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 10, overflow: 'hidden' }}>
          <div style={{ overflowX: 'auto' }}>
            <table style={{ width: '100%', borderCollapse: 'collapse', minWidth: 860 }}>
              <thead>
                <tr style={{ background: C.ivory }}>
                  {['No. Pengajuan', 'Mahasiswa', 'Perguruan Tinggi', 'Divisi Preferensi', 'Status', 'Tanggal', ''].map(h => (
                    <th key={h} style={{ padding: '12px 16px', textAlign: 'left', fontFamily: 'var(--font-body)', fontSize: 10.5, fontWeight: 700, letterSpacing: '0.08em', color: C.muted, textTransform: 'uppercase', whiteSpace: 'nowrap', borderBottom: `1px solid ${C.rule}` }}>{h}</th>
                  ))}
                </tr>
              </thead>
              <tbody>
                {filtered.length === 0 ? (
                  <tr><td colSpan={7} style={{ padding: '48px', textAlign: 'center' }}>
                    <div style={{ fontFamily: 'var(--font-display)', fontSize: 20, color: C.muted, marginBottom: 8 }}>Tidak ada pengajuan ditemukan.</div>
                    <div style={{ fontFamily: 'var(--font-body)', fontSize: 13.5, color: C.muted }}>Coba ubah filter pencarian.</div>
                  </td></tr>
                ) : filtered.map((p, i) => (
                  <tr key={p.id} style={{ borderBottom: i < filtered.length - 1 ? `1px solid ${C.rule}` : 'none' }}>
                    <td style={{ padding: '13px 16px' }}><span style={{ fontFamily: 'var(--font-mono)', fontSize: 12.5, color: C.greenMid, fontWeight: 600 }}>{p.id}</span></td>
                    <td style={{ padding: '13px 16px' }}>
                      <div style={{ fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 600, color: C.ink }}>{p.nama}</div>
                      <div style={{ fontFamily: 'var(--font-body)', fontSize: 11.5, color: C.muted }}>{p.nim} · {p.prodi}</div>
                    </td>
                    <td style={{ padding: '13px 16px', fontFamily: 'var(--font-body)', fontSize: 13, color: C.muted }}>{p.pt}</td>
                    <td style={{ padding: '13px 16px', fontFamily: 'var(--font-body)', fontSize: 13, color: C.muted }}>{p.divisiPreferensi}</td>
                    <td style={{ padding: '13px 16px' }}><StatusBadge status={p.status} /></td>
                    <td style={{ padding: '13px 16px', fontFamily: 'var(--font-body)', fontSize: 12.5, color: C.muted, whiteSpace: 'nowrap' }}>{p.tglPengajuan}</td>
                    <td style={{ padding: '13px 16px' }}>
                      <button onClick={() => openDetail(p.id)} style={{ display: 'flex', alignItems: 'center', gap: 5, padding: '6px 14px', background: C.greenSoft, border: '1px solid #C3DBD6', borderRadius: 6, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 12, fontWeight: 600, color: C.greenMid, whiteSpace: 'nowrap' }}>
                        <Eye size={13} /> Buka
                      </button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  )
}

// ─── Detail View ───────────────────────────────────────────────────────────────
function DetailView({ p, onBack, updateStatus }: { p: Pengajuan; onBack: () => void; updateStatus: (id: string, s: AppStatus, extra?: Partial<Pengajuan>) => void }) {
  const [catatanRevisi, setCatatanRevisi] = useState(p.catatan ?? '')
  const [alasanTolak, setAlasanTolak] = useState(p.alasanTolak ?? '')
  const [divisiPenempatan, setDivisiPenempatan] = useState(p.divisiPenempatan ?? '')
  const [catatanInternal, setCatatanInternal] = useState('')

  const divisis = ['Perencanaan & Evaluasi Pembangunan', 'Penelitian & Pengembangan', 'Infrastruktur & Tata Ruang', 'Teknologi Informasi', 'Keuangan & Administrasi Umum']

  return (
    <div>
      <div style={{ background: C.offwhite, borderBottom: `1px solid ${C.rule}`, padding: '20px 36px', display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 16 }}>
          <button onClick={onBack} style={{ display: 'flex', alignItems: 'center', gap: 7, background: 'none', border: 'none', cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13, color: C.muted, padding: 0 }}>
            <ArrowLeft size={15} /> Daftar Pengajuan
          </button>
          <div style={{ width: 1, height: 18, background: C.rule }} />
          <div>
            <span style={{ fontFamily: 'var(--font-mono)', fontSize: 14, color: C.green, fontWeight: 600 }}>{p.id}</span>
            <span style={{ fontFamily: 'var(--font-body)', fontSize: 14, color: C.muted, marginLeft: 10 }}>{p.nama}</span>
          </div>
        </div>
        <StatusBadge status={p.status} />
      </div>

      <div style={{ padding: '24px 36px' }}>
        <div style={{ display: 'grid', gridTemplateColumns: '1fr 360px', gap: 20 }}>
          {/* Left */}
          <div style={{ display: 'flex', flexDirection: 'column', gap: 18 }}>
            {/* Applicant */}
            <Section title="Data Mahasiswa">
              <Grid2 items={[
                ['Nama Lengkap', p.nama], ['NIM / NPM', p.nim],
                ['Perguruan Tinggi', p.pt], ['Program Studi', p.prodi],
                ['Divisi Preferensi', p.divisiPreferensi], ['Tanggal Pengajuan', p.tglPengajuan],
              ]} />
            </Section>

            {/* Penempatan (if decided) */}
            {p.divisiPenempatan && (
              <Section title="Penempatan Final">
                <Grid2 items={[['Divisi / Bidang', p.divisiPenempatan]]} />
              </Section>
            )}

            {/* Documents */}
            <Section title="Dokumen Pengajuan Mahasiswa">
              {[
                { nama: 'Surat Lamaran / Pengantar', format: 'PDF', size: '1.2 MB' },
                { nama: 'Curriculum Vitae', format: 'PDF', size: '0.8 MB' },
                { nama: 'Transkrip Nilai', format: 'PDF', size: '2.1 MB' },
              ].map((doc, i, arr) => (
                <div key={doc.nama} style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', padding: '12px 0', borderBottom: i < arr.length - 1 ? `1px solid ${C.rule}` : 'none' }}>
                  <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
                    <div style={{ width: 32, height: 32, background: C.greenSoft, borderRadius: 6, display: 'flex', alignItems: 'center', justifyContent: 'center' }}><FileText size={15} color={C.greenMid} /></div>
                    <div>
                      <div style={{ fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 600, color: C.ink }}>{doc.nama}</div>
                      <div style={{ fontFamily: 'var(--font-body)', fontSize: 11.5, color: C.muted }}>{doc.format} · {doc.size}</div>
                    </div>
                  </div>
                  <button style={{ display: 'flex', alignItems: 'center', gap: 6, padding: '6px 14px', background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 6, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 12.5, fontWeight: 600, color: C.muted }}>
                    <Eye size={13} /> Lihat
                  </button>
                </div>
              ))}
            </Section>

            {/* Alasan Tolak (if rejected) */}
            {p.status === 'Ditolak' && p.alasanTolak && (
              <div style={{ background: '#FEE2E2', border: '1px solid #FECACA', borderRadius: 10, padding: '16px 20px' }}>
                <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.10em', color: '#991B1B', textTransform: 'uppercase', marginBottom: 8 }}>Alasan Penolakan</div>
                <p style={{ fontFamily: 'var(--font-body)', fontSize: 13.5, color: '#991B1B', lineHeight: 1.6 }}>{p.alasanTolak}</p>
                <div style={{ fontFamily: 'var(--font-body)', fontSize: 11.5, color: '#991B1B', marginTop: 8, opacity: 0.7 }}>Mahasiswa telah dinotifikasi secara otomatis. Tidak ada surat penolakan formal.</div>
              </div>
            )}

            {/* Upload dokumen final (Diterima / Selesai) */}
            {(p.status === 'Diterima' || p.status === 'Sedang Magang') && (
              <Section title="Unggah Surat Penerimaan">
                <p style={{ fontFamily: 'var(--font-body)', fontSize: 13, color: C.muted, lineHeight: 1.6, marginBottom: 14 }}>
                  Surat penerimaan telah selesai diproses secara administratif di luar sistem. Unggah dokumen final agar mahasiswa dapat mengunduhnya.
                </p>
                {p.dokumenTersedia ? (
                  <div style={{ display: 'flex', alignItems: 'center', gap: 12, background: C.greenSoft, border: '1px solid #A7D4CB', borderRadius: 8, padding: '12px 16px' }}>
                    <CheckCircle2 size={18} color={C.green} />
                    <div>
                      <div style={{ fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 600, color: C.greenDark }}>Surat Penerimaan sudah diunggah</div>
                      <div style={{ fontFamily: 'var(--font-body)', fontSize: 12, color: C.greenMid }}>Mahasiswa dapat mengunduh · Diunggah 09 Agu 2026</div>
                    </div>
                    <button style={{ marginLeft: 'auto', display: 'flex', alignItems: 'center', gap: 5, padding: '6px 12px', background: 'none', border: `1px solid ${C.rule}`, borderRadius: 6, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 12, color: C.muted }}>
                      <Eye size={13} /> Lihat
                    </button>
                  </div>
                ) : (
                  <UploadBox label="Surat Penerimaan (Final)" note="PDF · Maks 10 MB · Sudah ditandatangani pejabat berwenang" onUpload={() => updateStatus(p.id, p.status, { dokumenTersedia: true })} />
                )}
              </Section>
            )}

            {p.status === 'Selesai' && (
              <Section title="Unggah Dokumen Akhir Magang">
                <p style={{ fontFamily: 'var(--font-body)', fontSize: 13, color: C.muted, lineHeight: 1.6, marginBottom: 14 }}>
                  Unggah dokumen yang diterbitkan Bappeda setelah magang selesai. Hanya unggah dokumen yang benar-benar diterbitkan.
                </p>
                {[
                  { nama: 'Surat Keterangan Selesai Magang', tersedia: p.dokumenTersedia },
                  { nama: 'Sertifikat Magang', tersedia: p.dokumenTersedia },
                  { nama: 'Surat Keterangan Penilaian', tersedia: false },
                  { nama: 'Surat Pengembalian Peserta', tersedia: false },
                ].map((doc, i) => (
                  <div key={doc.nama} style={{ marginBottom: i < 3 ? 10 : 0 }}>
                    {doc.tersedia ? (
                      <div style={{ display: 'flex', alignItems: 'center', gap: 10, background: C.greenSoft, border: '1px solid #A7D4CB', borderRadius: 8, padding: '10px 14px' }}>
                        <CheckCircle2 size={16} color={C.green} />
                        <span style={{ fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 600, color: C.greenDark }}>{doc.nama}</span>
                        <span style={{ fontFamily: 'var(--font-body)', fontSize: 11.5, color: C.greenMid, marginLeft: 4 }}>· Tersedia</span>
                      </div>
                    ) : (
                      <UploadBox label={doc.nama} note="PDF · Opsional — unggah hanya jika dokumen ini diterbitkan Bappeda" onUpload={() => {}} />
                    )}
                  </div>
                ))}
              </Section>
            )}

            {/* Pelaksanaan data (Diterima / Sedang Magang / Selesai) */}
            {['Diterima', 'Sedang Magang', 'Selesai'].includes(p.status) && (
              <Section title="Data Pelaksanaan Magang">
                <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '0 16px' }}>
                  {[
                    { label: 'Pembina / Penanggung Jawab', placeholder: 'Nama pegawai pembina' },
                    { label: 'Tanggal Mulai Magang', placeholder: '' },
                    { label: 'Tanggal Selesai Magang', placeholder: '' },
                    { label: 'Divisi Penempatan Final', placeholder: '' },
                  ].map(f => (
                    <div key={f.label} style={{ marginBottom: 14 }}>
                      <label style={{ display: 'block', fontFamily: 'var(--font-body)', fontSize: 12.5, fontWeight: 700, color: C.ink, marginBottom: 6 }}>{f.label}</label>
                      <input type={f.label.includes('Tanggal') ? 'date' : 'text'} placeholder={f.placeholder}
                        defaultValue={f.label === 'Pembina / Penanggung Jawab' ? 'Ibu Sari Dewi, S.IP' : f.label === 'Divisi Penempatan Final' ? p.divisiPenempatan : ''}
                        style={{ width: '100%', padding: '9px 14px', background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 7, fontFamily: 'var(--font-body)', fontSize: 13.5, color: C.ink, outline: 'none' }}
                      />
                    </div>
                  ))}
                </div>
                <button style={{ padding: '9px 20px', background: C.green, color: C.offwhite, border: 'none', borderRadius: 7, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 600 }}>Simpan Data Pelaksanaan</button>
              </Section>
            )}

            {/* Resignation verification */}
            {p.status === 'Mengundurkan Diri' && (
              <Section title="Verifikasi Pengunduran Diri">
                <div style={{ background: C.goldSoft, border: `1px solid ${C.gold}40`, borderRadius: 8, padding: '14px 16px', marginBottom: 14 }}>
                  <div style={{ fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 700, color: '#7A5A00', marginBottom: 4 }}>Mahasiswa mengajukan pengunduran diri</div>
                  <div style={{ fontFamily: 'var(--font-body)', fontSize: 12.5, color: '#7A5A00' }}>Surat pengunduran diri resmi dari kampus telah diunggah. Harap verifikasi keaslian dan kelengkapan surat.</div>
                </div>
                <div style={{ display: 'flex', gap: 10, alignItems: 'center', marginBottom: 14 }}>
                  <button style={{ display: 'flex', alignItems: 'center', gap: 6, padding: '8px 16px', background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 6, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 600, color: C.muted }}>
                    <Eye size={14} /> Lihat Surat Pengunduran Diri
                  </button>
                </div>
                <div style={{ display: 'flex', gap: 10 }}>
                  <button onClick={() => updateStatus(p.id, 'Mengundurkan Diri')} style={{ padding: '9px 18px', background: '#9B2C2C', color: C.offwhite, border: 'none', borderRadius: 7, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 700 }}>Konfirmasi — Status "Mengundurkan Diri"</button>
                  <button style={{ padding: '9px 18px', background: 'none', border: `1px solid ${C.rule}`, borderRadius: 7, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 600, color: C.muted }}>Tolak — Surat Tidak Memenuhi Syarat</button>
                </div>
              </Section>
            )}

            {/* Catatan internal */}
            <Section title="Catatan Internal">
              <textarea value={catatanInternal} onChange={e => setCatatanInternal(e.target.value)} placeholder="Catatan internal untuk pengajuan ini (tidak terlihat mahasiswa)..." rows={3} style={{ width: '100%', padding: '10px 14px', background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 8, fontFamily: 'var(--font-body)', fontSize: 13, color: C.ink, outline: 'none', resize: 'vertical', marginBottom: 10 }} />
              <button style={{ padding: '8px 18px', background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 7, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 600, color: C.ink }}>Simpan Catatan</button>
            </Section>
          </div>

          {/* Right: actions */}
          <div style={{ display: 'flex', flexDirection: 'column', gap: 16 }}>
            {/* Action panel per status */}
            <ActionPanel p={p} catatanRevisi={catatanRevisi} setCatatanRevisi={setCatatanRevisi} alasanTolak={alasanTolak} setAlasanTolak={setAlasanTolak} divisiPenempatan={divisiPenempatan} setDivisiPenempatan={setDivisiPenempatan} divisis={divisis} updateStatus={updateStatus} />

            {/* Status history */}
            <Section title="Riwayat Status">
              {[
                { s: p.status, date: '07 Agu 2026', actor: 'Sari Dewi' },
                { s: 'Diperiksa', date: '06 Agu 2026', actor: 'Sistem (otomatis)' },
                { s: 'Diajukan', date: '05 Agu 2026', actor: p.nama },
              ].map((h, i, arr) => (
                <div key={i} style={{ display: 'flex', gap: 10, marginBottom: i < arr.length - 1 ? 14 : 0 }}>
                  <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', width: 18 }}>
                    <div style={{ width: 12, height: 12, borderRadius: '50%', background: C.green, flexShrink: 0 }} />
                    {i < arr.length - 1 && <div style={{ width: 1, flex: 1, background: C.rule, marginTop: 4 }} />}
                  </div>
                  <div style={{ flex: 1, paddingBottom: i < arr.length - 1 ? 14 : 0 }}>
                    <StatusBadge status={h.s} />
                    <div style={{ fontFamily: 'var(--font-body)', fontSize: 11.5, color: C.muted, marginTop: 4 }}>{h.actor} · {h.date}</div>
                  </div>
                </div>
              ))}
            </Section>
          </div>
        </div>
      </div>
    </div>
  )
}

function ActionPanel({ p, catatanRevisi, setCatatanRevisi, alasanTolak, setAlasanTolak, divisiPenempatan, setDivisiPenempatan, divisis, updateStatus }: any) {
  const { id, status } = p

  if (status === 'Diajukan') {
    return (
      <Section title="Tindakan — Periksa Berkas">
        <p style={{ fontFamily: 'var(--font-body)', fontSize: 13, color: C.muted, lineHeight: 1.6, marginBottom: 14 }}>Periksa kelengkapan dan kesesuaian dokumen yang diunggah mahasiswa.</p>
        <div style={{ display: 'flex', flexDirection: 'column', gap: 8 }}>
          <button onClick={() => updateStatus(id, 'Diperiksa')} style={{ ...btnStyle(C.green) }}>Mulai Pemeriksaan Berkas</button>
        </div>
      </Section>
    )
  }

  if (status === 'Diperiksa') {
    return (
      <Section title="Tindakan — Hasil Pemeriksaan">
        <p style={{ fontFamily: 'var(--font-body)', fontSize: 13, color: C.muted, lineHeight: 1.6, marginBottom: 16 }}>Tentukan hasil pemeriksaan kelengkapan berkas.</p>
        <div style={{ marginBottom: 14 }}>
          <label style={{ fontFamily: 'var(--font-body)', fontSize: 12.5, fontWeight: 700, color: C.ink, display: 'block', marginBottom: 6 }}>
            Catatan Revisi <span style={{ color: '#9B2C2C' }}>*</span>
          </label>
          <textarea value={catatanRevisi} onChange={(e: any) => setCatatanRevisi(e.target.value)} placeholder="Jelaskan berkas yang perlu diperbaiki secara rinci..." rows={3} style={{ width: '100%', padding: '9px 12px', background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 7, fontFamily: 'var(--font-body)', fontSize: 13, color: C.ink, outline: 'none', resize: 'vertical' }} />
        </div>
        <div style={{ display: 'flex', flexDirection: 'column', gap: 8 }}>
          <button onClick={() => updateStatus(id, 'Perlu Revisi', { catatan: catatanRevisi })} style={{ ...btnStyle('#D97706') }}>Minta Revisi Berkas</button>
          <div style={{ height: 1, background: C.rule, margin: '4px 0' }} />
          <button onClick={() => updateStatus(id, 'Cek Kebutuhan Divisi')} style={{ ...btnStyle(C.green) }}>Berkas Lengkap — Lanjut Cek Divisi</button>
        </div>
      </Section>
    )
  }

  if (status === 'Perlu Revisi') {
    return (
      <Section title="Status — Menunggu Revisi Mahasiswa">
        <div style={{ background: '#FEF3C7', border: '1px solid #F59E0B', borderRadius: 8, padding: '12px 14px', marginBottom: 12 }}>
          <div style={{ fontFamily: 'var(--font-body)', fontSize: 12, fontWeight: 700, color: '#92400E', marginBottom: 4 }}>Catatan yang dikirim ke mahasiswa:</div>
          <div style={{ fontFamily: 'var(--font-body)', fontSize: 13, color: '#92400E', lineHeight: 1.5 }}>{p.catatan || '(belum ada catatan)'}</div>
        </div>
        <p style={{ fontFamily: 'var(--font-body)', fontSize: 13, color: C.muted, lineHeight: 1.6 }}>Menunggu mahasiswa mengunggah berkas yang telah diperbaiki dan mengirimkan kembali pengajuannya.</p>
      </Section>
    )
  }

  if (status === 'Cek Kebutuhan Divisi') {
    return (
      <Section title="Tindakan — Keputusan Akhir">
        <p style={{ fontFamily: 'var(--font-body)', fontSize: 13, color: C.muted, lineHeight: 1.6, marginBottom: 16 }}>
          Berkas dinyatakan lengkap. Cek ketersediaan divisi secara manual (telepon/langsung), lalu tetapkan keputusan.
        </p>
        <div style={{ marginBottom: 14 }}>
          <label style={{ fontFamily: 'var(--font-body)', fontSize: 12.5, fontWeight: 700, color: C.ink, display: 'block', marginBottom: 6 }}>
            Divisi Penempatan Final <span style={{ color: '#9B2C2C' }}>*</span>
          </label>
          <select value={divisiPenempatan} onChange={(e: any) => setDivisiPenempatan(e.target.value)} style={{ width: '100%', padding: '9px 12px', background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 7, fontFamily: 'var(--font-body)', fontSize: 13.5, color: C.ink, outline: 'none' }}>
            <option value="">Pilih divisi penempatan...</option>
            {divisis.map((d: string) => <option key={d}>{d}</option>)}
          </select>
          <div style={{ fontFamily: 'var(--font-body)', fontSize: 11.5, color: C.muted, marginTop: 5 }}>Boleh berbeda dari preferensi mahasiswa. Tidak perlu persetujuan ulang dari mahasiswa.</div>
        </div>
        <button onClick={() => { if (divisiPenempatan) updateStatus(id, 'Diterima', { divisiPenempatan }) }} style={{ ...btnStyle(C.green), marginBottom: 8, opacity: divisiPenempatan ? 1 : 0.5, cursor: divisiPenempatan ? 'pointer' : 'not-allowed' }}>
          ✓ Terima — Status Langsung "Diterima"
        </button>
        <div style={{ height: 1, background: C.rule, margin: '8px 0' }} />
        <div style={{ marginBottom: 10 }}>
          <label style={{ fontFamily: 'var(--font-body)', fontSize: 12.5, fontWeight: 700, color: C.ink, display: 'block', marginBottom: 6 }}>
            Alasan Penolakan <span style={{ color: '#9B2C2C' }}>*</span>
          </label>
          <textarea value={alasanTolak} onChange={(e: any) => setAlasanTolak(e.target.value)} placeholder="Wajib diisi — alasan akan dikirim sebagai notifikasi ke mahasiswa..." rows={3} style={{ width: '100%', padding: '9px 12px', background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 7, fontFamily: 'var(--font-body)', fontSize: 13, color: C.ink, outline: 'none', resize: 'vertical' }} />
          <div style={{ fontFamily: 'var(--font-body)', fontSize: 11.5, color: C.muted, marginTop: 4 }}>Mahasiswa mendapat notifikasi berisi alasan ini. Tidak ada surat penolakan formal yang diterbitkan sistem.</div>
        </div>
        <button onClick={() => { if (alasanTolak.trim()) updateStatus(id, 'Ditolak', { alasanTolak }) }} style={{ ...btnStyle('#9B2C2C'), opacity: alasanTolak.trim() ? 1 : 0.5, cursor: alasanTolak.trim() ? 'pointer' : 'not-allowed' }}>
          ✗ Tolak Pengajuan & Kirim Notifikasi
        </button>
      </Section>
    )
  }

  if (status === 'Diterima') {
    return (
      <Section title="Status — Diterima">
        <div style={{ background: C.greenSoft, border: '1px solid #A7D4CB', borderRadius: 8, padding: '12px 14px', marginBottom: 12 }}>
          <div style={{ fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 700, color: C.greenDark, marginBottom: 4 }}>Mahasiswa telah diterima</div>
          <div style={{ fontFamily: 'var(--font-body)', fontSize: 12.5, color: C.greenMid }}>Penempatan: {p.divisiPenempatan || '—'}</div>
        </div>
        <p style={{ fontFamily: 'var(--font-body)', fontSize: 13, color: C.muted, lineHeight: 1.6, marginBottom: 12 }}>
          Surat penerimaan diproses di luar sistem oleh Bappeda. Setelah selesai, unggah dokumen final di panel kiri.
        </p>
        {!p.dokumenTersedia && (
          <div style={{ display: 'flex', alignItems: 'flex-start', gap: 8, background: C.goldSoft, border: `1px solid ${C.gold}40`, borderRadius: 7, padding: '10px 12px', marginBottom: 12 }}>
            <AlertCircle size={14} color={C.gold} style={{ flexShrink: 0, marginTop: 2 }} />
            <span style={{ fontFamily: 'var(--font-body)', fontSize: 12.5, color: '#7A5A00' }}>Surat penerimaan belum diunggah. Mahasiswa melihat status "sedang diproses".</span>
          </div>
        )}
        <button onClick={() => updateStatus(id, 'Sedang Magang')} style={{ ...btnStyle(C.green) }}>
          Tandai Mulai Magang → "Sedang Magang"
        </button>
      </Section>
    )
  }

  if (status === 'Sedang Magang') {
    return (
      <Section title="Status — Sedang Magang">
        <p style={{ fontFamily: 'var(--font-body)', fontSize: 13, color: C.muted, lineHeight: 1.6, marginBottom: 12 }}>Mahasiswa sedang menjalani magang. Perbarui data pelaksanaan di panel kiri.</p>
        <button onClick={() => updateStatus(id, 'Selesai')} style={{ ...btnStyle(C.green), marginBottom: 8 }}>
          Tandai Selesai → "Selesai"
        </button>
      </Section>
    )
  }

  if (status === 'Selesai') {
    return (
      <Section title="Status — Selesai">
        <div style={{ background: '#D1FAE5', border: '1px solid #6EE7B7', borderRadius: 8, padding: '12px 14px' }}>
          <div style={{ fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 700, color: '#065F46', marginBottom: 4 }}>Magang telah selesai</div>
          <div style={{ fontFamily: 'var(--font-body)', fontSize: 12.5, color: '#065F46' }}>Unggah dokumen akhir magang di panel kiri agar dapat diunduh mahasiswa.</div>
        </div>
      </Section>
    )
  }

  if (status === 'Ditolak') {
    return (
      <Section title="Status — Ditolak">
        <div style={{ background: '#FEE2E2', border: '1px solid #FECACA', borderRadius: 8, padding: '12px 14px' }}>
          <div style={{ fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 700, color: '#991B1B', marginBottom: 4 }}>Pengajuan ditolak</div>
          <div style={{ fontFamily: 'var(--font-body)', fontSize: 12.5, color: '#991B1B' }}>Notifikasi dengan alasan penolakan telah dikirimkan ke mahasiswa. Tidak ada surat penolakan formal (sesuai PRD BR-012).</div>
        </div>
      </Section>
    )
  }

  return <Section title="Tidak ada tindakan"><p style={{ fontFamily: 'var(--font-body)', fontSize: 13, color: C.muted }}>Pengajuan ini tidak memerlukan tindakan lebih lanjut.</p></Section>
}

function btnStyle(bg: string): React.CSSProperties {
  return { width: '100%', padding: '11px', background: bg, color: '#FCFBF7', border: 'none', borderRadius: 8, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 700, textAlign: 'center' }
}

function Section({ title, children }: { title: string; children: React.ReactNode }) {
  return (
    <div style={{ background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 10, overflow: 'hidden' }}>
      <div style={{ padding: '13px 20px', borderBottom: `1px solid ${C.rule}` }}>
        <div style={{ fontFamily: 'var(--font-body)', fontSize: 11.5, fontWeight: 700, letterSpacing: '0.10em', color: C.muted, textTransform: 'uppercase' }}>{title}</div>
      </div>
      <div style={{ padding: '18px 20px' }}>{children}</div>
    </div>
  )
}

function Grid2({ items }: { items: [string, string][] }) {
  return (
    <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '10px 20px' }}>
      {items.map(([k, v]) => (
        <div key={k}>
          <div style={{ fontFamily: 'var(--font-body)', fontSize: 10.5, fontWeight: 700, letterSpacing: '0.08em', color: C.muted, textTransform: 'uppercase', marginBottom: 3 }}>{k}</div>
          <div style={{ fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 500, color: C.ink }}>{v}</div>
        </div>
      ))}
    </div>
  )
}

function UploadBox({ label, note, onUpload }: { label: string; note: string; onUpload: () => void }) {
  return (
    <div style={{ border: `2px dashed ${C.rule}`, borderRadius: 8, padding: '16px', background: C.ivory }}>
      <div style={{ fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 600, color: C.ink, marginBottom: 4 }}>{label}</div>
      <div style={{ fontFamily: 'var(--font-body)', fontSize: 11.5, color: C.muted, marginBottom: 12 }}>{note}</div>
      <button onClick={onUpload} style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '7px 16px', background: C.green, color: C.offwhite, border: 'none', borderRadius: 6, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 600 }}>
        <Upload size={14} /> Unggah Dokumen Final
      </button>
    </div>
  )
}

// ─── Laporan View ──────────────────────────────────────────────────────────────
function LaporanView({ pengajuans }: { pengajuans: Pengajuan[] }) {
  const total = pengajuans.length
  const byStatus = Object.entries(STATUS_COLORS).map(([s]) => ({
    status: s, count: pengajuans.filter(p => p.status === s).length,
  })).filter(x => x.count > 0)

  const byDivisi = ['Perencanaan & Evaluasi', 'Penelitian & Pengembangan', 'Infrastruktur & Tata Ruang', 'Teknologi Informasi', 'Keuangan & Administrasi'].map(d => ({
    divisi: d, count: pengajuans.filter(p => p.divisiPreferensi.includes(d.split(' ')[0])).length
  }))

  return (
    <div>
      <PageHeader title="Laporan & Ekspor" subtitle="Ringkasan data pengajuan magang untuk keperluan pelaporan." />
      <div style={{ padding: '28px 36px', display: 'flex', flexDirection: 'column', gap: 20 }}>
        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 20 }}>
          <Section title="Distribusi Status Pengajuan">
            <div>
              {byStatus.map(({ status, count }) => {
                const pct = total ? Math.round((count / total) * 100) : 0
                const c = STATUS_COLORS[status] ?? { bg: C.ivory, color: C.muted }
                return (
                  <div key={status} style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 10 }}>
                    <div style={{ width: 10, height: 10, borderRadius: '50%', background: c.color, flexShrink: 0 }} />
                    <div style={{ flex: 1, fontFamily: 'var(--font-body)', fontSize: 13, color: C.ink }}>{status}</div>
                    <div style={{ width: 80, height: 5, background: C.rule, borderRadius: 2, overflow: 'hidden' }}>
                      <div style={{ width: `${pct}%`, height: '100%', background: c.color }} />
                    </div>
                    <div style={{ fontFamily: 'var(--font-mono)', fontSize: 13, fontWeight: 600, color: C.ink, width: 24, textAlign: 'right' }}>{count}</div>
                  </div>
                )
              })}
            </div>
          </Section>

          <Section title="Pengajuan per Divisi (Preferensi)">
            <div>
              {byDivisi.map(({ divisi, count }) => {
                const pct = total ? Math.round((count / total) * 100) : 0
                return (
                  <div key={divisi} style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 10 }}>
                    <div style={{ flex: 1, fontFamily: 'var(--font-body)', fontSize: 12.5, color: C.ink }}>{divisi}</div>
                    <div style={{ width: 80, height: 5, background: C.rule, borderRadius: 2, overflow: 'hidden' }}>
                      <div style={{ width: `${pct}%`, height: '100%', background: C.green }} />
                    </div>
                    <div style={{ fontFamily: 'var(--font-mono)', fontSize: 13, fontWeight: 600, color: C.ink, width: 24, textAlign: 'right' }}>{count}</div>
                  </div>
                )
              })}
            </div>
          </Section>
        </div>

        {/* Export buttons */}
        <div style={{ background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 10, padding: '20px 24px' }}>
          <div style={{ fontFamily: 'var(--font-body)', fontSize: 12, fontWeight: 700, letterSpacing: '0.10em', color: C.muted, textTransform: 'uppercase', marginBottom: 14 }}>Ekspor Data</div>
          <div style={{ display: 'flex', gap: 12, flexWrap: 'wrap' }}>
            {[
              { label: 'Ekspor Semua Pengajuan (.xlsx)', desc: 'Semua data pengajuan dengan status terkini' },
              { label: 'Ekspor Mahasiswa Diterima (.xlsx)', desc: 'Data mahasiswa yang berstatus Diterima & Sedang Magang' },
              { label: 'Ekspor Mahasiswa Selesai (.xlsx)', desc: 'Data mahasiswa yang telah menyelesaikan magang' },
            ].map(btn => (
              <button key={btn.label} style={{ padding: '10px 18px', background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 8, cursor: 'pointer', textAlign: 'left' }}>
                <div style={{ fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 700, color: C.ink, marginBottom: 3, display: 'flex', alignItems: 'center', gap: 7 }}>
                  <Download size={14} color={C.greenMid} /> {btn.label}
                </div>
                <div style={{ fontFamily: 'var(--font-body)', fontSize: 11.5, color: C.muted }}>{btn.desc}</div>
              </button>
            ))}
          </div>
        </div>
      </div>
    </div>
  )
}
