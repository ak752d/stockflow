import { useState } from 'react'
import Dashboard from './Dashboard.jsx'
import Products from './Products.jsx'
import Orders from './Orders.jsx'

function App() {
  const [email, setEmail] = useState('admin@stockflow.test')
  const [password, setPassword] = useState('Admin123!')
  const [error, setError] = useState('')
  const [user, setUser] = useState(() => {
    const raw = localStorage.getItem('stockflow_user')
    return raw ? JSON.parse(raw) : null
  })

  async function handleLogin(event) {
    event.preventDefault()
    setError('')

    const res = await fetch('/api/login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email, password }),
    })
    const data = await res.json()

    if (!res.ok) {
      setError(data.message || 'Login failed')
      return
    }

    localStorage.setItem('stockflow_token', data.token)
    localStorage.setItem('stockflow_user', JSON.stringify(data.user))
    setUser(data.user)
  }

  function logout() {
    localStorage.removeItem('stockflow_token')
    localStorage.removeItem('stockflow_user')
    setUser(null)
  }

  if (user) {
    return (
      <div className="container py-4">
        <div className="d-flex justify-content-between align-items-center">
          <div>
            <h1 className="h3 mb-0">StockFlow</h1>
            <small className="text-muted">{user.name} ({user.role})</small>
          </div>
          <button className="btn btn-outline-secondary" onClick={logout}>
            Log out
          </button>
        </div>
        <Dashboard />
        <Products />
        <Orders />
      </div>
    )
  }

  return (
    <div className="container py-5" style={{ maxWidth: 420 }}>
      <h1 className="mb-4">StockFlow</h1>
      {error && <div className="alert alert-danger">{error}</div>}
      <form onSubmit={handleLogin}>
        <div className="mb-3">
          <label className="form-label">Email</label>
          <input
            className="form-control"
            type="email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            required
          />
        </div>
        <div className="mb-3">
          <label className="form-label">Password</label>
          <input
            className="form-control"
            type="password"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            required
          />
        </div>
        <button className="btn btn-primary w-100" type="submit">
          Log in
        </button>
      </form>
    </div>
  )
}

export default App
