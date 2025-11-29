<?php
// Cập nhật đường dẫn tuyệt đối theo yêu cầu của bạn
// Dựa trên cấu trúc thư mục, đường dẫn có vẻ là:
$quiz_file = 'C:/xampp/htdocs/cnWeb/BTTH01/BTTH/Quiz.txt';
$questions = [];

// Hàm phân tích (parse) file Quiz.txt (Logic tối ưu hóa độ bền)
function parse_quiz_file($quiz_file) {
    $questions = [];
    if (!file_exists($quiz_file)) {
        // Có vẻ như đường dẫn chính xác phải là:
        $quiz_file = str_replace('cnWeb/BTTH01/BTTH/Quiz.txt', 'cnWeb/BTTH01/Quiz.txt', $quiz_file);
        if (!file_exists($quiz_file)) {
             $quiz_file = 'C:/xampp/htdocs/cnWeb/BTTH01/BTTH/Quiz.txt'; // Sử dụng đường dẫn gốc nếu không tìm thấy
             if (!file_exists($quiz_file)) {
                 die("Lỗi: Không tìm thấy tệp tin Quiz.txt ở cả hai vị trí dự kiến.");
             }
        }
    }
    
    $content = file_get_contents($quiz_file);
    
    // Tách các khối câu hỏi dựa vào từ khóa "ANSWER:" và các từ khóa bắt đầu câu hỏi.
    // Tách toàn bộ nội dung thành các dòng để xử lý thủ công từng khối.
    $lines = explode("\n", $content);
    
    $q_text = '';
    $q_options = [];
    $answer_key = '';

    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) continue;
        
        // --- BƯỚC 1: LÀM SẠCH DÒNG ---
        // Loại bỏ trước khi xử lý
        $cleaned_line = preg_replace('/^\\s*/', '', $line);
        $cleaned_line = trim($cleaned_line);

        if (empty($cleaned_line)) continue; 
        
        // --- BƯỚC 2: PHÂN TÍCH ---

        // 2a. Phát hiện đáp án (ANSWER: X)
        if (preg_match('/^ANSWER:\s*(.*)/', $cleaned_line, $matches)) {
            $answer_key = trim($matches[1]);
            
            // Kết thúc khối câu hỏi và thêm vào mảng $questions
            if (!empty($q_text) && !empty($q_options) && !empty($answer_key)) {
                $questions[] = [
                    'text' => $q_text, 
                    'options' => $q_options, 
                    'correct' => $answer_key
                ];
            }
            // Thiết lập lại biến cho câu hỏi tiếp theo
            $q_text = '';
            $q_options = [];
            $answer_key = '';
            continue; // Chuyển sang dòng tiếp theo
        }

        // 2b. Phát hiện lựa chọn (A., B., C., D., E.)
        if (preg_match('/^([A-E])\.\s*(.*)/', $cleaned_line, $matches)) {
            if (isset($matches[1]) && isset($matches[2])) {
                $q_options[$matches[1]] = trim($matches[2]);
            }
        } 
        
        // 2c. Phát hiện câu hỏi (Chỉ lấy dòng đầu tiên không phải là lựa chọn)
        // Điều kiện: Chưa có options VÀ chưa có question text
        else if (empty($q_options) && empty($q_text)) {
            $q_text = $cleaned_line;
        }
    }
    return $questions;
}

$questions = parse_quiz_file($quiz_file);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bài Thi Trắc Nghiệm Android</title>
    <style>
        body { font-family: Arial, sans-serif; width: 80%; margin: 20px auto; line-height: 1.6; }
        .question-block { border: 1px solid #ccc; padding: 20px; margin-bottom: 25px; border-radius: 8px; }
        .question-title { font-weight: bold; margin-bottom: 15px; font-size: 1.1em; color: #333; }
        .option-label { display: block; margin: 10px 0; cursor: pointer; padding: 5px; border-radius: 4px; transition: background 0.2s; }
        .option-label:hover { background-color: #f0f0f0; }
        input[type="radio"], input[type="checkbox"] { margin-right: 10px; }
        .submit-btn { padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 1.1em; }
        .submit-btn:hover { background-color: #0056b3; }
    </style>
</head>
<body>

<h1>Bài Thi Trắc Nghiệm - Lập Trình Android</h1>
<p>Tổng số câu hỏi: <?php echo count($questions); ?>**</p>

<form id="quizForm" action="quiz_check.php" method="post">

<?php foreach ($questions as $index => $q): 
    $q_number = $index + 1;
    $is_multiple = (strpos($q['correct'], ',') !== false || substr_count($q['correct'], ',') >= 1); // Kiểm tra lại nhiều đáp án
    $input_type = $is_multiple ? 'checkbox' : 'radio';
    $input_name = "q_{$q_number}" . ($is_multiple ? '[]' : '');
?>
    <div class="question-block">
        <div class="question-title">Câu <?php echo $q_number; ?>: <?php echo $q['text']; ?></div>
        
        <?php foreach ($q['options'] as $key => $value): ?>
            <label class="option-label">
                <input type="<?php echo $input_type; ?>" name="<?php echo $input_name; ?>" value="<?php echo $key; ?>">
                <?php echo $key; ?>. <?php echo $value; ?>
            </label>
        <?php endforeach; ?>
        
        <?php if ($is_multiple): ?>
            <p style="font-size: 0.9em; color: #dc3545;">(Chọn nhiều đáp án)</p>
        <?php endif; ?>
    </div>
<?php endforeach; ?>

<button type="submit" class="submit-btn">Nộp Bài</button>

</form>

</body>
</html>