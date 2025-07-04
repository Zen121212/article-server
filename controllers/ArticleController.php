<?php
require(__DIR__ . "/../connection/connection.php");
require(__DIR__ . "/../services/ResponseService.php");
require(__DIR__ . "/../models/Article.php");
require(__DIR__ . "/../services/ArticleService.php");
require(__DIR__ . "/../services/CategoryService.php");

class ArticleController {

    public function getAllArticles() {
        global $mysqli;

        try {
            $articles = Article::all($mysqli);
            $articles_array = ArticleService::articlesToArray($articles);
            echo ResponseService::response($articles_array, 200);
        } catch (Exception $e) {
            echo ResponseService::response("Server error occurred while fetching articles", 500);
        }
    }

    public function getArticleById(int $id) {
        global $mysqli;

        try {
            $article = Article::find($mysqli, $id);

            if (!$article) {
                echo ResponseService::response("Article not found", 404);
                return;
            }
            echo ResponseService::response($article->toArray(), 200);
        } catch (Exception $e) {
            echo ResponseService::response("Server error occurred while fetching article", 500);
        }
    }

    public function createArticle() {
        global $mysqli;

        try {
            if (empty($_POST['name'])) {
                echo ResponseService::response("Missing required field: name", 400);
                return;
            }
            if (empty($_POST['author'])) {
                echo ResponseService::response("Missing required field: author", 400);
                return;
            }
            if (empty($_POST['description'])) {
                echo ResponseService::response("Missing required field: description", 400);
                return;
            }

            $data = [
                'name' => $_POST['name'],
                'author' => $_POST['author'],
                'description' => $_POST['description'],
            ];

            $success = Article::create($mysqli, $data);

            if ($success) {
                echo ResponseService::response("Article created", 201);
            } else {
                echo ResponseService::response("Failed to create article", 500);
            }
        } catch (Exception $e) {
            echo ResponseService::response("Server error occurred while creating article", 500);
        }
    }

    public function updateArticleById(int $id) {
        global $mysqli;

        try {
            $article = Article::find($mysqli, $id);
            if (!$article) {
                echo ResponseService::response("Article not found", 404);
                return;
            }
            $dataToUpdate = [];
            if (isset($_POST['name'])) {
                $dataToUpdate['name'] = $_POST['name'];
            }
            if (isset($_POST['author'])) {
                $dataToUpdate['author'] = $_POST['author'];
            }
            if (isset($_POST['description'])) {
                $dataToUpdate['description'] = $_POST['description'];
            }
            if (empty($dataToUpdate)) {
                echo ResponseService::response("No valid fields provided to update", 400);
                return;
            }
            $success = $article->update($mysqli, $dataToUpdate);
            if ($success) {
                echo ResponseService::response("Article updated", 200);
            } else {
                echo ResponseService::response("Failed to update article", 500);
            }

        } catch (Exception $e) {
            echo ResponseService::response("Internal server error: " . $e->getMessage(), 500);
        }
    }

    public function deleteArticleById(int $id) {
        global $mysqli;
        try {
            $deleted = Article::deleteById($mysqli, $id);
            if ($deleted) {
                echo ResponseService::response("Article deleted", 200);
            } else {
                echo ResponseService::response("Article not found", 404);
            }
        } catch (Exception $e) {
            echo ResponseService::response("Error: " . $e->getMessage(), 500);
        }
    }

    public function deleteAllArticles() {
        global $mysqli;
        try {
            Article::deleteAll($mysqli);
            echo ResponseService::response("All articles deleted", 200);
        } catch (Exception $e) {
            echo ResponseService::response("Failed to delete articles", 500);
        }
    }

    public function getCategoriesByArticleId(int $articleId) {
        try {
            $categories = CategoryService::getCategoriesByArticleId($articleId);
            $result = [];
            foreach ($categories as $category) {
                $result[] = $category->toArray();
            }
            echo ResponseService::response($result, 200);
        } catch (Exception $e) {
            echo ResponseService::response("Error: " . $e->getMessage(), 500);
        }
    }
    public function getArticlesByCategoryId(int $id) {
        global $mysqli;
        $articles = Article::findByCategory($mysqli, $id);
        $articles_array = ArticleService::articlesToArray($articles);
        echo ResponseService::response($articles_array, 200);
    }
}
