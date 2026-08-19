import { useState } from 'react'
import {
  LayoutDashboard, FileText, Users, Layers, Calendar,
  FileSignature, LogOut, ChevronDown, Search, Filter,
  Eye, CheckCircle2, Clock, XCircle, MoreHorizontal,
  TrendingUp, AlertCircle, Bell, Download, ChevronRight,
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

interface Props {
  navigate: (p: Page) => void
  activePage: Page
}

type AdminView = 'dashboard' | 'pengajuan' | 'detail' | 'pengguna' | 'bidang' | 'periode' | 'surat'

export default function AdminDashboard({ navigate, activePage }: Props) {
  const initial: AdminView =
    activePage === 'admin-pengajuan' ? 'pengajuan'
    : activePage === 'admin-pengguna' ? 'pengguna'
    : activePage === 'admin-bidang' ? 'bidang'
    : activePage === 'admin-periode' ? 'periode'
    : activePage === 'admin-surat' ? 'surat'
    : 'dashboard'

  const [view, setView] = useState<AdminView>(initial)

  return (
    <div style={{ minHeight: '100vh', display: 'grid', gridTemplateColumns: '260px 1fr', background: '#F1F3EE' }}>
      <AdminSidebar view={view} setView={setView} navigate={navigate} />
      <div style={{ minHeight: '100vh', overflow: 'auto' }}>
        {view === 'dashboard' && <AdminDashView setView={setView} />}
        {view === 'pengajuan' && <PengajuanView setView={setView} />}
        {view === 'detail' && <DetailView setView={setView} />}
        {view === 'pengguna' && <PenggunaView />}
        {view === 'bidang' && <BidangView />}
        {view === 'periode' && <PeriodeView />}
        {view === 'surat' && <SuratView />}
      </div>
    </div>
  )
}

const NAV = [
  { icon: <LayoutDashboard size={16} />, label: 'Dashboard', view: 'dashboard' as AdminView },
  { icon: <FileText size={16} />, label: 'Pengajuan', view: 'pengajuan' as AdminView },
  { icon: <Users size={16} />, label: 'Pengguna', view: 'pengguna' as AdminView },
  { icon: <Layers size={16} />, label: 'Bidang', view: 'bidang' as AdminView },
  { icon: <Calendar size={16} />, label: 'Periode', view: 'periode' as AdminView },
  { icon: <FileSignature size={16} />, label: 'Template Surat', view: 'surat' as AdminView },
]

function AdminSidebar({ view, setView, navigate }: { view: AdminView, setView: (v: AdminView) => void, navigate: (p: Page) => void }) {
  return (
    <aside
      style={{
        background: C.greenDark,
        display: 'flex',
        flexDirection: 'column',
        padding: '0 0 24px',
        position: 'sticky',
        top: 0,
        height: '100vh',
      }}
    >
      {/* Logo */}
      <div style={{ padding: '24px 20px', borderBottom: '1px solid rgba(255,255,255,0.08)' }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 16 }}>
          <div style={{ width: 34, height: 34, background: C.gold, borderRadius: 6, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
            <span style={{ fontFamily: 'var(--font-display)', fontSize: 18, color: C.greenDark }}>S</span>
          </div>
          <div>
            <div style={{ fontFamily: 'var(--font-body)', fontWeight: 800, fontSize: 14, color: C.offwhite }}>SIMAGANG</div>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 9, color: 'rgba(255,255,255,0.38)', letterSpacing: '0.06em', textTransform: 'uppercase' }}>BAPPEDA LAMPUNG</div>
          </div>
        </div>

        {/* Admin badge */}
        <div
          style={{
            background: 'rgba(217,165,29,0.15)',
            border: '1px solid rgba(217,165,29,0.25)',
            borderRadius: 6,
            padding: '8px 12px',
            display: 'flex',
            alignItems: 'center',
            gap: 8,
          }}
        >
          <div style={{ width: 28, height: 28, borderRadius: '50%', background: 'rgba(255,255,255,0.1)', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
            <span style={{ fontFamily: 'var(--font-body)', fontWeight: 700, fontSize: 12, color: C.offwhite }}>A</span>
          </div>
          <div>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 12.5, fontWeight: 600, color: C.offwhite }}>Ahmad Fauzi</div>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 10, color: 'rgba(255,255,255,0.45)' }}>Administrator</div>
          </div>
        </div>
      </div>

      {/* Navigation */}
      <nav style={{ flex: 1, padding: '16px 12px' }}>
        <div style={{ fontFamily: 'var(--font-body)', fontSize: 9, fontWeight: 700, letterSpacing: '0.14em', color: 'rgba(255,255,255,0.3)', textTransform: 'uppercase', padding: '0 8px', marginBottom: 8 }}>
          Menu Utama
        </div>
        {NAV.map(({ icon, label, view: v }) => (
          <button
            key={v}
            onClick={() => setView(v)}
            style={{
              display: 'flex',
              alignItems: 'center',
              gap: 10,
              width: '100%',
              padding: '9px 10px',
              background: view === v ? 'rgba(255,255,255,0.1)' : 'none',
              border: 'none',
              borderRadius: 7,
              cursor: 'pointer',
              fontFamily: 'var(--font-body)',
              fontSize: 13.5,
              fontWeight: view === v ? 600 : 400,
              color: view === v ? C.offwhite : 'rgba(255,255,255,0.55)',
              textAlign: 'left',
              marginBottom: 2,
              transition: 'all 0.15s',
              borderLeft: view === v ? `3px solid ${C.gold}` : '3px solid transparent',
            }}
          >
            <span style={{ opacity: view === v ? 1 : 0.7 }}>{icon}</span>
            {label}
          </button>
        ))}
      </nav>

      {/* Footer */}
      <div style={{ padding: '0 12px', borderTop: '1px solid rgba(255,255,255,0.08)', paddingTop: 16 }}>
        <button
          onClick={() => navigate('beranda')}
          style={{
            display: 'flex', alignItems: 'center', gap: 10, width: '100%',
            padding: '9px 10px', background: 'none', border: 'none', cursor: 'pointer',
            fontFamily: 'var(--font-body)', fontSize: 13, color: 'rgba(255,255,255,0.38)', textAlign: 'left',
          }}
        >
          <LogOut size={15} /> Keluar
        </button>
      </div>
    </aside>
  )
}

