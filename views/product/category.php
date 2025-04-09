<div class="container my-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light">
            <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $category->name; ?></li>
        </ol>
    </nav>
    
    <h1 class="mb-4"><?php echo $page_title; ?></h1>
    
    <?php if($num > 0): ?>
        <div class="row">
            <?php while($product_item = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <img src="<?php echo !empty($product_item['image']) ? $product_item['image'] : 'assets/images/no-image.jpg'; ?>" 
                             alt="<?php echo $product_item['name']; ?>" 
                             class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="index.php?controller=product&action=detail&id=<?php echo $product_item['id']; ?>">
                                    <?php echo $product_item['name']; ?>
                                </a>
                            </h5>
                            <p class="card-text text-danger font-weight-bold">
                                <?php echo number_format($product_item['price'], 0, ',', '.'); ?>₫
                            </p>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <form action="index.php?controller=cart&action=add" method="post">
                                <input type="hidden" name="product_id" value="<?php echo $product_item['id']; ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-shopping-cart"></i> Thêm vào giỏ
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        
        <?php if($total_pages > 1): ?>
            <nav aria-label="Page navigation" class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php if($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="index.php?controller=product&action=category&id=<?php echo $category_id; ?>&page=<?php echo $page-1; ?>">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                            <a class="page-link" href="index.php?controller=product&action=category&id=<?php echo $category_id; ?>&page=<?php echo $i; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="index.php?controller=product&action=category&id=<?php echo $category_id; ?>&page=<?php echo $page+1; ?>">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php else: ?>
        <div class="alert alert-info">
            Không có sản phẩm nào trong danh mục này.
        </div>
    <?php endif; ?>
</div>