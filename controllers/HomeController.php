<?php
class HomeController {
    public function index() {
        // Get featured products
        $product = new Product();
        $featured_products = $product->readAll(8);
        
        // Get categories
        $category = new Category();
        $categories = $category->readAll();
        
        // Include view
        include 'views/layouts/main.php';
        include 'views/home/index.php';
        include 'views/layouts/footer.php';
    }
}