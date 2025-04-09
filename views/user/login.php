<div class="container mt-4 mb-5">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Đăng nhập</h4>
                </div>
                <div class="card-body">
                    <?php if(isset($error_message)): ?>
                    <div class="alert alert-danger">
                        <?php echo $error_message; ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if(isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success">
                        <?php 
                        echo $_SESSION['success_message']; 
                        unset($_SESSION['success_message']);
                        ?>
                    </div>
                    <?php endif; ?>
                    
                    <form action="index.php?controller=user&action=login" method="POST">
                        <div class="form-group">
                            <label for="username">Tên đăng nhập</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Mật khẩu</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" name="login" class="btn btn-primary btn-block">
                                <i class="fa fa-sign-in-alt"></i> Đăng nhập
                            </button>
                        </div>
                    </form>
                    
                    <hr>
                    
                    <div class="text-center">
                        <p>Chưa có tài khoản? <a href="index.php?controller=user&action=register">Đăng ký ngay</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>