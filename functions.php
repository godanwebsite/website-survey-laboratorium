<?php
include 'config.php';

// Fungsi untuk mendapatkan semua pertanyaan
function getQuestions() {
    global $conn;
    $result = $conn->query("SELECT * FROM questions");
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Fungsi untuk menambahkan pertanyaan baru
function addQuestion($question_text) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO questions (question_text) VALUES (?)");
    $stmt->bind_param("s", $question_text);
    return $stmt->execute();
}

// Fungsi untuk mengupdate pertanyaan
function updateQuestion($id, $question_text) {
    global $mysqli;
    $query = "UPDATE questions SET question_text = ? WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('si', $question_text, $id); // 'si' berarti string dan integer
    $stmt->execute();
    $stmt->close();
}

// Fungsi untuk menghapus pertanyaan
function deleteQuestion($id) {
    global $mysqli;
    $query = "DELETE FROM questions WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $id); // 'i' berarti integer
    $stmt->execute();
    $stmt->close();
}

// Fungsi untuk menyimpan jawaban survei dari pengunjung
function saveResponse($question_id, $rating) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO survey_responses (question_id, rating) VALUES (?, ?)");
    $stmt->bind_param("ii", $question_id, $rating);
    return $stmt->execute();
}

// Fungsi untuk menghitung hasil rata-rata dari setiap pertanyaan
function getSurveyResults() {
    global $conn;
    
    // Mendapatkan hasil survei dan rata-rata kepuasan
    $result = $conn->query("SELECT questions.question_text, AVG(survey_responses.rating) as average_rating,
    questions.id as question_id FROM survey_responses JOIN questions ON survey_responses.question_id = questions.id
    GROUP BY question_id");
    $surveyResults = $result->fetch_all(MYSQLI_ASSOC);
    
    // Tentukan batas ambang untuk kepuasan
    $threshold = 3.0;
    $warnings = [];

    foreach ($surveyResults as $result) {
        // Jika rata-rata di bawah threshold, beri peringatan
        if ($result['average_rating'] < $threshold) {
            $warnings[] = "Pertanyaan: " . $result['question_text'] . " <span class='attention-warning'>perlu perhatian!</span>";
            // round($result['average_rating'], 2);
        }
    }

    // Menambahkan hasil Naive Bayes
    $naiveBayesResult = calculateNaiveBayes($surveyResults);
    
    return [
        'results' => $surveyResults, 
        'warnings' => $warnings,
        'naive_bayes' => $naiveBayesResult
    ];
}

// Fungsi untuk menghitung probabilitas Naive Bayes
// Fungsi untuk menghitung probabilitas Naive Bayes
function calculateNaiveBayes($surveyResults) {
    // Tentukan kategori untuk Naive Bayes (misalnya: "puas", "tidak puas")
    $categories = ['puas', 'tidak_puas'];
    
    // Inisialisasi array untuk menghitung frekuensi
    $categoryCount = ['puas' => 0, 'tidak_puas' => 0];
    $wordCount = ['puas' => [], 'tidak_puas' => []];
    $totalResponses = 0;

    // Hitung frekuensi kategori dan kata dalam masing-masing kategori
    foreach ($surveyResults as $response) {
        // Ambil kategori jawaban pengunjung (misalnya "puas" jika rating >= 4)
        $category = ($response['average_rating'] >= 3) ? 'puas' : 'tidak_puas';

        $categoryCount[$category]++;
        $totalResponses++;

        // Hitung frekuensi kata untuk setiap kategori (berdasarkan pertanyaan)
        $question_text = $response['question_text'];
        if (!isset($wordCount[$category][$question_text])) {
            $wordCount[$category][$question_text] = 0;
        }
        $wordCount[$category][$question_text]++;
    }

    // Cek apakah totalResponses 0 (belum ada yang mengisi survey)
    if ($totalResponses == 0) {
        // Jika belum ada respons, set probabilitas kategori ke 0
        $categoryProbabilities = [
            'puas' => 0,
            'tidak_puas' => 0
        ];
        // Tampilkan pesan jika belum ada pengunjung yang mengisi survei
        echo "<p style='color: red; font-size: 16px; font-weight: bold; text-align: center;
        padding: 10px; background-color: #ffe6e6; border: 1px solid red; border-radius: 5px;'>
        Belum ada pengunjung yang mengisi survei.</p>";

    } else {
        // Hitung probabilitas kategori jika ada respons
        $categoryProbabilities = [
            'puas' => $categoryCount['puas'] / $totalResponses,
            'tidak_puas' => $categoryCount['tidak_puas'] / $totalResponses
        ];
    }

    // Mengembalikan hasil Naive Bayes untuk setiap kategori
    return [
        'category_probabilities' => $categoryProbabilities,
        'word_probabilities' => $wordCount
    ];
}

// Fungsi untuk menyimpan masukan dan saran
// function saveFeedback($name, $email, $suggestion) {
//     global $conn;
//     $stmt = $conn->prepare("INSERT INTO feedback (name, email, suggestion) VALUES (?, ?, ?)");
//     $stmt->bind_param("sss", $name, $email, $suggestion);
//     return $stmt->execute();
// }

// Fungsi untuk mendapatkan semua masukan dan saran
function getFeedback() {
    global $conn;
    $result = $conn->query("SELECT * FROM feedback ORDER BY created_at DESC");
    return $result->fetch_all(MYSQLI_ASSOC);
}

?>
