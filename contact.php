<?php

include "includes/db.php";

$messageText = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $message = trim($_POST["message"]);

    if (
        !empty($name) &&
        !empty($message) &&
        filter_var($email, FILTER_VALIDATE_EMAIL)
    ) {

        $stmt = $conn->prepare(
            "INSERT INTO messages (name, email, message)
             VALUES (?, ?, ?)"
        );

        $stmt->bind_param(
            "sss",
            $name,
            $email,
            $message
        );

        $stmt->execute();

        $messageText = "Message sent successfully!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Contact - Smart Habit Tracker</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="css/style.css">

</head>

<body>

<nav class="navbar navbar-dark bg-dark">

    <div class="container">

        <a class="navbar-brand" href="index.php">
            Smart Habit Tracker
        </a>

        <a href="index.php"
           class="btn btn-outline-light">
            Home
        </a>

    </div>

</nav>

<div class="container mt-5">

    <div class="col-md-7 mx-auto">

        <div class="card p-4 fade-in">

            <h2 class="mb-4">
                Contact Us
            </h2>

            <?php if($messageText): ?>

                <div class="alert alert-success">

                    <?= $messageText ?>

                </div>

            <?php endif; ?>

            <form method="POST">

                <div class="mb-3">

                    <label class="form-label">
                        Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        required>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        required>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Message
                    </label>

                    <textarea
                        name="message"
                        class="form-control"
                        rows="5"
                        required></textarea>

                </div>

                <button
                    type="submit"
                    class="btn btn-primary">

                    Send Message

                </button>

            </form>

        </div>

    </div>

</div>

</body>
</html>