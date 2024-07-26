<?php
session_start();
include '../../config/database.php'; // Koneksi database

// Cek jika pengguna sudah login
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Redirect jika pengguna belum login
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: index.php?section=login");
        exit();
    }
}

// Logout pengguna
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: index.php?section=login");
    exit();
}

// Login dan Registrasi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_GET['section'] == 'login') {
        $username = $_POST['username'];
        $password = md5($_POST['password']);

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
        $stmt->execute(['username' => $username, 'password' => $password]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: index.php?section=dashboard");
            exit();
        } else {
            $error = "Username atau password salah.";
        }
    } elseif ($_GET['section'] == 'register') {
        $username = $_POST['username'];
        $password = md5($_POST['password']);

        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        if ($stmt->execute(['username' => $username, 'password' => $password])) {
            header("Location: index.php?section=login");
            exit();
        } else {
            $error = "Gagal mendaftar.";
        }
    } elseif ($_GET['section'] == 'create') {
        requireLogin();

        $name = $_POST['name'];
        $description = $_POST['description'];

        $stmt = $pdo->prepare("INSERT INTO items (name, description) VALUES (:name, :description)");
        if ($stmt->execute(['name' => $name, 'description' => $description])) {
            header("Location: index.php?section=dashboard");
            exit();
        } else {
            $error = "Gagal menambah item.";
        }
    } elseif ($_GET['section'] == 'edit') {
        requireLogin();

        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];

        $stmt = $pdo->prepare("UPDATE items SET name = :name, description = :description WHERE id = :id");
        if ($stmt->execute(['name' => $name, 'description' => $description, 'id' => $id])) {
            header("Location: index.php?section=dashboard");
            exit();
        } else {
            $error = "Gagal memperbarui item.";
        }
    } elseif ($_GET['section'] == 'delete') {
        requireLogin();

        $id = $_GET['id'];
        $stmt = $pdo->prepare("DELETE FROM items WHERE id = :id");
        if ($stmt->execute(['id' => $id])) {
            header("Location: index.php?section=dashboard");
            exit();
        } else {
            $error = "Gagal menghapus item.";
        }
    }
}

// Menampilkan halaman
$section = isset($_GET['section']) ? $_GET['section'] : 'login';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple CRUD Application</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    body {
        background-color: #f8f9fa;
        font-family: Arial, sans-serif;
    }

    .container {
        margin-top: 30px;
        max-width: 800px;
    }

    .header,
    .footer {
        padding: 10px 0;
        text-align: center;
        background-color: #343a40;
        color: white;
    }

    .form-control,
    .btn {
        border-radius: 0.25rem;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004085;
    }

    .alert {
        border-radius: 0.25rem;
    }

    table {
        background-color: white;
        border-radius: 0.25rem;
    }

    thead th {
        background-color: #007bff;
        color: white;
    }

    tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tbody tr:hover {
        background-color: #e9ecef;
    }

    .btn-link {
        color: #007bff;
    }

    .btn-link:hover {
        color: #0056b3;
    }
    </style>
</head>

<body>
    <div class="container">
        <?php if ($section == 'login') { ?>
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
            <a href="index.php?section=register" class="btn btn-link">Register</a>
            <?php if (isset($error)) echo '<div class="alert alert-danger mt-2">' . htmlspecialchars($error) . '</div>'; ?>
        </form>
        <?php } elseif ($section == 'register') { ?>
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
            <a href="index.php?section=login" class="btn btn-link">Login</a>
            <?php if (isset($error)) echo '<div class="alert alert-danger mt-2">' . htmlspecialchars($error) . '</div>'; ?>
        </form>
        <?php } elseif ($section == 'dashboard') { ?>
        <?php requireLogin(); ?>
        <h2>Dashboard</h2>
        <a href="index.php?section=create" class="btn btn-primary">Add Item</a>
        <a href="index.php?action=logout" class="btn btn-danger">Logout</a>
        <?php
            // Tampilkan daftar item
            $stmt = $pdo->query("SELECT * FROM items");
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['id']); ?></td>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo htmlspecialchars($item['description']); ?></td>
                    <td>
                        <a href="index.php?section=edit&id=<?php echo htmlspecialchars($item['id']); ?>"
                            class="btn btn-warning btn-sm">Edit</a>
                        <a href="index.php?section=delete&id=<?php echo htmlspecialchars($item['id']); ?>"
                            class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php } elseif ($section == 'create') { ?>
        <?php requireLogin(); ?>
        <h2>Add Item</h2>
        <form method="POST">
            <input type="hidden" name="section" value="create">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Item</button>
            <?php if (isset($error)) echo '<div class="alert alert-danger mt-2">' . htmlspecialchars($error) . '</div>'; ?>
        </form>
        <?php } elseif ($section == 'edit') { ?>
        <?php requireLogin(); ?>
        <?php
            $id = $_GET['id'];
            $stmt = $pdo->prepare("SELECT * FROM items WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $item = $stmt->fetch(PDO::FETCH_ASSOC);
            ?>
        <h2>Edit Item</h2>
        <form method="POST">
            <input type="hidden" name="section" value="edit">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($item['id']); ?>">
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
            <?php if (isset($error)) echo '<div class="alert alert-danger mt-2">' . htmlspecialchars($error) . '</div>'; ?>
        </form>
        <?php } ?>
    </div>
</body>

</html>