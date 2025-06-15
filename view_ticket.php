<?php
session_start();
include 'includes/dbconnection.php';

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Get ticket ID
$ticket_id = intval($_GET['id']);

// Fetch ticket info
$stmt = $conn->prepare("SELECT * FROM tickets WHERE id = ?");
$stmt->bind_param("i", $ticket_id);
$stmt->execute();
$ticket = $stmt->get_result()->fetch_assoc();

// Redirect if ticket is closed
if ($ticket['status'] === 'Closed') {
    $redirect = ($role === 'admin') ? 'admin_dashboard.php' : 'client_dashboard.php';
    header("Location: $redirect?msg=❌ This ticket is already closed&type=warning");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Ticket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-4">
        <a href="<?= ($role === 'admin') ? 'admin_dashboard.php' : 'client_dashboard.php' ?>" class="btn btn-outline-secondary mb-3">&larr; Back</a>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h4 class="card-title">Ticket #<?= $ticket['id'] ?> - <?= htmlspecialchars($ticket['issue']) ?></h4>
                <p><strong>Priority:</strong> <?= $ticket['priority'] ?></p>
                <p><strong>Status:</strong>
                    <span class="badge bg-<?= $ticket['status'] === 'Open' ? 'success' : ($ticket['status'] === 'In Progress' ? 'warning text-dark' : 'secondary') ?>">
                        <?= htmlspecialchars($ticket['status']) ?>
                    </span>
                </p>
                <p><strong>Submitted by:</strong> <?= htmlspecialchars($ticket['submitted_by']) ?></p>
                <p><strong>Date:</strong> <?= $ticket['created_at'] ?></p>
            </div>
        </div>

        <!-- Conversation -->
        <div class="card mb-3">
            <div class="card-header">Conversation</div>
            <div class="card-body" id="comments-container" style="max-height: 300px; overflow-y: auto;">
                <!-- Comments will load here via AJAX -->
                <div class="text-muted">Loading comments...</div>
            </div>
        </div>

        <!-- ✅ SINGLE Reply Form -->
        <form id="reply-form" method="POST" class="d-flex gap-2">
            <input type="hidden" name="ticket_id" value="<?= $ticket_id ?>">
            <input type="text" name="comment" class="form-control" placeholder="Type your reply..." required>
            <button type="submit" class="btn btn-primary">Send</button>
        </form>
    </div>

    <script>
        // Load comments
        function fetchComments() {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "includes/fetch_comments.php?ticket_id=<?= $ticket_id ?>", true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    document.getElementById("comments-container").innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }
        setInterval(fetchComments, 3000); // Refresh every 3 seconds
        window.onload = fetchComments;

        // Submit comment via AJAX
        document.getElementById("reply-form").addEventListener("submit", function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch("includes/add_comment_ajax.php", {
                method: "POST",
                body: formData
            }).then(res => res.text()).then(data => {
                document.querySelector('input[name="comment"]').value = "";
                fetchComments(); // Immediately update
            });
        });
    </script>
</body>

</html>