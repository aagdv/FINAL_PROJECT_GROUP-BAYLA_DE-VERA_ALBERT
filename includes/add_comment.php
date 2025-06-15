<?php
session_start();
include 'dbconnection.php';

if (!isset($_SESSION['username'])) {
  header("Location: ../index.php");
  exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];
$ticket_id = intval($_POST['ticket_id']);
$comment = trim($_POST['comment']);

// Prevent empty comment
if (empty($comment)) {
  header("Location: ../view_ticket.php?id=$ticket_id&msg=❌ Empty comment&type=warning");
  exit();
}

// 🔍 Step 1: Check current ticket status
$statusCheck = $conn->query("SELECT status FROM tickets WHERE id = $ticket_id");
$ticket = $statusCheck->fetch_assoc();
$current_status = $ticket['status'];

// 📝 Step 2: Insert comment
$stmt = $conn->prepare("INSERT INTO comments (ticket_id, username, comment) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $ticket_id, $username, $comment);
$stmt->execute();

// 🔄 Step 3: If admin replies AND status is not 'Closed', change to 'In Progress'
if ($role === 'admin' && $current_status !== 'Closed') {
  $conn->query("UPDATE tickets SET status = 'In Progress' WHERE id = $ticket_id");
}

// ✅ Step 4: Redirect back
header("Location: ../view_ticket.php?id=$ticket_id");
exit();
