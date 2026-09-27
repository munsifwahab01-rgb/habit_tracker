<?php

include "../includes/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($username) || empty($email) || empty($password)) {

        $message = "All fields are required.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Invalid email address.";

    } else {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare(
            "INSERT INTO users (username, email, password)
             VALUES (?, ?, ?)"
        );

        $stmt->bind_param("sss", $username, $email, $hashedPassword);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $message = "Email already exists.";
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">
    <div class="col-md-6 mx-auto">

        <div class="card p-4">

            <h2>Register</h2>

            <p class="text-danger">
                <?= $message ?>
            </p>

            <form method="POST">

                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button class="btn btn-primary">
                    Register
                </button>

            </form>

        </div>

    </div>
</div>

</body>
</html>