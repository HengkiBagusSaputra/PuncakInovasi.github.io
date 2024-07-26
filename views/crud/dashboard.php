<?php
include '../../config/database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Query untuk menghitung jumlah item
$stmt = $pdo->query("SELECT COUNT(*) as item_count FROM items");
$item_count = $stmt->fetchColumn();

// Query untuk menghitung jumlah pengguna
$stmt = $pdo->query("SELECT COUNT(*) as user_count FROM users");
$user_count = $stmt->fetchColumn();
?>

<?php include '../layouts/header.php'; ?>

<h2>Dashboard</h2>
<div class="row">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Items</h5>
                <p class="card-text"><?php echo htmlspecialchars($item_count); ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Users</h5>
                <p class="card-text"><?php echo htmlspecialchars($user_count); ?></p>
            </div>
        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>