<?php

class Material {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function addMaterial($name, $description, $barcode, $number, $associationId) {
        $query = "INSERT INTO materials (name, description, barcode, number, associationId) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$name, $description, $barcode, $number, $associationId]);
        return $stmt->rowCount() > 0;
    }

    public function updateMaterial($id, $name, $description, $barcode, $number, $associationId) {
        $query = "UPDATE materials SET name = ?, description = ?, barcode = ?, number = ?, associationId = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$name, $description, $barcode, $number, $associationId, $id]);
        return $stmt->rowCount() > 0;
    }

    public function deleteMaterial($id) {
        $query = "DELETE FROM materials WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }

    public function getMaterial($id) {
        $query = "SELECT * FROM materials WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllMaterials() {
        $query = "SELECT * FROM materials";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchMaterials($searchTerm) {
        $query = "SELECT * FROM materials WHERE name LIKE ? OR description LIKE ?";
        $stmt = $this->conn->prepare($query);
        $likeTerm = '%' . $searchTerm . '%';
        $stmt->execute([$likeTerm, $likeTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
