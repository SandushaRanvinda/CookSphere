<?php
// edit_recipe.php - Edit an existing recipe
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Check if user is logged in
if (!is_logged_in()) {
    header("Location: auth/login.php");
    exit;
}

$error = '';
$success = '';
$recipe_id = $_GET['id'] ?? null;
$user_id = $_SESSION['user_id'];

if (!$recipe_id) {
    header("Location: dashboard.php");
    exit;
}

// Fetch the existing recipe securely ensuring the current user owns it
try {
    $stmt = $pdo->prepare("SELECT * FROM recipes WHERE id = ? AND user_id = ?");
    $stmt->execute([$recipe_id, $user_id]);
    $recipe = $stmt->fetch();

    if (!$recipe) {
        // Recipe not found or doesn't belong to player
        header("Location: dashboard.php");
        exit;
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize_input($_POST['title'] ?? '');
    $prep_time = intval($_POST['prep_time'] ?? 0);
    $category = sanitize_input($_POST['category'] ?? '');
    $ingredients = sanitize_input($_POST['ingredients'] ?? '');
    $instructions = sanitize_input($_POST['instructions'] ?? '');

    if (empty($title) || empty($prep_time) || empty($category) || empty($ingredients) || empty($instructions)) {
        $error = 'All fields are required.';
    } else {
        try {
            $update_stmt = $pdo->prepare("UPDATE recipes SET title = ?, prep_time = ?, category = ?, ingredients = ?, instructions = ? WHERE id = ? AND user_id = ?");
            if ($update_stmt->execute([$title, $prep_time, $category, $ingredients, $instructions, $recipe_id, $user_id])) {
                $success = 'Your recipe has been successfully updated!';
                // Refresh local recipe array
                $recipe['title'] = $title;
                $recipe['prep_time'] = $prep_time;
                $recipe['category'] = $category;
                $recipe['ingredients'] = $ingredients;
                $recipe['instructions'] = $instructions;
            } else {
                $error = 'Failed to update recipe. Please try again.';
            }
        } catch (PDOException $e) {
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
    <title>Edit Recipe - CookSphere</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
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
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                    <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                        <a class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm" href="dashboard.php">
                            <i class="bi bi-person-circle me-2"></i><?php echo htmlspecialchars($_SESSION['username']); ?>
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                        <a class="btn btn-outline-danger rounded-pill px-4 fw-semibold shadow-sm" href="auth/logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <a href="dashboard.php" class="btn btn-outline-dark rounded-pill mb-4 px-4 fw-medium">
                    <i class="bi bi-arrow-left me-2"></i> Back to Dashboard
                </a>

                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="bg-dark text-white p-4 text-center">
                        <h2 class="fw-bold mb-0">Update Recipe: <span class="text-primary"><?php echo htmlspecialchars($recipe['title']); ?></span></h2>
                    </div>
                    
                    <div class="card-body p-5">
                        <?php if ($error): ?>
                            <div class="alert alert-danger rounded-3" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($success): ?>
                            <div class="alert alert-success rounded-3" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i> 
                                <?php echo htmlspecialchars($success); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="edit_recipe.php?id=<?php echo htmlspecialchars($recipe_id); ?>">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark">Recipe Title</label>
                                    <input type="text" name="title" class="form-control form-control-lg bg-light border-0" value="<?php echo htmlspecialchars($recipe['title']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark">Preparation Time (mins)</label>
                                    <input type="number" name="prep_time" class="form-control form-control-lg bg-light border-0" value="<?php echo htmlspecialchars($recipe['prep_time']); ?>" required min="1">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-dark">Category</label>
                                    <select name="category" class="form-select form-select-lg bg-light border-0" required>
                                        <option value="Breakfast" <?php echo ($recipe['category'] == 'Breakfast') ? 'selected' : ''; ?>>Breakfast</option>
                                        <option value="Lunch" <?php echo ($recipe['category'] == 'Lunch') ? 'selected' : ''; ?>>Lunch</option>
                                        <option value="Dinner" <?php echo ($recipe['category'] == 'Dinner') ? 'selected' : ''; ?>>Dinner</option>
                                        <option value="Desserts" <?php echo ($recipe['category'] == 'Desserts') ? 'selected' : ''; ?>>Desserts</option>
                                        <option value="Healthy" <?php echo ($recipe['category'] == 'Healthy') ? 'selected' : ''; ?>>Healthy</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-dark">Ingredients <span class="text-muted fw-normal small">(one per line)</span></label>
                                    <textarea name="ingredients" class="form-control form-control-lg bg-light border-0" rows="5" required><?php echo htmlspecialchars($recipe['ingredients']); ?></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-dark">Step-by-Step Instructions</label>
                                    <textarea name="instructions" class="form-control form-control-lg bg-light border-0" rows="6" required><?php echo htmlspecialchars($recipe['instructions']); ?></textarea>
                                </div>
                                <div class="col-12 mt-5">
                                    <button type="submit" class="btn btn-dark btn-lg px-5 rounded-pill fw-bold w-100 shadow-sm transition-hover">Save Changes <i class="bi bi-check2-circle ms-2"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
