<?php
session_start();
include 'includes/dbconnection.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'client') {
    header("Location: index.php");
    exit();
}

$ticket_id = intval($_GET['id']);
$username = $_SESSION['username'];

// Fetch ticket info
$result = $conn->prepare("SELECT * FROM tickets WHERE id = ? AND submitted_by = ?");
$result->bind_param("is", $ticket_id, $username);
$result->execute();
$ticket = $result->get_result()->fetch_assoc();

if (!$ticket) {
    echo "<div class='text-danger p-3'>Ticket not found or access denied.</div>";
    exit();
}

// Handle update submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $issue = $_POST['issue'];
    $priority = $_POST['priority'];

    $stmt = $conn->prepare("UPDATE tickets SET issue = ?, priority = ? WHERE id = ? AND submitted_by = ?");
    $stmt->bind_param("ssis", $issue, $priority, $ticket_id, $username);
    $stmt->execute();

    header("Location: client_dashboard.php?msg=Ticket updated&type=success");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Ticket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-4">
        <h4>Edit Ticket #<?= $ticket['id'] ?></h4>
        <form method="POST">
            <div class="mb-3">
                <label>Issue</label>
                <textarea name="issue" class="form-control" required><?= htmlspecialchars($ticket['issue']) ?></textarea>
            </div>
            <div class="mb-3">
                <label>Priority</label>
                <select name="priority" class="form-select" required>
                    <option value="Low" <?= $ticket['priority'] === 'Low' ? 'selected' : '' ?>>Low</option>
                    <option value="Medium" <?= $ticket['priority'] === 'Medium' ? 'selected' : '' ?>>Medium</option>
                    <option value="High" <?= $ticket['priority'] === 'High' ? 'selected' : '' ?>>High</option>
                </select>
            </div>
            <button class="btn btn-primary">Update Ticket</button>
            <a href="client_dashboard.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>