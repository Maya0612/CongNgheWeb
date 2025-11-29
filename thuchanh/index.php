
<?php
require 'data.php';
?>
<!DOCTYPE html> 
<html lang = "vi">
<head>
    <meta charset="UTF-8">
     <title>Danh sách hoa - Giao diện khách</title>
     <style>
        body {
            font-family: Arial;
            width: 80%;
            margin: 20px auto;
            line-height:1.6;
        }
        .flower {
            display: flex;
            margin-bottom: 40px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
        }
        .flower img{
            width: 200px;
            height: 200px;
            margin-right: 15px;
            object_fit: cover;
            border-radius: 5px;
        }
        .flower h2 {
            margin-top: 0;
        }
    </style>
</head>
<body>
<h1>14 Loại Hoa Tuyệt Đẹp Xuân – Hè</h1>

<?php foreach ($flowers as $flower): ?>
    <div class="flower">
        <img src="images/<?php echo $flower['image']; ?>" alt="">
        <div>
            <h2><?php echo $flower['name']; ?></h2>
            <p><?php echo $flower['description']; ?></p>
        </div>
    </div>
<?php endforeach; ?>

</body>
</html> 