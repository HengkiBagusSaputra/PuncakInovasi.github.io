<?php
include '../../config/database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
    $stmt->execute(['username' => $username, 'password' => $password]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: ../crud/index.php");
        exit();
    } else {
        $error = "Username atau password salah.";
    }
}
?>

<?php include '../layouts/header.php'; ?>

<h2>Login</h2>
<form method="POST">
    <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" class="form-control" id="username" name="username" required>
    </div>
    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
    <?php if (isset($error)) echo '<div class="alert alert-danger mt-2">' . $error . '</div>'; ?>
</form>

<?php include '../layouts/footer.php'; ?>