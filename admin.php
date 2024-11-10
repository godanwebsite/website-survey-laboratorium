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
    <h1>Admin</h1>
    <div class="header-links">
        <a href="tambah_pertanyaan.php" class="button-link">Tambah Pertanyaan</a>
        <a href="index.php" class="button-link">Logout</a>
    </div>
</header>


<div class="category-probability">
        <?php
        $puas_probabilitas = round($surveyData['naive_bayes']['category_probabilities']['puas']);
        $tidak_puas_probabilitas = round($surveyData['naive_bayes']['category_probabilities']['tidak_puas']);
        
        if ($puas_probabilitas > $tidak_puas_probabilitas) {
            echo "<li class='centered-warning'>Puas</li>";
        } elseif ($puas_probabilitas < $tidak_puas_probabilitas) {
            echo "<li class='centered-warning'>Tidak Puas</li>";
        } else {
            echo "<li class='centered-warning'>&#x1F62D; &#x1F62D; &#x1F62D;</li>";
        }
        ?>
</div>


    <!-- Peringatan jika ada area yang perlu perhatian -->
    <div class="warning">
        <h2>Peringatan Kepuasan Pengunjung</h2>
        <?php if (!empty($warnings)): ?>
            <ul class="warning-list">
                <?php foreach ($warnings as $warning): ?>
                    <li><?php echo $warning; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Semua sudah cukup memuaskan.</p>
        <?php endif; ?>
    </div>

    <div class="survey-results">
        <h2>Hasil Survey</h2>
        <table class="survey-table">
            <tr>
                <th>Pertanyaan</th>
                <th>Rata-rata Kepuasan</th>
                <th>Peringatan</th>
            </tr>
            <?php foreach ($surveyResults as $result): ?>
                <tr>
                    <td><?php echo $result['question_text']; ?></td>
                    <td><?php echo round($result['average_rating'], 2); ?></td>
                    <td>
                        <?php 
                        if ($result['average_rating'] < 0) {
                            echo "Belum ada yang mengisi survey";
                        } elseif ($result['average_rating'] >= 0 && $result['average_rating'] < 1) {
                            echo "Perhatian: Kepuasan sangat rendah, segera lakukan evaluasi!!!";
                        } elseif ($result['average_rating'] >= 1 && $result['average_rating'] < 2) {
                            echo "Perhatian: Kepuasan rendah, lakukan evaluasi!";
                        } elseif ($result['average_rating'] >= 2 && $result['average_rating'] < 3) {
                            echo "Netral";
                        } elseif ($result['average_rating'] >= 3 && $result['average_rating'] < 4) {
                            echo "Kepuasan cukup baik";
                        } else {
                            echo "Kepuasan Sangat Baik";
                        }
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <!-- <div class="category-probability">
        <p>Probabilitas kategori:</p>
        <ul>
            <?php
            $puas_probabilitas = round($surveyData['naive_bayes']['category_probabilities']['puas']);
            $tidak_puas_probabilitas = round($surveyData['naive_bayes']['category_probabilities']['tidak_puas']);
            
            if ($puas_probabilitas > $tidak_puas_probabilitas) {
                echo "<li>Puas</li>";
            }
            if ($puas_probabilitas < $tidak_puas_probabilitas) {
                echo "<li>Tidak Puas</li>";
            } else {
                echo "<li>Belum ada yang mengisi</li>";
            }
            ?>
        </ul>
    </div> -->

    <!-- <div class="feedback-list">
        <h2>Daftar Saran Pengunjung</h2>
        <ul>
            <?php
            $feedbackList = getFeedback();
            foreach ($feedbackList as $feedback): ?>
                <li>
                    <strong><?php echo htmlspecialchars($feedback['name']); ?></strong> (<?php echo htmlspecialchars($feedback['email']); ?>):
                    <p><?php echo htmlspecialchars($feedback['suggestion']); ?></p>
                    <small>Submitted on: <?php echo $feedback['created_at']; ?></small>
                </li>
            <?php endforeach; ?>
        </ul>
    </div> -->

    <div class="feedback-list">
    <h2>Daftar Saran Pengunjung</h2>
    <?php
    $feedbackList = getFeedback();
    if (empty($feedbackList)): ?>
        <p>Belum ada masukan dari pengunjung.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($feedbackList as $feedback): ?>
                <li>
                    <strong><?php echo htmlspecialchars($feedback['name']); ?></strong> (<?php echo htmlspecialchars($feedback['email']); ?>):
                    <p><?php echo htmlspecialchars($feedback['suggestion']); ?></p>
                    <small>Submitted on: <?php echo $feedback['created_at']; ?></small>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>


    <!-- <div class="add-question">
        <h2>Tambah Pertanyaan Survei</h2>
        <form method="POST">
            <input type="text" name="question_text" placeholder="Tulis pertanyaan baru" required>
            <button type="submit" name="add">Tambah Pertanyaan</button>
        </form>
    </div> -->

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
