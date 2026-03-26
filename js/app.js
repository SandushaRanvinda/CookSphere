// Recipe Data
const dummyRecipes = [
    {
        id: 'r1',
        title: 'Creamy Gourmet Pasta',
        category: 'Italian',
        mealType: ['Dinner', 'Lunch'],
        time: '25 mins',
        rating: 4.9,
        image: 'images/pasta.png',
        desc: 'A beautiful ceramic plate filled with rich, creamy pasta garnished with fresh basil leaves.',
        ingredients: [
            '8 oz fettuccine pasta',
            '1 cup heavy cream',
            '1/2 cup parmesan cheese, grated',
            '2 cloves garlic, minced',
            'Fresh basil for garnish',
            'Salt and black pepper to taste'
        ],
        instructions: [
            'Boil water and cook pasta according to package instructions.',
            'In a skillet, sauté garlic in a little butter until fragrant.',
            'Pour in heavy cream and simmer for 2 minutes.',
            'Stir in grated parmesan cheese until the sauce thickens.',
            'Toss cooked pasta in the sauce.',
            'Garnish with fresh basil and serve immediately.'
        ],
        featured: true
    },
    {
        id: 'r2',
        title: 'Classic Double Cheeseburger',
        category: 'American',
        mealType: ['Lunch', 'Dinner'],
        time: '20 mins',
        rating: 4.8,
        image: 'images/burger.png',
        desc: 'Juicy double beef patty with crispy bacon, melted cheese, and fresh lettuce on a toasted bun.',
        ingredients: [
            '2 beef patties (1/4 lb each)',
            '2 slices cheddar cheese',
            '2 strips crispy bacon',
            'Fresh lettuce and tomato slices',
            '1 brioche bun, toasted',
            'Burger sauce or mayo/ketchup mix'
        ],
        instructions: [
            'Season beef patties generously with salt and pepper.',
            'Grill or pan-fry the patties for 3-4 minutes per side.',
            'Add a slice of cheddar cheese to each patty during the last minute of cooking.',
            'Assemble the burger: spread sauce on the bun, add lettuce, tomato, patties, and bacon.',
            'Top with the other half of the bun and enjoy!'
        ],
        featured: true
    },
    {
        id: 'r3',
        title: 'Vibrant Superfood Salad',
        category: 'Healthy',
        mealType: ['Breakfast', 'Lunch', 'Dinner'],
        time: '15 mins',
        rating: 5.0,
        image: 'images/salad.png',
        desc: 'A healthy bowl filled with quinoa, avocado, and colorful vegetables for your well-being.',
        ingredients: [
            '1 cup cooked quinoa',
            '1 ripe avocado, sliced',
            '1 cup cherry tomatoes, halved',
            '2 cups mixed salad greens',
            '1/4 cup red onion, thinly sliced',
            'Lemon vinaigrette dressing'
        ],
        instructions: [
            'In a large bowl, combine the mixed salad greens and cooked quinoa.',
            'Add the sliced avocado, cherry tomatoes, and red onion.',
            'Drizzle generously with lemon vinaigrette dressing.',
            'Toss gently to combine without mashing the avocado.',
            'Serve chilled or at room temperature as a light, refreshing meal.'
        ],
        featured: true
    },
    {
        id: 'r4',
        title: 'Spicy Garlic Noodles',
        category: 'Asian',
        mealType: ['Lunch', 'Dinner'],
        time: '15 mins',
        rating: 4.9,
        image: 'images/noodles.png',
        desc: 'Quick and easy umami-packed garlic noodles with a spicy kick, garnished with scallions.',
        ingredients: [
            '8 oz dry noodles (ramen or spaghetti)',
            '4 cloves garlic, minced',
            '2 tbsp soy sauce',
            '1 tbsp oyster sauce',
            '1 tsp sesame oil',
            'Red pepper flakes to taste',
            'Chopped scallions for garnish'
        ],
        instructions: [
            'Cook noodles according to package directions, then drain.',
            'In a wok or large pan, heat some oil and sauté minced garlic until golden and fragrant.',
            'Add soy sauce, oyster sauce, sesame oil, and red pepper flakes to the pan to form a sauce.',
            'Toss the cooked noodles into the sauce, stirring continuously until evenly coated.',
            'Garnish with freshly chopped scallions and serve hot.'
        ],
        featured: true
    },
    {
        id: 'r5',
        title: 'Morning Pancakes',
        category: 'American',
        mealType: ['Breakfast', 'Desserts'],
        time: '15 mins',
        rating: 4.7,
        image: 'https://images.unsplash.com/photo-1528207776546-365bb710ee93?auto=format&fit=crop&w=800&q=80',
        desc: 'Fluffy buttermilk pancakes served with maple syrup and fresh berries.',
        ingredients: [
            '1 cup all-purpose flour',
            '1 cup milk',
            '1 egg',
            'Maple syrup and mixed berries'
        ],
        instructions: [
            'Mix flour, milk, and egg in a bowl until smooth.',
            'Pour batter onto a hot lightly oiled griddle.',
            'Cook until bubbles form, then flip and brown perfectly.',
            'Serve hot with maple syrup and fresh berries.'
        ],
        featured: false
    },
    {
        id: 'r6',
        title: 'Molten Chocolate Cake',
        category: 'Dessert',
        mealType: ['Desserts'],
        time: '30 mins',
        rating: 5.0,
        image: 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?auto=format&fit=crop&w=800&q=80',
        desc: 'A decadent and rich chocolate cake with a warm, gooey, molten center.',
        ingredients: [
            '4 oz semi-sweet chocolate',
            '1/2 cup butter',
            '1 cup powdered sugar',
            '2 eggs'
        ],
        instructions: [
            'Melt chocolate and butter over a double boiler.',
            'Whisk in powdered sugar until blended.',
            'Whisk in eggs one at a time.',
            'Bake at 425°F for 13 minutes until sides are firm but center is soft.',
            'Serve warm immediately.'
        ],
        featured: false
    },
    {
        id: 'r7',
        title: 'Artisan Avocado Toast',
        category: 'Breakfast',
        mealType: ['Breakfast'],
        time: '10 mins',
        rating: 4.8,
        image: 'images/avocado.png',
        desc: 'Crusty artisan bread topped with mashed avocado, a perfectly poached egg, and microgreens.',
        ingredients: [
            '2 slices sourdough bread',
            '1 ripe avocado',
            '2 eggs',
            'Lemon juice, salt, and pepper',
            'Microgreens for garnish'
        ],
        instructions: [
            'Toast the sourdough bread slices to your liking.',
            'Mash the avocado with lemon juice, salt, and pepper.',
            'Poach the eggs in simmering water for 3 minutes.',
            'Spread the avocado mix over the toast.',
            'Top each slice with a poached egg and garnish with microgreens.'
        ],
        featured: false
    },
    {
        id: 'r8',
        title: 'Strawberry Smoothie Bowl',
        category: 'Healthy',
        mealType: ['Breakfast', 'Desserts'],
        time: '5 mins',
        rating: 4.9,
        image: 'https://images.unsplash.com/photo-1494597564530-871f2b93ac55?auto=format&fit=crop&w=800&q=80',
        desc: 'A thick, creamy strawberry smoothie topped with fresh fruits, coconut, and chia seeds.',
        ingredients: [
            '2 cups frozen strawberries',
            '1 frozen banana',
            '1/2 cup almond milk',
            'Fresh berries, coconut flakes, chia seeds for topping'
        ],
        instructions: [
            'Blend the frozen strawberries, banana, and almond milk until thick and smooth.',
            'Pour the mixture into a wide bowl.',
            'Arrange fresh berries, coconut flakes, and chia seeds neatly on top.',
            'Serve immediately and enjoy with a spoon!'
        ],
        featured: false
    },
    {
        id: 'r9',
        title: 'Vegan Buddha Bowl',
        category: 'Healthy',
        mealType: ['Lunch', 'Dinner'],
        time: '20 mins',
        rating: 4.7,
        image: 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=800&q=80',
        desc: 'A nourishing bowl packed with roasted sweet potatoes, chickpeas, kale, and tahini dressing.',
        ingredients: [
            '1 cup quinoa, cooked',
            '1 sweet potato, diced and roasted',
            '1 cup canned chickpeas, rinsed',
            '2 cups fresh kale, chopped',
            '3 tbsp tahini dressing'
        ],
        instructions: [
            'Arrange the cooked quinoa at the base of your bowl.',
            'Add a serving of roasted sweet potatoes and chickpeas.',
            'Massage the kale lightly with olive oil and add it to the bowl.',
            'Drizzle everything generously with tahini dressing.',
            'Mix together before eating if desired.'
        ],
        featured: false
    },
    {
        id: 'r10',
        title: 'Teriyaki Chicken Stir Fry',
        category: 'Asian',
        mealType: ['Dinner', 'Lunch'],
        time: '25 mins',
        rating: 4.8,
        image: 'https://images.unsplash.com/photo-1543826173-70651703c5a4?auto=format&fit=crop&w=800&q=80',
        desc: 'Tender chicken pieces and crisp vegetables stir-fried in a rich, sweet, and savory teriyaki sauce.',
        ingredients: [
            '1 lb chicken breast, diced',
            '2 cups broccoli florets',
            '1 red bell pepper, sliced',
            '1/2 cup teriyaki sauce',
            'Cooked white rice or noodles for serving'
        ],
        instructions: [
            'Stir fry the diced chicken in a large pan until cooked through.',
            'Add the broccoli and bell pepper, cooking until slightly tender.',
            'Pour in the teriyaki sauce and simmer until it coats the chicken and veggies.',
            'Serve hot over a bed of white rice or alongside noodles.'
        ],
        featured: false
    },
    {
        id: 'r11',
        title: 'Hearty Beef Stew',
        category: 'American',
        mealType: ['Dinner'],
        time: '2 hours',
        rating: 5.0,
        image: 'https://plus.unsplash.com/premium_photo-1723676421151-6581c33afc55?auto=format&fit=crop&w=800&q=80',
        desc: 'A comforting, slow-cooked beef stew loaded with tender meat, carrots, and potatoes in a rich broth.',
        ingredients: [
            '2 lbs beef chuck, cubed',
            '4 carrots, chopped',
            '3 potatoes, diced',
            '1 onion, diced',
            '4 cups beef broth',
            '2 tbsp tomato paste'
        ],
        instructions: [
            'Sear the beef cubes in a large pot until browned on all sides.',
            'Add the onion, carrots, and potatoes, cooking for another 5 minutes.',
            'Stir in the tomato paste, then pour in the beef broth.',
            'Bring to a boil, then reduce heat, cover, and simmer for 1.5 to 2 hours.',
            'Serve hot with a side of crusty bread.'
        ],
        featured: false
    }
];

