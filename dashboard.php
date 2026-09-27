<?php

session_start();

include "includes/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: auth/login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// ADD HABIT
if (isset($_POST["add_habit"])) {

    $habit = trim($_POST["habit_name"]);

    if (!empty($habit)) {

        $stmt = $conn->prepare(
            "INSERT INTO habits (user_id, habit_name)
             VALUES (?, ?)"
        );

        $stmt->bind_param("is", $user_id, $habit);
        $stmt->execute();

        header("Location: dashboard.php");
        exit();
    }
}

// COMPLETE HABIT
if (isset($_GET["complete"])) {

    $habit_id = intval($_GET["complete"]);

    $stmt = $conn->prepare(
        "UPDATE habits
         SET status='Completed'
         WHERE id=? AND user_id=?"
    );

    $stmt->bind_param("ii", $habit_id, $user_id);
    $stmt->execute();

    header("Location: dashboard.php");
    exit();
}

// DELETE HABIT
if (isset($_GET["delete"])) {

    $habit_id = intval($_GET["delete"]);

    $stmt = $conn->prepare(
        "DELETE FROM habits
         WHERE id=? AND user_id=?"
    );

    $stmt->bind_param("ii", $habit_id, $user_id);
    $stmt->execute();

    header("Location: dashboard.php");
    exit();
}

// GET HABITS
$stmt = $conn->prepare(
    "SELECT * FROM habits
     WHERE user_id=?
     ORDER BY id DESC"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Smart Habit Tracker</title>

    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- Custom CSS -->
    <link
        rel="stylesheet"
        href="css/style.css">

</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

    <div class="container">

        <a class="navbar-brand" href="dashboard.php">
            Smart Habit Tracker
        </a>

        <div>

            <a href="index.php"
               class="btn btn-outline-light me-2">
                Home
            </a>

            <a href="contact.php"
               class="btn btn-outline-light me-2">
                Contact
            </a>

            <a href="auth/logout.php"
               class="btn btn-danger">
                Logout
            </a>

        </div>

    </div>

</nav>


<div class="container mt-5">

    <h2>
        Welcome,
        <?= htmlspecialchars($_SESSION["username"]) ?> 👋
    </h2>

    <p class="text-muted">
        Manage your daily habits below.
    </p>


    <!-- ADD HABIT -->
    <div class="card p-4 mt-4 fade-in">

        <h4 class="mb-3">
            Add New Habit
        </h4>

        <form method="POST">

            <div class="mb-3">

                <label class="form-label">
                    Habit Name
                </label>

                <input
                    type="text"
                    name="habit_name"
                    class="form-control"
                    placeholder="Example: Drink 2L Water"
                    required>

            </div>

            <button
                type="submit"
                name="add_habit"
                class="btn btn-primary">

                Add Habit

            </button>

        </form>

    </div>


    <hr class="my-5">


    <!-- FILTER BUTTONS -->
    <div class="mb-4">

        <button
            class="btn btn-secondary"
            onclick="filterHabits('all')">
            All
        </button>

        <button
            class="btn btn-success"
            onclick="filterHabits('Completed')">
            Completed
        </button>

        <button
            class="btn btn-warning"
            onclick="filterHabits('Pending')">
            Pending
        </button>

    </div>


    <h3 class="mb-3">
        Your Habits
    </h3>


    <div id="habitList">

        <?php if ($result->num_rows > 0): ?>

            <?php while($row = $result->fetch_assoc()): ?>

                <div
                    class="card p-3 mb-3 fade-in habit-card"
                    data-status="<?= htmlspecialchars($row["status"]) ?>">

                    <div class="row align-items-center">

                        <div class="col-md-6">

                            <h5>
                                <?= htmlspecialchars($row["habit_name"]) ?>
                            </h5>

                            <?php if($row["status"] == "Completed"): ?>

                                <span class="badge bg-success">
                                    Completed
                                </span>

                            <?php else: ?>

                                <span class="badge bg-warning text-dark">
                                    Pending
                                </span>

                            <?php endif; ?>

                        </div>


                        <div class="col-md-6 text-md-end mt-3 mt-md-0">

                            <?php if($row["status"] != "Completed"): ?>

                                <a
                                    href="?complete=<?= $row['id'] ?>"
                                    class="btn btn-success">

                                    Complete

                                </a>

                            <?php endif; ?>


                            <a
                                href="?delete=<?= $row['id'] ?>"
                                class="btn btn-danger"
                                onclick="return confirm('Are you sure you want to delete this habit?')">

                                Delete

                            </a>

                        </div>

                    </div>

                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <div class="alert alert-info">
                No habits added yet.
            </div>

        <?php endif; ?>

    </div>

</div>


<!-- Bootstrap JavaScript -->
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

<!-- Custom JavaScript -->
<script src="js/script.js"></script>

</body>

</html>