function AdminHeader({ title, subtitle }: { title: string; subtitle?: string }) {
  return (
    <div
      style={{
        background: C.offwhite,
        borderBottom: `1px solid ${C.rule}`,
        padding: '24px 36px',
        display: 'flex',
        alignItems: 'flex-start',
        justifyContent: 'space-between',
      }}
    >
      <div>
        <h1 style={{ fontFamily: 'var(--font-display)', fontSize: 26, fontWeight: 400, color: C.ink, letterSpacing: '-0.01em', marginBottom: 2 }}>
          {title}
        </h1>
        {subtitle && <p style={{ fontFamily: 'var(--font-body)', fontSize: 13.5, color: C.muted }}>{subtitle}</p>}
      </div>
      <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
        <button style={{ position: 'relative', background: 'none', border: 'none', cursor: 'pointer', color: C.muted, padding: 8 }}>
          <Bell size={18} />
          <span style={{ position: 'absolute', top: 6, right: 6, width: 7, height: 7, background: C.gold, borderRadius: '50%' }} />
        </button>
      </div>
    </div>
  )
}

function StatusBadge({ status }: { status: string }) {
  const map: Record<string, { bg: string; color: string }> = {
    'Draft': { bg: C.ivory, color: C.muted },
    'Menunggu Verifikasi': { bg: C.goldSoft, color: '#7A5A00' },
    'Diteruskan': { bg: C.greenSoft, color: C.greenMid },
    'Disetujui': { bg: '#D1FAE5', color: '#065F46' },
    'Ditolak': { bg: '#FEE2E2', color: '#991B1B' },
    'Revisi Berkas': { bg: '#FEF3C7', color: '#92400E' },
    'Selesai': { bg: C.greenSoft, color: C.green },
    'Menunggu Persetujuan': { bg: C.goldSoft, color: '#7A5A00' },
    'Dibatalkan': { bg: C.ivory, color: C.muted },
  }
  const style = map[status] ?? { bg: C.ivory, color: C.muted }
  return (
    <span
      style={{
        fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.05em',
        textTransform: 'uppercase', background: style.bg, color: style.color,
        padding: '3px 9px', borderRadius: 4, display: 'inline-block',
      }}
    >
      {status}
    </span>
  )
}

// Sample data
const PENGAJUAN_DATA = [
  { id: 'PGJ-1002', mahasiswa: 'Najwa Ramadhani', npm: '2021010234', universitas: 'Unila', prodi: 'Teknik Informatika', bidang: 'Perencanaan', periode: 'Jan–Mar 2027', status: 'Diteruskan', tanggal: '05 Agu 2026' },
  { id: 'PGJ-1001', mahasiswa: 'Rizal Maulana', npm: '2020031456', universitas: 'UBL', prodi: 'Manajemen', bidang: 'Keuangan', periode: 'Jan–Mar 2027', status: 'Disetujui', tanggal: '04 Agu 2026' },
  { id: 'PGJ-1000', mahasiswa: 'Siti Aisyah', npm: '2021054321', universitas: 'Unila', prodi: 'Statistika', bidang: 'Penelitian', periode: 'Jan–Mar 2027', status: 'Menunggu Verifikasi', tanggal: '03 Agu 2026' },
  { id: 'PGJ-0999', mahasiswa: 'Budi Santoso', npm: '2019078901', universitas: 'IIB Darmajaya', prodi: 'Sistem Informasi', bidang: 'Teknologi Informasi', periode: 'Okt–Des 2026', status: 'Selesai', tanggal: '15 Jul 2026' },
  { id: 'PGJ-0998', mahasiswa: 'Dewi Kurnia', npm: '2020092345', universitas: 'Unila', prodi: 'Ekonomi Pembangunan', bidang: 'Perencanaan', periode: 'Okt–Des 2026', status: 'Ditolak', tanggal: '12 Jul 2026' },
]

