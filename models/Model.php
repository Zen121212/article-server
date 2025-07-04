<?php 
abstract class Model{

    protected static string $table;
    protected static string $primary_key = "id";


    protected static function detectTypes(array $values): string {
        $types = '';
        foreach ($values as $value) {
            if (is_int($value)) {
                $types .= 'i';
            } elseif (is_float($value)) {
                $types .= 'd';
            } elseif (is_string($value)) {
                $types .= 's';
            } else {
                $types .= 's'; 
            }
        }
        return $types;
    }

    public static function find(mysqli $mysqli, int $id){
        echo"no";
        $sql = sprintf("Select * from %s WHERE %s = ?", 
                        static::$table, 
                        static::$primary_key);
        
        $query = $mysqli->prepare($sql);
        $query->bind_param("i", $id);
        $query->execute();

        $data = $query->get_result()->fetch_assoc();
        echo"yes";
        return $data ? new static($data) : null;
    }

    public static function all(mysqli $mysqli){
        $sql = sprintf("Select * from %s", static::$table);
        
        $query = $mysqli->prepare($sql);
        $query->execute();

        $data = $query->get_result();

        $objects = [];
        while($row = $data->fetch_assoc()){
            $objects[] = new static($row); //creating an object of type "static" / "parent" and adding the object to the array
        }

        return $objects; //we are returning an array of objects!!!!!!!!
    }

    public static function create(mysqli $mysqli,array $data) {
        $columns = array_keys($data);
        $placeholders = implode(', ', array_fill(0, count($columns), '?'));
        $columnList = implode(', ', $columns);
        $values = array_values($data);
        $types = static::detectTypes($values);
        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            static::$table,
            $columnList,
            $placeholders
        );
        $query = $mysqli->prepare($sql);
        $query->bind_param($types, ...$values);
        $query->execute();

        $data['id'] = $mysqli->insert_id;
        return new static($data);
    }
    public static function deleteAll(mysqli $mysqli){
        $sql = sprintf("DELETE FROM %s", static::$table);
        $query = $mysqli->prepare($sql);
        $query->execute();
        $success = $query->execute();
        return $success;
    }
    public static function deleteById(mysqli $mysqli, $id) {
        $sql = sprintf("DELETE FROM %s WHERE id = ?", static::$table);
        $query = $mysqli->prepare($sql);
        $query->bind_param("i", $id);
        $success = $query->execute();

        return $success;
    }

public function update(mysqli $mysqli, array $data): bool {
    $columns = array_keys($data);
    $placeholders = implode(' = ?, ', $columns) . ' = ?';
    $values = array_values($data);
    $sql = sprintf(
        "UPDATE %s SET %s WHERE %s = ?",
        static::$table,
        $placeholders,
        static::$primary_key
    );
    $query = $mysqli->prepare($sql);
    $values[] = $this->getId();
    $types = static::detectTypes($values);
    $query->bind_param($types, ...$values);

    return $query->execute();
}



    //you have to continue with the same mindset
    //Find a solution for sending the $mysqli everytime... 
    //Implement the following: 
    //1- update() -> non-static function 
    //2- create() -> static function
    //3- delete() -> static function 
}



