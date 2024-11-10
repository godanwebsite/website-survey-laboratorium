<?php
include 'functions.php';
$questions = getQuestions();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($_POST['ratings'] as $question_id => $rating) {
        saveResponse($question_id, $rating);
    }
    echo "<script>alert('Terima kasih atas partisipasi Anda dalam survei!');</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Survey Kepuasan Pengunjung Laboratorium</title>
    <link rel="stylesheet" href="style_index.css">
</head>
<body>
    <div class="header">
        <h1>Survey Kepuasan Pengunjung Laboratorium</h1>
        <a href="login.php" class="login-link">Login Admin</a>
    </div>
    
    <!-- <div class="survey-form">
        <form method="POST">
            <?php foreach ($questions as $question): ?>
                <div class="question-block">
                    <label><?php echo $question['question_text']; ?></label>
                    <select name="ratings[<?php echo $question['id']; ?>]" required>
                        <option value="1">1 - Sangat Tidak Puas</option>
                        <option value="2">2 - Tidak Puas</option>
                        <option value="3">3 - Netral</option>
                        <option value="4">4 - Puas</option>
                        <option value="5">5 - Sangat Puas</option>
                    </select>
                </div>
            <?php endforeach; ?>
            <button type="submit" class="submit-button">Kirim Survey</button>
        </form>
    </div> -->

    <div class="survey-form">
    <form method="POST">
        <?php if (!empty($questions)): ?>
            <?php foreach ($questions as $question): ?>
                <div class="question-block">
                    <label><?php echo $question['question_text']; ?></label>
                    <select name="ratings[<?php echo $question['id']; ?>]" required>
                        <option value="1">1 - Sangat Tidak Puas</option>
                        <option value="2">2 - Tidak Puas</option>
                        <option value="3">3 - Netral</option>
                        <option value="4">4 - Puas</option>
                        <option value="5">5 - Sangat Puas</option>
                    </select>
                </div>
            <?php endforeach; ?>
            <button type="submit" class="submit-button">Kirim Survey</button>
        <?php else: ?>
            <p>Pertanyaan tidak ada.</p>
        <?php endif; ?>
    </form>
</div>


    <div class="feedback-form">
        <h2>Kotak Masukan dan Saran</h2>
        <form action="submit_feedback.php" method="POST">
            <label for="name">Nama:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="suggestion">Saran:</label>
            <textarea id="suggestion" name="suggestion" rows="4" required></textarea>

            <button type="submit" class="submit-button">Kirim Saran</button>
        </form>
    </div>
</body>
</html>
