<?php
// Format currency
function formatCurrency($amount) {
    return number_format($amount, 0, ',', '.') . ' VNĐ';
}

// Format date
function formatDate($date) {
    return date('d/m/Y H:i', strtotime($date));
}

// Calculate cart total
function getCartTotal() {
    $total = 0;
    if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    }
    return $total;
}

// Count cart items
function getCartCount() {
    $count = 0;
    if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach($_SESSION['cart'] as $item) {
            $count += $item['quantity'];
        }
    }
    return $count;
}

// Generate pagination
function generatePagination($total_pages, $current_page, $url = '') {
    $pagination = '';
    
    if($total_pages > 1) {
        $pagination .= '<ul class="pagination">';
        
        // Previous page
        if($current_page > 1) {
            $pagination .= '<li class="page-item"><a class="page-link" href="' . $url . '&page=' . ($current_page - 1) . '">&laquo;</a></li>';
        } else {
            $pagination .= '<li class="page-item disabled"><a class="page-link" href="#">&laquo;</a></li>';
        }
        
        // Page numbers
        for($i = 1; $i <= $total_pages; $i++) {
            if($i == $current_page) {
                $pagination .= '<li class="page-item active"><a class="page-link" href="#">' . $i . '</a></li>';
            } else {
                $pagination .= '<li class="page-item"><a class="page-link" href="' . $url . '&page=' . $i . '">' . $i . '</a></li>';
            }
        }
        
        // Next page
        if($current_page < $total_pages) {
            $pagination .= '<li class="page-item"><a class="page-link" href="' . $url . '&page=' . ($current_page + 1) . '">&raquo;</a></li>';
        } else {
            $pagination .= '<li class="page-item disabled"><a class="page-link" href="#">&raquo;</a></li>';
        }
        
        $pagination .= '</ul>';
    }
    
    return $pagination;
}

// Truncate text
function truncateText($text, $length = 100) {
    if(strlen($text) > $length) {
        $text = substr($text, 0, $length) . '...';
    }
    return $text;
}

// Get order status label
function getOrderStatusLabel($status) {
    switch($status) {
        case 'pending':
            return '<span class="badge bg-warning">Chờ xử lý</span>';
        case 'processing':
            return '<span class="badge bg-info">Đang xử lý</span>';
        case 'shipped':
            return '<span class="badge bg-primary">Đang giao hàng</span>';
        case 'delivered':
            return '<span class="badge bg-success">Đã giao hàng</span>';
        case 'cancelled':
            return '<span class="badge bg-danger">Đã hủy</span>';
        default:
            return '<span class="badge bg-secondary">Không xác định</span>';
    }
}

// Flash messages
function displayMessage() {
    if(isset($_SESSION['success_message'])) {
        echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
        unset($_SESSION['success_message']);
    }
    
    if(isset($_SESSION['error_message'])) {
        echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
        unset($_SESSION['error_message']);
    }
}