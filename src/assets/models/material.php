<?php

class Material {

    public function registerMaterial($name, $description, $barcode, $number, $associationId) {
        // Sanitize input data
        $name = $this->filterStringPolyfill($name);
        $description = $this->filterStringPolyfill($description);
    
        if (empty($name) || empty($description) || empty($barcode) || empty($associationId)) {
            return false;
        }

        if (empty($number)) {
            $number = 1;
        }
        
        // SQL query to insert user data
        $query = "INSERT INTO users (firstName, lastName, hash, email, phone) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
    
        // Bind parameters
        $stmt->bindParam(1, $name);
        $stmt->bindParam(2, $description);
        $stmt->bindParam(3, $barcode);
        $stmt->bindParam(4, $number);
        $stmt->bindParam(5, $associationId);
    
        // Execute the query
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }


    public function filterStringPolyfill(string $string): string
    {
        $str = preg_replace('/\x00|<[^>]*>?/', '', $string);
        return str_replace(["'", '"'], ['&#39;', '&#34;'], $str);
    }
}

?>