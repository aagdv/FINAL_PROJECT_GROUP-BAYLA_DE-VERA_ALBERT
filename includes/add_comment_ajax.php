<?php
session_start();
include 'dbconnection.php';

$username = $_SESSION['username'];
$ticket_id = intval($_POST['ticket_id']);
$comment = trim($_POST['comment']);

// Insert comment
$stmt = $conn->prepare("INSERT INTO comments (ticket_id, username, comment) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $ticket_id, $username, $comment);
$stmt->execute();

// Optional: Set status to In Progress if admin
if ($_SESSION['role'] === 'admin') {
    $conn->query("UPDATE tickets SET status = 'In Progress' WHERE id = $ticket_id AND status != 'Closed'");
}
?>
