import { useEffect, useState } from 'react'
import { Bar, Doughnut } from 'react-chartjs-2'
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  BarElement,
  ArcElement,
  Tooltip,
  Legend,
} from 'chart.js'

ChartJS.register(CategoryScale, LinearScale, BarElement, ArcElement, Tooltip, Legend)

function authHeaders() {
  return {
    Authorization: `Bearer ${localStorage.getItem('stockflow_token')}`,
  }
}

function Dashboard() {
  const [products, setProducts] = useState([])
  const [orders, setOrders] = useState([])

  useEffect(() => {
    Promise.all([
      fetch('/api/products', { headers: authHeaders() }).then((r) => r.json()),
      fetch('/api/orders', { headers: authHeaders() }).then((r) => r.json()),
    ]).then(([p, o]) => {
      setProducts(Array.isArray(p) ? p : [])
      setOrders(Array.isArray(o) ? o : [])
    })
  }, [])

  const lowStock = products.filter(
    (p) => Number(p.stock_quantity) <= Number(p.low_stock_threshold)
  ).length
  const revenue = orders
    .filter((o) => o.status !== 'cancelled')
    .reduce((sum, o) => sum + Number(o.total), 0)

  const statusCounts = ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'].map(
    (status) => orders.filter((o) => o.status === status).length
  )

  return (
    <div className="mt-4">
      <h2>Dashboard</h2>
      <div className="row g-3 mb-4">
        <div className="col-md-3">
          <div className="card"><div className="card-body">
            <div className="text-muted">Products</div>
            <div className="fs-3">{products.length}</div>
          </div></div>
        </div>
        <div className="col-md-3">
          <div className="card"><div className="card-body">
            <div className="text-muted">Low stock</div>
            <div className="fs-3">{lowStock}</div>
          </div></div>
        </div>
        <div className="col-md-3">
          <div className="card"><div className="card-body">
            <div className="text-muted">Orders</div>
            <div className="fs-3">{orders.length}</div>
          </div></div>
        </div>
        <div className="col-md-3">
          <div className="card"><div className="card-body">
            <div className="text-muted">Revenue</div>
            <div className="fs-3">{revenue.toFixed(2)}</div>
          </div></div>
        </div>
      </div>

      <div className="row g-3">
        <div className="col-md-7">
          <div className="card"><div className="card-body">
            <h5>Stock by product</h5>
            <Bar
              data={{
                labels: products.map((p) => p.sku),
                datasets: [{
                  label: 'Stock',
                  data: products.map((p) => Number(p.stock_quantity)),
                  backgroundColor: '#0d6efd',
                }],
              }}
            />
          </div></div>
        </div>
        <div className="col-md-5">
          <div className="card"><div className="card-body">
            <h5>Orders by status</h5>
            <Doughnut
              data={{
                labels: ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'],
                datasets: [{
                  data: statusCounts,
                  backgroundColor: ['#ffc107', '#0d6efd', '#6f42c1', '#198754', '#dc3545'],
                }],
              }}
            />
          </div></div>
        </div>
      </div>
    </div>
  )
}

export default Dashboard
