<?php
// contact.php - Contact form handling
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

// If PHPMailer is installed via composer, require it here.
// require 'vendor/autoload.php';
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

$error = '';
$success = '';

// Pre-fill fields if user is logged in
$user_name = '';
$user_email = '';
if (is_logged_in()) {
    $user_name = $_SESSION['username'] ?? '';
    // Fetch email if we want to pre-fill
    try {
        $stmt = $pdo->prepare("SELECT email FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $row = $stmt->fetch();
        if ($row)
            $user_email = $row['email'];
    }
    catch (PDOException $e) {
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize_input($_POST['name'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $subject = sanitize_input($_POST['subject'] ?? 'General Inquiry');
    $message = sanitize_input($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        $error = 'Please fill out all required fields.';
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    }
    else {
        // Insert into database
        try {
            $stmt = $pdo->prepare("INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$name, $email, $subject, $message])) {
                $success = 'true';

            // --- Optional PHPMailer Integration ---
            /*
             if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
             $mail = new PHPMailer(true);
             try {
             // Server settings
             $mail->isSMTP();
             $mail->Host       = 'smtp.example.com'; 
             $mail->SMTPAuth   = true;
             $mail->Username   = 'your_email@example.com'; 
             $mail->Password   = 'your_password'; 
             $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
             $mail->Port       = 587;
             // Recipients
             $mail->setFrom('noreply@cooksphere.com', 'CookSphere System');
             $mail->addAddress('admin@cooksphere.com', 'Admin'); 
             $mail->addReplyTo($email, $name);
             // Content
             $mail->isHTML(true);
             $mail->Subject = 'New Contact Query: ' . $subject;
             $mail->Body    = "<h3>You have a new message!</h3><br><b>Name:</b> $name<br><b>Email:</b> $email<br><b>Message:</b><p>$message</p>";
             $mail->send();
             } catch (Exception $e) {
             // Log error quietly $mail->ErrorInfo
             }
             }
             */
            // --------------------------------------

            }
            else {
                $error = 'Failed to submit your message. Please try again later.';
            }
        }
        catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - CookSphere</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css?v=1.1">
</head>
<body class="bg-light pt-5 mt-4">

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg fixed-top shadow-sm bg-white" id="mainNav">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3 text-primary d-flex align-items-center gap-2" href="index.php">
                <i class="bi bi-egg-fried"></i> CookSphere
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list fs-1 text-dark"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 fw-medium">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#recipes-view">Recipes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="submit_recipe.php">Submit Recipe</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#about-view">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active text-primary" href="contact.php">Contact</a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                            <a class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm" href="dashboard.php">
                                <i class="bi bi-person-circle me-2"></i><?php echo htmlspecialchars($_SESSION['username']); ?>
                            </a>
                        </li>
                        <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                            <a class="btn btn-outline-danger rounded-pill px-4 fw-semibold shadow-sm" href="auth/logout.php">Logout</a>
                        </li>
                    <?php
else: ?>
                        <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                            <a class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm" href="auth/login.php">Get Started</a>
                        </li>
                    <?php
endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Header Section -->
    <div class="hero-section text-center position-relative py-5" style="background: url('images/hero.png') center/cover no-repeat; margin-top: 20px; min-height: 400px; display: flex; align-items: center; justify-content: center;">
        <div class="hero-overlay"></div>
        <div class="container position-relative text-white" style="z-index: 2;">
            <h1 class="display-3 fw-bold mb-3">Get in Touch</h1>
            <p class="lead text-white-50 mx-auto" style="max-width: 600px;">Have questions, feedback, or a magical recipe you want to share with the inner circle? We'd love to hear from you.</p>
        </div>
    </div>

    <!-- Contact Form Section -->
    <div class="container position-relative" style="margin-top: -60px; z-index: 10;">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-5">
                    <div class="row g-0">
                        
                        <!-- Left Info Panel -->
                        <div class="col-md-5 bg-primary text-white p-5 pattern-bg d-flex flex-column justify-content-center">
                            <h3 class="fw-bold mb-4 position-relative z-1">Contact Information</h3>
                            <p class="mb-4 text-white-50 position-relative z-1">Fill up the form and our team will get back to you within 24 hours.</p>
                            
                            <div class="d-flex align-items-center mb-4 position-relative z-1">
                                <i class="bi bi-telephone-fill fs-4 me-3"></i>
                                <span>+94 72 846 1395</span>
                            </div>
                            <div class="d-flex align-items-center mb-4 position-relative z-1">
                                <i class="bi bi-envelope-fill fs-4 me-3"></i>
                                <span>hello@cooksphere.com</span>
                            </div>
                            <div class="d-flex align-items-center position-relative z-1">
                                <i class="bi bi-geo-alt-fill fs-4 me-3"></i>
                                <span>123 Culinary Hub, Foodie St.<br>Sri Lanka</span>
                            </div>
                        </div>

                        <!-- Right Form Panel -->
                        <div class="col-md-7 p-5 bg-white">
                            <?php if ($error): ?>
                                <div class="alert alert-danger rounded-3" role="alert">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo htmlspecialchars($error); ?>
                                </div>
                            <?php
endif; ?>

                            <form method="POST" action="contact.php" id="contactForm">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-dark">Your Name <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0"><i class="bi bi-person text-muted"></i></span>
                                            <input type="text" name="name" class="form-control bg-light border-0 py-2 shadow-none" placeholder="John Doe" value="<?php echo htmlspecialchars($user_name); ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-dark">Email Address <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0"><i class="bi bi-envelope text-muted"></i></span>
                                            <input type="email" name="email" class="form-control bg-light border-0 py-2 shadow-none" placeholder="john@example.com" value="<?php echo htmlspecialchars($user_email); ?>" required>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-semibold text-dark">Subject <span class="text-muted fw-normal small">(Optional)</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0"><i class="bi bi-chat-right-text text-muted"></i></span>
                                            <input type="text" name="subject" class="form-control bg-light border-0 py-2 shadow-none" placeholder="How can we help you?">
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-semibold text-dark">Message <span class="text-danger">*</span></label>
                                        <textarea name="message" class="form-control bg-light border-0 p-3 shadow-none" rows="5" placeholder="Write your message here..." required></textarea>
                                    </div>
                                    
                                    <div class="col-12 mt-4">
                                        <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold shadow-sm transition-hover">
                                            Send Message <i class="bi bi-send-fill ms-2"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Check for success trigger
        <?php if ($success === 'true'): ?>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Message Sent!',
                    text: 'Thank you for reaching out. We will get back to you shortly.',
                    confirmButtonColor: '#ff6b6b',
                    customClass: { popup: 'rounded-4' }
                }).then(() => {
                    // Clean the form url parameters optionally or just let it sit
                    window.history.replaceState(null, null, window.location.pathname);
                });
            });
        <?php
endif; ?>
    </script>
</body>
</html>
