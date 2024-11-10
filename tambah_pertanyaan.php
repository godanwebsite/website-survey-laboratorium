<?php
include 'functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add'])) {
        addQuestion($_POST['question_text']);
    } elseif (isset($_POST['delete'])) {
        deleteQuestion($_POST['id']);
    } elseif (isset($_POST['update'])) {
        updateQuestion($_POST['id'], $_POST['question_text']);
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Mendapatkan hasil survei dan peringatan
$surveyData = getSurveyResults();
$surveyResults = $surveyData['results'];
$warnings = $surveyData['warnings'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin - Survey Kepuasan Laboratorium</title>
    <link rel="stylesheet" href="style_admin.css">
</head>
<body>
<header>
    <h1>Tambah Pertanyaan</h1>
    <div class="header-links">
        <a href="admin.php" class="button-link">Kembali</a>
    </div>
</header>

    <div class="add-question">
        <h2>Tambah Pertanyaan Survei</h2>
        <form method="POST">
            <input type="text" name="question_text" placeholder="Tulis pertanyaan baru" required>
            <button type="submit" name="add">Tambah Pertanyaan</button>
        </form>
    </div>

    <!-- <div class="question-list">
    <h2>Daftar Pertanyaan</h2>
    <ul>
        <?php foreach ($surveyResults as $question): ?>
            <li>
                <form method="POST" style="display: inline;">
                    <input type="text" name="question_text" value="<?php echo $question['question_text']; ?>" required>
                    <input type="hidden" name="id" value="<?php echo $question['id']; ?>">
                    <button type="submit" name="update">Update</button>
                    <button type="submit" name="delete">Hapus</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</div> -->


</body>
</html>
