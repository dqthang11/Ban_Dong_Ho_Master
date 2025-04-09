<div class="container mt-4 mb-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Đăng ký tài khoản</h4>
                </div>
                <div class="card-body">
                    <?php if(isset($errors) && !empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    
                    <form action="index.php?controller=user&action=register" method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="username">Tên đăng nhập <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="username" name="username" value="<?php echo isset($username) ? $username : ''; ?>" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Mật khẩu <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="confirm_password">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="fullname">Họ tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo isset($fullname) ? $fullname : ''; ?>" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Số điện thoại</label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="<?php echo isset($phone) ? $phone : ''; ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="address">Địa chỉ</label>
                            <textarea class="form-control" id="address" name="address" rows="3"><?php echo isset($address) ? $address : ''; ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" name="register" class="btn btn-primary btn-block">
                                <i class="fa fa-user-plus"></i> Đăng ký
                            </button>
                        </div>
                    </form>
                    
                    <hr>
                    
                    <div class="text-center">
                        <p>Đã có tài khoản? <a href="index.php?controller=user&action=login">Đăng nhập</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>