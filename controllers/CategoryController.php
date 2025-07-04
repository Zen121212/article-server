<?php

require(__DIR__ . "/../connection/connection.php");
require(__DIR__ . "/../models/Category.php");
require(__DIR__ . "/../services/CategoryService.php");
require(__DIR__ . "/../services/ResponseService.php");

class CategoryController {

    public function getAllCategories() {
        global $mysqli;
        try {
            $categories = Category::all($mysqli);
            $categories_array = CategoryService::categoriesToArray($categories);
            echo ResponseService::response($categories_array, 200);
        } catch (Exception $e) {
            echo ResponseService::response("Server error occurred while fetching categories", 500);
        }
    }

    public function getCategoryById($id) {
        global $mysqli;
        try {
            $category = Category::find($mysqli, $id);
            if (!$category) {
                echo ResponseService::response("Category not found", 404);
                return;
            }
            echo ResponseService::response($category->toArray(), 200);
        } catch (Exception $e) {
            echo ResponseService::response("Server error occurred while fetching category", 500);
        }
    }

    public function createCategory() {
        global $mysqli;
        try {
            if (empty($_POST['name'])) {
                echo ResponseService::response("Missing required field: name", 400);
                return;
            }
            $data = ['name' => $_POST['name']];
            $success = Category::create($mysqli, $data);
            if ($success) {
                echo ResponseService::response("Category created", 201);
            } else {
                echo ResponseService::response("Failed to create category", 500);
            }
        } catch (Exception $e) {
            echo ResponseService::response("Server error occurred while creating category", 500);
        }
    }
    
    public function updateCategoryById($id) {
        global $mysqli;
        try {
            $category = Category::find($mysqli, $id);
            if (!$category) {
                echo ResponseService::response("Category not found", 404);
                return;
            }
            if (!isset($_POST['name'])) {
                echo ResponseService::response("No name provided", 400);
                return;
            }
            $dataToUpdate = ['name' => $_POST['name']];
            $success = $category->update($mysqli, $dataToUpdate);
            if ($success) {
                echo ResponseService::response("Category updated", 200);
            } else {
                echo ResponseService::response("Failed to update category", 500);
            }
        } catch (Exception $e) {
            echo ResponseService::response("Server error occurred while updating category", 500);
        }
    }

    public function deleteCategoryById($id) {
        global $mysqli;
        try {
            $deleted = Category::deleteById($mysqli, $id);
            if ($deleted) {
                echo ResponseService::response("Category deleted", 200);
            } else {
                echo ResponseService::response("Category not found", 404);
            }
        } catch (Exception $e) {
            echo ResponseService::response("Server error occurred while deleting category", 500);
        }
    }
}
