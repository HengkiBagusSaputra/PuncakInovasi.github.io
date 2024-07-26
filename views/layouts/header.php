<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'My CRUD App'; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="/">CRUD App</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"><a class="nav-link" href="crud/dashboard.php">Dashboard</a></li>
                <?php if (!isset($_SESSION['user_id'])): ?>
                <li class="nav-item"><a class="nav-link" href="auth/login.php">Login</a></li>
                <li class="nav-item"><a class="nav-link" href="auth/register.php">Register</a></li>
                <?php else: ?>
                <li class="nav-item"><a class="nav-link" href="crud/index.php">Items</a></li>
                <li class="nav-item"><a class="nav-link" href="auth/logout.php">Logout</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <div class="container mt-4">