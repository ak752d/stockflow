import { useEffect, useState } from 'react'

function authHeaders() {
  return {
    'Content-Type': 'application/json',
    Authorization: `Bearer ${localStorage.getItem('stockflow_token')}`,
  }
}

function Orders() {
  const [products, setProducts] = useState([])
  const [orders, setOrders] = useState([])
  const [error, setError] = useState('')
  const [customer, setCustomer] = useState({
    customer_name: '',
    customer_email: '',
    customer_phone: '',
  })
  const [items, setItems] = useState([{ product_id: '', quantity: 1 }])

  async function load() {
    const [prodRes, orderRes] = await Promise.all([
      fetch('/api/products', { headers: authHeaders() }),
      fetch('/api/orders', { headers: authHeaders() }),
    ])
    setProducts(await prodRes.json())
    setOrders(await orderRes.json())
  }

  useEffect(() => {
    load().catch(() => setError('Could not load orders.'))
  }, [])

  function updateItem(index, field, value) {
    const next = [...items]
    next[index] = { ...next[index], [field]: value }
    setItems(next)
  }

  async function createOrder(event) {
    event.preventDefault()
    setError('')

    const res = await fetch('/api/orders', {
      method: 'POST',
      headers: authHeaders(),
      body: JSON.stringify({
        ...customer,
        items: items
          .filter((i) => i.product_id)
          .map((i) => ({
            product_id: Number(i.product_id),
            quantity: Number(i.quantity),
          })),
      }),
    })
    const data = await res.json()
    if (!res.ok) {
      setError(data.message || JSON.stringify(data.messages || data))
      return
    }

    setCustomer({ customer_name: '', customer_email: '', customer_phone: '' })
    setItems([{ product_id: '', quantity: 1 }])
    await load()
  }

  return (
    <div className="mt-5">
      <h2>Orders</h2>
      {error && <div className="alert alert-danger">{error}</div>}

      <form className="mb-4" onSubmit={createOrder}>
        <div className="row g-2 mb-2">
          <div className="col-md-4">
            <input className="form-control" placeholder="Customer name" value={customer.customer_name} onChange={(e) => setCustomer({ ...customer, customer_name: e.target.value })} required />
          </div>
          <div className="col-md-4">
            <input className="form-control" placeholder="Email" type="email" value={customer.customer_email} onChange={(e) => setCustomer({ ...customer, customer_email: e.target.value })} />
          </div>
          <div className="col-md-4">
            <input className="form-control" placeholder="Phone" value={customer.customer_phone} onChange={(e) => setCustomer({ ...customer, customer_phone: e.target.value })} />
          </div>
        </div>

        {items.map((item, index) => (
          <div className="row g-2 mb-2" key={index}>
            <div className="col-md-8">
              <select className="form-select" value={item.product_id} onChange={(e) => updateItem(index, 'product_id', e.target.value)} required>
                <option value="">Select product</option>
                {products.map((p) => (
                  <option key={p.id} value={p.id}>
                    {p.name} ({p.sku}) — stock {p.stock_quantity}
                  </option>
                ))}
              </select>
            </div>
            <div className="col-md-4">
              <input className="form-control" type="number" min="1" value={item.quantity} onChange={(e) => updateItem(index, 'quantity', e.target.value)} required />
            </div>
          </div>
        ))}

        <button className="btn btn-outline-secondary me-2" type="button" onClick={() => setItems([...items, { product_id: '', quantity: 1 }])}>
          Add line
        </button>
        <button className="btn btn-primary" type="submit">Create order</button>
      </form>

      <table className="table table-striped">
        <thead>
          <tr>
            <th>#</th>
            <th>Customer</th>
            <th>Status</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          {orders.length === 0 && (
            <tr><td colSpan="4">No orders yet.</td></tr>
          )}
          {orders.map((o) => (
            <tr key={o.id}>
              <td>{o.id}</td>
              <td>{o.customer_name}</td>
              <td>{o.status}</td>
              <td>{o.total}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  )
}

export default Orders
