<?php
include '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
    if ($stmt->execute(['username' => $username, 'password' => $password])) {
        header("Location: login.php");
        exit();
    } else {
        $error = "Gagal mendaftar. Coba lagi.";
    }
}
?>

<?php include '../layouts/header.php'; ?>

<h2>Register</h2>
<form method="POST">
    <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" class="form-control" id="username" name="username" required>
    </div>
    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary">Register</button>
    <?php if (isset($error)) echo '<div class="alert alert-danger mt-2">' . $error . '</div>'; ?>
</form>

<?php include '../layouts/footer.php'; ?>