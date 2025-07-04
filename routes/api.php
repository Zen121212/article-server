<?php

$apis = [
    '/articles' => [
        'controller' => 'ArticleController',
        'method' => 'getAllArticles'
    ],
    '/article/{id}' => [
        'controller' => 'ArticleController',
        'method' => 'getArticleById'
    ],
    '/article/create' => [
        'controller' => 'ArticleController',
        'method' => 'createArticle'
    ],
    '/article/update/{id}' => [
        'controller' => 'ArticleController',
        'method' => 'updateArticleById'
    ],
    '/article/delete/{id}' => [
        'controller' => 'ArticleController',
        'method' => 'deleteArticleById'
    ],
    '/delete_articles' => [
        'controller' => 'ArticleController',
        'method' => 'deleteAllArticles'
    ],
    '/article/{id}/categories' => [
        'controller' => 'ArticleController',
        'method' => 'getCategoriesByArticleId'
    ],
    '/categories' => [
        'controller' => 'CategoryController',
        'method' => 'getAllCategories'
    ],
    '/category/{id}' => [
        'controller' => 'CategoryController',
        'method' => 'getCategoryById'
    ],
    '/category/create' => [
        'controller' => 'CategoryController',
        'method' => 'createCategory'
    ],
    '/category/update/{id}' => [
        'controller' => 'CategoryController',
        'method' => 'updateCategoryById'
    ],
    '/category/delete/{id}' => [
        'controller' => 'CategoryController',
        'method' => 'deleteCategoryById'
    ],
    '/category/{id}/articles' => [
        'controller' => 'ArticleController',
        'method' => 'getArticlesByCategoryId'
    ],
    '/login' => [
        'controller' => 'AuthController',
        'method' => 'login'
    ],
    '/register' => [
        'controller' => 'AuthController',
        'method' => 'register'
    ],
];
