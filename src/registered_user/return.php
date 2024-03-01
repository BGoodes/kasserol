<?php
ob_start();
// FILEPATH: /home/drakk/Documents/innov/kasserol/src/registered_user/return.php

// Include the database configuration file
require_once '../config/config.php';

$quantity = 0;
$transaction = $number = $name = null;
$userId = $_SESSION['user_id'];

// Check if the material_id parameter is provided
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Create an instance of the User class
    $user = new User($conn);
    // Get the material_id from the URL
    $materialId = isset($_POST['material_id']) ? $_POST['material_id'] : 0;

    if (isset($_POST['return-all'])) {
        if ($user->returnMaterial($userId, $materialId, null)) {
            header("Location: profile.php");
        }
    } else {
        $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 0;

        if (empty($quantity)) {
            $quantityErr = "Quantity is required";
        }

        if (empty($quantityErr) && $quantity > 0) {
            echo "returnMaterial($userId, $materialId, $quantity)";
            if ($user->returnMaterial($userId, $materialId, $quantity)) {
                header("Location: profile.php");
            }
        } else {
            echo "Error returning material. Please try again";
        }
    }
} else {
    $materialId = $_GET['material_id'];

    // Retrieve the material based on the transaction
    $query = "SELECT t.number, m.name, m.description, m.barcode FROM transactions t JOIN materials m ON m.id = t.materialId WHERE t.materialId = ? AND t.userId = ?";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(1, $materialId, PDO::PARAM_INT);
    $stmt->bindParam(2, $userId, PDO::PARAM_INT);
    $stmt->execute();
    $transaction = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($transaction) {
        $number = $transaction['number'];
        $name = $transaction['name'];
    }
}

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">

    <title>Return request</title>
</head>
<body>
    <?php include '../components/header.php'; ?>

    <div class="container">
        <div class="row mt-5">
            <div class="col-md-6 offset-md-3">
                <h2>Return</h2>
                <?php if (is_null($number)) : ?>
                    <p>No items to return</p>
                <?php else : ?>
                    <form method="POST" action="return.php">
                        <input type="hidden" name="material_id" value="<?php echo $materialId; ?>">
                        <div class="form-group">
                            <label for="quantity">Quantity:</label>
                            <input type="number" class="form-control" name="quantity" autofocus="autofocus" min="0">
                            <span class="text-danger"><?php echo isset($quantityErr) ? $quantityErr : ''; ?></span>
                        </div>
                        <button type="submit" name="return" class="btn btn-primary">Return</button>
                        <button type="submit" name="return-all" class="btn btn-danger">Return all <?php echo "($number $name(s))"; ?></button>
                    </form>
                <?php endif ?>
            </div>
        </div>
    </div>


    <?php include '../components/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
