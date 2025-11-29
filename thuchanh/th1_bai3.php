<?php
// Tên tệp tin CSV cần đọc
$csv_file = 'C:\xampp\htdocs\cnWeb\BTTH01\BTTH\65HTTT_Danh_sach_diem_danh.csv';

$data = [];
$header = [];
$error = '';

// Kiểm tra xem tệp tin có tồn tại không
if (!file_exists($csv_file)) {
    $error = "Lỗi: Không tìm thấy tệp tin CSV: " . htmlspecialchars($csv_file);
} else {
    // Mở tệp tin để đọc ('r')
    // Nếu mở thành công, $handle sẽ là một resource
    if (($handle = fopen($csv_file, "r")) !== FALSE) {
        // Đọc hàng đầu tiên làm tiêu đề (Header)
        $header = fgetcsv($handle, 1000, ","); 

        // Đọc các hàng dữ liệu còn lại
        while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // Đảm bảo rằng chỉ những hàng có dữ liệu hợp lệ (không phải là hàng trống) mới được thêm vào
            if (count($row) > 1 || (count($row) == 1 && !empty($row[0]))) {
                 $data[] = $row;
            }
        }
        
        // Đóng tệp tin
        fclose($handle);
    } else {
        $error = "Lỗi: Không thể mở tệp tin " . htmlspecialchars($csv_file);
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bài 03: Đọc và Hiển thị Tệp tin CSV</title>
    <style>
        body { font-family: Arial, sans-serif; width: 80%; margin: 20px auto; line-height: 1.6; }
        h1 { color: #007bff; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .error { color: red; font-weight: bold; }
    </style>
</head>
<body>

<h1>Bài 03: Nội dung Tệp tin CSV</h1>

<?php if (!empty($error)): ?>
    <p class="error"><?php echo $error; ?></p>
<?php elseif (empty($header) && empty($data)): ?>
    <p>Tệp tin <?php echo htmlspecialchars($csv_file); ?> không chứa dữ liệu.</p>
<?php else: ?>
    
    <h2>Dữ liệu từ tệp tin: <?php echo htmlspecialchars($csv_file); ?></h2>
    
    <table>
        <thead>
            <tr>
                <?php 
                // Hiển thị tiêu đề cột
                if (!empty($header)) {
                    foreach ($header as $col_name) {
                        echo "<th>" . htmlspecialchars($col_name) . "</th>";
                    }
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Hiển thị dữ liệu các hàng
            foreach ($data as $row) {
                echo "<tr>";
                foreach ($row as $cell) {
                    echo "<td>" . htmlspecialchars($cell) . "</td>";
                }
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

<?php endif; ?>

</body>
</html>