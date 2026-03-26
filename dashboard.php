<?php
// dashboard.php - User dashboard
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Check if user is logged in
if (!is_logged_in()) {
    header("Location: auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle Recipe Deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_recipe_id'])) {
    $delete_id = intval($_POST['delete_recipe_id']);
    try {
        // Securely delete only if the active user owns the recipe
        $del_stmt = $pdo->prepare("DELETE FROM recipes WHERE id = ? AND user_id = ?");
        if ($del_stmt->execute([$delete_id, $user_id])) {
            header("Location: dashboard.php?msg=deleted");
            exit;
        }
    } catch (PDOException $e) {
        $error = "Failed to delete recipe.";
    }
}

// Fetch user info
$stmt = $pdo->prepare("SELECT username, email, created_at FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Fetch user's recipes
$stmt_recipes = $pdo->prepare("SELECT id, title, category, prep_time, image, created_at FROM recipes WHERE user_id = ? ORDER BY created_at DESC");
$stmt_recipes->execute([$user_id]);
$recipes = $stmt_recipes->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CookSphere</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
                        <a class="nav-link" href="index.php#recipes-view">Recipes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="submit_recipe.php">Submit Recipe</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#about-view">About</a>
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
        
        <div class="row mb-5">
            <div class="col-12">
                <h1 class="fw-bold mb-4">My Dashboard</h1>
                
                <!-- User Info Card -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-body p-4 d-flex align-items-center">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-4 shadow-sm" style="width: 80px; height: 80px; font-size: 2.5rem;">
                            <i class="bi bi-person"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-1">Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h3>
                            <p class="text-muted mb-0"><i class="bi bi-envelope me-2"></i><?php echo htmlspecialchars($user['email']); ?></p>
                            <p class="text-muted small mt-1 mb-0"><i class="bi bi-calendar3 me-2"></i>Member since <?php echo date('M Y', strtotime($user['created_at'])); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        </div>

        <div class="row mb-4 align-items-center">
            <div class="col-8">
                <h3 class="fw-bold mb-0">My Uploaded Recipes</h3>
            </div>
            <div class="col-4 text-end">
                <a href="submit_recipe.php" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                    <i class="bi bi-plus-lg me-2"></i>Upload Recipe
                </a>
            </div>
        </div>

        <!-- Recipes Table -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <?php if (count($recipes) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                <th class="px-4 py-3 fw-semibold">Recipe</th>
                                <th class="py-3 fw-semibold">Category</th>
                                <th class="py-3 fw-semibold">Prep Time</th>
                                <th class="py-3 fw-semibold">Date Uploaded</th>
                                <th class="px-4 py-3 fw-semibold text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recipes as $recipe): ?>
                                <tr>
                                    <td class="px-4 py-3 fw-medium text-dark">
                                        <div class="d-flex align-items-center">
                                            <img src="<?php echo htmlspecialchars($recipe['image'] ?? 'images/default-recipe.png'); ?>" alt="Dish" class="rounded-3 shadow-sm me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                            <?php echo htmlspecialchars($recipe['title']); ?>
                                        </div>
                                    </td>
                                        <td class="py-3"><span class="badge bg-secondary-subtle text-secondary fw-semibold border"><?php echo htmlspecialchars($recipe['category']); ?></span></td>
                                        <td class="py-3"><i class="bi bi-clock me-1 text-muted"></i> <?php echo htmlspecialchars($recipe['prep_time']); ?> mins</td>
                                        <td class="py-3 text-muted small"><?php echo date('M d, Y', strtotime($recipe['created_at'])); ?></td>
                                        <td class="px-4 py-3 text-end">
                                            <a href="edit_recipe.php?id=<?php echo $recipe['id']; ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 me-2 fw-medium">
                                                <i class="bi bi-pencil-square me-1"></i> Edit
                                            </a>
                                            <form id="delete-form-<?php echo $recipe['id']; ?>" method="POST" action="dashboard.php" class="d-inline">
                                                <input type="hidden" name="delete_recipe_id" value="<?php echo $recipe['id']; ?>">
                                                <button type="button" onclick="confirmDelete(<?php echo $recipe['id']; ?>)" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-medium">
                                                    <i class="bi bi-trash me-1"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="p-5 text-center">
                        <div class="mb-3">
                            <i class="bi bi-egg text-muted" style="font-size: 4rem;"></i>
                        </div>
                        <h4 class="fw-bold text-dark">No Recipes Yet</h4>
                        <p class="text-muted mb-4">You haven't uploaded any recipes yet. Start bringing flavor to the community!</p>
                        <a href="submit_recipe.php" class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold">Upload Your First Recipe</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Custom color matches CookSphere primary styling
        const primaryColor = '#ff6b6b';
        
        <?php if (isset($_GET['msg'])): ?>
            document.addEventListener('DOMContentLoaded', function() {
                <?php if ($_GET['msg'] === 'published'): ?>
                    Swal.fire({
                        icon: 'success',
                        title: 'Published!',
                        text: 'Your recipe has been successfully published.',
                        confirmButtonColor: primaryColor,
                        customClass: { popup: 'rounded-4' }
                    });
                <?php elseif ($_GET['msg'] === 'deleted'): ?>
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'Recipe successfully removed from your dashboard.',
                        confirmButtonColor: primaryColor,
                        customClass: { popup: 'rounded-4' }
                    });
                <?php endif; ?>
            });
        <?php endif; ?>

        function confirmDelete(recipeId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                customClass: { popup: 'rounded-4' }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + recipeId).submit();
                }
            })
        }
    </script>
</body>
</html>
