<div class="container my-5">
    <h1 class="mb-4"><?php echo $page_title; ?></h1>
    
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

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Thông tin giao hàng</h5>
                </div>
                <div class="card-body">
                    <form action="index.php?controller=cart&action=placeOrder" method="post">
                        <div class="form-group">
                            <label for="shipping_name">Họ tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="shipping_name" name="shipping_name" 
                                   value="<?php echo isset($user_info['fullname']) ? $user_info['fullname'] : ''; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="shipping_email">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="shipping_email" name="shipping_email" 
                                   value="<?php echo isset($user_info['email']) ? $user_info['email'] : ''; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="shipping_phone">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="shipping_phone" name="shipping_phone" 
                                   value="<?php echo isset($user_info['phone']) ? $user_info['phone'] : ''; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="shipping_address">Địa chỉ giao hàng <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="shipping_address" name="shipping_address" rows="3" required><?php echo isset($user_info['address']) ? $user_info['address'] : ''; ?></textarea>
                        </div>
                        
                        <hr>
                        
                        <div class="form-group">
                            <label>Phương thức thanh toán <span class="text-danger">*</span></label>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="cod" name="payment_method" value="cod" class="custom-control-input" checked>
                                <label class="custom-control-label" for="cod">Thanh toán khi nhận hàng (COD)</label>
                            </div>
                            <div class="custom-control custom-radio mt-2">
                                <input type="radio" id="bank_transfer" name="payment_method" value="bank_transfer" class="custom-control-input">
                                <label class="custom-control-label" for="bank_transfer">Chuyển khoản ngân hàng</label>
                            </div>
                        </div>
                        
                        <div class="text-right mt-4">
                            <a href="index.php?controller=cart" class="btn btn-outline-secondary mr-2">Quay lại giỏ hàng</a>
                            <button type="submit" name="place_order" class="btn btn-primary">Đặt hàng</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Đơn hàng của bạn</h5>
                </div>
                <div class="card-body">
                    <div class="order-summary">
                        <?php 
                        $total = 0;
                        foreach($_SESSION['cart'] as $item): 
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                        ?>
                        <div class="d-flex justify-content-between mb-2">
                            <span><?php echo $item['name']; ?> x <?php echo $item['quantity']; ?></span>
                            <span><?php echo number_format($subtotal, 0, ',', '.'); ?>₫</span>
                        </div>
                        <?php endforeach; ?>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Tạm tính:</strong>
                            <span><?php echo number_format($total, 0, ',', '.'); ?>₫</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Phí vận chuyển:</strong>
                            <span>Miễn phí</span>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between">
                            <strong>Tổng cộng:</strong>
                            <strong class="text-primary"><?php echo number_format($total, 0, ',', '.'); ?>₫</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>