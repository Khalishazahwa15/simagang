import { useState } from 'react'
import {
  LayoutDashboard, Layers, Users, LogOut, Bell,
  Plus, MoreHorizontal, CheckCircle2, XCircle, Edit2, Trash2,
  BarChart2, Shield,
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

interface Props { navigate: (p: Page) => void; onLogout: () => void }
type View = 'dashboard' | 'divisi' | 'pengguna'

interface Divisi {
  id: number; nama: string; kapasitas: number; terpakai: number
  statusKebutuhan: 'Dibutuhkan' | 'Penuh' | 'Tidak Menerima'
}

interface UserInternal {
  id: number; nama: string; email: string
  role: 'Sekretariat' | 'Admin Sistem'; status: 'Aktif' | 'Nonaktif'
  terdaftar: string
}

const INITIAL_DIVISIS: Divisi[] = [
  { id: 1, nama: 'Perencanaan & Evaluasi Pembangunan', kapasitas: 10, terpakai: 7, statusKebutuhan: 'Dibutuhkan' },
  { id: 2, nama: 'Penelitian & Pengembangan', kapasitas: 6, terpakai: 3, statusKebutuhan: 'Dibutuhkan' },
  { id: 3, nama: 'Infrastruktur & Tata Ruang', kapasitas: 8, terpakai: 8, statusKebutuhan: 'Penuh' },
  { id: 4, nama: 'Teknologi Informasi', kapasitas: 5, terpakai: 5, statusKebutuhan: 'Penuh' },
  { id: 5, nama: 'Keuangan & Administrasi Umum', kapasitas: 4, terpakai: 2, statusKebutuhan: 'Dibutuhkan' },
]

const INITIAL_USERS: UserInternal[] = [
  { id: 1, nama: 'Sari Dewi, S.IP', email: 'sari.dewi@bappeda.lampung.go.id', role: 'Sekretariat', status: 'Aktif', terdaftar: '01 Jan 2026' },
  { id: 2, nama: 'Budi Prasetyo', email: 'budi.p@bappeda.lampung.go.id', role: 'Sekretariat', status: 'Aktif', terdaftar: '01 Jan 2026' },
  { id: 3, nama: 'Ahmad Fauzi, S.Kom', email: 'ahmad.fauzi@bappeda.lampung.go.id', role: 'Admin Sistem', status: 'Aktif', terdaftar: '01 Jan 2026' },
]

export default function AdminSistemDashboard({ navigate, onLogout }: Props) {
  const [view, setView] = useState<View>('dashboard')
  const [divisis, setDivisis] = useState<Divisi[]>(INITIAL_DIVISIS)
  const [users, setUsers] = useState<UserInternal[]>(INITIAL_USERS)

  return (
    <div style={{ minHeight: '100vh', display: 'grid', gridTemplateColumns: '252px 1fr', background: '#F1F3EE' }}>
      <AdminSidebar view={view} setView={setView} onLogout={onLogout} />
      <div style={{ minHeight: '100vh', overflow: 'auto' }}>
        {view === 'dashboard' && <AdminOverview divisis={divisis} users={users} setView={setView} />}
        {view === 'divisi' && <DivisiView divisis={divisis} setDivisis={setDivisis} />}
        {view === 'pengguna' && <PenggunaView users={users} setUsers={setUsers} />}
      </div>
    </div>
  )
}

function AdminSidebar({ view, setView, onLogout }: { view: View; setView: (v: View) => void; onLogout: () => void }) {
  const items = [
    { icon: <LayoutDashboard size={16} />, label: 'Dashboard', v: 'dashboard' as View },
    { icon: <Layers size={16} />, label: 'Kelola Divisi', v: 'divisi' as View },
    { icon: <Users size={16} />, label: 'Kelola Pengguna Internal', v: 'pengguna' as View },
  ]

  return (
    <aside style={{ background: C.ink, display: 'flex', flexDirection: 'column', padding: '0 0 24px', position: 'sticky', top: 0, height: '100vh' }}>
      <div style={{ padding: '24px 20px', borderBottom: '1px solid rgba(255,255,255,0.08)' }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: 10, marginBottom: 20 }}>
          <div style={{ width: 34, height: 34, background: C.gold, borderRadius: 6, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
            <span style={{ fontFamily: 'var(--font-display)', fontSize: 18, color: C.greenDark }}>S</span>
          </div>
          <div>
            <div style={{ fontFamily: 'var(--font-body)', fontWeight: 800, fontSize: 14, color: C.offwhite }}>SIMAGANG</div>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 9, color: 'rgba(255,255,255,0.3)', letterSpacing: '0.06em', textTransform: 'uppercase' }}>Admin Sistem</div>
          </div>
        </div>
        <div style={{ background: 'rgba(255,255,255,0.06)', border: '1px solid rgba(255,255,255,0.1)', borderRadius: 7, padding: '10px 12px', display: 'flex', alignItems: 'center', gap: 10 }}>
          <div style={{ width: 28, height: 28, borderRadius: '50%', background: 'rgba(255,255,255,0.1)', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
            <Shield size={14} color="rgba(255,255,255,0.6)" />
          </div>
          <div>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 600, color: C.offwhite }}>Ahmad Fauzi, S.Kom</div>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 10, color: 'rgba(255,255,255,0.35)' }}>Admin Sistem — Bappeda Lampung</div>
          </div>
        </div>
      </div>

      <div style={{ padding: '12px 16px', background: 'rgba(217,165,29,0.08)', borderBottom: '1px solid rgba(255,255,255,0.06)' }}>
        <p style={{ fontFamily: 'var(--font-body)', fontSize: 11.5, color: 'rgba(255,255,255,0.4)', lineHeight: 1.5 }}>
          <strong style={{ color: 'rgba(255,255,255,0.55)' }}>Catatan:</strong> Admin Sistem mengelola master data teknis dan akun internal. Keputusan penerimaan adalah wewenang Sekretariat.
        </p>
      </div>

      <nav style={{ flex: 1, padding: '16px 12px' }}>
        <div style={{ fontFamily: 'var(--font-body)', fontSize: 9, fontWeight: 700, letterSpacing: '0.14em', color: 'rgba(255,255,255,0.25)', textTransform: 'uppercase', padding: '0 8px', marginBottom: 8 }}>Menu</div>
        {items.map(({ icon, label, v }) => (
          <button key={v} onClick={() => setView(v)} style={{ display: 'flex', alignItems: 'center', gap: 10, width: '100%', padding: '9px 10px', background: view === v ? 'rgba(255,255,255,0.08)' : 'none', border: 'none', borderRadius: 7, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: view === v ? 600 : 400, color: view === v ? C.offwhite : 'rgba(255,255,255,0.5)', textAlign: 'left', marginBottom: 2, borderLeft: view === v ? `3px solid ${C.gold}` : '3px solid transparent' }}>
            <span style={{ opacity: view === v ? 1 : 0.6 }}>{icon}</span>{label}
          </button>
        ))}
      </nav>

      <div style={{ padding: '0 12px', borderTop: '1px solid rgba(255,255,255,0.08)', paddingTop: 16 }}>
        <button onClick={onLogout} style={{ display: 'flex', alignItems: 'center', gap: 10, width: '100%', padding: '9px 10px', background: 'none', border: 'none', cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13, color: 'rgba(255,255,255,0.3)', textAlign: 'left' }}>
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
      </button>
    </div>
  )
}

