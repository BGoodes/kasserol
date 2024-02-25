<?php

class User {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function registerUser($firstName, $lastName, $email, $password, $phone) {
        // Sanitize input data
        $firstName = $this->filterStringPolyfill($firstName);
        $lastName = $this->filterStringPolyfill($lastName);
        $email = $this->filterEmailPolyfill($email);
        $phone = $this->filterStringPolyfill($phone);
    
        // Additional validation if needed (e.g., check if email is valid)
        if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($phone)) {
            return false;
        }
    
        // Hash the password before storing it in the database
        $hash = password_hash($password, PASSWORD_BCRYPT);
    
        // SQL query to insert user data
        $query = "INSERT INTO users (firstName, lastName, hash, email, phone) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
    
        // Bind parameters
        $stmt->bindParam(1, $firstName);
        $stmt->bindParam(2, $lastName);
        $stmt->bindParam(3, $hash);
        $stmt->bindParam(4, $email);
        $stmt->bindParam(5, $phone);
    
        // Execute the query
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    


    public function loginUser($email, $password) {
        $query = "SELECT id, hash FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['hash'])) {
            return false; // Invalid email or password
        }

        return $user['id']; // Return user ID on successful login
    }

    public function emailExists($email) {
        $query = "SELECT id FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    private function filterStringPolyfill(string $string): string
    {
        $str = preg_replace('/\x00|<[^>]*>?/', '', $string);
        return str_replace(["'", '"'], ['&#39;', '&#34;'], $str);
    }

    private function filterEmailPolyfill(string $email): string
    {
        return filter_var($email, FILTER_SANITIZE_EMAIL);
    }
}

?>
