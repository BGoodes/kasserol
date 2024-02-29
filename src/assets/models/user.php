<?php

class User {
    private $conn;
    const PASSWORD_HASH_ALGO = PASSWORD_BCRYPT;

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

        if (!$this->validateEmail($email) || !$this->validateInput($firstName, $lastName, $password, $phone)) {
            return false;
        }

        if ($this->emailExists($email)) {
            return false;
        }

        $hash = password_hash($password, self::PASSWORD_HASH_ALGO);

        $query = "INSERT INTO users (firstName, lastName, hash, email, phone) VALUES (:firstName, :lastName, :hash, :email, :phone)";
        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':firstName' => $firstName,
            ':lastName' => $lastName,
            ':hash' => $hash,
            ':email' => $email,
            ':phone' => $phone
        ]);
    }

    public function emailExists($email) {
        if (!$this->validateEmail($email)) {
            return false;
        }

        $query = "SELECT id FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':email' => $email]);

        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    private function validateEmail(string $email): bool {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    private function validateInput(...$inputs): bool {
        foreach ($inputs as $input) {
            $input = $this->filterStringPolyfill($input);
            if (empty($input)) {
                return false;
            }
        }
        return true;
    }

    private function filterStringPolyfill(string $string): string {
        $str = preg_replace('/\x00|<[^>]*>?/', '', $string);
        return str_replace(["'", '"'], ['&#39;', '&#34;'], $str);
    }

    public function loginUser($email, $password) {
        $query = "SELECT id, hash FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':email' => $email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['hash'])) {
            return false;
        }

        return $user['id'];
    }

    public function getUserName($userId) {
        $query = "SELECT firstName FROM users WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user ? $user['firstName'] : false;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getUserData($userId) {
        $query = "SELECT * FROM users WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user ?: false;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updateUserInfo($userId, $updatedFirstName, $updatedLastName, $updatedEmail, $updatedPhone) {
        $query = "UPDATE users SET firstName = ?, lastName = ?, email = ?, phone = ? WHERE id = ?";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $updatedFirstName, PDO::PARAM_STR);
            $stmt->bindParam(2, $updatedLastName, PDO::PARAM_STR);
            $stmt->bindParam(3, $updatedEmail, PDO::PARAM_STR);
            $stmt->bindParam(4, $updatedPhone, PDO::PARAM_STR);
            $stmt->bindParam(5, $userId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function borrowMaterial($userId, $materialId, $quantity) {
        $query = "SELECT m.number, COALESCE(SUM(t.number), 0) taken FROM materials m JOIN transactions t ON m.id = t.materialId WHERE m.id = ?";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $materialId, PDO::PARAM_INT);
            $stmt->execute();
            $material = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($material['number'] - $material['taken'] < $quantity) {
                echo " ERROR : Not enough materials available.";
                return false;
            }
        } catch (PDOException $e) {
            echo " ERROR : Not able to fetch material details.";
            return false;
        }
        $query = "INSERT INTO transactions (userId, materialId, number, date) VALUES (:userid, :materialid, :quantity, NOW()) ON DUPLICATE KEY UPDATE number = number + :quantity";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':userid', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':materialid', $materialId, PDO::PARAM_INT);
            $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo " ERROR : Not able to borrow material. Please try again.";
            echo "\n $e";
            return false;
        }
    }

    public function returnMaterial($userId, $materialId) {
        $query = "DELETE FROM transactions WHERE userId = ? AND materialId = ?";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $userId, PDO::PARAM_INT);
            $stmt->bindParam(2, $materialId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}

?>