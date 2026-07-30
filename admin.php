<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// -------------------------------------------------------------
// 1. ԱԴՄԻՆԻ ԳԱՂՏՆԱԲԱՌԻ ԿԱՐԳԱՎՈՐՈՒՄ
// -------------------------------------------------------------
define('ADMIN_PASSWORD', 'admin123'); 

$error_message = '';

if (isset($_POST['admin_login'])) {
    $entered_password = $_POST['admin_password'] ?? '';
    if ($entered_password === ADMIN_PASSWORD) {
        $_SESSION['is_admin_logged_in'] = true;
        header("Location: admin.php");
        exit();
    } else {
        $error_message = "Սխալ գաղտնաբառ:";
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    unset($_SESSION['is_admin_logged_in']);
    header("Location: admin.php");
    exit();
}

if (empty($_SESSION['is_admin_logged_in'])):
?>
<!DOCTYPE html>
<html lang="hy">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background: #0f172a;
            color: #f8fafc;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: system-ui, -apple-system, sans-serif;
        }
        .login-card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>
<body>

<div class="login-card text-center">
    <div class="mb-4">
        <i class="bi bi-shield-lock-fill text-primary display-4"></i>
        <h4 class="fw-bold mt-2">Ադմին Մուտք</h4>
        <p class="text-muted small">Մուտքագրեք գաղտնաբառը շարունակելու համար</p>
    </div>

    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger py-2 small" role="alert">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="admin.php">
        <div class="mb-3 text-start">
            <label class="form-label small fw-semibold">Գաղտնաբառ</label>
            <input type="password" name="admin_password" class="form-control bg-dark text-white border-secondary" placeholder="••••••••" required>
        </div>
        <button type="submit" name="admin_login" class="btn btn-primary w-100 fw-bold py-2">
            Մուտք Գործել
        </button>
    </form>
</div>

</body>
</html>
<?php 
exit(); 
endif; 

// -------------------------------------------------------------
// 2. ԱԴՄԻՆ ՊԱՆԵԼ
// -------------------------------------------------------------
require_once 'user.php';

$userObj = new User();
$connect = $userObj->connect;

// Վերցնում ենք բոլոր օգտատերերին նվազման հերթականությամբ
$query = "SELECT * FROM `users` ORDER BY `id` ASC";
$usersResult = mysqli_query($connect, $query);
$totalUsers = mysqli_num_rows($usersResult);
?>

<!DOCTYPE html>
<html lang="hy">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0f172a;
            color: #f8fafc;
            min-height: 100vh;
        }

        .navbar-admin {
            background: #1e1b4b;
            border-bottom: 1px solid #312e81;
        }

        .card-custom {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
        }

        .table-custom {
            color: #f8fafc;
        }

        .table-custom th {
            background-color: #0f172a;
            color: #94a3b8;
            border-bottom: 1px solid #334155;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            padding: 14px;
        }

        .table-custom td {
            border-bottom: 1px solid #334155;
            padding: 14px;
            vertical-align: middle;
            background-color: transparent;
            color: #f8fafc;
        }

        .table-custom tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.03);
        }

        .user-id-badge {
            background: rgba(99, 102, 241, 0.2);
            color: #818cf8;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 8px;
            display: inline-block;
        }

        /* ՈՒՂՂՎԱԾ՝ Ամսաթվի և էլ. փոստի տեքստի գույնը դարձված է բաց և պարզ երևացող */
        .date-text {
            color: #cbd5e1 !important;
            font-weight: 500;
        }

        .email-text {
            color: #e2e8f0 !important;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-admin py-3 mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center gap-2 fs-4" href="admin.php">
            <i class="bi bi-shield-lock-fill text-warning"></i> Admin Panel
        </a>
        <a href="index.php" class="btn btn-danger btn-sm rounded-3 px-3 d-flex align-items-center gap-1">
            <i class="bi bi-box-arrow-right"></i> Ելք
        </a>
    </div>
</nav>

<div class="container pb-5">
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card-custom p-4 d-flex align-items-center gap-3">
                <div class="bg-primary bg-opacity-20 text-primary p-3 rounded-circle fs-3">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <span class="text-muted small d-block">Գրանցված օգտատերեր</span>
                    <h3 class="fw-bold m-0"><?php echo $totalUsers; ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card-custom p-4">
        <h5 class="fw-bold mb-4 d-flex align-items-center gap-2">
            <i class="bi bi-person-lines-fill text-primary"></i> Օգտատերերի Ցուցակ
        </h5>

        <div class="table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Անուն</th>
                        <th>Ազգանուն</th>
                        <th>Էլ․ փոստ</th>
                        <th>Գրանցման ամսաթիվ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($usersResult && mysqli_num_rows($usersResult) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($usersResult)): ?>
                            <tr>
                                <td>
                                    <span class="user-id-badge">#<?php echo htmlspecialchars($row['id']); ?></span>
                                </td>
                                <td class="fw-medium"><?php echo htmlspecialchars($row['firstName'] ?? ''); ?></td>
                                <td class="fw-medium"><?php echo htmlspecialchars($row['lastName'] ?? ''); ?></td>
                                <td class="email-text">
                                    <i class="bi bi-envelope me-1 text-primary"></i>
                                    <?php echo htmlspecialchars($row['email'] ?? ''); ?>
                                </td>
                                <td class="date-text">
                                    <i class="bi bi-calendar3 me-1 text-warning"></i>
                                    <?php 
                                        echo !empty($row['created_at']) 
                                            ? htmlspecialchars($row['created_at']) 
                                            : '—'; 
                                    ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                Գրանցված օգտատերեր չկան։
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>