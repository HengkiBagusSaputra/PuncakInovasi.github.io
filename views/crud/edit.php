<?php
include '../../config/database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM items WHERE id = :id");
$stmt->execute(['id' => $id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];

    $stmt = $pdo->prepare("UPDATE items SET name = :name, description = :description WHERE id = :id");
    if ($stmt->execute(['name' => $name, 'description' => $description, 'id' => $id])) {
        header("Location: index.php");
        exit();
    } else {
        $error = "Gagal memperbarui item.";
    }
}
?>

<?php include '../layouts/header.php'; ?>

<h2>Edit Item</h2>
<form method="POST">
    <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" class="form-control" id="name" name="name"
            value="<?php echo htmlspecialchars($item['name']); ?>" required>
    </div>
    <div class="form-group">
        <label for="description">Description:</label>
        <textarea class="form-control" id="description" name="description"
            required><?php echo htmlspecialchars($item['description']); ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Update Item</button>
    <?php if (isset($error)) echo '<div class="alert alert-danger mt-2">' . $error . '</div>'; ?>
</form>

<?php include '../layouts/footer.php'; ?>