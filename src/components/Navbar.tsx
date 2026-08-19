import { useState } from 'react'
import { Menu, X } from 'lucide-react'
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
  currentPage: Page
}

export default function Navbar({ navigate, currentPage }: Props) {
  const [mobileOpen, setMobileOpen] = useState(false)

  const links: { label: string; page: Page }[] = [
    { label: 'Beranda', page: 'beranda' },
    { label: 'Alur Pengajuan', page: 'alur' },
    { label: 'Persyaratan', page: 'persyaratan' },
    { label: 'FAQ', page: 'faq' },
  ]

  return (
    <header
      style={{
        position: 'sticky',
        top: 0,
        zIndex: 100,
        background: C.offwhite,
        borderBottom: `1px solid ${C.rule}`,
      }}
    >
      {/* Thin gold accent line */}
      <div style={{ height: 3, background: C.gold }} />

      <div
        style={{
          maxWidth: 1240,
          margin: '0 auto',
          padding: '0 32px',
          height: 64,
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'space-between',
        }}
      >
        {/* Logo */}
        <button
          onClick={() => navigate('beranda')}
          style={{ display: 'flex', alignItems: 'center', gap: 12, background: 'none', border: 'none', cursor: 'pointer' }}
        >
          <div
            style={{
              width: 36,
              height: 36,
              background: C.green,
              borderRadius: 8,
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
            }}
          >
            <span style={{ color: C.gold, fontFamily: 'var(--font-display)', fontSize: 16, fontWeight: 400 }}>S</span>
          </div>
          <div style={{ textAlign: 'left' }}>
            <div style={{ fontFamily: 'var(--font-body)', fontWeight: 700, fontSize: 15, color: C.green, lineHeight: 1.2, letterSpacing: '-0.01em' }}>
              SIMAGANG
            </div>
            <div style={{ fontFamily: 'var(--font-body)', fontSize: 10, color: C.muted, letterSpacing: '0.04em', lineHeight: 1.2 }}>
              BAPPEDA PROVINSI LAMPUNG
            </div>
          </div>
        </button>

        {/* Desktop nav */}
        <nav style={{ display: 'flex', alignItems: 'center', gap: 4 }} className="hidden-mobile">
          {links.map(({ label, page }) => (
            <button
              key={page}
              onClick={() => navigate(page)}
              style={{
                padding: '8px 16px',
                background: currentPage === page ? C.green : 'none',
                border: 'none',
                cursor: 'pointer',
                fontFamily: 'var(--font-body)',
                fontSize: 13.5,
                fontWeight: currentPage === page ? 700 : 500,
                color: currentPage === page ? C.offwhite : C.muted,
                borderRadius: 8,
                transition: 'all 0.15s',
                borderBottom: currentPage === page ? `3px solid ${C.gold}` : '3px solid transparent',
              }}
            >
              {label}
            </button>
          ))}
        </nav>

        {/* Actions */}
        <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
          <button
            onClick={() => navigate('login')}
            style={{
              padding: '8px 18px',
              background: 'none',
              border: `1px solid ${C.rule}`,
              borderRadius: 8,
              cursor: 'pointer',
              fontFamily: 'var(--font-body)',
              fontSize: 13.5,
              fontWeight: 600,
              color: C.ink,
              transition: 'all 0.15s',
            }}
          >
            Masuk
          </button>
          <button
            onClick={() => navigate('login')}
            style={{
              padding: '8px 18px',
              background: C.green,
              border: `1px solid ${C.green}`,
              borderRadius: 8,
              cursor: 'pointer',
              fontFamily: 'var(--font-body)',
              fontSize: 13.5,
              fontWeight: 600,
              color: C.offwhite,
              transition: 'all 0.15s',
            }}
          >
            Ajukan Magang
          </button>
          <button
            className="mobile-only"
            onClick={() => setMobileOpen(!mobileOpen)}
            style={{ background: 'none', border: 'none', cursor: 'pointer', padding: 8, color: C.ink }}
          >
            {mobileOpen ? <X size={20} /> : <Menu size={20} />}
          </button>
        </div>
      </div>

      {/* Mobile menu */}
      {mobileOpen && (
        <div style={{ borderTop: `1px solid ${C.rule}`, background: C.offwhite, padding: '12px 24px 20px' }}>
          {links.map(({ label, page }) => (
            <button
              key={page}
              onClick={() => { navigate(page); setMobileOpen(false) }}
              style={{
                display: 'block',
                width: '100%',
                textAlign: 'left',
                padding: '12px 16px',
                background: currentPage === page ? C.green : 'none',
                border: 'none',
                borderLeft: currentPage === page ? `3px solid ${C.gold}` : '3px solid transparent',
                borderRadius: 8,
                cursor: 'pointer',
                fontFamily: 'var(--font-body)',
                fontSize: 15,
                fontWeight: currentPage === page ? 700 : 500,
                color: currentPage === page ? C.offwhite : C.ink,
                marginBottom: 4,
              }}
            >
              {label}
            </button>
          ))}
        </div>
      )}

      <style>{`
        @media (max-width: 768px) {
          .hidden-mobile { display: none !important; }
        }
        @media (min-width: 769px) {
          .mobile-only { display: none !important; }
        }
      `}</style>
    </header>
  )
}
