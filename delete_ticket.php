<?php
session_start();
include 'includes/dbconnection.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'client') {
    header("Location: index.php");
    exit();
}

$ticket_id = intval($_GET['id']);
$username = $_SESSION['username'];

// Only allow user to delete their own ticket
$stmt = $conn->prepare("DELETE FROM tickets WHERE id = ? AND submitted_by = ?");
$stmt->bind_param("is", $ticket_id, $username);
$stmt->execute();

header("Location: client_dashboard.php?msg=Ticket deleted&type=success");
exit();
?>
