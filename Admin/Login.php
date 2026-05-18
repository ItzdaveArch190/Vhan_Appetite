<?php
session_start();
require_once('../Database/database.php');

$loginErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["username"]); 
    $password = trim($_POST["password"]);

    if (empty($email) || empty($password)) {
        $loginErr = "Please fill in all fields.";
    } else {
        $con = new Database();
        $user = $con->loginUser($email, $password);

        if ($user) {
            // Sinesave ang data ng admin sa session variables
            $_SESSION["user_id"] = $user['id_user']; 
            $_SESSION["first_name"] = $user['first_name'];
            $_SESSION["account_type"] = $user['account_type']; 

            // Tinitiyak kung Admin ang nag-login bago papasukin sa Dashboard
            if (strtolower($user['account_type']) == 'admin') {
                header("Location: Dashboard.php"); 
                exit();
            } else {
                $loginErr = "Access Denied. Para sa mga Admins lamang ito.";
            }
        } else {
            $loginErr = "Maling email o password, kapatid!";
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Vhan Appetite — Login</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <div class="container app-shell d-flex align-items-center justify-content-center py-4 login-shell" style="height: 100vh;">
    <div class="row w-100 justify-content-center">
      <div class="col-12 col-sm-10 col-md-8 col-lg-5 col-xl-4">
        <div class="card p-4 p-md-5 login-card shadow-lg">
          <div class="login-header text-center mb-4">
            <div class="badge rounded-pill text-bg-success px-3 py-2 mb-3">Vhan Appetite</div>
            <h4 class="mb-1 fw-semibold">Welcome Back</h4>
            <p class="small text-muted mb-0">Sign in with your registered email to continue.</p>
          </div>

          <?php if (!empty($loginErr)): ?>
            <div class="alert alert-danger text-center py-2 small" role="alert">
              <?php echo $loginErr; ?>
            </div>
          <?php endif; ?>

          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="mb-3">
              <label class="form-label fw-medium">Email Address</label>
              <input class="form-control" name="username" type="email" placeholder="e.g., admin@samplemail.com" required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-medium">Password</label>
              <input class="form-control" name="password" type="password" placeholder="••••••••" required>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="rememberMe" name="rememberMe">
                <label class="form-check-label text-muted small" for="rememberMe">Remember me</label>
              </div>
              <a class="small text-decoration-none" href="#">Forgot password?</a>
            </div>

            <div class="d-grid gap-2">
              <button class="btn btn-primary btn-lg" type="submit">Sign In</button>
            </div>

            <hr class="my-4">
            <div class="text-muted small text-center">
              © Vhan Appetite 2024. All rights reserved.
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>