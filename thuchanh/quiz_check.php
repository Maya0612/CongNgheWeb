<?php
// Cập nhật đường dẫn tuyệt đối
$quiz_file = 'C:/xampp/htdocs/cnWeb/BTTH01/BTTH/Quiz.txt';
$questions = [];

// Hàm phân tích (parse) file Quiz.txt (Đã sửa lỗi Regex và Parsing chi tiết)
function parse_quiz_file($quiz_file) {
    $questions = [];
    if (!file_exists($quiz_file)) {
        die("Không tìm thấy tệp tin: {$quiz_file}");
    }
    
    $content = file_get_contents($quiz_file);
    
    // Tách các khối câu hỏi dựa trên các từ khóa mở đầu câu
    $pattern = '/(?=Thành phần nào|Layout nào|Intent|Vòng đời|Để xử lý|Kiểu dữ liệu nào|SharedPreferences|Toast|Để tạo một ứng dụng|Adapter|Fragment|RecyclerView|Manifest file|Gradle|AsyncTask|ContentProvider|SQLite|BroadcastReceiver|Service|Thread|Activity Lifecycle|Layout inflater|Drawable|dp|Để định nghĩa|ViewGroup|Thuộc tính android:layout_width|Thuộc tính android:gravity|AndroidManifest\.xml|Để chạy một ứng dụng|dp và sp|AlertDialog|Intent Filter|Serializable|Sự khác nhau|ViewHolder pattern|Data Binding|MVVM|Retrofit|Gson|Picasso|Firebase|ConstraintLayout|DataBinding giúp|ViewModel|LiveData|Room|Jetpack Compose|Những thành phần nào|Những phát biểu nào|Những phương thức nào|Những thư viện nào|Những lợi ích nào|Những thành phần nào sau đây thuộc kiến trúc MVVM|Những công cụ nào|Những kỹ thuật nào|Những khái niệm nào)/';
    $question_blocks = preg_split($pattern, $content, -1, PREG_SPLIT_NO_EMPTY);
    
    foreach ($question_blocks as $block) {
        $block = trim($block);
        if (empty($block)) continue;
        
        // 1. Tách phần câu hỏi/lựa chọn và phần đáp án ANSWER
        $parts = preg_split('/ANSWER:\s*/', $block, 2);
        
        if (count($parts) < 2) continue;
        
        $question_options_text = trim($parts[0]);
        $answer_key = trim($parts[1]);

        // 2. Tách câu hỏi và lựa chọn
        $lines = explode("\n", $question_options_text);
        
        $q_text = '';
        $q_options = [];
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            // Xử lý các lựa chọn: Tìm mẫu (A-E). (Nội dung)
            if (preg_match('/^([A-E])\.\s*(.*)/', $line, $matches)) {
                if (isset($matches[1]) && isset($matches[2])) {
                    $option_text = preg_replace('/^\\s*/', '', $matches[2]);
                    $q_options[$matches[1]] = trim($option_text);
                }
            } 
            // Xử lý câu hỏi: Lấy dòng đầu tiên không phải là option và không phải là 
            else if (empty($q_options) && empty($q_text)) {
                $cleaned_line = preg_replace('/^\\s*/', '', $line);
                if (!empty($cleaned_line)) {
                     $q_text = $cleaned_line;
                }
            }
        }

        if (!empty($q_text) && !empty($q_options)) {
            $questions[] = [
                'text' => $q_text, 
                'options' => $q_options, 
                'correct' => $answer_key
            ];
        }
    }
    return $questions;
}

$questions = parse_quiz_file($quiz_file);

// Xử lý bài nộp
$score = 0;
$total_questions = count($questions);
$results = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($questions as $index => $q) {
        $q_number = $index + 1;
        $user_answers = [];
        
        if (isset($_POST["q_{$q_number}"])) {
            $user_answers = $_POST["q_{$q_number}"];
            if (!is_array($user_answers)) {
                $user_answers = [$user_answers];
            }
        }

        $correct_answers = array_map('trim', explode(',', $q['correct']));
        
        $is_correct = false;
        
        sort($user_answers);
        sort($correct_answers);

        if ($user_answers === $correct_answers) {
            $is_correct = true;
            $score++;
        }
        
        $results[] = [
            'question' => $q['text'],
            'options' => $q['options'],
            'user_answers' => $user_answers,
            'correct_answers' => $correct_answers,
            'is_correct' => $is_correct
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kết Quả Bài Thi Trắc Nghiệm</title>
    <style>
        body { font-family: Arial, sans-serif; width: 80%; margin: 20px auto; line-height: 1.6; }
        .score-box { background-color: #e9ecef; padding: 20px; text-align: center; margin-bottom: 30px; border-radius: 8px; }
        .score-text { font-size: 1.5em; font-weight: bold; color: #007bff; }
        .result-block { border: 1px solid #ccc; padding: 20px; margin-bottom: 25px; border-radius: 8px; }
        .correct { background-color: #d4edda; border-color: #c3e6cb; color: #155724; }
        .incorrect { background-color: #f8d7da; border-color: #f5c6cb; color: #721c24; }
        .result-title { font-weight: bold; margin-bottom: 15px; font-size: 1.1em; }
        .answer-info { margin-top: 10px; padding: 10px; border-top: 1px dashed #aaa; }
        .correct-answer-display { color: #155724; font-weight: bold; }
        .user-answer-display { color: #721c24; font-weight: bold; }
    </style>
</head>
<body>

<h1>Kết Quả Bài Thi Trắc Nghiệm</h1>

<div class="score-box">
    <p class="score-text">Bạn đã trả lời đúng <?php echo $score; ?> / <?php echo $total_questions; ?> câu.</p>
</div>

<?php foreach ($results as $index => $r): ?>
    <?php $q_number = $index + 1; ?>
    <div class="result-block <?php echo $r['is_correct'] ? 'correct' : 'incorrect'; ?>">
        <div class="result-title">
            Câu <?php echo $q_number; ?>: <?php echo $r['question']; ?>
            (<?php echo $r['is_correct'] ? 'ĐÚNG' : 'SAI'; ?>)
        </div>
        
        <?php foreach ($r['options'] as $key => $value): ?>
            <?php 
                $is_user_selected = in_array($key, $r['user_answers']);
                $is_correct_option = in_array($key, $r['correct_answers']);
                $style = '';

                if ($is_correct_option) {
                    $style = 'style="font-weight: bold; color: #155724;"'; 
                } else if ($is_user_selected) {
                    $style = 'style="font-weight: bold; color: #721c24; text-decoration: line-through;"'; 
                }
            ?>
            <div <?php echo $style; ?>>
                <?php echo $key; ?>. <?php echo $value; ?>
                <?php if ($is_user_selected && !$is_correct_option): ?>
                    (Bạn đã chọn sai)
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        
        <div class="answer-info">
            - Đáp án đúng:<span class="correct-answer-display"><?php echo implode(', ', $r['correct_answers']); ?></span>
            <br>
            - Câu trả lời của bạn:
            <span class="user-answer-display">
                <?php echo !empty($r['user_answers']) ? implode(', ', $r['user_answers']) : 'Chưa trả lời'; ?>
            </span>
        </div>
    </div>
<?php endforeach; ?>

<p><a href="quiz.php">Quay lại làm bài thi</a></p>

</body>
</html>