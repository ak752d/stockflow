import { useEffect, useState } from 'react'

function authHeaders() {
  return {
    'Content-Type': 'application/json',
    Authorization: `Bearer ${localStorage.getItem('stockflow_token')}`,
  }
}

function Products() {
  const [categories, setCategories] = useState([])
  const [products, setProducts] = useState([])
  const [error, setError] = useState('')
  const [categoryName, setCategoryName] = useState('')
  const [form, setForm] = useState({
    category_id: '',
    name: '',
    sku: '',
    price: '',
    stock_quantity: '0',
    low_stock_threshold: '5',
  })

  async function load() {
    const [catRes, prodRes] = await Promise.all([
      fetch('/api/categories', { headers: authHeaders() }),
      fetch('/api/products', { headers: authHeaders() }),
    ])
    setCategories(await catRes.json())
    setProducts(await prodRes.json())
  }

  useEffect(() => {
    load().catch(() => setError('Could not load products.'))
  }, [])

  async function addCategory(event) {
    event.preventDefault()
    setError('')
    const res = await fetch('/api/categories', {
      method: 'POST',
      headers: authHeaders(),
      body: JSON.stringify({ name: categoryName }),
    })
    const data = await res.json()
    if (!res.ok) {
      setError(data.message || JSON.stringify(data.messages || data))
      return
    }
    setCategoryName('')
    await load()
  }

  async function addProduct(event) {
    event.preventDefault()
    setError('')
    const res = await fetch('/api/products', {
      method: 'POST',
      headers: authHeaders(),
      body: JSON.stringify({
        ...form,
        category_id: Number(form.category_id),
        price: form.price,
        stock_quantity: Number(form.stock_quantity),
        low_stock_threshold: Number(form.low_stock_threshold),
      }),
    })
    const data = await res.json()
    if (!res.ok) {
      setError(data.message || JSON.stringify(data.messages || data))
      return
    }
    setForm({
      category_id: form.category_id,
      name: '',
      sku: '',
      price: '',
      stock_quantity: '0',
      low_stock_threshold: '5',
    })
    await load()
  }

  return (
    <div className="mt-4">
      <h2>Products</h2>
      {error && <div className="alert alert-danger">{error}</div>}

      <form className="row g-2 align-items-end mb-4" onSubmit={addCategory}>
        <div className="col-md-4">
          <label className="form-label">New category</label>
          <input
            className="form-control"
            value={categoryName}
            onChange={(e) => setCategoryName(e.target.value)}
            required
          />
        </div>
        <div className="col-md-2">
          <button className="btn btn-secondary" type="submit">Add category</button>
        </div>
      </form>

      <form className="row g-2 align-items-end mb-4" onSubmit={addProduct}>
        <div className="col-md-2">
          <label className="form-label">Category</label>
          <select
            className="form-select"
            value={form.category_id}
            onChange={(e) => setForm({ ...form, category_id: e.target.value })}
            required
          >
            <option value="">Select</option>
            {categories.map((c) => (
              <option key={c.id} value={c.id}>{c.name}</option>
            ))}
          </select>
        </div>
        <div className="col-md-2">
          <label className="form-label">Name</label>
          <input className="form-control" value={form.name} onChange={(e) => setForm({ ...form, name: e.target.value })} required />
        </div>
        <div className="col-md-2">
          <label className="form-label">SKU</label>
          <input className="form-control" value={form.sku} onChange={(e) => setForm({ ...form, sku: e.target.value })} required />
        </div>
        <div className="col-md-2">
          <label className="form-label">Price</label>
          <input className="form-control" value={form.price} onChange={(e) => setForm({ ...form, price: e.target.value })} required />
        </div>
        <div className="col-md-2">
          <label className="form-label">Stock</label>
          <input className="form-control" type="number" min="0" value={form.stock_quantity} onChange={(e) => setForm({ ...form, stock_quantity: e.target.value })} required />
        </div>
        <div className="col-md-2">
          <button className="btn btn-primary" type="submit">Add product</button>
        </div>
      </form>

      <table className="table table-striped">
        <thead>
          <tr>
            <th>SKU</th>
            <th>Name</th>
            <th>Price</th>
            <th>Stock</th>
          </tr>
        </thead>
        <tbody>
          {products.length === 0 && (
            <tr><td colSpan="4">No products yet.</td></tr>
          )}
          {products.map((p) => (
            <tr key={p.id}>
              <td>{p.sku}</td>
              <td>{p.name}</td>
              <td>{p.price}</td>
              <td>{p.stock_quantity}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  )
}

export default Products
