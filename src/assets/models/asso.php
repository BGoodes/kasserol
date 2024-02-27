<?php

class Association {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function addAssociation($name, $managerId) {
        $query = "INSERT INTO associations (name, managerId) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$name, $managerId]);
        return $stmt->rowCount() > 0;
    }

    public function updateAssociation($id, $name, $managerId) {
        $query = "UPDATE associations SET name = ?, managerId = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$name, $managerId, $id]);
        return $stmt->rowCount() > 0;
    }

    public function deleteAssociation($id) {
        $query = "DELETE FROM associations WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }

    public function getAssociation($id) {
        $query = "SELECT * FROM associations WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllAssociations() {
        $query = "SELECT * FROM associations";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchAssociations($searchTerm) {
        $query = "SELECT * FROM associations WHERE name LIKE ?";
        $stmt = $this->conn->prepare($query);
        $likeTerm = '%' . $searchTerm . '%';
        $stmt->execute([$likeTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>