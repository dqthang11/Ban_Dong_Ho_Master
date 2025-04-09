<?php
// Check if there are products
if($num > 0) {
?>
    <!-- Product List -->
    <div class="container mt-4">
        <h1><?php echo $page_title; ?></h1>
        
        <!-- Search Form -->
        <div class="mb-4">
            <form action="index.php" method="GET" class="form-inline">
                <input type="hidden" name="controller" value="product">
                <input type="hidden" name="action" value="search">
                <div class="input-group w-100">
                    <input type="text" name="q" class="form-control" placeholder="Tìm kiếm sản phẩm..." value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fa fa-search"></i> Tìm kiếm
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="row">
            <!-- Sidebar - Categories -->
            <div class="col-md-3">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Danh mục sản phẩm</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php
                        while($category_row = $categories->fetch(PDO::FETCH_ASSOC)) {
                            echo '<a href="index.php?controller=product&action=category&id=' . $category_row['id'] . '" class="list-group-item list-group-item-action">' . $category_row['name'] . '</a>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            
            <!-- Product Grid -->
            <div class="col-md-9">
                <div class="row">
                    <?php
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <img class="card-img-top" src="<?php echo !empty($image) ? 'assets/images/products/' . $image : 'assets/images/no-image.jpg'; ?>" alt="<?php echo $name; ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $name; ?></h5>
                                    <p class="card-text text-danger font-weight-bold"><?php echo number_format($price, 0, ',', '.'); ?> VNĐ</p>
                                    <p class="card-text small text-muted"><?php echo substr($description, 0, 100) . '...'; ?></p>
                                </div>
                                <div class="card-footer bg-white border-top-0">
                                    <div class="btn-group w-100">
                                        <a href="index.php?controller=product&action=detail&id=<?php echo $id; ?>" class="btn btn-outline-primary">
                                            <i class="fa fa-eye"></i> Chi tiết
                                        </a>
                                        <form action="index.php?controller=cart&action=add" method="POST">
                                            <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-shopping-cart"></i> Mua ngay
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                
                <!-- Pagination -->
                <?php if($total_pages > 1): ?>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php if($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="index.php?controller=product&page=<?php echo ($page - 1); ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <?php for($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                            <a class="page-link" href="index.php?controller=product&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                        <?php endfor; ?>
                        
                        <?php if($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="index.php?controller=product&page=<?php echo ($page + 1); ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php
} else {
?>
    <div class="container mt-4">
        <div class="alert alert-info">
            Không có sản phẩm nào.
        </div>
    </div>
<?php
}
?>