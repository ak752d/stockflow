<?php

namespace App\Controllers;

use App\Models\OrderItemModel;
use App\Models\OrderModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Config\Database;
use Throwable;

class OrderController extends ResourceController
{
    protected $modelName = OrderModel::class;
    protected $format    = 'json';

    private array $allowed = [
        'pending'   => ['confirmed', 'cancelled'],
        'confirmed' => ['shipped', 'cancelled'],
        'shipped'   => ['delivered'],
        'delivered' => [],
        'cancelled' => [],
    ];

    public function index(): ResponseInterface
    {
        return $this->respond($this->model->orderBy('id', 'DESC')->findAll());
    }

    public function show($id = null): ResponseInterface
    {
        $order = $this->model->find($id);

        if ($order === null) {
            return $this->failNotFound('Order not found.');
        }

        $order['items'] = (new OrderItemModel())->where('order_id', $id)->findAll();

        return $this->respond($order);
    }

    public function create(): ResponseInterface
    {
        $data = $this->request->getJSON(true) ?? [];

        if (! $this->validateData($data, [
            'customer_name'  => 'required|min_length[2]|max_length[150]',
            'customer_email' => 'permit_empty|valid_email',
            'customer_phone' => 'permit_empty|max_length[30]',
            'items'          => 'required',
        ])) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $items = $this->mergeItems($data['items'] ?? []);

        if ($items === []) {
            return $this->failValidationErrors(['items' => 'At least one item is required.']);
        }

        $db = Database::connect();
        $db->transStart();

        try {
            $orderId = $this->model->insert([
                'user_id'        => (int) $this->request->user->sub,
                'customer_name'  => $data['customer_name'],
                'customer_email' => $data['customer_email'] ?? null,
                'customer_phone' => $data['customer_phone'] ?? null,
                'status'         => 'pending',
                'total'          => '0.00',
            ]);

            $total = '0.00';
            $itemModel = new OrderItemModel();

            foreach ($items as $productId => $quantity) {
                $product = $db->query(
                    'SELECT * FROM products WHERE id = ? AND deleted_at IS NULL FOR UPDATE',
                    [$productId]
                )->getRowArray();

                if ($product === null) {
                    $db->transRollback();

                    return $this->failNotFound("Product {$productId} not found.");
                }

                if ((int) $product['stock_quantity'] < $quantity) {
                    $db->transRollback();

                    return $this->fail(
                        "Insufficient stock for SKU {$product['sku']}.",
                        422
                    );
                }

                $lineTotal = sprintf('%.2f', round((float) $product['price'] * $quantity, 2));
                $total     = sprintf('%.2f', (float) $total + (float) $lineTotal);

                $itemModel->insert([
                    'order_id'     => $orderId,
                    'product_id'   => $productId,
                    'product_name' => $product['name'],
                    'sku'          => $product['sku'],
                    'quantity'     => $quantity,
                    'unit_price'   => $product['price'],
                    'line_total'   => $lineTotal,
                ]);

                $db->query(
                    'UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?',
                    [$quantity, $productId]
                );
            }

            $this->model->update($orderId, ['total' => $total]);
            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->failServerError('Could not create order.');
            }
        } catch (Throwable $e) {
            $db->transRollback();

            return $this->failServerError('Could not create order.');
        }

        $order = $this->model->find($orderId);
        $order['items'] = (new OrderItemModel())->where('order_id', $orderId)->findAll();

        return $this->respondCreated($order);
    }

    public function update($id = null): ResponseInterface
    {
        $order = $this->model->find($id);

        if ($order === null) {
            return $this->failNotFound('Order not found.');
        }

        $data = $this->request->getJSON(true) ?? [];
        $status = (string) ($data['status'] ?? '');

        if (! in_array($status, $this->allowed[$order['status']] ?? [], true)) {
            return $this->fail("Cannot change status from {$order['status']} to {$status}.", 422);
        }

        $db = Database::connect();
        $db->transStart();

        try {
            if ($status === 'cancelled') {
                $lines = (new OrderItemModel())->where('order_id', $id)->findAll();

                foreach ($lines as $line) {
                    $db->query(
                        'SELECT id FROM products WHERE id = ? FOR UPDATE',
                        [$line['product_id']]
                    );
                    $db->query(
                        'UPDATE products SET stock_quantity = stock_quantity + ? WHERE id = ?',
                        [$line['quantity'], $line['product_id']]
                    );
                }
            }

            $this->model->update($id, ['status' => $status]);
            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->failServerError('Could not update order.');
            }
        } catch (Throwable $e) {
            $db->transRollback();

            return $this->failServerError('Could not update order.');
        }

        $order = $this->model->find($id);
        $order['items'] = (new OrderItemModel())->where('order_id', $id)->findAll();

        return $this->respond($order);
    }

    /**
     * @param list<array<string, mixed>> $items
     *
     * @return array<int, int>
     */
    private function mergeItems(array $items): array
    {
        $merged = [];

        foreach ($items as $item) {
            $productId = (int) ($item['product_id'] ?? 0);
            $quantity  = (int) ($item['quantity'] ?? 0);

            if ($productId < 1 || $quantity < 1) {
                continue;
            }

            $merged[$productId] = ($merged[$productId] ?? 0) + $quantity;
        }

        return $merged;
    }
}
