<?php

session_start();

include "../includes/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare(
        "SELECT * FROM users WHERE email = ?"
    );

    $stmt->bind_param("s", $email);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];

            header("Location: ../dashboard.php");
            exit();
        }
    }

    $message = "Invalid email or password.";
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">

    <div class="col-md-6 mx-auto">

        <div class="card p-4">

            <h2>Login</h2>

            <p class="text-danger">
                <?= $message ?>
            </p>

            <form method="POST">

                <div class="mb-3">
                    <label>Email</label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        required>
                </div>

                <div class="mb-3">
                    <label>Password</label>

                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        required>
                </div>

                <button class="btn btn-success">
                    Login
                </button>

            </form>

        </div>

    </div>

</div>

</body>
</html>