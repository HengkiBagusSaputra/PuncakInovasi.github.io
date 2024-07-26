<?php
include '../../config/database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];

    $stmt = $pdo->prepare("INSERT INTO items (name, description) VALUES (:name, :description)");
    if ($stmt->execute(['name' => $name, 'description' => $description])) {
        header("Location: index.php");
        exit();
    } else {
        $error = "Gagal menambahkan item.";
    }
}
?>

<?php include '../layouts/header.php'; ?>

<h2>Add New Item</h2>
<form method="POST">
    <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="form-group">
        <label for="description">Description:</label>
        <textarea class="form-control" id="description" name="description" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Add Item</button>
    <?php if (isset($error)) echo '<div class="alert alert-danger mt-2">' . $error . '</div>'; ?>
</form>

<?php include '../layouts/footer.php'; ?>