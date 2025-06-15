<?php
include 'dbconnection.php';
$ticket_id = intval($_GET['ticket_id']);
$stmt = $conn->prepare("SELECT * FROM comments WHERE ticket_id = ? ORDER BY created_at ASC");
$stmt->bind_param("i", $ticket_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo "<div class='mb-2'>";
    echo "<strong>" . htmlspecialchars($row['username']) . ":</strong><br>";
    echo nl2br(htmlspecialchars($row['comment'])) . "<br>";
    echo "<small class='text-muted'>Posted on " . $row['created_at'] . "</small>";
    echo "</div><hr>";
}
?>
