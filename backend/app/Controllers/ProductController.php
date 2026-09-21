<?php

namespace App\Controllers;

use App\Models\ProductModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class ProductController extends ResourceController
{
    protected $modelName = ProductModel::class;
    protected $format    = 'json';

    public function index(): ResponseInterface
    {
        return $this->respond($this->model->findAll());
    }

    public function show($id = null): ResponseInterface
    {
        $product = $this->model->find($id);

        if ($product === null) {
            return $this->failNotFound('Product not found.');
        }

        return $this->respond($product);
    }

    public function create(): ResponseInterface
    {
        $data = $this->request->getJSON(true) ?? [];

        if (! $this->validateData($data, $this->rules())) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $id = $this->model->insert($data);

        return $this->respondCreated($this->model->find($id));
    }

    public function update($id = null): ResponseInterface
    {
        if ($this->model->find($id) === null) {
            return $this->failNotFound('Product not found.');
        }

        $data = $this->request->getJSON(true) ?? [];

        if (! $this->validateData($data, $this->rules((int) $id))) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $this->model->update($id, $data);

        return $this->respond($this->model->find($id));
    }

    public function delete($id = null): ResponseInterface
    {
        if ($this->model->find($id) === null) {
            return $this->failNotFound('Product not found.');
        }

        $this->model->delete($id);

        return $this->respondDeleted(['id' => (int) $id]);
    }

    private function rules(?int $id = null): array
    {
        $skuUnique = 'is_unique[products.sku]';
        if ($id !== null) {
            $skuUnique = "is_unique[products.sku,id,{$id}]";
        }

        return [
            'category_id'         => 'required|is_not_unique[categories.id]',
            'name'                => 'required|min_length[2]|max_length[150]',
            'sku'                 => "required|max_length[64]|{$skuUnique}",
            'price'               => 'required|decimal',
            'stock_quantity'      => 'required|integer|greater_than_equal_to[0]',
            'low_stock_threshold' => 'permit_empty|integer|greater_than_equal_to[0]',
            'image'               => 'permit_empty|max_length[255]',
        ];
    }
}
