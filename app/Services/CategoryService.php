<?php

namespace App\Services;

use App\Repositories\CategoryRepository;

class CategoryService
{
    protected $categoryRepo;

    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    public function getAllCategories()
    {
        return $this->categoryRepo->getAll();
    }

    public function storeCategory(array $data)
    {
        return $this->categoryRepo->create($data);
    }

    public function getCategoryById($id)
    {
        return $this->categoryRepo->findById($id);
    }

    public function updateCategory($id, array $data)
    {
        return $this->categoryRepo->update($id, $data);
    }

    public function deleteCategory($id)
    {
        return $this->categoryRepo->delete($id);
    }
}