// ─── Overview ──────────────────────────────────────────────────────────────────
function AdminOverview({ divisis, users, setView }: { divisis: Divisi[]; users: UserInternal[]; setView: (v: View) => void }) {
  const divisiPenuh = divisis.filter(d => d.statusKebutuhan === 'Penuh' || d.statusKebutuhan === 'Tidak Menerima').length
  const kapasitasTotal = divisis.reduce((a, d) => a + d.kapasitas, 0)
  const terpakai = divisis.reduce((a, d) => a + d.terpakai, 0)

  return (
    <div>
      <PageHeader title="Admin Sistem" subtitle="Pengelolaan master data teknis dan akun internal sistem SIMAGANG." />
      <div style={{ padding: '28px 36px' }}>
        {/* Scope reminder */}
        <div style={{ background: C.goldSoft, border: `1px solid ${C.gold}40`, borderLeft: `4px solid ${C.gold}`, borderRadius: 8, padding: '14px 18px', marginBottom: 24 }}>
          <div style={{ fontFamily: 'var(--font-body)', fontSize: 12, fontWeight: 700, color: '#7A5A00', marginBottom: 4, textTransform: 'uppercase', letterSpacing: '0.08em' }}>Ruang Lingkup Admin Sistem</div>
          <p style={{ fontFamily: 'var(--font-body)', fontSize: 13, color: '#7A5A00', lineHeight: 1.6 }}>
            Admin Sistem mengelola <strong>master data divisi</strong> (nama, kapasitas, status kebutuhan) dan <strong>akun/role pengguna internal</strong> (Sekretariat & Admin Sistem). Keputusan penerimaan/penolakan pengajuan adalah wewenang eksklusif Sekretariat.
          </p>
        </div>

        {/* Stats */}
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(4, 1fr)', gap: 0, background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 10, overflow: 'hidden', marginBottom: 24 }}>
          {[
            { label: 'Total Divisi Aktif', value: divisis.length, color: C.green, note: `${divisiPenuh} penuh / tidak menerima` },
            { label: 'Kapasitas Total', value: kapasitasTotal, color: C.greenMid, note: 'Slot tersedia di semua divisi' },
            { label: 'Slot Terpakai', value: terpakai, color: C.gold, note: `${kapasitasTotal - terpakai} slot masih tersedia` },
            { label: 'Pengguna Internal', value: users.length, color: C.ink, note: `${users.filter(u => u.role === 'Sekretariat').length} Sekretariat · ${users.filter(u => u.role === 'Admin Sistem').length} Admin` },
          ].map(({ label, value, color, note }, i) => (
            <div key={label} style={{ padding: '20px', borderLeft: i > 0 ? `1px solid ${C.rule}` : 'none' }}>
              <div style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.08em', color: C.muted, textTransform: 'uppercase', marginBottom: 8 }}>{label}</div>
              <div style={{ fontFamily: 'var(--font-mono)', fontSize: 32, fontWeight: 600, color, lineHeight: 1, marginBottom: 4 }}>{value}</div>
              <div style={{ fontFamily: 'var(--font-body)', fontSize: 11.5, color: C.muted }}>{note}</div>
            </div>
          ))}
        </div>

        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 20 }}>
          {/* Divisi summary */}
          <div style={{ background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 10, overflow: 'hidden' }}>
            <div style={{ padding: '14px 20px', borderBottom: `1px solid ${C.rule}`, display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
              <div style={{ fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 700, color: C.ink }}>Status Divisi</div>
              <button onClick={() => setView('divisi')} style={{ background: 'none', border: 'none', cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 12.5, fontWeight: 600, color: C.greenMid }}>Kelola →</button>
            </div>
            {divisis.map((d, i) => {
              const pct = d.kapasitas ? Math.round((d.terpakai / d.kapasitas) * 100) : 0
              const barColor = d.statusKebutuhan === 'Penuh' || d.statusKebutuhan === 'Tidak Menerima' ? '#9B2C2C' : pct >= 75 ? C.gold : C.green
              return (
                <div key={d.id} style={{ padding: '12px 20px', borderBottom: i < divisis.length - 1 ? `1px solid ${C.rule}` : 'none' }}>
                  <div style={{ display: 'flex', alignItems: 'flex-start', justifyContent: 'space-between', marginBottom: 8 }}>
                    <div style={{ fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 500, color: C.ink, flex: 1, marginRight: 12, lineHeight: 1.4 }}>{d.nama}</div>
                    <KebutuhanBadge status={d.statusKebutuhan} />
                  </div>
                  <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
                    <div style={{ flex: 1, height: 5, background: C.rule, borderRadius: 3, overflow: 'hidden' }}>
                      <div style={{ width: `${pct}%`, height: '100%', background: barColor, borderRadius: 3 }} />
                    </div>
                    <span style={{ fontFamily: 'var(--font-mono)', fontSize: 11.5, color: C.muted, whiteSpace: 'nowrap' }}>{d.terpakai}/{d.kapasitas}</span>
                  </div>
                </div>
              )
            })}
          </div>

          {/* Users summary */}
          <div style={{ background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 10, overflow: 'hidden' }}>
            <div style={{ padding: '14px 20px', borderBottom: `1px solid ${C.rule}`, display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
              <div style={{ fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 700, color: C.ink }}>Pengguna Internal</div>
              <button onClick={() => setView('pengguna')} style={{ background: 'none', border: 'none', cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 12.5, fontWeight: 600, color: C.greenMid }}>Kelola →</button>
            </div>
            {users.map((u, i) => (
              <div key={u.id} style={{ display: 'flex', alignItems: 'center', gap: 12, padding: '12px 20px', borderBottom: i < users.length - 1 ? `1px solid ${C.rule}` : 'none' }}>
                <div style={{ width: 36, height: 36, borderRadius: '50%', background: u.role === 'Admin Sistem' ? C.ink : C.greenSoft, display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                  <span style={{ fontFamily: 'var(--font-body)', fontWeight: 700, fontSize: 14, color: u.role === 'Admin Sistem' ? C.offwhite : C.greenMid }}>{u.nama.charAt(0)}</span>
                </div>
                <div style={{ flex: 1 }}>
                  <div style={{ fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 600, color: C.ink }}>{u.nama}</div>
                  <div style={{ fontFamily: 'var(--font-body)', fontSize: 11.5, color: C.muted }}>{u.email}</div>
                </div>
                <RoleBadge role={u.role} />
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  )
}

// ─── Divisi View ───────────────────────────────────────────────────────────────
function DivisiView({ divisis, setDivisis }: { divisis: Divisi[]; setDivisis: (d: Divisi[]) => void }) {
  const [showAdd, setShowAdd] = useState(false)
  const [editing, setEditing] = useState<Divisi | null>(null)
  const [form, setForm] = useState({ nama: '', kapasitas: 5, statusKebutuhan: 'Dibutuhkan' as Divisi['statusKebutuhan'] })

  const save = () => {
    if (!form.nama.trim()) return
    if (editing) {
      setDivisis(divisis.map(d => d.id === editing.id ? { ...d, ...form } : d))
      setEditing(null)
    } else {
      setDivisis([...divisis, { id: Date.now(), ...form, terpakai: 0 }])
    }
    setForm({ nama: '', kapasitas: 5, statusKebutuhan: 'Dibutuhkan' })
    setShowAdd(false)
  }

  const startEdit = (d: Divisi) => {
    setEditing(d)
    setForm({ nama: d.nama, kapasitas: d.kapasitas, statusKebutuhan: d.statusKebutuhan })
    setShowAdd(true)
  }

  const remove = (id: number) => setDivisis(divisis.filter(d => d.id !== id))

  const updateKebutuhan = (id: number, s: Divisi['statusKebutuhan']) => {
    setDivisis(divisis.map(d => d.id === id ? { ...d, statusKebutuhan: s } : d))
  }

  return (
    <div>
      <PageHeader title="Kelola Divisi / Bidang" subtitle="Master data divisi magang dan status kebutuhan masing-masing." />
      <div style={{ padding: '24px 36px' }}>
        <div style={{ display: 'flex', justifyContent: 'flex-end', marginBottom: 16, gap: 10 }}>
          <button onClick={() => { setEditing(null); setForm({ nama: '', kapasitas: 5, statusKebutuhan: 'Dibutuhkan' }); setShowAdd(!showAdd) }} style={{ display: 'flex', alignItems: 'center', gap: 7, padding: '9px 18px', background: C.green, color: C.offwhite, border: 'none', borderRadius: 8, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 600 }}>
            <Plus size={16} /> Tambah Divisi
          </button>
        </div>

        {/* Add/Edit form */}
        {showAdd && (
          <div style={{ background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 10, padding: '20px 24px', marginBottom: 16 }}>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 700, color: C.ink, marginBottom: 16 }}>
              {editing ? `Edit: ${editing.nama}` : 'Tambah Divisi Baru'}
            </div>
            <div style={{ display: 'grid', gridTemplateColumns: '2fr 120px 200px auto', gap: 12, alignItems: 'flex-end' }}>
              <div>
                <label style={{ display: 'block', fontFamily: 'var(--font-body)', fontSize: 12, fontWeight: 700, color: C.muted, marginBottom: 5 }}>Nama Divisi / Bidang *</label>
                <input value={form.nama} onChange={e => setForm(f => ({ ...f, nama: e.target.value }))} placeholder="Nama divisi..." style={{ width: '100%', padding: '9px 12px', background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 7, fontFamily: 'var(--font-body)', fontSize: 13.5, color: C.ink, outline: 'none' }} />
              </div>
              <div>
                <label style={{ display: 'block', fontFamily: 'var(--font-body)', fontSize: 12, fontWeight: 700, color: C.muted, marginBottom: 5 }}>Kapasitas</label>
                <input type="number" min={1} value={form.kapasitas} onChange={e => setForm(f => ({ ...f, kapasitas: parseInt(e.target.value) || 1 }))} style={{ width: '100%', padding: '9px 12px', background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 7, fontFamily: 'var(--font-mono)', fontSize: 14, color: C.ink, outline: 'none' }} />
              </div>
              <div>
                <label style={{ display: 'block', fontFamily: 'var(--font-body)', fontSize: 12, fontWeight: 700, color: C.muted, marginBottom: 5 }}>Status Kebutuhan</label>
                <select value={form.statusKebutuhan} onChange={e => setForm(f => ({ ...f, statusKebutuhan: e.target.value as Divisi['statusKebutuhan'] }))} style={{ width: '100%', padding: '9px 12px', background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 7, fontFamily: 'var(--font-body)', fontSize: 13.5, color: C.ink, outline: 'none' }}>
                  <option>Dibutuhkan</option>
                  <option>Penuh</option>
                  <option>Tidak Menerima</option>
                </select>
              </div>
              <div style={{ display: 'flex', gap: 8 }}>
                <button onClick={save} style={{ padding: '9px 18px', background: C.green, color: C.offwhite, border: 'none', borderRadius: 7, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 700 }}>Simpan</button>
                <button onClick={() => { setShowAdd(false); setEditing(null) }} style={{ padding: '9px 14px', background: 'none', border: `1px solid ${C.rule}`, borderRadius: 7, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13, color: C.muted }}>Batal</button>
              </div>
            </div>
          </div>
        )}

        {/* Table */}
        <div style={{ background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 10, overflow: 'hidden' }}>
          <table style={{ width: '100%', borderCollapse: 'collapse' }}>
            <thead>
              <tr style={{ background: C.ivory }}>
                {['Divisi / Bidang', 'Kapasitas', 'Terpakai', 'Tersedia', 'Status Kebutuhan', ''].map(h => (
                  <th key={h} style={{ padding: '12px 16px', textAlign: 'left', fontFamily: 'var(--font-body)', fontSize: 10.5, fontWeight: 700, letterSpacing: '0.08em', color: C.muted, textTransform: 'uppercase', borderBottom: `1px solid ${C.rule}` }}>{h}</th>
                ))}
              </tr>
            </thead>
            <tbody>
              {divisis.map((d, i) => {
                const avail = d.kapasitas - d.terpakai
                const pct = d.kapasitas ? (d.terpakai / d.kapasitas) * 100 : 0
                const barColor = pct >= 100 ? '#9B2C2C' : pct >= 75 ? C.gold : C.green
                return (
                  <tr key={d.id} style={{ borderBottom: i < divisis.length - 1 ? `1px solid ${C.rule}` : 'none' }}>
                    <td style={{ padding: '14px 16px', fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 600, color: C.ink }}>{d.nama}</td>
                    <td style={{ padding: '14px 16px', fontFamily: 'var(--font-mono)', fontSize: 14, fontWeight: 600, color: C.ink }}>{d.kapasitas}</td>
                    <td style={{ padding: '14px 16px' }}>
                      <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
                        <span style={{ fontFamily: 'var(--font-mono)', fontSize: 14, fontWeight: 600, color: C.ink }}>{d.terpakai}</span>
                        <div style={{ width: 56, height: 5, background: C.rule, borderRadius: 3, overflow: 'hidden' }}>
                          <div style={{ width: `${Math.min(pct, 100)}%`, height: '100%', background: barColor, borderRadius: 3 }} />
                        </div>
                      </div>
                    </td>
                    <td style={{ padding: '14px 16px', fontFamily: 'var(--font-mono)', fontSize: 14, fontWeight: 600, color: avail <= 0 ? '#9B2C2C' : C.greenMid }}>{avail}</td>
                    <td style={{ padding: '14px 16px' }}>
                      <select value={d.statusKebutuhan} onChange={e => updateKebutuhan(d.id, e.target.value as Divisi['statusKebutuhan'])} style={{ padding: '5px 10px', background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 6, fontFamily: 'var(--font-body)', fontSize: 12.5, color: C.ink, outline: 'none', cursor: 'pointer' }}>
                        <option>Dibutuhkan</option>
                        <option>Penuh</option>
                        <option>Tidak Menerima</option>
                      </select>
                    </td>
                    <td style={{ padding: '14px 16px' }}>
                      <div style={{ display: 'flex', gap: 6 }}>
                        <button onClick={() => startEdit(d)} style={{ padding: '6px', background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 5, cursor: 'pointer', color: C.muted, display: 'flex', alignItems: 'center' }}><Edit2 size={14} /></button>
                        <button onClick={() => remove(d.id)} style={{ padding: '6px', background: '#FEE2E2', border: '1px solid #FECACA', borderRadius: 5, cursor: 'pointer', color: '#DC2626', display: 'flex', alignItems: 'center' }}><Trash2 size={14} /></button>
                      </div>
                    </td>
                  </tr>
                )
              })}
            </tbody>
          </table>
        </div>

        <div style={{ marginTop: 16, background: C.greenSoft, border: '1px solid #C3DBD6', borderRadius: 8, padding: '12px 16px' }}>
          <p style={{ fontFamily: 'var(--font-body)', fontSize: 12.5, color: C.greenMid, lineHeight: 1.6 }}>
            <strong>Status Kebutuhan</strong> adalah informasi operasional untuk Sekretariat saat menetapkan penempatan. Ini diperbarui secara manual berdasarkan hasil pengecekan ketersediaan divisi (telepon/langsung). Data "Terpakai" mencerminkan mahasiswa dengan status Diterima atau Sedang Magang.
          </p>
        </div>
      </div>
    </div>
  )
}

// ─── Pengguna Internal View ────────────────────────────────────────────────────
function PenggunaView({ users, setUsers }: { users: UserInternal[]; setUsers: (u: UserInternal[]) => void }) {
  const [showAdd, setShowAdd] = useState(false)
  const [form, setForm] = useState({ nama: '', email: '', role: 'Sekretariat' as UserInternal['role'] })

  const save = () => {
    if (!form.nama.trim() || !form.email.trim()) return
    setUsers([...users, { id: Date.now(), ...form, status: 'Aktif', terdaftar: new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) }])
    setForm({ nama: '', email: '', role: 'Sekretariat' })
    setShowAdd(false)
  }

  const toggleStatus = (id: number) => setUsers(users.map(u => u.id === id ? { ...u, status: u.status === 'Aktif' ? 'Nonaktif' : 'Aktif' } : u))
  const remove = (id: number) => setUsers(users.filter(u => u.id !== id))

  return (
    <div>
      <PageHeader title="Kelola Pengguna Internal" subtitle="Akun dan role untuk Sekretariat dan Admin Sistem SIMAGANG." />
      <div style={{ padding: '24px 36px' }}>
        {/* Scope note */}
        <div style={{ background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 8, padding: '12px 16px', marginBottom: 16 }}>
          <p style={{ fontFamily: 'var(--font-body)', fontSize: 12.5, color: C.muted, lineHeight: 1.6 }}>
            Halaman ini khusus untuk akun <strong>internal Bappeda</strong> (Sekretariat dan Admin Sistem). Akun Mahasiswa dikelola secara mandiri melalui registrasi publik. Sekretaris dan Kepala Badan bukan aktor sistem (tidak memerlukan akun).
          </p>
        </div>

        <div style={{ display: 'flex', justifyContent: 'flex-end', marginBottom: 16 }}>
          <button onClick={() => setShowAdd(!showAdd)} style={{ display: 'flex', alignItems: 'center', gap: 7, padding: '9px 18px', background: C.green, color: C.offwhite, border: 'none', borderRadius: 8, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 600 }}>
            <Plus size={16} /> Tambah Pengguna Internal
          </button>
        </div>

        {showAdd && (
          <div style={{ background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 10, padding: '20px 24px', marginBottom: 16 }}>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 13, fontWeight: 700, color: C.ink, marginBottom: 16 }}>Tambah Pengguna Internal</div>
            <div style={{ display: 'grid', gridTemplateColumns: '2fr 2fr 160px auto', gap: 12, alignItems: 'flex-end' }}>
              <div>
                <label style={{ display: 'block', fontFamily: 'var(--font-body)', fontSize: 12, fontWeight: 700, color: C.muted, marginBottom: 5 }}>Nama Lengkap *</label>
                <input value={form.nama} onChange={e => setForm(f => ({ ...f, nama: e.target.value }))} placeholder="Nama beserta gelar" style={{ width: '100%', padding: '9px 12px', background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 7, fontFamily: 'var(--font-body)', fontSize: 13.5, color: C.ink, outline: 'none' }} />
              </div>
              <div>
                <label style={{ display: 'block', fontFamily: 'var(--font-body)', fontSize: 12, fontWeight: 700, color: C.muted, marginBottom: 5 }}>Email Dinas *</label>
                <input type="email" value={form.email} onChange={e => setForm(f => ({ ...f, email: e.target.value }))} placeholder="nama@bappeda.lampung.go.id" style={{ width: '100%', padding: '9px 12px', background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 7, fontFamily: 'var(--font-body)', fontSize: 13.5, color: C.ink, outline: 'none' }} />
              </div>
              <div>
                <label style={{ display: 'block', fontFamily: 'var(--font-body)', fontSize: 12, fontWeight: 700, color: C.muted, marginBottom: 5 }}>Role</label>
                <select value={form.role} onChange={e => setForm(f => ({ ...f, role: e.target.value as UserInternal['role'] }))} style={{ width: '100%', padding: '9px 12px', background: C.ivory, border: `1px solid ${C.rule}`, borderRadius: 7, fontFamily: 'var(--font-body)', fontSize: 13.5, color: C.ink, outline: 'none' }}>
                  <option>Sekretariat</option>
                  <option>Admin Sistem</option>
                </select>
              </div>
              <div style={{ display: 'flex', gap: 8 }}>
                <button onClick={save} style={{ padding: '9px 18px', background: C.green, color: C.offwhite, border: 'none', borderRadius: 7, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 700 }}>Simpan</button>
                <button onClick={() => setShowAdd(false)} style={{ padding: '9px 14px', background: 'none', border: `1px solid ${C.rule}`, borderRadius: 7, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 13, color: C.muted }}>Batal</button>
              </div>
            </div>
          </div>
        )}

        <div style={{ background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 10, overflow: 'hidden' }}>
          <table style={{ width: '100%', borderCollapse: 'collapse' }}>
            <thead>
              <tr style={{ background: C.ivory }}>
                {['Nama', 'Email', 'Role', 'Status', 'Terdaftar', ''].map(h => (
                  <th key={h} style={{ padding: '12px 16px', textAlign: 'left', fontFamily: 'var(--font-body)', fontSize: 10.5, fontWeight: 700, letterSpacing: '0.08em', color: C.muted, textTransform: 'uppercase', borderBottom: `1px solid ${C.rule}` }}>{h}</th>
                ))}
              </tr>
            </thead>
            <tbody>
              {users.map((u, i) => (
                <tr key={u.id} style={{ borderBottom: i < users.length - 1 ? `1px solid ${C.rule}` : 'none' }}>
                  <td style={{ padding: '14px 16px' }}>
                    <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
                      <div style={{ width: 34, height: 34, borderRadius: '50%', background: u.role === 'Admin Sistem' ? C.ink + '15' : C.greenSoft, display: 'flex', alignItems: 'center', justifyContent: 'center', flexShrink: 0 }}>
                        <span style={{ fontFamily: 'var(--font-body)', fontWeight: 700, fontSize: 14, color: u.role === 'Admin Sistem' ? C.ink : C.greenMid }}>{u.nama.charAt(0)}</span>
                      </div>
                      <span style={{ fontFamily: 'var(--font-body)', fontSize: 13.5, fontWeight: 600, color: C.ink }}>{u.nama}</span>
                    </div>
                  </td>
                  <td style={{ padding: '14px 16px', fontFamily: 'var(--font-body)', fontSize: 13, color: C.muted }}>{u.email}</td>
                  <td style={{ padding: '14px 16px' }}><RoleBadge role={u.role} /></td>
                  <td style={{ padding: '14px 16px' }}>
                    <button onClick={() => toggleStatus(u.id)} style={{ padding: '3px 9px', background: u.status === 'Aktif' ? '#D1FAE5' : C.ivory, border: u.status === 'Aktif' ? '1px solid #6EE7B7' : `1px solid ${C.rule}`, borderRadius: 4, cursor: 'pointer', fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.05em', textTransform: 'uppercase', color: u.status === 'Aktif' ? '#065F46' : C.muted }}>
                      {u.status}
                    </button>
                  </td>
                  <td style={{ padding: '14px 16px', fontFamily: 'var(--font-body)', fontSize: 13, color: C.muted }}>{u.terdaftar}</td>
                  <td style={{ padding: '14px 16px' }}>
                    <button onClick={() => remove(u.id)} style={{ padding: '6px', background: '#FEE2E2', border: '1px solid #FECACA', borderRadius: 5, cursor: 'pointer', color: '#DC2626', display: 'flex', alignItems: 'center' }}><Trash2 size={14} /></button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>

        <div style={{ marginTop: 16, background: C.offwhite, border: `1px solid ${C.rule}`, borderRadius: 8, padding: '14px 18px' }}>
          <div style={{ fontFamily: 'var(--font-body)', fontSize: 12, fontWeight: 700, letterSpacing: '0.10em', color: C.muted, textTransform: 'uppercase', marginBottom: 10 }}>Keterangan Role</div>
          <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '8px 24px' }}>
            {[
              { role: 'Sekretariat' as const, desc: 'Memeriksa berkas, menetapkan keputusan penerimaan/penolakan, mengunggah dokumen final, mencatat data pelaksanaan magang. Mencakup Kasubag Umum & Kepegawaian beserta staf administrasi/verifikasi.' },
              { role: 'Admin Sistem' as const, desc: 'Mengelola master data divisi (nama, kapasitas, status kebutuhan) dan akun/role pengguna internal. Bukan pengambil keputusan penerimaan atau penempatan.' },
            ].map(({ role, desc }) => (
              <div key={role} style={{ display: 'flex', gap: 10 }}>
                <RoleBadge role={role} />
                <p style={{ fontFamily: 'var(--font-body)', fontSize: 12.5, color: C.muted, lineHeight: 1.6, flex: 1 }}>{desc}</p>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  )
}

function KebutuhanBadge({ status }: { status: Divisi['statusKebutuhan'] }) {
  const m = {
    'Dibutuhkan': { bg: C.greenSoft, color: C.greenMid },
    'Penuh': { bg: '#FEF3C7', color: '#92400E' },
    'Tidak Menerima': { bg: '#FEE2E2', color: '#991B1B' },
  }
  const s = m[status]
  return <span style={{ fontFamily: 'var(--font-body)', fontSize: 10.5, fontWeight: 700, letterSpacing: '0.05em', textTransform: 'uppercase', background: s.bg, color: s.color, padding: '3px 8px', borderRadius: 4, whiteSpace: 'nowrap' }}>{status}</span>
}

function RoleBadge({ role }: { role: UserInternal['role'] }) {
  const m = {
    'Sekretariat': { bg: C.greenSoft, color: C.greenMid },
    'Admin Sistem': { bg: C.ink + '10', color: C.ink },
  }
  const s = m[role] ?? { bg: C.ivory, color: C.muted }
  return <span style={{ fontFamily: 'var(--font-body)', fontSize: 11, fontWeight: 700, letterSpacing: '0.05em', textTransform: 'uppercase', background: s.bg, color: s.color, padding: '3px 9px', borderRadius: 4, whiteSpace: 'nowrap' }}>{role}</span>
}
