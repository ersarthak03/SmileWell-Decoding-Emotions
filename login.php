<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database configuration
$host = 'localhost';
$db   = 'smilewell';
$user = 'heroku';
$pass = 'sarthak@123';

// Initialize messages
$error = '';
$success = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $conn = new mysqli($host, $user, $pass, $db);
        
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        if (isset($_POST['login'])) {
    // LOGIN PROCESSING
    $username = trim($conn->real_escape_string($_POST['username']));
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        throw new Exception("Please fill all fields");
    }

    $stmt = $conn->prepare("SELECT id, username, password, role, email, points FROM users WHERE username = ?");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("s", $username);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        // Corrected - now binds all 4 selected columns
        $stmt->bind_result($id, $db_username, $hashed_password, $role, $emailsaved, $points);
        $stmt->fetch();
        
        if (password_verify($password, $hashed_password)) {
            $rank_stmt = $conn->prepare("SELECT COUNT(*) + 1 FROM users WHERE points > ?");
            $rank_stmt->bind_param("i", $points);
            $rank_stmt->execute();
            $rank_result = $rank_stmt->get_result();
            $rank = $rank_result->fetch_row()[0];
            $rank_stmt->close();
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $db_username;
            $_SESSION['role'] = $role; // Now properly set
            $_SESSION['logged_in'] = true;
            $_SESSION['emailsaved'] = $emailsaved;
            $_SESSION['rank'] = $rank;
            $_SESSION['points'] = $points;
            
            header("Location: index.php");
            exit();
        } else {
            throw new Exception("Invalid credentials");
        }
    } else {
        throw new Exception("User not found");
    }
    $stmt->close();
}
        elseif (isset($_POST['register'])) {
            // REGISTRATION PROCESSING
            $new_username = trim($conn->real_escape_string($_POST['new_username']));
            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
            $new_password = trim($_POST['new_password']);
            $confirm_password = trim($_POST['confirm_password']);

            // Validation
            if (empty($new_username) || empty($email) || empty($new_password)) {
                throw new Exception("All fields are required");
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Invalid email format");
            }
            
            if ($new_password !== $confirm_password) {
                throw new Exception("Passwords don't match");
            }
            
            if (strlen($new_password) < 8) {
                throw new Exception("Password must be at least 8 characters");
            }

            // Check if username or email exists
            $check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            if (!$check) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            
            $check->bind_param("ss", $new_username, $email);
            if (!$check->execute()) {
                throw new Exception("Execute failed: " . $check->error);
            }
            
            $check->store_result();
            
            if ($check->num_rows > 0) {
                throw new Exception("Username or email already exists");
            }
            
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            
            $stmt->bind_param("sss", $new_username, $email, $hashed_password);
            
            if ($stmt->execute()) {
                $success = "Registration successful! Please login.";
                // Auto-login after registration
                $_SESSION['user_id'] = $stmt->insert_id;
                $_SESSION['username'] = $new_username;
                header("Location: index.php");
                exit();
            } else {
                throw new Exception("Registration failed: " . $conn->error);
            }
            $stmt->close();
            $check->close();
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    } finally {
        if (isset($conn) && $conn) {
            $conn->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmileWell - Login/Register</title>
    <style>
*,
*:before,
*:after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}



body {
    font-family: 'Open Sans', Helvetica, Arial, sans-serif;
    background: #ffffff;
    background: url('/images/bg') no-repeat center center fixed;
    background-size: cover;
    position: relative;
}

input,
button {
    border: none;
    outline: none;
    background: none;
    font-family: 'Open Sans', Helvetica, Arial, sans-serif;
}

.tip {
    font-size: 20px;
    margin: 40px auto 50px;
    text-align: center;
}

/* Main container - desktop styles */
.cont {
    border-radius: 20px;
    overflow: hidden;
    position: relative;
    width: 900px;
    height: 550px;
    margin: 0 auto 100px;
    background: #fff;
    box-shadow: -10px -10px 15px rgba(255, 255, 255, 0.3), 
                10px 10px 15px rgba(70, 70, 70, 0.15), 
                inset -10px -10px 15px rgba(255, 255, 255, 0.3), 
                inset 10px 10px 15px rgba(70, 70, 70, 0.15);
}

/* Mobile adjustments */
@media (max-width: 950px) {
    .cont {
        width: 95%;
        max-width: 420px;
        height: auto;
        min-height: 600px;
        margin: 30px auto;
    }

    .form {
        width: 100%;
        height: auto;
        padding: 40px 20px 20px;
        position: relative;
    }

    .sub-cont {
        position: absolute;
        left: 100%;
        width: 100%;
        height: 100%;
        padding-left: 0;
        background: #fff;
    }

    .cont.s--signup .sub-cont {
        transform: translate3d(-100%, 0, 0);
    }

    .img {
        width: 100%;
        height: 180px;
        padding-top: 0;
        position: relative;
    }

    .img:before {
        width: 100%;
        background-position: center;
    }

    .img__text {
        top: 20px;
    }

    .img__text h2 {
        font-size: 22px;
    }

    .img__text p {
        font-size: 13px;
    }

    .img__btn {
        margin-top: 20px;
    }

    label {
        width: 80%;
        margin: 20px auto 0;
    }

    button {
        width: 80%;
    }

    .submit {
        margin-top: 30px;
        margin-bottom: 15px;
    }
}

/* Original desktop styles remain unchanged below this line */
.form {
    position: relative;
    width: 640px;
    height: 100%;
    transition: transform 1.2s ease-in-out;
    padding: 50px 30px 0;
}

.sub-cont {
    overflow: hidden;
    position: absolute;
    left: 640px;
    top: 0;
    width: 900px;
    height: 100%;
    padding-left: 260px;
    background: #fff;
    transition: transform 1.2s ease-in-out;
}

.cont.s--signup .sub-cont {
    transform: translate3d(-640px, 0, 0);
}

button {
    display: block;
    margin: 0 auto;
    width: 260px;
    height: 36px;
    border-radius: 30px;
    color: #fff;
    font-size: 15px;
    cursor: pointer;
}

.img {
    overflow: hidden;
    z-index: 2;
    position: absolute;
    left: 0;
    top: 0;
    width: 260px;
    height: 100%;
    padding-top: 360px;
}

.img:before {
    content: '';
    position: absolute;
    right: 0;
    top: 0;
    width: 900px;
    height: 100%;
    background-image: url("ext.jpg");
    opacity: .8;
    background-size: cover;
    transition: transform 1.2s ease-in-out;
}

.img:after {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
}

.cont.s--signup .img:before {
    transform: translate3d(640px, 0, 0);
}

.img__text {
    z-index: 2;
    position: absolute;
    left: 0;
    top: 50px;
    width: 100%;
    padding: 0 20px;
    text-align: center;
    color: #fff;
    transition: transform 1.2s ease-in-out;
}

.img__text h2 {
    margin-bottom: 10px;
    font-weight: normal;
}

.img__text p {
    font-size: 14px;
    line-height: 1.5;
}

.cont.s--signup .img__text.m--up {
    transform: translateX(520px);
}

.img__text.m--in {
    transform: translateX(-520px);
}

.cont.s--signup .img__text.m--in {
    transform: translateX(0);
}

.img__btn {
    overflow: hidden;
    z-index: 2;
    position: relative;
    width: 100px;
    height: 36px;
    margin: 0 auto;
    background: transparent;
    color: #fff;
    text-transform: uppercase;
    font-size: 15px;
    cursor: pointer;
}

.img__btn:after {
    content: '';
    z-index: 2;
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    border: 2px solid #fff;
    border-radius: 30px;
}

.img__btn span {
    position: absolute;
    left: 0;
    top: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    height: 100%;
    transition: transform 1.2s;
}

.img__btn span.m--in {
    transform: translateY(-72px);
}

.cont.s--signup .img__btn span.m--in {
    transform: translateY(0);
}

.cont.s--signup .img__btn span.m--up {
    transform: translateY(72px);
}

h2 {
    width: 100%;
    font-size: 26px;
    text-align: center;
}

label {
    display: block;
    width: 260px;
    margin: 25px auto 0;
    text-align: center;
}

label span {
    font-size: 12px;
    color: #cfcfcf;
    text-transform: uppercase;
}

input {
    display: block;
    width: 100%;
    margin-top: 5px;
    padding-bottom: 5px;
    font-size: 16px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.4);
    text-align: center;
}

