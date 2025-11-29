
<?php
require 'data.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản trị danh sách hoa</title>
    <style>
        body {
            font-family: Arial;
            width: 95%;
            margin: 30px auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            table-layout: fixed;
        }
        table, th, td {
            border: 1px solid #aaa;
        }
        th, td {
            padding: 10px;
            text-align: left;
            vertical-align: top; /* Căn lề trên cho nội dung cell */
            word-wrap: break-word;
        }
        /* PHẦN ĐẶT ĐỘ RỘNG CÁC CỘT */
        table th:nth-child(1), table td:nth-child(1) { width: 4%; } /* # */
        table th:nth-child(2), table td:nth-child(2) { width: 15%; } /* Ảnh */
        table th:nth-child(3), table td:nth-child(3) { width: 15%; } /* Tên hoa */
        table th:nth-child(4), table td:nth-child(4) { width: 53%; } /* Mô tả */
        table th:nth-child(5), table td:nth-child(5) { width: 13%; } /* Hành động */
        img {
            width: 100px;
            height: 100px;
            object-fit : cover;
            border-radius: 5px;
        }
        .btn {
            padding: 6px 12px;
            border: none;
            cursor: pointer;
            color: white;
        }
        .edit { background: #28a745; }
        .delete { background: #dc3545; }
    </style>
</head>
<body>

<h1>Quản trị danh sách hoa</h1>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Ảnh</th>
            <th>Tên hoa</th>
            <th>Mô tả</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>

        <?php $i = 1; foreach ($flowers as $flower): ?>
        <tr>
            <td><?php echo $i++; ?></td>
            <td><img src="images/<?php echo $flower['image']; ?>"></td>
            <td><?php echo $flower['name']; ?></td>
            <td><?php echo $flower['description']; ?></td>
            <td>
                <button class="btn edit">Sửa</button>
                <button class="btn delete">Xóa</button>
            </td>
        </tr>
        <?php endforeach; ?>

    </tbody>
</table>

</body>
</html>
