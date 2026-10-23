<?php
include 'connect.php'; // connect.php should contain session_start()

$error = '';

if(isset($_POST['login'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if(mysqli_num_rows($result) == 1){
        $user = mysqli_fetch_assoc($result);
        // Securely verify hashed password
        if(password_verify($password, $user['password'])){
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "Email not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Auction Rush</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); /* Midnight Blue theme */
            --accent-color: #10b981; /* Emerald Green accent */
        }

        body {
            background: var(--primary-gradient);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 420px;
            transition: transform 0.3s ease;
        }

        .brand-logo {
            font-size: 2.5rem;
            color: #1e293b;
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .login-card h2 {
            font-weight: 700;
            color: #334155;
            text-align: center;
            margin-bottom: 1.5rem;
            letter-spacing: -1px;
        }

        .input-group-text {
            background: transparent;
            border-right: none;
            color: #64748b;
        }

        .form-control {
            border-left: none;
            padding: 12px;
            border-radius: 0 8px 8px 0;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #dee2e6;
        }

        .input-group:focus-within .input-group-text,
        .input-group:focus-within .form-control {
            border-color: var(--accent-color);
            color: var(--accent-color);
        }

        .btn-login {
            background: #1e293b;
            color: white;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            border: none;
            margin-top: 1rem;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: #334155;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            color: white;
        }

        .alert-custom {
            border-radius: 10px;
            font-size: 0.9rem;
            border: none;
            background: #fee2e2;
            color: #991b1b;
        }

        .register-link {
            text-decoration: none;
            color: var(--accent-color);
            font-weight: 600;
        }

        .register-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="brand-logo">
        <i class="fas fa-gavel text-success"></i>
    </div>
    <h2>Auction Rush</h2>

    <?php if($error): ?>
        <div class="alert alert-custom d-flex align-items-center mb-3">
            <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label small fw-bold text-muted text-uppercase">Email Address</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label small fw-bold text-muted text-uppercase">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
        </div>

        <button type="submit" name="login" class="btn btn-login w-100">
            <i class="fas fa-sign-in-alt me-2"></i> Sign In
        </button>
    </form>

    <div class="text-center mt-4">
        <p class="text-muted small">New to the platform? <a href="register.php" class="register-link">Create an account</a></p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>