.forgot-pass {
    margin-top: 15px;
    text-align: center;
    font-size: 12px;
    color: #cfcfcf;
}

.submit {
    margin-top: 40px;
    margin-bottom: 20px;
    background: #d4af7a;
    text-transform: uppercase;
}

.fb-btn {
    border: 2px solid #d3dae9;
    color: #8fa1c7;
}

.fb-btn span {
    font-weight: bold;
    color: #455a81;
}

.sign-in {
    transition-timing-function: ease-out;
}

.cont.s--signup .sign-in {
    transition-timing-function: ease-in-out;
    transition-duration: 1.2s;
    transform: translate3d(640px, 0, 0);
}

.sign-up {
    transform: translate3d(-900px, 0, 0);
}

.cont.s--signup .sign-up {
    transform: translate3d(0, 0, 0);
}
@media (max-width: 950px) {
    .cont {
        width: 95%;
        max-width: 420px;
        height: auto;
        min-height: 500px;
        margin: 30px auto;
        overflow: hidden;
    }

    .form {
        width: 100%;
        height: auto;
        padding: 30px 20px;
        position: relative;
        transition: none !important;
        transform: none !important;
    }

    /* Mobile toggle button styling */
   @media (max-width: 950px) {
    .cont {
        width: 95%;
        max-width: 420px;
        height: auto;
        min-height: 500px;
        margin: 30px auto;
        overflow: hidden;
        transition: all 0.4s ease-in-out;
    }

    .form {
        width: 100%;
        height: auto;
        padding: 30px 20px;
        position: relative;
        transition: opacity 0.4s ease-in-out, transform 0.4s ease-in-out;
        transform: translateX(0);
        opacity: 1;
    }

    /* Mobile toggle button styling */
    .mobile-toggle-btn {
        display: block;
        text-align: center;
        margin: 20px auto;
        color: #d4af7a;
        font-weight: bold;
        cursor: pointer;
        background: none;
        border: none;
        font-size: 14px;
        text-decoration: underline;
        width: 100%;
        padding: 10px;
        transition: color 0.3s ease;
    }

    .mobile-toggle-btn:hover {
        color: #b38c5a;
    }

    /* Adjust the image section for mobile */
    .img {
        display: none;
    }

    /* Show the sub-cont (signup form) properly on mobile */
    .sub-cont {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: auto;
        padding-left: 0;
        transform: translateX(100%);
        display: block;
        background: #fff;
        transition: transform 0.4s ease-in-out;
    }

    .cont.s--signup .sub-cont {
        transform: translateX(0);
    }

    .cont.s--signup .sign-in {
        transform: translateX(-100%);
        opacity: 0;
        position: absolute;
    }

    /* Form input styling */
    input {
        width: 100%;
        padding: 12px;
        margin: 8px 0;
        border: 1px solid #ddd;
        border-radius: 25px;
        background: #f9f9f9;
        text-align: left;
        padding-left: 15px;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    input:focus {
        border-color: #d4af7a;
        box-shadow: 0 0 0 2px rgba(212, 175, 122, 0.2);
    }

    label {
        width: 100%;
        margin: 15px 0;
        transition: all 0.3s ease;
    }

    label span {
        display: block;
        text-align: left;
        margin-bottom: 5px;
        padding-left: 5px;
        color: #666;
    }

    .submit {
        width: 100%;
        padding: 14px;
        margin: 25px 0 15px;
        background: #d4af7a;
        color: white;
        font-weight: bold;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .submit:hover {
        background-color: #b38c5a;
    }

    .submit:active {
        transform: scale(0.98);
    }

    .forgot-pass {
        text-align: center;
        margin: 10px 0;
        color: #666;
        transition: color 0.3s ease;
    }

    .forgot-pass:hover {
        color: #333;
    }
}

    </style>
</head>
<body>
    <div class="cont <?php echo (isset($_POST['register']) ? 's--signup' : ''); ?>">
        <!-- Login Form -->
        <div class="form sign-in">
            <h2>Welcome Back</h2>
            <?php if ($error && isset($_POST['login'])): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <label>
                    <span>Username</span>
                    <input type="text" name="username" required />
                </label>
                <label>
                    <span>Password</span>
                    <input type="password" name="password" required />
                </label>
                <p class="forgot-pass">Forgot password?</p>
                <button type="submit" name="login" class="submit">Sign In</button>
                <button type="button" class="mobile-toggle-btn">Don't have an account? Sign Up</button>
                
            </form>
        </div>
        
        <div class="sub-cont">
            <div class="img">
                <div class="img__text m--up">
                    <h3>Don't have an account? Please Sign up!</h3>
                </div>
                <div class="img__text m--in">
                    <h3>If you already have an account, just sign in.</h3>
                </div>
                <div class="img__btn">
                    <span class="m--up">Sign Up</span>
                    <span class="m--in">Sign In</span>
                </div>
            </div>
            
            <!-- Signup Form -->
            <div class="form sign-up">
                <h2>Create your Account</h2>
                <?php if ($error && isset($_POST['register'])): ?>
                    <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <form method="POST">
                    <label>
                        <span>Username</span>
                        <input type="text" name="new_username" required />
                    </label>
                    <label>
                        <span>Email</span>
                        <input type="email" name="email" required />
                    </label>
                    <label>
                        <span>Password</span>
                        <input type="password" name="new_password" required />
                    </label>
                    <label>
                        <span>Confirm Password</span>
                        <input type="password" name="confirm_password" required />
                    </label>
                    <button type="submit" name="register" class="submit">Sign Up</button>
                    <button type="button" class="mobile-toggle-btn">Already have an account? Sign In</button>
                </form>
            </div>
        </div>
    </div>

    <script>
// Toggle functionality for both desktop and mobile
// Toggle functionality with enhanced transitions
function toggleForms() {
    const container = document.querySelector('.cont');
    const forms = document.querySelectorAll('.form');
    const isMobile = window.innerWidth <= 950;
    
    if (isMobile) {
        // Add transition class
        container.classList.add('mobile-transition');
        
        // Toggle the signup state
        container.classList.toggle('s--signup');
        
        // Clear messages
        clearMessages();
        
        // Scroll to top when toggling forms on mobile
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
        
        // Remove transition class after animation completes
        setTimeout(() => {
            container.classList.remove('mobile-transition');
        }, 400);
    } else {
        // Desktop behavior remains the same
        container.classList.toggle('s--signup');
        clearMessages();
    }
}

// Set up event listeners
document.querySelector('.img__btn')?.addEventListener('click', toggleForms);
document.querySelectorAll('.mobile-toggle-btn').forEach(btn => {
    btn.addEventListener('click', toggleForms);
});

// Clear error/success messages
function clearMessages() {
    document.querySelectorAll('.error-message, .success-message').forEach(el => {
        el.style.opacity = '0';
        setTimeout(() => {
            el.textContent = '';
            el.style.opacity = '1';
        }, 300);
    });
}

// Preserve form state on error
<?php if (isset($_POST['register']) && $error): ?>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('.cont').classList.add('s--signup');
        // Scroll to top if on mobile
        if (window.innerWidth <= 950) {
            window.scrollTo(0, 0);
        }
    });
<?php endif; ?>
    </script>
</body>
</html>