// Combine dynamic dbRecipes with dummyRecipes so users see the newly submitted 
// recipes at the top without losing the rich demo content
let recipesData = window.dbRecipes && window.dbRecipes.length > 0 
    ? [...window.dbRecipes, ...dummyRecipes] 
    : dummyRecipes;

// SPA Navigation Logic
function navigate(viewId) {
    // Hide all views
    const views = document.querySelectorAll('.view-section');
    views.forEach(view => {
        view.classList.remove('active-view');
    });

    // Show target view
    const targetView = document.getElementById(viewId + '-view');
    if (targetView) {
        targetView.classList.add('active-view');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Update nav buttons state
    const navBtns = document.querySelectorAll('.nav-btn');
    navBtns.forEach(btn => {
        btn.classList.remove('active');
        if (btn.getAttribute('data-target') === viewId) {
            btn.classList.add('active');
        }
    });

    // Close mobile menu if open
    const navbarCollapse = document.getElementById('navbarContent');
    if (navbarCollapse.classList.contains('show')) {
        if (typeof bootstrap !== 'undefined') {
            const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
            if (bsCollapse) {
                bsCollapse.hide();
            }
        }
    }
}

// Global function to show recipe details
window.viewRecipe = function(recipeId) {
    const recipe = recipesData.find(r => r.id === recipeId);
    if (!recipe) return;

    const detailContainer = document.getElementById('recipe-detail-container');
    
    // Generate ingredients HTML
    const ingredientsHtml = recipe.ingredients.map(ing => `<li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i>${ing}</li>`).join('');
    
    // Generate instructions HTML
    const instructionsHtml = recipe.instructions.map((inst, index) => {
        return `
        <div class="d-flex mb-4">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm me-3" style="width: 40px; height: 40px; flex-shrink: 0;">
                ${index + 1}
            </div>
            <div>
                <p class="mb-0 fs-5">${inst}</p>
            </div>
        </div>
        `;
    }).join('');

    detailContainer.innerHTML = `
        <button class="btn btn-outline-dark rounded-pill mb-4 px-4" onclick="navigate('recipes')">
            <i class="bi bi-arrow-left me-2"></i> Back to Recipes
        </button>
        <div class="row g-5">
            <div class="col-lg-6">
                <img src="${recipe.image}" alt="${recipe.title}" class="img-fluid rounded-4 shadow-lg w-100 object-fit-cover" style="height: 500px;">
            </div>
            <div class="col-lg-6">
                <div class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill mb-3 fw-bold">${recipe.category}</div>
                <h1 class="display-4 fw-bold mb-3">${recipe.title}</h1>
                <p class="lead text-muted mb-4">${recipe.desc}</p>
                
                <div class="d-flex gap-4 mb-5 border-top border-bottom py-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-clock fs-3 text-primary me-2"></i>
                        <div>
                            <p class="mb-0 text-muted small text-uppercase fw-bold">Prep Time</p>
                            <p class="mb-0 fw-bold">${recipe.time}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-star-fill fs-3 text-warning me-2"></i>
                        <div>
                            <p class="mb-0 text-muted small text-uppercase fw-bold">Rating</p>
                            <p class="mb-0 fw-bold">${recipe.rating} / 5.0</p>
                        </div>
                    </div>
                </div>

                <h3 class="fw-bold mb-4 border-bottom pb-2">Ingredients</h3>
                <ul class="list-unstyled fs-5 mb-5">
                    ${ingredientsHtml}
                </ul>
            </div>
        </div>
        
        <div class="row mt-5">
            <div class="col-12">
                <div class="bg-white p-5 rounded-4 shadow-sm border border-light">
                    <h3 class="fw-bold mb-4 border-bottom pb-2">Instructions</h3>
                    <div class="mt-4">
                        ${instructionsHtml}
                    </div>
                </div>
            </div>
        </div>
    `;

    // Un-highlight nav items since we are deep inside a recipe
    const navBtns = document.querySelectorAll('.nav-btn');
    navBtns.forEach(btn => btn.classList.remove('active'));

    navigate('recipe-detail');
};

const createCard = (recipe, index) => `
    <div class="col-md-4" style="animation-delay: ${index * 0.1}s">
        <div class="card recipe-card h-100 border-0 shadow-sm rounded-4 overflow-hidden" onclick="viewRecipe('${recipe.id}')">
            <div class="card-img-wrapper">
                <img src="${recipe.image}" class="card-img-top object-fit-cover" alt="${recipe.title}" height="250">
                <div class="badge bg-white text-dark position-absolute top-0 end-0 m-3 shadow-sm rounded-pill px-3 py-2 fw-bold"><i class="bi bi-star-fill text-warning me-1"></i> ${recipe.rating}</div>
            </div>
            <div class="card-body p-4 d-flex flex-column">
                <p class="text-primary fw-semibold mb-1 small text-uppercase tracking-wider">${recipe.category}</p>
                <h4 class="card-title fw-bold mb-2">${recipe.title}</h4>
                <p class="card-text text-muted mb-4 line-clamp-2">${recipe.desc}</p>
                <div class="d-flex justify-content-between align-items-center mt-auto border-top pt-3">
                    <span class="text-muted small"><i class="bi bi-clock me-1"></i> ${recipe.time}</span>
                    <button class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-medium">View Recipe</button>
                </div>
            </div>
        </div>
    </div>
`;

window.renderAllRecipes = function(category = 'All') {
    const allContainer = document.getElementById('all-recipes-container');
    if (!allContainer) return;

    // State
    let filteredRecipes = [...recipesData];
    if (category !== 'All') {
        filteredRecipes = recipesData.filter(r => r.mealType.includes(category));
    }

    if (filteredRecipes.length === 0) {
        allContainer.innerHTML = '<div class="col-12 text-center py-5"><h4 class="text-muted">No recipes found for this category yet.</h4></div>';
        return;
    }

    allContainer.innerHTML = filteredRecipes.map((r, i) => createCard(r, i)).join('');
};

window.filterByCategory = function(category) {
    // Navigate to recipes view
    navigate('recipes');
    
    // Update active state of filter buttons
    const filterBtns = document.querySelectorAll('.filter-btn');
    filterBtns.forEach(btn => {
        btn.classList.remove('btn-dark', 'active');
        btn.classList.add('btn-outline-dark');
        if (btn.getAttribute('data-category') === category) {
            btn.classList.remove('btn-outline-dark');
            btn.classList.add('btn-dark', 'active');
        }
    });

    // Render the filtered cards
    renderAllRecipes(category);
};

// Initialization and DOM manipulation
document.addEventListener('DOMContentLoaded', () => {
    
    // Generate Home Featured Recipes
    const homeContainer = document.getElementById('home-recipes-container');
    if (homeContainer) {
        homeContainer.innerHTML = recipesData.filter(r => r.featured).slice(0, 3).map((r, i) => createCard(r, i)).join('');
    }

    // Generate All Recipes initially
    renderAllRecipes('All');

    // HTML form will naturally POST to submit_recipe.php

    // Filter Buttons Logic in Recipes Page
    const filterBtns = document.querySelectorAll('.filter-btn');
    filterBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            const category = e.target.getAttribute('data-category');
            filterByCategory(category);
        });
    });

    // Make Navbar background solid on scroll
    window.addEventListener('scroll', () => {
        const nav = document.getElementById('mainNav');
        if (window.scrollY > 50) {
            nav.classList.add('shadow');
        } else {
            nav.classList.remove('shadow');
        }
    });
});
