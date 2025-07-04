<?php 
require("../connection/connection.php");


$query = "CREATE TABLE article_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT NOT NULL,
    category_id INT NOT NULL,
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    UNIQUE KEY unique_article_category (article_id, category_id))";

$execute = $mysqli->prepare($query);
$execute->execute();
