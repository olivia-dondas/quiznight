
<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'olivia-dondas_quiznight';
    private $username = 'root';
    private $password = 'root';
    public $conn;

    //  private $username = 'oliviadondas';
   // private $password = 'kzCFKQbU3N@t9j7';

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8mb4");
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
