<?php
session_start();
require_once 'includes/db.php';

// Fetch submitted recipes from the DB
$recipesData = [];
try {
    $stmt = $pdo->query("SELECT r.*, u.username as author FROM recipes r JOIN users u ON r.user_id = u.id ORDER BY r.created_at DESC");
    $db_recipes = $stmt->fetchAll();

    foreach ($db_recipes as $r) {
        $recipesData[] = [
            'id' => 'db_' . $r['id'],
            'title' => $r['title'],
            'category' => $r['category'],
            'mealType' => [$r['category']], // fallback
            'time' => $r['prep_time'] . ' mins',
            'rating' => 5.0, // Default beautiful rating for frontend
            'image' => $r['image'],
            'desc' => 'A delicious ' . htmlspecialchars($r['category']) . ' recipe submitted by ' . htmlspecialchars($r['author']) . '.',
            'ingredients' => array_filter(array_map('trim', explode("\n", $r['ingredients']))),
            'instructions' => array_filter(array_map('trim', explode("\n", $r['instructions']))),
            'featured' => false,
            'author' => $r['author']
        ];
    }
    
    // Mark the 3 most recent recipes as featured for the home grid
    for ($i = 0; $i < min(3, count($recipesData)); $i++) {
        $recipesData[$i]['featured'] = true;
    }
} catch (PDOException $e) {
    // Silently continue if tables aren't perfectly set up
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CookSphere - Recipes for Everyone</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css?v=1.1">
</head>
<body>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg fixed-top shadow-sm" id="mainNav">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3 text-primary d-flex align-items-center gap-2" href="#" onclick="navigate('home')">
                <i class="bi bi-egg-fried"></i> CookSphere
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list fs-1 text-dark"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 fw-medium">
                    <li class="nav-item">
                        <a class="nav-link nav-btn active" href="#" data-target="home" onclick="navigate('home')">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-btn" href="#" data-target="recipes" onclick="navigate('recipes')">Recipes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-btn" href="#" data-target="submit" onclick="navigate('submit')">Submit Recipe</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-btn" href="#" data-target="about" onclick="navigate('about')">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
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
                    <?php else: ?>
                        <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                            <a class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm" href="#" data-bs-toggle="modal" data-bs-target="#authModal">Get Started</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="main-content">

        <!-- ======================= HOME VIEW ======================= -->
        <section id="home-view" class="view-section active-view fade-in">
            <!-- Hero Section -->
            <div class="hero-section text-center position-relative">
                <div class="hero-overlay"></div>
                <div class="container position-relative hero-content text-white" style="z-index: 2;">
                    <h1 class="display-3 fw-bold mb-4">Find Amazing Recipes</h1>
                    <div class="search-box mx-auto bg-white p-2 rounded-pill shadow d-flex">
                        <i class="bi bi-search text-muted ms-3 me-2 fs-5 align-self-center"></i>
                        <input type="text" class="form-control border-0 shadow-none bg-transparent py-2" placeholder="Search by ingredients, dish, or dietary needs...">
                        <button class="btn btn-primary rounded-pill px-4 py-2 fw-semibold">Search</button>
                    </div>
                </div>
            </div>

            <!-- Featured Recipes -->
            <div class="container py-5">
                <div class="text-center mb-5">
                    <h2 class="fw-bold section-title">Featured Recipes</h2>
                    <p class="text-muted">Hand-picked favorites just for you.</p>
                </div>
                
                <div class="row g-4" id="home-recipes-container">
                    <!-- Recipes generated by JS -->
                </div>
            </div>

            <!-- Categories Section (4 small cards) -->
            <div class="bg-light py-5">
                <div class="container">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold">Browse Categories</h3>
                    </div>
                    <div class="row g-4 justify-content-center">
                        <div class="col-6 col-md-3">
                            <div class="category-card p-4 text-center rounded-4 bg-white shadow-sm h-100 cursor-pointer" onclick="filterByCategory('Breakfast')">
                                <div class="icon-circle bg-primary-subtle text-primary mb-3 mx-auto">
                                    <i class="bi bi-cup-hot fs-3"></i>
                                </div>
                                <h5 class="fw-semibold mb-0">Breakfast</h5>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="category-card p-4 text-center rounded-4 bg-white shadow-sm h-100 cursor-pointer" onclick="filterByCategory('Lunch')">
                                <div class="icon-circle bg-success-subtle text-success mb-3 mx-auto">
                                    <i class="bi bi-egg-fried fs-3"></i>
                                </div>
                                <h5 class="fw-semibold mb-0">Lunch</h5>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="category-card p-4 text-center rounded-4 bg-white shadow-sm h-100 cursor-pointer" onclick="filterByCategory('Dinner')">
                                <div class="icon-circle bg-danger-subtle text-danger mb-3 mx-auto">
                                    <i class="bi bi-moon-stars fs-3"></i>
                                </div>
                                <h5 class="fw-semibold mb-0">Dinner</h5>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="category-card p-4 text-center rounded-4 bg-white shadow-sm h-100 cursor-pointer" onclick="filterByCategory('Desserts')">
                                <div class="icon-circle bg-warning-subtle text-warning mb-3 mx-auto">
                                    <i class="bi bi-cake2 fs-3"></i>
                                </div>
                                <h5 class="fw-semibold mb-0">Desserts</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ======================= RECIPES VIEW ======================= -->
        <section id="recipes-view" class="view-section fade-in py-5 mt-5">
            <div class="container">
                <div class="text-center mb-5">
                    <h1 class="display-5 fw-bold section-title">All Recipes</h1>
                    <p class="text-muted">Explore our vast collection of delicious meals.</p>
                </div>

                <!-- Search & Filters -->
                <div class="row justify-content-center mb-5">
                    <div class="col-lg-8">
                        <div class="search-box mx-auto bg-white p-2 rounded-pill shadow-sm border d-flex mb-4">
                            <i class="bi bi-search text-muted ms-3 me-2 fs-5 align-self-center"></i>
                            <input type="text" class="form-control border-0 shadow-none bg-transparent py-2" placeholder="What are you craving?">
                        </div>
                        <div class="d-flex justify-content-center flex-wrap gap-2" id="recipe-filters">
                            <button class="btn btn-dark rounded-pill px-4 filter-btn active" data-category="All">All</button>
                            <button class="btn btn-outline-dark rounded-pill px-4 filter-btn" data-category="Breakfast">Breakfast</button>
                            <button class="btn btn-outline-dark rounded-pill px-4 filter-btn" data-category="Lunch">Lunch</button>
                            <button class="btn btn-outline-dark rounded-pill px-4 filter-btn" data-category="Dinner">Dinner</button>
                            <button class="btn btn-outline-dark rounded-pill px-4 filter-btn" data-category="Desserts">Desserts</button>
                        </div>
                    </div>
                </div>

                <div class="row g-4" id="all-recipes-container">
                    <!-- Recipes generated by JS -->
                </div>
            </div>
        </section>

        <!-- ======================= RECIPE DETAIL VIEW ======================= -->
        <section id="recipe-detail-view" class="view-section fade-in py-5 mt-5">
            <div class="container" id="recipe-detail-container">
                <!-- Recipe details injected by JS -->
            </div>
        </section>

        <!-- ======================= SUBMIT VIEW ======================= -->
        <section id="submit-view" class="view-section fade-in py-5 mt-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                            <div class="bg-primary text-white p-5 text-center position-relative pattern-bg">
                                <h2 class="fw-bold mb-2 position-relative z-1">Share Your Recipe</h2>
                                <p class="mb-0 text-white-50 position-relative z-1">Join the CookSphere community and showcase your culinary skills.</p>
                            </div>
                            <div class="card-body p-5">
                                <?php if(isset($_SESSION['user_id'])): ?>
                                <form id="submitRecipeForm" method="POST" action="submit_recipe.php" enctype="multipart/form-data">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold text-dark">Recipe Title</label>
                                            <input type="text" name="title" class="form-control form-control-lg bg-light border-0" placeholder="e.g. Margherita Pizza" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold text-dark">Preparation Time (mins)</label>
                                            <input type="number" name="prep_time" class="form-control form-control-lg bg-light border-0" placeholder="e.g. 45" required min="1">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-semibold text-dark">Category</label>
                                            <select name="category" class="form-select form-select-lg bg-light border-0" required>
                                                <option value="" selected disabled>Choose a category...</option>
                                                <option value="Breakfast">Breakfast</option>
                                                <option value="Lunch">Lunch</option>
                                                <option value="Dinner">Dinner</option>
                                                <option value="Desserts">Desserts</option>
                                                <option value="Healthy">Healthy</option>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-semibold text-dark">Recipe Image (Optional)</label>
                                            <input type="file" name="image" class="form-control form-control-lg bg-light border-0" accept="image/*">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-semibold text-dark">Ingredients (one per line)</label>
                                            <textarea name="ingredients" class="form-control form-control-lg bg-light border-0" rows="5" placeholder="2 cups flour&#10;1 tsp salt&#10;..." required></textarea>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-semibold text-dark">Step-by-Step Instructions</label>
                                            <textarea name="instructions" class="form-control form-control-lg bg-light border-0" rows="6" placeholder="1. Preheat oven...&#10;2. Mix ingredients..." required></textarea>
                                        </div>
                                        <div class="col-12 mt-5">
                                            <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill fw-bold w-100 shadow-sm transition-hover">Publish Recipe <i class="bi bi-arrow-right ms-2"></i></button>
                                        </div>
                                    </div>
                                </form>
                                <?php else: ?>
                                <div class="text-center py-5">
                                    <i class="bi bi-lock text-muted mb-3" style="font-size: 4rem;"></i>
                                    <h4 class="fw-bold">Login Required</h4>
                                    <p class="text-muted mb-4 fs-5">You must be proudly signed in to share your recipes with our community.</p>
                                    <button class="btn btn-primary rounded-pill px-5 py-2 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#authModal">Login to Continue</button>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ======================= ABOUT VIEW ======================= -->
        <section id="about-view" class="view-section fade-in py-5 mt-5">
            <div class="container">
                <div class="row align-items-center mb-5 mt-4">
                    <div class="col-lg-6 pe-lg-5 mb-4 mb-lg-0">
                        <div class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill mb-3 fw-bold">Our Story</div>
                        <h2 class="display-4 fw-bold mb-4">Cooking made simple, for everyone.</h2>
                        <p class="lead text-muted mb-4">CookSphere started as a simple idea: bringing people together through the joy of cooking. We believe that everyone can create amazing meals with the right inspiration.</p>
                        <p class="text-muted mb-4">Our platform is designed to be a digital cookbook where culinary enthusiasts from around the globe share their favorite recipes, tips, and experiences.</p>
                        <button class="btn btn-outline-dark rounded-pill px-4 py-2 fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#readMoreAbout" aria-expanded="false" aria-controls="readMoreAbout">
                            Read More
                        </button>
                        <div class="collapse mt-3" id="readMoreAbout">
                            <div class="card card-body border-0 bg-light rounded-4 shadow-sm p-4">
                                <h5 class="fw-bold text-dark mb-2">Our Founding Journey</h5>
                                <p class="text-muted fs-6 mb-3">CookSphere was conceptualized in a small kitchen by three friends passionate about bringing worldwide cuisines into their apartments. We noticed a gap between complex, gate-kept gourmet recipes and everyday home-cooked meals.</p>
                                <p class="text-muted fs-6 mb-0">Today, we've bridged that gap by collaborating with a robust community who believe that great food doesn't have to be complicated. Whether you're a seasoned chef or a microwave connoisseur, there's a place for you at our table.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="position-relative">
                            <img src="images/pasta.png" alt="About Us" class="img-fluid rounded-4 shadow-lg object-fit-cover w-100" style="height: 400px;">
                            <div class="position-absolute bottom-0 start-0 bg-white p-4 rounded-3 shadow-lg m-4 glass-card border-0">
                                <h3 class="fw-bold mb-0 text-primary">10k+</h3>
                                <p class="mb-0 text-muted small fw-semibold text-uppercase">Active Home Chefs</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Platform Details Section -->
                <div class="mt-5 pt-5 pb-5 border-top">
                    <div class="row align-items-center mb-5">
                        <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                            <h3 class="fw-bold mb-4">What Makes CookSphere Special?</h3>
                            <ul class="list-unstyled fs-5">
                                <li class="mb-4 d-flex align-items-start">
                                    <i class="bi bi-search text-primary fs-3 me-3 mt-1"></i>
                                    <div>
                                        <h5 class="fw-bold mb-1">Smart Search & Filtering</h5>
                                        <p class="text-muted fs-6">Easily find the perfect meal for any occasion with our advanced category filtering and search tools. Whether you need a quick breakfast or a hearty dinner, we've got you covered.</p>
                                    </div>
                                </li>
                                <li class="mb-4 d-flex align-items-start">
                                    <i class="bi bi-cloud-arrow-up text-primary fs-3 me-3 mt-1"></i>
                                    <div>
                                        <h5 class="fw-bold mb-1">Community Driven</h5>
                                        <p class="text-muted fs-6">Share your own culinary masterpieces with the world. Our easy-to-use submission system allows you to publish your recipes, complete with ingredients, steps, and preparation times.</p>
                                    </div>
                                </li>
                                <li class="mb-4 d-flex align-items-start">
                                    <i class="bi bi-star text-primary fs-3 me-3 mt-1"></i>
                                    <div>
                                        <h5 class="fw-bold mb-1">Honest Ratings</h5>
                                        <p class="text-muted fs-6">Discover the most loved recipes through our transparent rating system. Try out the highest-rated dishes and leave your own feedback to help fellow home chefs.</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-6 order-lg-1 pe-lg-5">
                            <div class="p-5 bg-light rounded-4 shadow-sm border border-light h-100 d-flex flex-column justify-content-center">
                                <h4 class="fw-bold text-dark mb-3">Our Mission</h4>
                                <p class="text-muted fs-5 fst-italic">"To democratize culinary knowledge and empower everyone to cook delicious, healthy, and accessible meals at home."</p>
                                <hr class="my-4 border-secondary opacity-25">
                                <div class="d-flex align-items-center text-primary fw-bold">
                                    <i class="bi bi-globe fs-4 me-2"></i> Join thousands of users worldwide.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white pt-5 pb-4 mt-auto">
        <div class="container">
            <div class="row align-items-center border-bottom border-secondary pb-4 mb-4 g-4">
                <div class="col-md-6 text-center text-md-start">
                    <a class="navbar-brand fw-bold fs-4 text-white d-flex align-items-center justify-content-center justify-content-md-start gap-2" href="#">
                        <i class="bi bi-egg-fried text-primary"></i> CookSphere
                    </a>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="d-flex justify-content-center justify-content-md-end gap-3">
                        <a href="#" class="text-white text-decoration-none hover-primary"><i class="bi bi-facebook fs-5"></i></a>
                        <a href="#" class="text-white text-decoration-none hover-primary"><i class="bi bi-twitter-x fs-5"></i></a>
                        <a href="#" class="text-white text-decoration-none hover-primary"><i class="bi bi-instagram fs-5"></i></a>
                        <a href="#" class="text-white text-decoration-none hover-primary"><i class="bi bi-pinterest fs-5"></i></a>
                    </div>
                </div>
            </div>
            <div class="row text-center text-md-start">
                <div class="col-12 text-center text-white-50 small">
                    <p class="mb-0">© 2026 CookSphere. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <?php if (!isset($_SESSION['user_id'])): ?>
    <!-- Auth Modal -->
    <div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-5 text-center pattern-bg position-relative">
                    <div class="mb-4 position-relative z-1">
                        <i class="bi bi-egg-fried text-primary" style="font-size: 3.5rem;"></i>
                    </div>
                    <h3 class="fw-bold mb-3 position-relative z-1" id="authModalLabel">Welcome to CookSphere!</h3>
                    <p class="text-muted mb-4 position-relative z-1">Sign in to discover, share, and manage your favorite recipes.</p>
                    <div class="d-grid gap-3 position-relative z-1">
                        <a href="auth/login.php" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm transition-hover">Login</a>
                        <a href="auth/register.php" class="btn btn-outline-dark btn-lg rounded-pill fw-bold transition-hover">Register</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Inject PHP populated recipes data for JS to consume -->
    <script>
        window.dbRecipes = <?php echo json_encode($recipesData); ?>;
    </script>

    <!-- Main JS Application Logic -->
    <script src="js/app.js?v=2"></script>

</body>
</html>
