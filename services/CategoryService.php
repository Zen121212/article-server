<?php 

class CategoryService {

    public static function categoriesToArray($categories_db){
        $results = [];

        foreach($categories_db as $a){
             $results[] = $a->toArray(); //hence, we decided to iterate again on the articles array and now to store the result of the toArray() which is an array. 
        } 

        return $results;
    }
    public static function getCategoriesByArticleId(int $articleId): array {
        global $mysqli;

        $categories = [];
        $sql = "SELECT c.* FROM categories c
                INNER JOIN article_categories ac ON c.id = ac.category_id
                WHERE ac.article_id = ?";

        $query = $mysqli->prepare($sql);
        $query->bind_param('i', $articleId);
        $result = $query->get_result();
        while ($row = $result->fetch_assoc()) {
            $categories[] = new Category($row);
        }

        return $categories;
    }
}