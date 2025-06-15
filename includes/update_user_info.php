<?php
session_start();
include 'dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $newRole = $_POST['role'];

    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    $result = $conn->query("SELECT * FROM users WHERE id = $id");
    if (!$result || $result->num_rows === 0) {
        header("Location: ../admin_dashboard.php?tab=users&msg=User not found&type=danger");
        exit();
    }

    $user = $result->fetch_assoc();
    $updatePassword = false;

    // If password fields are filled, validate them
    if (!empty($currentPassword) || !empty($newPassword) || !empty($confirmPassword)) {
        if (!password_verify($currentPassword, $user['password'])) {
            header("Location: ../admin_dashboard.php?tab=users&msg=Incorrect current password&type=warning");
            exit();
        }

        if ($newPassword !== $confirmPassword) {
            header("Location: ../admin_dashboard.php?tab=users&msg=Passwords do not match&type=warning");
            exit();
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updatePassword = true;
    }

    // Prepare update query
    if ($updatePassword) {
        $stmt = $conn->prepare("UPDATE users SET role = ?, password = ? WHERE id = ?");
        $stmt->bind_param("ssi", $newRole, $hashedPassword, $id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->bind_param("si", $newRole, $id);
    }

    if ($stmt->execute()) {
        header("Location: ../admin_dashboard.php?tab=users&msg=User updated successfully&type=success");
    } else {
        header("Location: ../admin_dashboard.php?tab=users&msg=Failed to update user&type=danger");
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../admin_dashboard.php?tab=users");
    exit();
}
