<?php
require_once("Model.php");

class Article extends Model{

    private int $id; 
    private string $name; 
    private string $author; 
    private string $description; 
    
    protected static string $table = "articles";
    protected static string $primary_key = "id";

    public function __construct(array $data) {
        $this->id = $data['id'] ?? 0;
        $this->name = $data['name'] ?? '';
        $this->author = $data['author'] ?? '';
        $this->description = $data['description'] ?? '';
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getAuthor(): string {
        return $this->author;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function setName(string $name){
        $this->name = $name;
    }

    public function setAuthor(string $author){
        $this->author = $author;
    }

    public function setDescription(string $description){
        $this->description = $description;
    }

    public function toArray(){
        return [$this->id, $this->name, $this->author, $this->description];
    }
    public static function findByCategory(mysqli $mysqli, int $categoryId) {
        $sql = "
            SELECT a.*
            FROM articles a
            JOIN article_categories ac ON ac.article_id = a.id
            WHERE ac.category_id = ?
        ";

        $query = $mysqli->prepare($sql);
        if (!$query) {
            throw new Exception("Prepare failed: " . $mysqli->error);
        }
        $query->bind_param("i", $categoryId);
        $query->execute();
        $result = $query->get_result();
        $articles = [];
        while ($row = $result->fetch_assoc()) {
            $articles[] = new Article($row);
        }
        $query->close();
        return $articles;
    }

}
