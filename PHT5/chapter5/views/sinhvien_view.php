<?php 
// Tệp View CHỈ chứa HTML và logic hiển thị (echo, foreach) 
// Tệp View KHÔNG chứa câu lệnh SQL 
?> 
<!DOCTYPE html> 
<html lang="vi"> 
<head> 
    <meta charset="UTF-8"> 
    <title>PHT Chương 5 - MVC</title> 
    <style> 
        table { width: 100%; border-collapse: collapse; } 
        th, td { border: 1px solid #ddd; padding: 8px; } 
        th { background-color: #f2f2f2; } 
    </style> 
</head> 
<body> 
    <h2>Thêm Sinh Viên Mới (Kiến trúc MVC)</h2> 
     
    <h2>Danh Sách Sinh Viên (Kiến trúc MVC)</h2> 
    <table> 
        <tr> 
            <th>ID</th> 
            <th>Tên Sinh Viên</th> 
            <th>Email</th> 
            <th>Ngày Tạo</th> 
        </tr> 
        <!-- // TODO 4: Dùng vòng lặp foreach để duyệt qua biến $danh_sach_sv 
        // (Biến $danh_sach_sv này sẽ được Controller truyền sang) 
        // Gợi ý: foreach ($danh_sach_sv as $sv) { ... } 
         
            // TODO 5: In (echo) các dòng <tr> và <td> chứa dữ liệu $sv 
            // Gợi ý: echo "<tr><td>" . htmlspecialchars($sv['id']) . -->

        <?php  if (!empty($danh_sach_sv) && is_array($danh_sach_sv)): ?>
            <?php foreach ($danh_sach_sv as $sv): ?>
                <tr>
                    <td><?php echo htmlspecialchars($sv['id']) ?></td>
                    <td><?php echo htmlspecialchars($sv['ten_sinh_vien']) ?></td>
                    <td><?php echo htmlspecialchars($sv['email']) ?></td>
                    <td><?php echo htmlspecialchars($sv['ngay_tao']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4"> Không có sinh viên nào. </td>
            </td>
        <?php endif; ?>
    </table> 
</body> 
</html>