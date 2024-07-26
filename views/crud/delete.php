<?php
include '../../config/database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM items WHERE id = :id");
if ($stmt->execute(['id' => $id])) {
    header("Location: index.php");
    exit();
} else {
    die("Gagal menghapus item.");
}
?>