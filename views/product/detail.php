<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
        <li class="breadcrumb-item"><a href="index.php?controller=product">Sản phẩm</a></li>
        <li class="breadcrumb-item"><a href="index.php?controller=product&action=category&id=<?php echo $product->category_id; ?>"><?php echo $product->category_name; ?></a></li>
        <li class="breadcrumb-item active" aria-current="page"><?php echo $product->name; ?></li>
    </ol>
</nav>

<div class="product-detail mb-5">
    <div class="row">
        <!-- Product Image -->
        <div class="col-md-6 mb-4">
            <div class="product-image">
                <img src="<?php echo $product->image; ?>" class="img-fluid" alt="<?php echo $product->name; ?>">
            </div>
        </div>
        
        <!-- Product Info -->
        <div class="col-md-6">
            <h1 class="product-title"><?php echo $product->name; ?></h1>
            <p class="product-category">Danh mục: <a href="index.php?controller=product&action=category&id=<?php echo $product->category_id; ?>"><?php echo $product->category_name; ?></a></p>
            <div class="product-price mb-3">
                <span class="price"><?php echo number_format($product->price, 0, ',', '.'); ?> ₫</span>
            </div>
            <div class="product-description mb-4">
                <h4>Mô tả sản phẩm</h4>
                <p><?php echo nl2br($product->description); ?></p>
            </div>
            <form action="index.php?controller=cart&action=add" method="POST" class="add-to-cart-form mb-4">
                <div class="form-group">
                    <label for="quantity">Số lượng:</label>
                    <div class="input-group" style="width: 150px;">
                        <div class="input-group-prepend">
                            <button type="button" class="btn btn-outline-secondary" onclick="decreaseQuantity()">-</button>
                        </div>
                        <input type="number" name="quantity" id="quantity" class="form-control text-center" value="1" min="1" max="10">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-outline-secondary" onclick="increaseQuantity()">+</button>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-shopping-cart"></i> Thêm vào giỏ hàng
                </button>
                <button type="button" class="btn btn-outline-secondary btn-lg">
                    <i class="far fa-heart"></i> Yêu thích
                </button>
            </form>
            <div class="product-meta">
                <p><strong>Mã sản phẩm:</strong> WS-<?php echo str_pad($product->id, 4, '0', STR_PAD_LEFT); ?></p>
                <p><strong>Tình trạng:</strong> <span class="badge badge-success">Còn hàng</span></p>
                <div class="social-share mt-3">
                    <p><strong>Chia sẻ:</strong></p>
                    <a href="#" class="btn btn-sm btn-facebook"><i class="fab fa-facebook-f"></i> Facebook</a>
                    <a href="#" class="btn btn-sm btn-twitter"><i class="fab fa-twitter"></i> Twitter</a>
                    <a href="#" class="btn btn-sm btn-instagram"><i class="fab fa-instagram"></i> Instagram</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Related Products -->
<div class="related-products mb-5">
    <h3 class="section-title">Sản phẩm liên quan</h3>
    <div class="row">
        <?php 
        // Get related products by category
        $related_product = new Product();
        $related_products = $related_product->readByCategory($product->category_id, 4, 1);
        
        if($related_products->rowCount() > 0):
            while($related = $related_products->fetch(PDO::FETCH_ASSOC)):
                // Skip current product
                if($related['id'] == $product->id) continue;
        ?>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="product-card">
                    <div class="card h-100">
                        <a href="index.php?controller=product&action=detail&id=<?php echo $related['id']; ?>">
                            <img src="<?php echo $related['image']; ?>" class="card-img-top" alt="<?php echo $related['name']; ?>">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="index.php?controller=product&action=detail&id=<?php echo $related['id']; ?>">
                                    <?php echo $related['name']; ?>
                                </a>
                            </h5>
                            <p class="card-text category"><?php echo $related['category_name']; ?></p>
                            <p class="card-text price"><?php echo number_format($related['price'], 0, ',', '.'); ?> ₫</p>
                        </div>
                        <div class="card-footer">
                            <form action="index.php?controller=cart&action=add" method="POST" class="add-to-cart-form">
                                <input type="hidden" name="product_id" value="<?php echo $related['id']; ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-shopping-cart"></i> Thêm vào giỏ
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php 
            endwhile;
        endif;
        ?>
    </div>
</div>

<script>
function decreaseQuantity() {
    var quantityInput = document.getElementById('quantity');
    var currentValue = parseInt(quantityInput.value);
    if (currentValue > 1) {
        quantityInput.value = currentValue - 1;
    }
}

function increaseQuantity() {
    var quantityInput = document.getElementById('quantity');
    var currentValue = parseInt(quantityInput.value);
    if (currentValue < 10) {
        quantityInput.value = currentValue + 1;
    }
}
</script>