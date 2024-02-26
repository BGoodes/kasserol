<?php
// FILEPATH: /home/drakk/Documents/innov/kasserol/src/stocks_assos.php

// Include the database configuration file
require_once 'config/config.php';

// Check if the association_id parameter is provided
if (isset($_GET['association_id'])) {
    // Get the association_id from the URL
    $association_id = $_GET['association_id'];

    // Fetch the equipment of the association from the database
    $query = "SELECT * FROM materials WHERE associationId = :association_id";
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
        <h2>Equipment List</h2>
        <!-- Display the equipment list -->
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Equipment ID</th>
                    <th scope="col">Equipment Name</th>
                    <th scope="col">Number</th>
                    <th scope="col">Association ID</th>
                    <!-- Add more table headers as needed -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($equipment as $item) : ?>
                    <tr>
                        <td><?php echo $item['name']; ?></td>
                        <td><?php echo $item['description']; ?></td>
                        <td><?php echo $item['number']; ?></td>
                        <td><?php echo $item['associationId']; ?></td>
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
