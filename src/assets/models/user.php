<?php

class User {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function startSession($userId) {
        $_SESSION['user_id'] = $userId;
    }

    public function endSession() {
        session_unset();
        session_destroy();
    }

    public function isUserLoggedIn() {
        return isset($_SESSION['user_id']);
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

    public function filterStringPolyfill(string $string): string
    {
        $str = preg_replace('/\x00|<[^>]*>?/', '', $string);
        return str_replace(["'", '"'], ['&#39;', '&#34;'], $str);
    }

    public function filterEmailPolyfill(string $email): string
    {
        return filter_var($email, FILTER_SANITIZE_EMAIL);
    }

    
    public function getUserName($userId) {
        $query = "SELECT firstName FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return false; // Unable to fetch user name
        }

        return $user['firstName']; // Return user's first name
    }

    public function getUserData($userId) {
        $query = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
    
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$user) {
            return false; // Unable to fetch user data
        }
    
        return $user; // Return user data
    }

    public function updateUserInfo($userId, $updatedFirstName, $updatedLastName, $updatedEmail, $updatedPhone) {
        // SQL query to update user information
        $query = "UPDATE users SET firstName = ?, lastName = ?, email = ?, phone = ? WHERE id = ?";
        
        // Prepare the query
        $stmt = $this->conn->prepare($query);
    
        // Bind parameters
        $stmt->bindParam(1, $updatedFirstName);
        $stmt->bindParam(2, $updatedLastName);
        $stmt->bindParam(3, $updatedEmail);
        $stmt->bindParam(4, $updatedPhone);
        $stmt->bindParam(5, $userId);
    
        // Execute the query
        return $stmt->execute();
    }
}

?>