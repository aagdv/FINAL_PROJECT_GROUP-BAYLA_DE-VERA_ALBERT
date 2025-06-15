<?php
session_start();

// Check if user is logged in as client
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'client') {
  header("Location: index.php");
  exit();
}

include 'includes/dbconnection.php';

// Get session values
$username = $_SESSION['username'];
$email = $_SESSION['email'] ?? ''; // Ensure this is set during login
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Client Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">
  <div class="container py-4">
    <!-- Top Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4>Welcome, <?= htmlspecialchars($username) ?></h4>
      <a href="includes/logout.php" class="btn btn-outline-danger btn-sm">
        <i class="bi bi-box-arrow-right"></i> Logout
      </a>
    </div>

    <!-- Toast Message -->
    <?php if (isset($_GET['msg'])): ?>
      <div class="alert alert-<?= htmlspecialchars($_GET['type'] ?? 'info') ?> d-flex align-items-center gap-2">
        <i class="bi bi-info-circle-fill"></i>
        <div><?= htmlspecialchars($_GET['msg']) ?></div>
      </div>
    <?php endif; ?>

    <!-- Submit Ticket -->
    <div class="card mb-4">
      <div class="card-header"><i class="bi bi-pencil-square"></i> Submit a Ticket</div>
      <div class="card-body">
        <form action="includes/submit_ticket.php" method="POST">
          <!-- Hidden Fields -->
          <input type="hidden" name="submitted_by" value="<?= htmlspecialchars($username) ?>">
          <input type="hidden" name="name" value="<?= htmlspecialchars($username) ?>">
          <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">

          <div class="mb-3">
            <textarea name="issue" class="form-control" placeholder="Describe your issue..." required></textarea>
          </div>
          <div class="mb-3">
            <select name="priority" class="form-select" required>
              <option value="" disabled selected>Select Priority</option>
              <option value="Low">Low</option>
              <option value="Medium">Medium</option>
              <option value="High">High</option>
            </select>
          </div>
          <button class="btn btn-primary w-100"><i class="bi bi-send-fill"></i> Submit</button>
        </form>
      </div>
    </div>

    <!-- Ticket List -->
    <div class="card shadow-sm">
      <div class="card-header"><i class="bi bi-ticket-perforated-fill"></i> Your Tickets</div>
      <div class="card-body">
        <?php
        $stmt = $conn->prepare("SELECT * FROM tickets WHERE submitted_by = ? ORDER BY created_at DESC");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        ?>

        <?php if ($result->num_rows > 0): ?>
          <div class="table-responsive">
            <table class="table table-bordered align-middle">
              <thead class="table-light">
                <tr>
                  <th>Issue</th>
                  <th>Priority</th>
                  <th>Status</th>
                  <th>Date</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                  <tr>
                    <td><?= htmlspecialchars($row['issue']) ?></td>
                    <td>
                      <span class="badge bg-<?=
                                            $row['priority'] === 'High' ? 'danger' : ($row['priority'] === 'Medium' ? 'warning text-dark' : 'success') ?>">
                        <?= htmlspecialchars($row['priority']) ?>
                      </span>
                    </td>
                    <td>
                      <span class="badge bg-<?=
                                            $row['status'] === 'Closed' ? 'secondary' : ($row['status'] === 'In Progress' ? 'warning text-dark' : 'success') ?>">
                        <?= htmlspecialchars($row['status']) ?>
                      </span>
                    </td>
                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                    <td class="text-nowrap">
                      <a href="view_ticket.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary" title="View Ticket">
                        <i class="bi bi-eye"></i>
                      </a>

                      <?php if ($row['status'] !== 'Closed'): ?>
                        <a href="edit_ticket.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning" title="Edit Ticket">
                          <i class="bi bi-pencil-square"></i>
                        </a>
                      <?php else: ?>
                        <button class="btn btn-sm btn-outline-secondary" disabled title="Closed">
                          <i class="bi bi-pencil-square"></i>
                        </button>
                      <?php endif; ?>

                      <a href="delete_ticket.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" title="Delete Ticket" onclick="return confirm('Are you sure?')">
                        <i class="bi bi-trash"></i>
                      </a>
                    </td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <div class="alert alert-info text-center">
            <i class="bi bi-info-circle"></i> You haven't submitted any tickets yet.
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>