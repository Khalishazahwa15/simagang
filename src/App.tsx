import { useState } from 'react'
import Navbar from './components/Navbar'
import Beranda from './pages/Beranda'
import Alur from './pages/Alur'
import Persyaratan from './pages/Persyaratan'
import FAQ from './pages/FAQ'
import Login from './pages/Login'
import StudentDashboard from './pages/StudentDashboard'
import SekretariatDashboard from './pages/SekretariatDashboard'
import AdminSistemDashboard from './pages/AdminSistemDashboard'

export type Page =
  | 'beranda'
  | 'alur'
  | 'persyaratan'
  | 'faq'
  | 'login'
  | 'register'
  | 'student-dashboard'
  | 'sekretariat-dashboard'
  | 'admin-sistem-dashboard'

export type UserRole = 'mahasiswa' | 'sekretariat' | 'admin_sistem' | null

export default function App() {
  const [page, setPage] = useState<Page>('beranda')
  const [role, setRole] = useState<UserRole>(null)

  const navigate = (p: Page) => {
    setPage(p)
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }

  const login = (r: UserRole) => {
    setRole(r)
    if (r === 'mahasiswa') navigate('student-dashboard')
    else if (r === 'sekretariat') navigate('sekretariat-dashboard')
    else if (r === 'admin_sistem') navigate('admin-sistem-dashboard')
  }

  const logout = () => {
    setRole(null)
    navigate('beranda')
  }

  const isAuth = page === 'login' || page === 'register'
  const isStudent = page === 'student-dashboard'
  const isSekretariat = page === 'sekretariat-dashboard'
  const isAdminSistem = page === 'admin-sistem-dashboard'
  const isInternal = isStudent || isSekretariat || isAdminSistem

  return (
    <div style={{ minHeight: '100vh', fontFamily: 'var(--font-body)' }}>
      {!isInternal && !isAuth && (
        <Navbar navigate={navigate} currentPage={page} />
      )}

      {page === 'beranda' && <Beranda navigate={navigate} />}
      {page === 'alur' && <Alur navigate={navigate} />}
      {page === 'persyaratan' && <Persyaratan navigate={navigate} />}
      {page === 'faq' && <FAQ navigate={navigate} />}
      {isAuth && <Login navigate={navigate} mode={page === 'register' ? 'register' : 'login'} onLogin={login} />}
      {isStudent && <StudentDashboard navigate={navigate} onLogout={logout} />}
      {isSekretariat && <SekretariatDashboard navigate={navigate} onLogout={logout} />}
      {isAdminSistem && <AdminSistemDashboard navigate={navigate} onLogout={logout} />}
    </div>
  )
}
