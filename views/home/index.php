<div class="hero-banner mb-4">
    <div id="heroCarousel" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#heroCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#heroCarousel" data-slide-to="1"></li>
            <li data-target="#heroCarousel" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="assets/images/banner1.jpg" class="d-block w-100" alt="Watch Banner 1">
                <div class="carousel-caption d-none d-md-block">
                    <h2>Bộ sưu tập đồng hồ cao cấp</h2>
                    <p>Khám phá những mẫu đồng hồ sang trọng và đẳng cấp</p>
                    <a href="index.php?controller=product" class="btn btn-primary">Mua ngay</a>
                </div>
            </div>
            <div class="carousel-item">
                <img src="assets/images/banner2.jpg" class="d-block w-100" alt="Watch Banner 2">
                <div class="carousel-caption d-none d-md-block">
                    <h2>Đồng hồ thông minh</h2>
                    <p>Kết nối cuộc sống của bạn với công nghệ hiện đại</p>
                    <a href="index.php?controller=product&action=category&id=2" class="btn btn-primary">Xem thêm</a>
                </div>
            </div>
            <div class="carousel-item">
                <img src="assets/images/banner3.jpg" class="d-block w-100" alt="Watch Banner 3">
                <div class="carousel-caption d-none d-md-block">
                    <h2>Đồng hồ thể thao</h2>
                    <p>Mạnh mẽ và bền bỉ cho mọi hoạt động</p>
                    <a href="index.php?controller=product&action=category&id=3" class="btn btn-primary">Khám phá</a>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#heroCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#heroCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</div>

<!-- Featured Categories -->
<div class="featured-categories mb-5">
    <h2 class="section-title text-center mb-4">Danh mục nổi bật</h2>
    <div class="row">
        <?php if(isset($categories) && $categories->rowCount() > 0): ?>
            <?php 
            // Reset the pointer to beginning
            $categories->execute();
            $count = 0;
            while($category_item = $categories->fetch(PDO::FETCH_ASSOC) && $count < 3): 
                $count++;
            ?>
                <div class="col-md-4">
                    <div class="category-card">
                        <a href="index.php?controller=product&action=category&id=<?php echo $category_item['id']; ?>">
                            <div class="card">
                                <img src="assets/images/categories/category<?php echo $category_item['id']; ?>.jpg" class="card-img-top" alt="<?php echo $category_item['name']; ?>">
                                <div class="card-body text-center">
                                    <h5 class="card-title"><?php echo $category_item['name']; ?></h5>
                                    <p class="card-text"><?php echo substr($category_item['description'], 0, 100) . '...'; ?></p>
                                    <a href="index.php?controller=product&action=category&id=<?php echo $category_item['id']; ?>" class="btn btn-outline-primary">Xem sản phẩm</a>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Featured Products -->
<div class="featured-products mb-5">
    <h2 class="section-title text-center mb-4">Sản phẩm nổi bật</h2>
    <div class="row">
        <?php if(isset($featured_products) && $featured_products->rowCount() > 0): ?>
            <?php while($product = $featured_products->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="product-card">
                        <div class="card h-100">
                            <a href="index.php?controller=product&action=detail&id=<?php echo $product['id']; ?>">
                                <img src="<?php echo $product['image']; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="index.php?controller=product&action=detail&id=<?php echo $product['id']; ?>">
                                        <?php echo $product['name']; ?>
                                    </a>
                                </h5>
                                <p class="card-text category"><?php echo $product['category_name']; ?></p>
                                <p class="card-text price"><?php echo number_format($product['price'], 0, ',', '.'); ?> ₫</p>
                            </div>
                            <div class="card-footer">
                                <form action="index.php?controller=cart&action=add" method="POST" class="add-to-cart-form">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-shopping-cart"></i> Thêm vào giỏ
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">Không có sản phẩm nào.</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Banner -->
<div class="promo-banner mb-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card bg-dark text-white">
                <img src="assets/images/promo-banner.jpg" class="card-img" alt="Promotion">
                <div class="card-img-overlay d-flex flex-column justify-content-center text-center">
                    <h3 class="card-title">Giảm giá lên đến 30%</h3>
                    <p class="card-text">Ưu đãi đặc biệt cho các sản phẩm đồng hồ cao cấp</p>
                    <a href="index.php?controller=product" class="btn btn-light">Mua ngay</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Brand Logos -->
<div class="brand-logos mb-5">
    <h2 class="section-title text-center mb-4">Thương hiệu</h2>
    <div class="row">
        <div class="col-md-2 col-4 text-center mb-3">
            <img src="assets/images/brands/brand1.png" alt="Brand 1" class="img-fluid">
        </div>
        <div class="col-md-2 col-4 text-center mb-3">
            <img src="assets/images/brands/brand2.png" alt="Brand 2" class="img-fluid">
        </div>
        <div class="col-md-2 col-4 text-center mb-3">
            <img src="assets/images/brands/brand3.png" alt="Brand 3" class="img-fluid">
        </div>
        <div class="col-md-2 col-4 text-center mb-3">
            <img src="assets/images/brands/brand4.png" alt="Brand 4" class="img-fluid">
        </div>
        <div class="col-md-2 col-4 text-center mb-3">
            <img src="assets/images/brands/brand5.png" alt="Brand 5" class="img-fluid">
        </div>
        <div class="col-md-2 col-4 text-center mb-3">
            <img src="assets/images/brands/brand6.png" alt="Brand 6" class="img-fluid">
        </div>
    </div>
</div>