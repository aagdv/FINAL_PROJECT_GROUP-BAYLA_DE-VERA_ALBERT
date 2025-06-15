<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php"); // ✅ Fixed path for login
    exit();
}
include 'includes/dbconnection.php';

// Handle ticket deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM tickets WHERE id = $id");
    header("Location: admin_dashboard.php?tab=tickets&msg=Ticket deleted successfully&type=success");
    exit();
}

// Handle status toggle (Open/Closed)
if (isset($_GET['toggle'])) {
    $id = intval($_GET['toggle']);
    $result = $conn->query("SELECT status FROM tickets WHERE id = $id");
    $row = $result->fetch_assoc();
    $newStatus = ($row['status'] === 'Open') ? 'Closed' : 'Open';
    $conn->query("UPDATE tickets SET status = '$newStatus' WHERE id = $id");
    header("Location: admin_dashboard.php?tab=tickets&msg=Status updated&type=info");
    exit();
}

$activeTab = $_GET['tab'] ?? 'tickets';
?>
<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<body class="bg-light">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Admin Dashboard</h3>
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle d-flex align-items-center gap-2 border" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle fs-5"></i>
                    <span class="fw-semibold"><?= htmlspecialchars($_SESSION['username']) ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#changePassModal">Change Password</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item text-danger" href="includes/logout.php">Logout</a></li>
                </ul>
            </div>
        </div>

        <ul class="nav nav-tabs">
            <li class="nav-item"><a class="nav-link <?= $activeTab === 'tickets' ? 'active' : '' ?>" href="?tab=tickets">Tickets</a></li>
            <li class="nav-item"><a class="nav-link <?= $activeTab === 'users' ? 'active' : '' ?>" href="?tab=users">User Management</a></li>
        </ul>

        <div class="tab-content mt-3">
            <?php if ($activeTab === 'tickets'): ?>
                <div class="tab-pane fade show active">
                    <div class="card">
                        <div class="card-body">
                            <h5>All Submitted Tickets</h5>
                            <?php $result = $conn->query("SELECT * FROM tickets ORDER BY created_at DESC"); ?>
                            <?php if ($result->num_rows > 0): ?>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Issue</th>
                                            <th>Priority</th>
                                            <th>Status</th>
                                            <th>Submitted By</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?= $row['id'] ?></td>
                                                <td><?= htmlspecialchars($row['issue']) ?></td>

                                                <!-- Priority Badge -->
                                                <td>
                                                    <?php
                                                    $priority = $row['priority'];
                                                    if ($priority === 'High') {
                                                        echo '<span class="badge bg-danger">High</span>';
                                                    } elseif ($priority === 'Medium') {
                                                        echo '<span class="badge bg-warning text-dark">Medium</span>';
                                                    } else {
                                                        echo '<span class="badge bg-success">Low</span>';
                                                    }
                                                    ?>
                                                </td>

                                                <!-- Status Badge -->
                                                <td>
                                                    <?php
                                                    $status = $row['status'];
                                                    if ($status === 'Open') {
                                                        echo '<span class="badge bg-success">Open</span>';
                                                    } elseif ($status === 'In Progress') {
                                                        echo '<span class="badge bg-warning text-dark">In Progress</span>';
                                                    } elseif ($status === 'Closed') {
                                                        echo '<span class="badge bg-secondary">Closed</span>';
                                                    } else {
                                                        echo htmlspecialchars($status);
                                                    }
                                                    ?>
                                                </td>


                                                <td><?= htmlspecialchars($row['submitted_by']) ?></td>
                                                <td><?= $row['created_at'] ?></td>

                                                <!-- Action Buttons with Icons + Tooltips -->
                                                <td>
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <a href="?toggle=<?= $row['id'] ?>" class="btn btn-sm btn-warning" title="Toggle Status">
                                                            <i class="bi bi-arrow-repeat"></i>
                                                        </a>

                                                        <a href="view_ticket.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary" title="View Ticket">
                                                            <i class="bi bi-eye"></i>
                                                        </a>

                                                        <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" title="Delete Ticket" onclick="return confirm('Delete this ticket?')">
                                                            <i class="bi bi-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>

                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            <?php else: ?><p>No tickets found.</p><?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php elseif ($activeTab === 'users'): ?>
                <div class="tab-pane fade show active">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">All User Accounts</h5>
                                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                    <i class="bi bi-plus-circle"></i> Add User
                                </button>
                            </div>

                            <!-- Add User Modal -->
                            <div class="modal fade" id="addUserModal" tabindex="-1">
                                <div class="modal-dialog">
                                    <form action="includes/add_user.php" method="POST" class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Add New User</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label>Username</label>
                                                <input type="text" name="username" class="form-control" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Password</label>
                                                <input type="password" name="password" class="form-control" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Role</label>
                                                <select name="role" class="form-select">
                                                    <option value="client">Client</option>
                                                    <option value="admin">Admin</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button class="btn btn-success" type="submit">Add User</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Users List -->
                            <div class="row g-3">
                                <?php $users = $conn->query("SELECT * FROM users ORDER BY role ASC, username ASC"); ?>
                                <?php while ($user = $users->fetch_assoc()): ?>
                                    <div class="col-md-4">
                                        <div class="card shadow-sm">
                                            <div class="card-body">
                                                <h5 class="card-title mb-1"><?= htmlspecialchars($user['username']) ?></h5>
                                                <p><span class="badge bg-<?= $user['role'] === 'admin' ? 'primary' : 'secondary' ?>"><?= ucfirst($user['role']) ?></span></p>
                                                <?php if ($user['username'] !== $_SESSION['username']): ?>
                                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editUserModal<?= $user['id'] ?>">Edit</button>
                                                    <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal<?= $user['id'] ?>">Delete</button>

                                                    <!-- Edit User Modal -->
                                                    <div class="modal fade" id="editUserModal<?= $user['id'] ?>" tabindex="-1">
                                                        <div class="modal-dialog">
                                                            <form action="includes/update_user_info.php" method="POST" class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Edit User</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                                                    <div class="mb-3">
                                                                        <label>Role</label>
                                                                        <select name="role" class="form-select">
                                                                            <option value="client" <?= $user['role'] === 'client' ? 'selected' : '' ?>>Client</option>
                                                                            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label>Current Password</label>
                                                                        <input type="password" name="current_password" class="form-control">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label>New Password</label>
                                                                        <input type="password" name="new_password" class="form-control">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label>Confirm Password</label>
                                                                        <input type="password" name="confirm_password" class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <button class="btn btn-success">Save Changes</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>

                                                    <!-- Delete User Modal -->
                                                    <div class="modal fade" id="deleteUserModal<?= $user['id'] ?>" tabindex="-1">
                                                        <div class="modal-dialog">
                                                            <form action="includes/delete_user.php" method="POST" class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Confirm Delete</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Are you sure you want to delete <strong><?= htmlspecialchars($user['username']) ?></strong>?
                                                                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <button class="btn btn-danger">Delete</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <!-- Change Password Modal -->
                                                    <div class="modal fade" id="changePassModal" tabindex="-1">
                                                        <div class="modal-dialog">
                                                            <form action="includes/admin_change_password.php" method="POST" class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Change Password</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label>Current Password</label>
                                                                        <input type="password" name="current_password" class="form-control" required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label>New Password</label>
                                                                        <input type="password" name="new_password" class="form-control" required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label>Confirm New Password</label>
                                                                        <input type="password" name="confirm_password" class="form-control" required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <button class="btn btn-primary" type="submit">Update Password</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>

                                                <?php else: ?>
                                                    <span class="text-muted">This is you</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999; margin-top: 60px;">
            <div id="liveToast" class="toast align-items-center text-white bg-<?= $_GET['type'] ?? 'info' ?> border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body"><?= htmlspecialchars($_GET['msg']) ?></div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/script.js"></script>

</body>

</html>