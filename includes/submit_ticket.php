<?php
session_start();
include 'dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $issue = trim($_POST['issue']);
  $priority = $_POST['priority'];
  $submitted_by = $_POST['submitted_by'];

  // Optional: email (do not require in validation)
  $email = trim($_POST['email'] ?? '');

  // Validation: only check essential fields
  if (!empty($name) && !empty($issue) && !empty($priority) && !empty($submitted_by)) {
    // Insert with or without email depending on your DB schema
    $stmt = $conn->prepare("INSERT INTO tickets (name, issue, priority, submitted_by, email) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $issue, $priority, $submitted_by, $email);

    if ($stmt->execute()) {
      header("Location: ../client_dashboard.php?msg=Ticket submitted&type=success");
      exit();
    } else {
      header("Location: ../client_dashboard.php?msg=Failed to submit ticket&type=danger");
      exit();
    }
  } else {
    header("Location: ../client_dashboard.php?msg=Please fill all fields&type=warning");
    exit();
  }
}
