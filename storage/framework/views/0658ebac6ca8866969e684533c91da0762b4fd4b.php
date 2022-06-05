<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .alert {
            padding: 20px;
            background-color: #f44336;
            color: white;
        }

        .closebtn {
            margin-left: 15px;
            color: white;
            font-weight: bold;
            float: right;
            font-size: 22px;
            line-height: 20px;
            cursor: pointer;
            transition: 0.3s;
        }

        .closebtn:hover {
            color: black;
        }
    </style>
</head>
<body>

<h2>Nội dung tin nhắn</h2>

<p>Cảnh báo chủ nhân <?php echo e($full_name); ?> đang sở là chủ sở hữu tài khoản <?php echo e($content); ?>.</p>
<div class="alert">
    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
    <strong>Cảnh báo!</strong> Tài khoản <?php echo e($content); ?> của bạn hiện tại đang đăng nhập ở một thiết bị vào lúc <?php echo e($login_in_at); ?> Xin vui lòng kiểm tra lại tài khoản của bạn.
</div>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\hospital\resources\views/report_issue_user_login_other.blade.php ENDPATH**/ ?>