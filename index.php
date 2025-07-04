<?php
require_once './routes/api.php';

// This block is used to extract the route name from the URL
//----------------------------------------------------------
// Define your base directory
$base_dir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// Remove the base directory from the request if present
if (strpos($request, $base_dir) === 0) {
    $request = substr($request, strlen($base_dir));
}
// Ensure the request is at least '/'
if ($request == '') {
    $request = '/';
}

//----------------------------------------------------------
//Routing starts here (Mapping between the request and the controller & method names)
//It's an key-value array where the value is an key-value array
//----------------------------------------------------------
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

//----------------------------------------------------------
//Routing Logic here
//This is a dynamic logic, that works on any array...
//----------------------------------------------------------
$matched_route = null;
$route_params = [];

if (isset($apis[$request])) {
    $matched_route = $apis[$request];
} else {
    foreach ($apis as $pattern => $route_info) {
        // Convert route pattern to regex
        $regex_pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $pattern);
        $regex_pattern = '#^' . $regex_pattern . '$#';
        
        if (preg_match($regex_pattern, $request, $matches)) {
            $matched_route = $route_info;
            
            // Extract parameter names from the original pattern
            preg_match_all('/\{([^}]+)\}/', $pattern, $param_names);
            
            // Map parameter values to names
            for ($i = 1; $i < count($matches); $i++) {
                $param_name = $param_names[1][$i - 1];
                $route_params[$param_name] = $matches[$i];
            }
            break;
        }
    }
}

if ($matched_route) {
    $controller_name = $matched_route['controller'];
    $method = $matched_route['method'];
    
    require_once "controllers/{$controller_name}.php";
    $controller = new $controller_name();
    
    if (method_exists($controller, $method)) {
        // Pass the extracted parameters to the method
        if (!empty($route_params)) {
            // Pass individual parameters instead of array
            if (count($route_params) == 1) {
                $controller->$method(array_values($route_params)[0]);
            } else {
                $controller->$method($route_params);
            }
        } else {
            $controller->$method();
        }
    } else {
        echo "Error: Method {$method} not found in {$controller_name}.";
    }
} else {
    echo "404 Not Found";
}
