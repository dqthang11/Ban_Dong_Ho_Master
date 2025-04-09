<div class="container my-5">
    <h1 class="mb-4"><?php echo $page_title; ?></h1>
    
    <?php if(isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php 
                echo $_SESSION['success_message']; 
                unset($_SESSION['success_message']);
            ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php 
                echo $_SESSION['error_message']; 
                unset($_SESSION['error_message']);
            ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>
    
    <?php if(!isset($_SESSION['cart']) || empty($_SESSION['cart'])): ?>
        <div class="text-center my-5">
            <div class="mb-4">
                <i class="fas fa-shopping-cart fa-5x text-muted"></i>
            </div>
            <h3>Giỏ hàng của bạn đang trống</h3>
            <p class="text-muted">Hãy thêm sản phẩm vào giỏ hàng để tiếp tục mua sắm</p>
            <a href="index.php?controller=product" class="btn btn-primary">Tiếp tục mua sắm</a>
        </div>
    <?php else: ?>
        <form action="index.php?controller=cart&action=update" method="post">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Giá</th>
                            <th width="120">Số lượng</th>
                            <th class="text-right">Thành tiền</th>
                            <th width="50"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0;
                        foreach($_SESSION['cart'] as $item): 
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                        ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo !empty($item['image']) ? $item['image'] : 'assets/images/no-image.jpg'; ?>" 
                                         alt="<?php echo $item['name']; ?>" 
                                         class="img-thumbnail mr-3" style="width: 60px;">
                                    <div>
                                        <h6 class="mb-0">
                                            <a href="index.php?controller=product&action=detail&id=<?php echo $item['id']; ?>">
                                                <?php echo $item['name']; ?>
                                            </a>
                                        </h6>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo number_format($item['price'], 0, ',', '.'); ?>₫</td>
                            <td>
                                <input type="number" name="quantity[<?php echo $item['id']; ?>]" 
                                       value="<?php echo $item['quantity']; ?>" 
                                       min="1" class="form-control form-control-sm">
                            </td>
                            <td class="text-right"><?php echo number_format($subtotal, 0, ',', '.'); ?>₫</td>
                            <td>
                                <a href="index.php?controller=cart&action=remove&id=<?php echo $item['id']; ?>" 
                                   class="btn btn-sm btn-outline-danger" 
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-right"><strong>Tổng cộng:</strong></td>
                            <td class="text-right"><strong><?php echo number_format($total, 0, ',', '.'); ?>₫</strong></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="d-flex justify-content-between mt-4">
                <div>
                    <a href="index.php?controller=cart&action=clear" class="btn btn-outline-danger">
                        <i class="fas fa-trash"></i> Xóa giỏ hàng
                    </a>
                    <button type="submit" name="update_cart" class="btn btn-outline-secondary ml-2">
                        <i class="fas fa-sync"></i> Cập nhật giỏ hàng
                    </button>
                </div>
                <div>
                    <a href="index.php?controller=product" class="btn btn-outline-primary mr-2">
                        <i class="fas fa-arrow-left"></i> Tiếp tục mua sắm
                    </a>
                    <a href="index.php?controller=cart&action=checkout" class="btn btn-success">
                        <i class="fas fa-check"></i> Thanh toán
                    </a>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>