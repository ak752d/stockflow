<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class CategoryController extends ResourceController
{
    protected $modelName = CategoryModel::class;
    protected $format    = 'json';

    public function index(): ResponseInterface
    {
        return $this->respond($this->model->findAll());
    }

    public function show($id = null): ResponseInterface
    {
        $category = $this->model->find($id);

        if ($category === null) {
            return $this->failNotFound('Category not found.');
        }

        return $this->respond($category);
    }

    public function create(): ResponseInterface
    {
        $data = $this->request->getJSON(true) ?? [];

        if (! $this->validateData($data, [
            'name' => 'required|min_length[2]|max_length[100]|is_unique[categories.name]',
        ])) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $id = $this->model->insert($data);

        return $this->respondCreated($this->model->find($id));
    }

    public function update($id = null): ResponseInterface
    {
        if ($this->model->find($id) === null) {
            return $this->failNotFound('Category not found.');
        }

        $data = $this->request->getJSON(true) ?? [];

        if (! $this->validateData($data, [
            'name' => "required|min_length[2]|max_length[100]|is_unique[categories.name,id,{$id}]",
        ])) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $this->model->update($id, $data);

        return $this->respond($this->model->find($id));
    }

    public function delete($id = null): ResponseInterface
    {
        if ($this->model->find($id) === null) {
            return $this->failNotFound('Category not found.');
        }

        $this->model->delete($id);

        return $this->respondDeleted(['id' => (int) $id]);
    }
}
