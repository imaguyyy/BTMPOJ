<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

try {
    $stmt = $pdo->query("SELECT * FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>e-Latihan Industri(Undang-Undang)</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="icon" href="images/fevicon.png" type="image/gif" />
    <style>
        body, html {
            overflow-x: hidden;
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: #FFBC00;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px;
            box-sizing: border-box;
            color: #fff;
        }

        .sidebar .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar .logo img {
            width: 40%;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin-bottom: 20px;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: #fff;
            font-size: 18px;
            display: flex;
            align-items: center;
        }

        .sidebar ul li a i {
            margin-right: 10px;
        }

        .header {
            height: 60px;
            background: #fff;
            padding: 10px 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-left: 250px;
            box-sizing: border-box;
        }

        .header .search {
            flex: 1;
            margin-left: 20px;
        }

        .header .search input {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header .user-info {
            display: flex;
            align-items: center;
        }

        .header .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .main {
            margin-left: 250px;
            padding: 20px;
            box-sizing: border-box;
            max-width: calc(100% - 250px);
            overflow-x: hidden;
        }

        .main h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .main .table {
            margin-top: 20px;
            width: 100%;
            overflow-x: auto;
        }

        .main .table thead th {
            background-color: #343a40;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <a href="admin_dashboard.php"><img src="images/gov.png" alt="Logo"></a>
        </div>
        <hr>
        <h2>e-Latihan Industri</h2>
        <h2>(Undang-Undang)</h2>
        <hr>
        <ul>
            <li><a href="admin_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="admin_senaraipemohon.php"><i class="fas fa-file-alt"></i> Senarai Permohonan</a></li>
            <li><a href="manage_users.php"><i class="fas fa-share"></i> Kemaskini Pengguna</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Log Keluar</a></li>
        </ul>
    </div>

    <div class="header">
        <div class="search">
            <div class="user-info">
                <?php
                if (isset($_SESSION['username'])) {
                    echo "<span>Hi, " . htmlspecialchars($_SESSION['username']) . "</span>";
                } else {
                    echo "<span>Welcome</span>";
                }
                ?>
            </div>
        </div>
    </div>

    <div class="main">
        <h2>Manage Users</h2>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Institusi</th>
                    <th>Email</th>
                    <th>No Telefon</th>
                    <th>Kemaskini</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($user['college_uni']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['no_phone']); ?></td>
                        <td>
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editUserModal" data-id="<?php echo htmlspecialchars($user['user_id']); ?>" data-college_uni="<?php echo htmlspecialchars($user['college_uni']); ?>" data-email="<?php echo htmlspecialchars($user['email']); ?>" data-phone="<?php echo htmlspecialchars($user['no_phone']); ?>">Edit</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="update_user.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">Kemaskini Pengguna</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="user_id" id="editUserId">
                        <div class="form-group">
                            <label for="editCollegeUni">Institusi</label>
                            <input type="text" class="form-control" name="college_uni" id="editCollegeUni" required>
                        </div>
                        <div class="form-group">
                            <label for="editEmail">Email</label>
                            <input type="email" class="form-control" name="email" id="editEmail" required>
                        </div>
                        <div class="form-group">
                            <label for="editPhone">No Telefon</label>
                            <input type="text" class="form-control" name="no_phone" id="editPhone" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script>
        $('#editUserModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var userId = button.data('id');
            var collegeUni = button.data('college_uni');
            var email = button.data('email');
            var phone = button.data('phone');

            var modal = $(this);
            modal.find('#editUserId').val(userId);
            modal.find('#editCollegeUni').val(collegeUni);
            modal.find('#editEmail').val(email);
            modal.find('#editPhone').val(phone);
        });
    </script>
</body>
</html>
