<div class="container mt-4 mb-5">
    <h1>Thông tin tài khoản</h1>

    <?php if(isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success">
        <?php 
        echo $_SESSION['success_message']; 
        unset($_SESSION['success_message']);
        ?>
    </div>
    <?php endif; ?>
    
    <?php if(isset($errors) && !empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach($errors as $error): ?>
            <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Thông tin cá nhân</h5>
                </div>
                <div class="card-body">
                    <p><strong>Tên đăng nhập:</strong> <?php echo $user->username; ?></p>
                    <p><strong>Họ tên:</strong> <?php echo $user->fullname; ?></p>
                    <p><strong>Email:</strong> <?php echo $user->email; ?></p>
                    <p><strong>Số điện thoại:</strong> <?php echo $user->phone; ?></p>
                    <p><strong>Địa chỉ:</strong> <?php echo $user->address; ?></p>
                    <p><strong>Ngày đăng ký:</strong> <?php echo date('d/m/Y', strtotime($user->created_at)); ?></p>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Cập nhật thông tin</h5>
                </div>
                <div class="card-body">
                    <form action="index.php?controller=user&action=profile" method="POST">
                        <div class="form-group">
                            <label for="fullname">Họ tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo $user->fullname; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $user->email; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Số điện thoại</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $user->phone; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="address">Địa chỉ</label>
                            <textarea class="form-control" id="address" name="address" rows="3"><?php echo $user->address; ?></textarea>
                        </div>
                        
                        <hr>
                        
                        <h5>Đổi mật khẩu</h5>
                        <p class="text-muted small">Chỉ điền nếu muốn đổi mật khẩu</p>
                        
                        <div class="form-group">
                            <label for="current_password">Mật khẩu hiện tại</label>
                            <input type="password" class="form-control" id="current_password" name="current_password">
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password">Mật khẩu mới</label>
                            <input type="password" class="form-control" id="new_password" name="new_password">
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password">Xác nhận mật khẩu mới</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" name="update_profile" class="btn btn-primary">
                                <i class="fa fa-save"></i> Cập nhật thông tin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Lịch sử đơn hàng</h5>
                </div>
                <div class="card-body">
                    <?php if($orders && $orders->rowCount() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Mã đơn hàng</th>
                                    <th>Ngày đặt</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Chi tiết</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($order_row = $orders->fetch(PDO::FETCH_ASSOC)): ?>
                                <tr>
                                    <td>#<?php echo $order_row['id']; ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($order_row['created_at'])); ?></td>
                                    <td><?php echo number_format($order_row['total_amount'], 0, ',', '.'); ?> VNĐ</td>
                                    <td>
                                        <?php
                                        switch($order_row['order_status']) {
                                            case 'pending':
                                                echo '<span class="badge badge-warning">Chờ xác nhận</span>';
                                                break;
                                            case 'processing':
                                                echo '<span class="badge badge-info">Đang xử lý</span>';
                                                break;
                                            case 'shipped':
                                                echo '<span class="badge badge-primary">Đang giao hàng</span>';
                                                break;
                                            case 'delivered':
                                                echo '<span class="badge badge-success">Đã giao hàng</span>';
                                                break;
                                            case 'cancelled':
                                                echo '<span class="badge badge-danger">Đã hủy</span>';
                                                break;
                                            default:
                                                echo '<span class="badge badge-secondary">Không xác định</span>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <a href="index.php?controller=cart&action=success&id=<?php echo $order_row['id']; ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fa fa-eye"></i> Xem
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-info">
                        Bạn chưa có đơn hàng nào.
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>