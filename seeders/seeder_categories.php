<?php
require_once '../connection/connection.php';

$categories = [
    ['id' => 1, 'name' => 'Technology'],
    ['id' => 2, 'name' => 'Health'],
    ['id' => 3, 'name' => 'Business'],
    ['id' => 4, 'name' => 'Entertainment'],
    ['id' => 5, 'name' => 'Sports'],
];

foreach ($categories as $category) {
    $query = $mysqli->prepare("INSERT INTO categories (id, name) VALUES (?, ?)");
    $query->bind_param("is", $category['id'], $category['name']);
    $query->close();
}

$mysqli->close();
