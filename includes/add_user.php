<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'dbconnection.php';

    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Check if username already exists
    $check = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        header("Location: ../admin_dashboard.php?tab=users&msg=Username already exists&type=danger");
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);

    if ($stmt->execute()) {
        header("Location: ../admin_dashboard.php?tab=users&msg=User added successfully&type=success");
    } else {
        header("Location: ../admin_dashboard.php?tab=users&msg=Failed to add user&type=danger");
    }
}
?>