function AdminDashView({ setView }: { setView: (v: AdminView) => void }) {
  const stats = [
    { label: 'Total Pengajuan', value: '47', delta: '+8 bulan ini', icon: <FileText size={18} />, color: C.green },
    { label: 'Menunggu Verifikasi', value: '12', delta: '4 mendesak', icon: <Clock size={18} />, color: '#B45309' },
    { label: 'Menunggu Persetujuan', value: '8', delta: '2 hari ini', icon: <AlertCircle size={18} />, color: C.gold },
    { label: 'Disetujui', value: '21', delta: 'Periode ini', icon: <CheckCircle2 size={18} />, color: C.greenMid },
    { label: 'Selesai', value: '6', delta: 'Telah menyelesaikan magang', icon: <TrendingUp size={18} />, color: C.ink },
  ]

  const activities = [
    { time: '09:42', text: 'Pengajuan PGJ-1002 diteruskan ke tahap persetujuan.', actor: 'Sari Dewi (Verifikator)' },
    { time: '08:15', text: 'Pengajuan PGJ-0997 disetujui oleh Kepala Bidang.', actor: 'Irwan Hadi (Approver)' },
    { time: 'Kemarin', text: 'Pengajuan PGJ-0996 membutuhkan revisi berkas.', actor: 'Sari Dewi (Verifikator)' },
    { time: 'Kemarin', text: '3 pengajuan baru masuk pada periode Jan–Mar 2027.', actor: 'Sistem' },
  ]

  return (
    <div>
      <AdminHeader title="Dashboard" subtitle={`Periode aktif: Januari – Maret 2027 · ${new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}`} />

      <div style={{ padding: '32px 36px' }}>
        {/* Stats row */}
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(5, 1fr)', gap: 0, background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 10, overflow: 'hidden', marginBottom: 28 }}>
          {stats.map(({ label, value, delta, icon, color }, i) => (
            <div
              key={label}
              style={{
                padding: '20px 20px',
                borderLeft: i > 0 ? `1px solid ${C.rule}` : 'none',
              }}
            >
              <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', marginBottom: 10 }}>
                <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.08em', color: C.muted, textTransform: 'uppercase', lineHeight: 1.4 }}>{label}</div>
                <span style={{ color: color, opacity: 0.7 }}>{icon}</span>
              </div>
              <div style={{ fontFamily: 'var(--font-mono)', fontSize: 28, fontWeight: 600, color: color, lineHeight: 1, marginBottom: 6 }}>{value}</div>
              <div style={{ fontFamily: 'var(--font-body)', fontSize: 11.5, color: C.muted }}>{delta}</div>
            </div>
          ))}
        </div>

        {/* Two columns */}
        <div style={{ display: 'grid', gridTemplateColumns: '1fr 300px', gap: 24 }}>
          {/* Recent applications */}
          <div style={{ background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 10, overflow: 'hidden' }}>
            <div style={{ padding: '16px 24px', borderBottom: `1px solid ${C.rule}`, display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
              <div style={{ fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 700, color: C.ink }}>Pengajuan Terbaru</div>
              <button
                onClick={() => setView('pengajuan')}
                style={{ background: 'none', border: 'none', cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 12.5, fontWeight: 600, color: C.greenMid, display: 'flex', alignItems: 'center', gap: 4 }}
              >
                Lihat semua <ChevronRight size={13} />
              </button>
            </div>

            {/* Table */}
            <div style={{ overflowX: 'auto' }}>
              <table style={{ width: '100%', borderCollapse: 'collapse' }}>
                <thead>
                  <tr style={{ background: C.ivory }}>
                    {['No. Pengajuan', 'Mahasiswa', 'Bidang', 'Status', 'Tanggal'].map(h => (
                      <th key={h} style={{ padding: '10px 16px', textAlign: 'left', fontFamily: 'var(--font-body)', fontSize: 10.5, fontWeight: 700, letterSpacing: '0.08em', color: C.muted, textTransform: 'uppercase', whiteSpace: 'nowrap', borderBottom: `1px solid ${C.rule}` }}>
                        {h}
                      </th>
                    ))}
                    <th style={{ padding: '10px 16px', borderBottom: `1px solid ${C.rule}` }} />
                  </tr>
                </thead>
                <tbody>
                  {PENGAJUAN_DATA.slice(0, 5).map((row, i) => (
                    <tr key={row.id} style={{ borderBottom: i < 4 ? `1px solid ${C.rule}` : 'none' }}>
                      <td style={{ padding: '12px 16px' }}>
                        <span style={{ fontFamily: 'var(--font-mono)', fontSize: 12.5, color: C.greenMid, fontWeight: 600 }}>{row.id}</span>
                      </td>
                      <td style={{ padding: '12px 16px' }}>
                        <div style={{ fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 600, color: C.ink }}>{row.mahasiswa}</div>
                        <div style={{ fontFamily: 'var(--font-body)', fontSize: 11.5, color: C.muted }}>{row.universitas}</div>
                      </td>
                      <td style={{ padding: '12px 16px', fontFamily: 'var(--font-body)', fontSize: 12.5, color: C.muted }}>{row.bidang}</td>
                      <td style={{ padding: '12px 16px' }}><StatusBadge status={row.status} /></td>
                      <td style={{ padding: '12px 16px', fontFamily: 'var(--font-body)', fontSize: 12.5, color: C.muted, whiteSpace: 'nowrap' }}>{row.tanggal}</td>
                      <td style={{ padding: '12px 16px' }}>
                        <button
                          onClick={() => setView('detail')}
                          style={{ background: 'none', border: 'none', cursor: 'pointer', color: C.muted, padding: 4 }}
                        >
                          <Eye size={15} />
                        </button>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>

          {/* Activity timeline */}
          <div style={{ background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 10, overflow: 'hidden' }}>
            <div style={{ padding: '16px 20px', borderBottom: `1px solid ${C.rule}` }}>
              <div style={{ fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 700, color: C.ink }}>Aktivitas Terbaru</div>
            </div>
            <div style={{ padding: '8px 0' }}>
              {activities.map((a, i) => (
                <div key={i} style={{ padding: '12px 20px', borderBottom: i < activities.length - 1 ? `1px solid ${C.rule}` : 'none' }}>
                  <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: 4 }}>
                    <div style={{ fontFamily: 'var(--font-mono)', fontSize: 11, color: C.gold, fontWeight: 600 }}>{a.time}</div>
                  </div>
                  <div style={{ fontFamily: 'var(--font-body)', fontSize: 13, color: C.ink, lineHeight: 1.5, marginBottom: 4 }}>{a.text}</div>
                  <div style={{ fontFamily: 'var(--font-body)', fontSize: 11.5, color: C.muted }}>{a.actor}</div>
                </div>
              ))}
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}

function PengajuanView({ setView }: { setView: (v: AdminView) => void }) {
  const [search, setSearch] = useState('')
  const [statusFilter, setStatusFilter] = useState('')

  const filtered = PENGAJUAN_DATA.filter(r =>
    (search === '' || r.mahasiswa.toLowerCase().includes(search.toLowerCase()) || r.id.includes(search)) &&
    (statusFilter === '' || r.status === statusFilter)
  )

  return (
    <div>
      <AdminHeader title="Kelola Pengajuan" subtitle={`${PENGAJUAN_DATA.length} total pengajuan`} />

      <div style={{ padding: '28px 36px' }}>
        {/* Toolbar */}
        <div
          style={{
            display: 'flex',
            gap: 12,
            marginBottom: 20,
            flexWrap: 'wrap',
            alignItems: 'center',
          }}
        >
          {/* Search */}
          <div style={{ position: 'relative', flex: '1 1 240px', minWidth: 200 }}>
            <Search size={15} style={{ position: 'absolute', left: 12, top: '50%', transform: 'translateY(-50%)', color: C.muted }} />
            <input
              value={search}
              onChange={e => setSearch(e.target.value)}
              placeholder="Cari nama atau nomor pengajuan..."
              style={{
                width: '100%',
                padding: '9px 14px 9px 36px',
                background: C.offwhite,
                border: `1px solid ${C.rule}`,
                borderRadius: 8,
                fontFamily: 'var(--font-body)',
                fontSize: 13.5,
                color: C.ink,
                outline: 'none',
              }}
            />
          </div>

          {/* Status filter */}
          <select
            value={statusFilter}
            onChange={e => setStatusFilter(e.target.value)}
            style={{
              padding: '9px 14px',
              background: C.offwhite,
              border: `1px solid ${C.rule}`,
              borderRadius: 8,
              fontFamily: 'var(--font-body)',
              fontSize: 13.5,
              color: statusFilter ? C.ink : C.muted,
              outline: 'none',
              cursor: 'pointer',
            }}
          >
            <option value="">Semua Status</option>
            <option>Menunggu Verifikasi</option>
            <option>Diteruskan</option>
            <option>Menunggu Persetujuan</option>
            <option>Disetujui</option>
            <option>Ditolak</option>
            <option>Selesai</option>
          </select>

          <select
            style={{ padding: '9px 14px', background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 8, fontFamily: 'var(--font-body)', fontSize: 13.5, color: C.muted, outline: 'none', cursor: 'pointer' }}
          >
            <option>Semua Periode</option>
            <option>Jan–Mar 2027</option>
            <option>Okt–Des 2026</option>
          </select>

          <select
            style={{ padding: '9px 14px', background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 8, fontFamily: 'var(--font-body)', fontSize: 13.5, color: C.muted, outline: 'none', cursor: 'pointer' }}
          >
            <option>Semua Bidang</option>
            <option>Perencanaan</option>
            <option>Keuangan</option>
            <option>Penelitian</option>
            <option>Teknologi Informasi</option>
          </select>

          <button style={{ padding: '9px 16px', background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 8, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13.5, color: C.muted, display: 'flex', alignItems: 'center', gap: 6 }}>
            <Download size={14} /> Ekspor
          </button>
        </div>

        {/* Table */}
        <div style={{ background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 10, overflow: 'hidden' }}>
          <div style={{ overflowX: 'auto' }}>
            <table style={{ width: '100%', borderCollapse: 'collapse', minWidth: 900 }}>
              <thead>
                <tr style={{ background: C.ivory }}>
                  {['No. Pengajuan', 'Mahasiswa', 'Universitas', 'Program Studi', 'Bidang', 'Periode', 'Status', 'Tanggal', ''].map(h => (
                    <th key={h} style={{ padding: '12px 16px', textAlign: 'left', fontFamily: 'var(--font-body)', fontSize: 10.5, fontWeight: 700, letterSpacing: '0.08em', color: C.muted, textTransform: 'uppercase', whiteSpace: 'nowrap', borderBottom: `1px solid ${C.rule}` }}>
                      {h}
                    </th>
                  ))}
                </tr>
              </thead>
              <tbody>
                {filtered.length === 0 ? (
                  <tr>
                    <td colSpan={9} style={{ padding: '48px 24px', textAlign: 'center' }}>
                      <div style={{ fontFamily: 'var(--font-display)', fontSize: 20, color: C.muted, marginBottom: 8 }}>Tidak ada pengajuan ditemukan.</div>
                      <div style={{ fontFamily: 'var(--font-body)', fontSize: 13.5, color: C.muted }}>Coba ubah filter pencarian Anda.</div>
                    </td>
                  </tr>
                ) : filtered.map((row, i) => (
                  <tr key={row.id} style={{ borderBottom: i < filtered.length - 1 ? `1px solid ${C.rule}` : 'none' }}>
                    <td style={{ padding: '13px 16px' }}>
                      <span style={{ fontFamily: 'var(--font-mono)', fontSize: 12.5, color: C.greenMid, fontWeight: 600 }}>{row.id}</span>
                    </td>
                    <td style={{ padding: '13px 16px' }}>
                      <div style={{ fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 600, color: C.ink }}>{row.mahasiswa}</div>
                      <div style={{ fontFamily: 'var(--font-body)', fontSize: 11.5, color: C.muted }}>{row.npm}</div>
                    </td>
                    <td style={{ padding: '13px 16px', fontFamily: 'var(--font-body)', fontSize: 13, color: C.muted, whiteSpace: 'nowrap' }}>{row.universitas}</td>
                    <td style={{ padding: '13px 16px', fontFamily: 'var(--font-body)', fontSize: 13, color: C.muted }}>{row.prodi}</td>
                    <td style={{ padding: '13px 16px', fontFamily: 'var(--font-body)', fontSize: 13, color: C.muted }}>{row.bidang}</td>
                    <td style={{ padding: '13px 16px', fontFamily: 'var(--font-body)', fontSize: 12.5, color: C.muted, whiteSpace: 'nowrap' }}>{row.periode}</td>
                    <td style={{ padding: '13px 16px' }}><StatusBadge status={row.status} /></td>
                    <td style={{ padding: '13px 16px', fontFamily: 'var(--font-body)', fontSize: 12.5, color: C.muted, whiteSpace: 'nowrap' }}>{row.tanggal}</td>
                    <td style={{ padding: '13px 16px' }}>
                      <button
                        onClick={() => setView('detail')}
                        style={{ padding: '6px 14px', background: C.greenSoft, border: `1px solid #C3DBD6`, borderRadius: 6, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 12, fontWeight: 600, color: C.greenMid, display: 'flex', alignItems: 'center', gap: 5, whiteSpace: 'nowrap' }}
                      >
                        <Eye size={13} /> Lihat
                      </button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>

          {/* Pagination */}
          <div style={{ padding: '14px 20px', borderTop: `1px solid ${C.rule}`, display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 13, color: C.muted }}>
              Menampilkan {filtered.length} dari {PENGAJUAN_DATA.length} pengajuan
            </div>
            <div style={{ display: 'flex', gap: 4 }}>
              {[1, 2, 3].map(p => (
                <button
                  key={p}
                  style={{
                    width: 32, height: 32, borderRadius: 6,
                    background: p === 1 ? C.green : 'none',
                    border: `1px solid ${p === 1 ? C.green : C.rule}`,
                    cursor: 'pointer',
                    fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: p === 1 ? 700 : 400,
                    color: p === 1 ? C.offwhite : C.muted,
                  }}
                >
                  {p}
                </button>
              ))}
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}

function DetailView({ setView }: { setView: (v: AdminView) => void }) {
  const [docStatuses, setDocStatuses] = useState<Record<string, string>>({
    'Surat Pengantar Kampus': 'Valid',
    'Curriculum Vitae': 'Valid',
    'Transkrip Nilai': 'Valid',
    'KTP': 'Valid',
    'Pas Foto': 'Belum Diperiksa',
    'Proposal Magang': 'Perlu Revisi',
  })

  const docs = Object.entries(docStatuses)

  return (
    <div>
      <AdminHeader
        title="Detail Pengajuan"
        subtitle="PGJ-1002 · Najwa Ramadhani"
      />

      <div style={{ padding: '28px 36px' }}>
        <div style={{ marginBottom: 16 }}>
          <button
            onClick={() => setView('pengajuan')}
            style={{ background: 'none', border: 'none', cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13, color: C.muted, display: 'flex', alignItems: 'center', gap: 6, padding: 0 }}
          >
            ← Kembali ke Daftar Pengajuan
          </button>
        </div>

        <div style={{ display: 'grid', gridTemplateColumns: '1fr 340px', gap: 24 }}>
          {/* Left column */}
          <div style={{ display: 'flex', flexDirection: 'column', gap: 20 }}>
            {/* Applicant data */}
            <div style={{ background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 10, overflow: 'hidden' }}>
              <div style={{ padding: '14px 20px', borderBottom: `1px solid ${C.rule}`, display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                <div style={{ fontFamily: 'var(--font-body)', fontSize: 12, fontWeight: 700, letterSpacing: '0.08em', color: C.muted, textTransform: 'uppercase' }}>Data Mahasiswa</div>
              </div>
              <div style={{ padding: '20px', display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '12px 24px' }}>
                {[
                  ['Nama Lengkap', 'Najwa Ramadhani'],
                  ['NIM', '2021010234'],
                  ['Perguruan Tinggi', 'Universitas Lampung'],
                  ['Program Studi', 'Teknik Informatika'],
                  ['Semester', '9'],
                  ['Email', 'najwa@student.unila.ac.id'],
                ].map(([k, v]) => (
                  <div key={k}>
                    <div style={{ fontFamily: 'var(--font-body)', fontSize: 10.5, fontWeight: 700, letterSpacing: '0.08em', color: C.muted, textTransform: 'uppercase', marginBottom: 3 }}>{k}</div>
                    <div style={{ fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 500, color: C.ink }}>{v}</div>
                  </div>
                ))}
              </div>
            </div>

            <div style={{ background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 10, overflow: 'hidden' }}>
              <div style={{ padding: '14px 20px', borderBottom: `1px solid ${C.rule}` }}>
                <div style={{ fontFamily: 'var(--font-body)', fontSize: 12, fontWeight: 700, letterSpacing: '0.08em', color: C.muted, textTransform: 'uppercase' }}>Data Pengajuan</div>
              </div>
              <div style={{ padding: '20px', display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '12px 24px' }}>
                {[
                  ['Nomor Pengajuan', 'PGJ-1002'],
                  ['Bidang', 'Perencanaan & Evaluasi Pembangunan'],
                  ['Periode', 'Januari – Maret 2027'],
                  ['Tanggal Pengajuan', '05 Agustus 2026'],
                ].map(([k, v]) => (
                  <div key={k}>
                    <div style={{ fontFamily: 'var(--font-body)', fontSize: 10.5, fontWeight: 700, letterSpacing: '0.08em', color: C.muted, textTransform: 'uppercase', marginBottom: 3 }}>{k}</div>
                    <div style={{ fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 500, color: C.ink }}>{v}</div>
                  </div>
                ))}
              </div>
            </div>

            {/* Documents */}
            <div style={{ background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 10, overflow: 'hidden' }}>
              <div style={{ padding: '14px 20px', borderBottom: `1px solid ${C.rule}` }}>
                <div style={{ fontFamily: 'var(--font-body)', fontSize: 12, fontWeight: 700, letterSpacing: '0.08em', color: C.muted, textTransform: 'uppercase' }}>Verifikasi Dokumen</div>
              </div>
              <div>
                {docs.map(([doc, status], i) => (
                  <div
                    key={doc}
                    style={{
                      display: 'grid',
                      gridTemplateColumns: '1fr 180px 80px',
                      padding: '14px 20px',
                      borderBottom: i < docs.length - 1 ? `1px solid ${C.rule}` : 'none',
                      alignItems: 'center',
                      gap: 16,
                    }}
                  >
                    <div style={{ fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 500, color: C.ink }}>{doc}</div>
                    <select
                      value={status}
                      onChange={e => setDocStatuses(prev => ({ ...prev, [doc]: e.target.value }))}
                      style={{
                        padding: '6px 10px',
                        background: C.ivory,
                        border: `1px solid ${C.rule}`,
                        borderRadius: 6,
                        fontFamily: 'var(--font-body)',
                        fontSize: 12.5,
                        color: C.ink,
                        outline: 'none',
                        cursor: 'pointer',
                      }}
                    >
                      <option>Belum Diperiksa</option>
                      <option>Valid</option>
                      <option>Perlu Revisi</option>
                      <option>Invalid</option>
                    </select>
                    <button style={{ background: 'none', border: 'none', cursor: 'pointer', color: C.muted, display: 'flex', alignItems: 'center', gap: 4, fontFamily: 'var(--font-body)', fontSize: 12.5 }}>
                      <Eye size={14} /> Lihat
                    </button>
                  </div>
                ))}
              </div>
            </div>
          </div>

          {/* Right column */}
          <div style={{ display: 'flex', flexDirection: 'column', gap: 16 }}>
            {/* Status & actions */}
            <div style={{ background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 10, overflow: 'hidden' }}>
              <div style={{ padding: '14px 20px', borderBottom: `1px solid ${C.rule}` }}>
                <div style={{ fontFamily: 'var(--font-body)', fontSize: 12, fontWeight: 700, letterSpacing: '0.08em', color: C.muted, textTransform: 'uppercase' }}>Status & Tindakan</div>
              </div>
              <div style={{ padding: '20px' }}>
                <div style={{ marginBottom: 16 }}>
                  <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.08em', color: C.muted, textTransform: 'uppercase', marginBottom: 8 }}>Status Saat Ini</div>
                  <StatusBadge status="Diteruskan" />
                </div>

                <hr style={{ border: 'none', borderTop: `1px solid ${C.rule}`, margin: '16px 0' }} />

                <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.08em', color: C.muted, textTransform: 'uppercase', marginBottom: 10 }}>Tindakan (Verifikator)</div>

                <div style={{ display: 'flex', flexDirection: 'column', gap: 8 }}>
                  <button style={{ padding: '10px 16px', background: C.green, border: 'none', borderRadius: 8, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 700, color: C.offwhite }}>
                    Teruskan ke Persetujuan
                  </button>
                  <button style={{ padding: '10px 16px', background: C.goldSoft, border: `1px solid ${C.gold}40`, borderRadius: 8, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 700, color: '#7A5A00' }}>
                    Minta Revisi Berkas
                  </button>
                  <button style={{ padding: '10px 16px', background: '#FEE2E2', border: `1px solid #FECACA`, borderRadius: 8, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 700, color: '#991B1B' }}>
                    Tolak Pengajuan
                  </button>
                </div>
              </div>
            </div>

            {/* Workflow timeline */}
            <div style={{ background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 10, overflow: 'hidden' }}>
              <div style={{ padding: '14px 20px', borderBottom: `1px solid ${C.rule}` }}>
                <div style={{ fontFamily: 'var(--font-body)', fontSize: 12, fontWeight: 700, letterSpacing: '0.08em', color: C.muted, textTransform: 'uppercase' }}>Riwayat Status</div>
              </div>
              <div style={{ padding: '16px 20px' }}>
                {[
                  { step: 'Diteruskan', date: '07 Agu 2026', actor: 'Sari Dewi', done: true },
                  { step: 'Verifikasi Berkas', date: '06 Agu 2026', actor: 'Sari Dewi', done: true },
                  { step: 'Pengajuan Masuk', date: '05 Agu 2026', actor: 'Najwa Ramadhani', done: true },
                ].map((item, i) => (
                  <div key={i} style={{ display: 'flex', gap: 12, marginBottom: i < 2 ? 16 : 0 }}>
                    <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', width: 20 }}>
                      <div style={{ width: 14, height: 14, borderRadius: '50%', background: C.green, flexShrink: 0, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                        <CheckCircle2 size={8} color={C.offwhite} />
                      </div>
                      {i < 2 && <div style={{ width: 1, height: '100%', background: C.rule, marginTop: 4 }} />}
                    </div>
                    <div style={{ flex: 1, paddingBottom: i < 2 ? 16 : 0 }}>
                      <div style={{ fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 600, color: C.ink }}>{item.step}</div>
                      <div style={{ fontFamily: 'var(--font-body)', fontSize: 11.5, color: C.muted }}>{item.actor} · {item.date}</div>
                    </div>
                  </div>
                ))}
              </div>
            </div>

            {/* Notes */}
            <div style={{ background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 10, padding: '16px 20px' }}>
              <div style={{ fontFamily: 'var(--font-body)', fontSize: 12, fontWeight: 700, letterSpacing: '0.08em', color: C.muted, textTransform: 'uppercase', marginBottom: 10 }}>Catatan Internal</div>
              <textarea
                rows={4}
                placeholder="Tambahkan catatan untuk pengajuan ini..."
                style={{ width: '100%', padding: '10px', background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 8, fontFamily: 'var(--font-body)', fontSize: 13, color: C.ink, outline: 'none', resize: 'vertical' }}
              />
              <button style={{ marginTop: 8, padding: '8px 16px', background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 6, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 600, color: C.ink }}>
                Simpan Catatan
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}

function PenggunaView() {
  const users = [
    { nama: 'Najwa Ramadhani', email: 'najwa@student.unila.ac.id', role: 'Mahasiswa', status: 'Aktif', tanggal: '01 Agu 2026' },
    { nama: 'Rizal Maulana', email: 'rizal@student.ubl.ac.id', role: 'Mahasiswa', status: 'Aktif', tanggal: '28 Jul 2026' },
    { nama: 'Sari Dewi', email: 'sari.dewi@bappeda.lampung.go.id', role: 'Verifikator', status: 'Aktif', tanggal: '01 Jan 2026' },
    { nama: 'Irwan Hadi', email: 'irwan.hadi@bappeda.lampung.go.id', role: 'Approver', status: 'Aktif', tanggal: '01 Jan 2026' },
    { nama: 'Ahmad Fauzi', email: 'ahmad.fauzi@bappeda.lampung.go.id', role: 'Administrator', status: 'Aktif', tanggal: '01 Jan 2026' },
  ]

  const roleBadge = (role: string) => {
    const map: Record<string, { bg: string; color: string }> = {
      'Mahasiswa': { bg: C.ivory, color: C.muted },
      'Verifikator': { bg: C.greenSoft, color: C.greenMid },
      'Approver': { bg: C.goldSoft, color: '#7A5A00' },
      'Administrator': { bg: C.greenDark + '20', color: C.greenDark },
    }
    const s = map[role] ?? { bg: C.ivory, color: C.muted }
    return (
      <span style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.05em', textTransform: 'uppercase', background: s.bg, color: s.color, padding: '3px 9px', borderRadius: 4 }}>
        {role}
      </span>
    )
  }

  return (
    <div>
      <AdminHeader title="Kelola Pengguna" subtitle={`${users.length} pengguna terdaftar`} />
      <div style={{ padding: '28px 36px' }}>
        <div style={{ display: 'flex', justifyContent: 'flex-end', marginBottom: 16 }}>
          <button style={{ padding: '9px 18px', background: C.green, border: 'none', borderRadius: 8, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 600, color: C.offwhite }}>
            + Tambah Pengguna
          </button>
        </div>
        <div style={{ background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 10, overflow: 'hidden' }}>
          <table style={{ width: '100%', borderCollapse: 'collapse' }}>
            <thead>
              <tr style={{ background: C.ivory }}>
                {['Nama', 'Email', 'Role', 'Status', 'Terdaftar', ''].map(h => (
                  <th key={h} style={{ padding: '12px 16px', textAlign: 'left', fontFamily: 'var(--font-body)', fontSize: 10.5, fontWeight: 700, letterSpacing: '0.08em', color: C.muted, textTransform: 'uppercase', borderBottom: `1px solid ${C.rule}` }}>
                    {h}
                  </th>
                ))}
              </tr>
            </thead>
            <tbody>
              {users.map((u, i) => (
                <tr key={u.email} style={{ borderBottom: i < users.length - 1 ? `1px solid ${C.rule}` : 'none' }}>
                  <td style={{ padding: '13px 16px', fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 600, color: C.ink }}>{u.nama}</td>
                  <td style={{ padding: '13px 16px', fontFamily: 'var(--font-body)', fontSize: 13, color: C.muted }}>{u.email}</td>
                  <td style={{ padding: '13px 16px' }}>{roleBadge(u.role)}</td>
                  <td style={{ padding: '13px 16px' }}>
                    <span style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.05em', textTransform: 'uppercase', background: '#D1FAE5', color: '#065F46', padding: '3px 9px', borderRadius: 4 }}>
                      {u.status}
                    </span>
                  </td>
                  <td style={{ padding: '13px 16px', fontFamily: 'var(--font-body)', fontSize: 13, color: C.muted }}>{u.tanggal}</td>
                  <td style={{ padding: '13px 16px' }}>
                    <button style={{ background: 'none', border: 'none', cursor: 'pointer', color: C.muted }}><MoreHorizontal size={16} /></button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  )
}

function BidangView() {
  const bidangs = [
    { nama: 'Perencanaan & Evaluasi Pembangunan', periode: 'Jan–Mar 2027', kuota: 10, terpakai: 7, status: 'Aktif' },
    { nama: 'Penelitian & Pengembangan', periode: 'Jan–Mar 2027', kuota: 6, terpakai: 3, status: 'Aktif' },
    { nama: 'Infrastruktur & Wilayah', periode: 'Jan–Mar 2027', kuota: 8, terpakai: 5, status: 'Aktif' },
    { nama: 'Teknologi Informasi', periode: 'Jan–Mar 2027', kuota: 5, terpakai: 5, status: 'Penuh' },
    { nama: 'Keuangan & Administrasi', periode: 'Jan–Mar 2027', kuota: 4, terpakai: 1, status: 'Aktif' },
  ]

  return (
    <div>
      <AdminHeader title="Kelola Bidang" subtitle="Master data bidang magang dan kuota per periode." />
      <div style={{ padding: '28px 36px' }}>
        <div style={{ display: 'flex', justifyContent: 'flex-end', marginBottom: 16 }}>
          <button style={{ padding: '9px 18px', background: C.green, border: 'none', borderRadius: 8, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 600, color: C.offwhite }}>
            + Tambah Bidang
          </button>
        </div>

        <div style={{ background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 10, overflow: 'hidden' }}>
          <table style={{ width: '100%', borderCollapse: 'collapse' }}>
            <thead>
              <tr style={{ background: C.ivory }}>
                {['Bidang', 'Periode', 'Kuota', 'Terpakai', 'Tersedia', 'Status', ''].map(h => (
                  <th key={h} style={{ padding: '12px 16px', textAlign: 'left', fontFamily: 'var(--font-body)', fontSize: 10.5, fontWeight: 700, letterSpacing: '0.08em', color: C.muted, textTransform: 'uppercase', borderBottom: `1px solid ${C.rule}` }}>
                    {h}
                  </th>
                ))}
              </tr>
            </thead>
            <tbody>
              {bidangs.map((b, i) => {
                const pct = (b.terpakai / b.kuota) * 100
                return (
                  <tr key={b.nama} style={{ borderBottom: i < bidangs.length - 1 ? `1px solid ${C.rule}` : 'none' }}>
                    <td style={{ padding: '14px 16px', fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 600, color: C.ink }}>{b.nama}</td>
                    <td style={{ padding: '14px 16px', fontFamily: 'var(--font-body)', fontSize: 13, color: C.muted }}>{b.periode}</td>
                    <td style={{ padding: '14px 16px', fontFamily: 'var(--font-mono)', fontSize: 14, fontWeight: 600, color: C.ink }}>{b.kuota}</td>
                    <td style={{ padding: '14px 16px' }}>
                      <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
                        <span style={{ fontFamily: 'var(--font-mono)', fontSize: 14, fontWeight: 600, color: C.ink }}>{b.terpakai}</span>
                        <div style={{ width: 60, height: 5, background: C.rule, borderRadius: 3, overflow: 'hidden' }}>
                          <div style={{ width: `${pct}%`, height: '100%', background: pct >= 100 ? '#9B2C2C' : pct >= 75 ? C.gold : C.green, borderRadius: 3 }} />
                        </div>
                      </div>
                    </td>
                    <td style={{ padding: '14px 16px', fontFamily: 'var(--font-mono)', fontSize: 14, fontWeight: 600, color: b.kuota - b.terpakai === 0 ? '#9B2C2C' : C.greenMid }}>
                      {b.kuota - b.terpakai}
                    </td>
                    <td style={{ padding: '14px 16px' }}>
                      <StatusBadge status={b.status === 'Penuh' ? 'Ditolak' : 'Disetujui'} />
                    </td>
                    <td style={{ padding: '14px 16px' }}>
                      <button style={{ background: 'none', border: 'none', cursor: 'pointer', color: C.muted }}><MoreHorizontal size={16} /></button>
                    </td>
                  </tr>
                )
              })}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  )
}

function PeriodeView() {
  const periodes = [
    { nama: 'Periode Ganjil 2026/2027', daftar_mulai: '01 Jul 2026', daftar_selesai: '31 Agu 2026', pelaksanaan: 'Jan – Mar 2027', status: 'Berjalan', jumlah: 47 },
    { nama: 'Periode Genap 2025/2026', daftar_mulai: '01 Jan 2026', daftar_selesai: '28 Feb 2026', pelaksanaan: 'Jul – Sep 2026', status: 'Ditutup', jumlah: 38 },
    { nama: 'Periode Ganjil 2025/2026', daftar_mulai: '01 Jul 2025', daftar_selesai: '31 Agu 2025', pelaksanaan: 'Jan – Mar 2026', status: 'Ditutup', jumlah: 32 },
  ]

  const statusColor = (s: string) => s === 'Berjalan' ? { bg: C.greenSoft, color: C.greenMid } : s === 'Belum Dibuka' ? { bg: C.goldSoft, color: '#7A5A00' } : { bg: C.ivory, color: C.muted }

  return (
    <div>
      <AdminHeader title="Kelola Periode" subtitle="Manajemen periode pendaftaran dan pelaksanaan magang." />
      <div style={{ padding: '28px 36px' }}>
        <div style={{ display: 'flex', justifyContent: 'flex-end', marginBottom: 16 }}>
          <button style={{ padding: '9px 18px', background: C.green, border: 'none', borderRadius: 8, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 600, color: C.offwhite }}>
            + Tambah Periode
          </button>
        </div>

        <div style={{ display: 'flex', flexDirection: 'column', gap: 12 }}>
          {periodes.map((p) => {
            const sc = statusColor(p.status)
            return (
              <div
                key={p.nama}
                style={{
                  background: C.offwhite,
                  border: `1px solid ${C.rule}`,
                  borderRadius: 10,
                  padding: '20px 24px',
                  display: 'grid',
                  gridTemplateColumns: '2fr 1fr 1fr 1fr 80px 80px',
                  alignItems: 'center',
                  gap: 16,
                }}
              >
                <div>
                  <div style={{ fontFamily: 'var(--font-body)', fontSize: 14, fontWeight: 700, color: C.ink, marginBottom: 3 }}>{p.nama}</div>
                  <div style={{ fontFamily: 'var(--font-body)', fontSize: 12.5, color: C.muted }}>Pelaksanaan: {p.pelaksanaan}</div>
                </div>
                <div>
                  <div style={{ fontFamily: 'var(--font-body)', fontSize: 10.5, fontWeight: 700, letterSpacing: '0.08em', color: C.muted, textTransform: 'uppercase', marginBottom: 3 }}>Pendaftaran</div>
                  <div style={{ fontFamily: 'var(--font-body)', fontSize: 12.5, color: C.ink }}>{p.daftar_mulai} – {p.daftar_selesai}</div>
                </div>
                <div>
                  <span style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.06em', textTransform: 'uppercase', background: sc.bg, color: sc.color, padding: '4px 10px', borderRadius: 4 }}>
                    {p.status}
                  </span>
                </div>
                <div>
                  <div style={{ fontFamily: 'var(--font-body)', fontSize: 10.5, fontWeight: 700, letterSpacing: '0.08em', color: C.muted, textTransform: 'uppercase', marginBottom: 3 }}>Pengajuan</div>
                  <div style={{ fontFamily: 'var(--font-mono)', fontSize: 18, fontWeight: 600, color: C.ink }}>{p.jumlah}</div>
                </div>
                <button style={{ padding: '7px 14px', background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 6, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 12.5, fontWeight: 600, color: C.muted }}>
                  Edit
                </button>
                <button style={{ background: 'none', border: 'none', cursor: 'pointer', color: C.muted }}><MoreHorizontal size={16} /></button>
              </div>
            )
          })}
        </div>
      </div>
    </div>
  )
}

function SuratView() {
  const templates = [
    { nama: 'Template Surat Penerimaan Magang', updated: '01 Agu 2026', status: 'Aktif', digunakan: 21 },
    { nama: 'Template Surat Penolakan Magang', updated: '01 Agu 2026', status: 'Aktif', digunakan: 5 },
  ]

  return (
    <div>
      <AdminHeader title="Template Surat" subtitle="Kelola template surat resmi yang diterbitkan sistem." />
      <div style={{ padding: '28px 36px' }}>
        <div style={{ display: 'flex', flexDirection: 'column', gap: 16 }}>
          {templates.map((t) => (
            <div
              key={t.nama}
              style={{
                background: C.offwhite,
                border: `1px solid ${C.rule}`,
                borderRadius: 10,
                overflow: 'hidden',
              }}
            >
              <div style={{ padding: '20px 24px', display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between' }}>
                <div>
                  <div style={{ display: 'flex', alignItems: 'center', gap: 12, marginBottom: 8 }}>
                    <div style={{ width: 36, height: 36, background: C.greenSoft, borderRadius: 8, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                      <FileSignature size={18} color={C.greenMid} />
                    </div>
                    <div>
                      <div style={{ fontFamily: 'var(--font-body)', fontSize: 15, fontWeight: 700, color: C.ink }}>{t.nama}</div>
                      <div style={{ fontFamily: 'var(--font-body)', fontSize: 12, color: C.muted }}>Terakhir diperbarui: {t.updated} · Digunakan {t.digunakan} kali</div>
                    </div>
                  </div>
                </div>
                <div style={{ display: 'flex', gap: 10, flexShrink: 0 }}>
                  <button style={{ padding: '8px 16px', background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 8, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 600, color: C.muted, display: 'flex', alignItems: 'center', gap: 6 }}>
                    <Eye size={14} /> Preview
                  </button>
                  <button style={{ padding: '8px 16px', background: C.green, border: 'none', borderRadius: 8, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 600, color: C.offwhite }}>
                    Edit Template
                  </button>
                </div>
              </div>
              <div style={{ borderTop: `1px solid ${C.rule}`, padding: '14px 24px', background: C.ivory, display: 'flex', gap: 24 }}>
                <div style={{ fontFamily: 'var(--font-body)', fontSize: 12, color: C.muted }}>
                  Status: <strong style={{ color: C.greenMid }}>{t.status}</strong>
                </div>
                <div style={{ fontFamily: 'var(--font-body)', fontSize: 12, color: C.muted }}>
                  Versi: <strong style={{ color: C.ink }}>v3.1</strong>
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  )
}
