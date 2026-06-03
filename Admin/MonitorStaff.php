<?php
    require_once('auth.php');
    require_once('../Database/database.php');
    require_once('sidebar.php');

    $con = new Database();
    $ownerID = $_SESSION['user_id'];
    $employees = $con->getEmployeesForAdmin();

    $latestAttendance = [];
    foreach($con->getLatestAttendanceByEmployee() as $attendance){
        $latestAttendance[$attendance['Employee_ID']] = $attendance;
    }

    if(isset($_POST['addEmployee'])){
        $firstName = trim($_POST['first_name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phoneNumber = trim($_POST['phone_number'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if($firstName === '' || $lastName === '' || $email === '' || $password === ''){
            $_SESSION['error_message'] = 'Please fill in all required fields.';
            header('Location: MonitorStaff.php');
            exit();
        }

        try{
            $con->AddEmployee($ownerID, $firstName, $lastName, $email, $phoneNumber, $password);
            $_SESSION['success_message'] = 'Employee added successfully.';
            header('Location: MonitorStaff.php');
            exit();
        } catch(Exception $e){
            $_SESSION['error_message'] = 'Unable to add employee: ' . $e->getMessage();
            header('Location: MonitorStaff.php');
            exit();
        }
    }

    $totalEmployees = count($employees);
    $withAttendance = count($latestAttendance);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <title>Monitor Staff</title>
    <style>
        body{
            background:
                radial-gradient(circle at top right, rgba(15, 138, 82, 0.08), transparent 26%),
                radial-gradient(circle at bottom left, rgba(227, 151, 16, 0.08), transparent 24%),
                #f7f8f4;
        }

        .staff-shell{
            margin-left: 350px;
            min-height: 100vh;
            padding: 28px;
        }

        .staff-hero{
            border: 0;
            border-radius: 26px;
            background:
                radial-gradient(circle at top left, rgba(255,255,255,.07), transparent 30%),
                linear-gradient(145deg, #13181d 0%, #22282e 58%, #1c2126 100%);
            color: #f8fafc;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.10);
        }

        .staff-hero__body{
            padding: 30px 32px;
        }

        .staff-kicker{
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(15, 138, 82, 0.22);
            color: #e6fff0;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
        }

        .staff-title{
            margin: 14px 0 8px;
            font-size: clamp(2rem, 3vw, 2.8rem);
            line-height: 1;
            font-weight: 800;
        }

        .staff-copy{
            color: rgba(255,255,255,.84);
            max-width: 720px;
        }

        .summary-card,
        .table-card{
            border: 0;
            border-radius: 24px;
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.08);
            background: #fff;
        }

        .summary-metric{
            border-radius: 18px;
            background: #fff;
            border: 1px solid #e7ece3;
            padding: 16px;
        }

        .summary-metric .label{
            color: #6b7280;
            font-size: .76rem;
            letter-spacing: .12em;
            text-transform: uppercase;
            font-weight: 700;
        }

        .summary-metric .value{
            margin-top: 4px;
            color: #1f2937;
            font-size: 1.45rem;
            font-weight: 800;
        }

        .staff-table thead th{
            font-size: .78rem;
            letter-spacing: .10em;
            text-transform: uppercase;
            color: #64748b;
            border-bottom: 1px solid #e2e8f0;
        }

        .staff-table tbody td{
            vertical-align: middle;
            color: #1f2937;
        }

        .staff-pill{
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(15, 138, 82, 0.08);
            color: #0d6b42;
            font-weight: 700;
            font-size: .88rem;
        }

        .action-row{
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        @media (max-width: 991px){
            .staff-shell{
                margin-left: 0;
                padding: 18px 14px 28px;
            }
        }
    </style>
</head>
<body>
<div class="d-flex">
    <?php renderAdminSidebar(); ?>

    <main class="staff-shell w-100">
        <?php if(isset($_SESSION['error_message'])){ ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_SESSION['error_message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php unset($_SESSION['error_message']); } ?>

        <?php if(isset($_SESSION['success_message'])){ ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_SESSION['success_message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php unset($_SESSION['success_message']); } ?>

        <div class="card staff-hero mb-4">
            <div class="staff-hero__body">
                <span class="staff-kicker">Monitor Staff</span>
                <h1 class="staff-title">Employee list and onboarding</h1>
                <p class="staff-copy mb-0">Review every employee registered in the system and add new staff from a single modal form.</p>
            </div>
        </div>

        <div class="card summary-card mb-4">
            <div class="card-body p-3 p-md-4">
                <div class="row g-3 align-items-stretch">
                    <div class="col-12 col-md-4">
                        <div class="summary-metric h-100">
                            <div class="label">Total Employees</div>
                            <div class="value"><?php echo $totalEmployees; ?></div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="summary-metric h-100">
                            <div class="label">With Attendance Logs</div>
                            <div class="value"><?php echo $withAttendance; ?></div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="summary-metric h-100 d-flex align-items-center justify-content-between gap-2">
                            <div>
                                <div class="label">Actions</div>
                                <div class="value" style="font-size:1.05rem;">Add / review staff</div>
                            </div>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">Add Employee</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card table-card">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <h5 class="mb-0">Staff members</h5>
                    <input id="staffSearch" class="form-control form-control-sm" style="max-width: 280px;" placeholder="Search staff name...">
                </div>
                <div class="table-responsive">
                    <table class="table table-hover staff-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Latest Duty In</th>
                                <th>Latest Duty Out</th>
                            </tr>
                        </thead>
                        <tbody id="staffBody">
                            <?php if(empty($employees)){ ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">No employees found.</td>
                                </tr>
                            <?php } ?>

                            <?php foreach($employees as $employee){
                                $attendance = $latestAttendance[$employee['Employee_ID']] ?? [];
                            ?>
                                <tr>
                                    <td><?php echo (int)$employee['Employee_ID']; ?></td>
                                    <td>
                                        <span class="staff-pill"><?php echo htmlspecialchars($employee['username']); ?></span>
                                        <div class="small text-muted mt-1">Owner ID: <?php echo (int)($employee['Owner_ID'] ?? 0); ?></div>
                                    </td>
                                    <td><?php echo htmlspecialchars($employee['Email'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($employee['Phone_Number'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($attendance['Time_In'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($attendance['Time_Out'] ?? '-'); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEmployeeModalLabel">Add Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control" name="first_name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control" name="last_name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="text" class="form-control" name="phone_number" maxlength="11">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Password</label>
                            <input type="text" class="form-control" name="password" required>
                            <div class="form-text">Keep this aligned with the current staff login flow.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" name="addEmployee">Save Employee</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    (function(){
        const input = document.getElementById('staffSearch');
        const rows = Array.from(document.querySelectorAll('#staffBody tr'));
        if(!input) return;
        input.addEventListener('input', function(){
            const q = this.value.trim().toLowerCase();
            rows.forEach(function(row){
                const text = (row.textContent || '').toLowerCase();
                row.style.display = (!q || text.indexOf(q) !== -1) ? '' : 'none';
            });
        });
    })();
</script>
<script src="../functions/window.js"></script>
</body>
</html>