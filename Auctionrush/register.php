<?php
// connect.php usually handles session_start()
include 'connect.php'; 

$msg = '';
$error = '';

if(isset($_POST['register'])){
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // 1. Validate password match
    if($password !== $confirm_password){
        $error = "Passwords do not match!";
    } else {
        // 2. Check for existing email
        $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
        if(mysqli_num_rows($check) > 0){
            $error = "Email already registered!";
        } else {
            // 3. Hash password and insert user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert = mysqli_query($conn, "INSERT INTO users (username,email,password) VALUES ('$username','$email','$hashed_password')");
            if($insert){
                $msg = "Success! <a href='login.php' class='alert-link'>Login here</a>";
            } else {
                $error = "Database error: " . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | Auction Rush</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            --accent-color: #10b981; /* Emerald Green */
        }

        body {
            background: var(--primary-gradient);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 20px;
        }

        .register-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 480px;
        }

        .register-card h2 {
            font-weight: 800;
            color: #1e293b;
            text-align: center;
            margin-bottom: 1.5rem;
            letter-spacing: -1px;
        }

        .form-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
            color: #64748b;
        }

        .input-group-text {
            background: transparent;
            border-right: none;
            color: #94a3b8;
        }

        .form-control {
            border-left: none;
            padding: 10px;
            border-radius: 0 8px 8px 0;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #dee2e6;
        }

        .input-group:focus-within .input-group-text,
        .input-group:focus-within .form-control {
            border-color: var(--accent-color);
            color: #1e293b;
        }

        .btn-register {
            background: #1e293b;
            color: white;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            border: none;
            margin-top: 1rem;
            transition: all 0.3s ease;
        }

        .btn-register:hover {
            background: #334155;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            color: white;
        }

        .alert-custom {
            border-radius: 10px;
            font-size: 0.85rem;
            border: none;
        }

        .login-link {
            text-decoration: none;
            color: var(--accent-color);
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="register-card">
    <h2><i class="fas fa-user-plus text-success me-2"></i>Join Auction Rush</h2>

    <?php if($msg): ?>
        <div class="alert alert-success alert-custom d-flex align-items-center mb-3">
            <i class="fas fa-check-circle me-2"></i> <?php echo $msg; ?>
        </div>
    <?php endif; ?>

    <?php if($error): ?>
        <div class="alert alert-danger alert-custom d-flex align-items-center mb-3">
            <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="text" name="username" class="form-control" placeholder="Choose a public name" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                <input type="email" name="email" class="form-control" placeholder="your@email.com" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Confirm</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-shield-alt"></i></span>
                    <input type="password" name="confirm_password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>
        </div>

        <button type="submit" name="register" class="btn btn-register w-100">
            Create Free Account
        </button>
    </form>

    <div class="text-center mt-4">
        <p class="text-muted small">Already a member? <a href="login.php" class="login-link">Sign In</a></p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>