<?php
require_once("Model.php");

class Category extends Model {
    private int $id;
    private string $name;

    protected static string $table = "categories";
    protected static string $primary_key = "id";

    public function __construct(array $data = []) {
        $this->id = $data['id'] ?? 0;
        $this->name = $data['name'] ?? '';
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

}
