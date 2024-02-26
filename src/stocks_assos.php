<?php
// FILEPATH: /home/drakk/Documents/innov/kasserol/src/stocks_assos.php

// Include the database configuration file
require_once 'config/config.php';

// Check if the association_id parameter is provided
if (isset($_GET['association_id'])) {
    // Get the association_id from the URL
    $association_id = $_GET['association_id'];

    // Fetch the association from the database
    $query = "SELECT * FROM associations WHERE id = :association_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':association_id', $association_id);
    $stmt->execute();
    $association = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch the equipment of the association from the database + number taken per equipment
    $query = "SELECT m.*, SUM(t.number) taken FROM transactions t JOIN materials m ON materialId = m.id WHERE associationId = :association_id GROUP BY materialId;";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':association_id', $association_id);
    $stmt->execute();
    $equipment = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // If the association_id parameter is not provided, display an error message
    echo "Association ID is missing.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">

    <title>Equipment List</title>
</head>
<body>
    <?php include 'components/header.php'; ?>

    <div class="container mt-5">
        <h2>Equipment List for <?php echo $association['name']; ?></h2>
        <!-- Display the equipment list -->
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Number available / Total number</th>
                    <th scope="col" class="text-center">Actions</th>
                    <!-- Add more table headers as needed -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($equipment as $item) : ?>
                    <tr>
                        <td><?php echo $item['name']; ?></td>
                        <td><?php echo $item['description']; ?></td>
                        <td><?php echo $item['number'] - $item['taken']; ?> / <?php echo $item['number']; ?></td>
                        <td class="text-center">
                            <?php if ($item['number'] - $item['taken'] > 0) : ?>
                                <a href="borrow.php?material_id=<?php echo $item['id']; ?>"><button class="btn btn-primary">Borrow</button></a>
                            <?php endif; ?>
                            <?php if ($item['taken'] > 0) : ?>
                                <a href="return.php?material_id=<?php echo $item['id']; ?>"><button class="btn btn-secondary">Return</button></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php include 'components/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
