<?php
include 'config.php';  // Pastikan config.php sudah mengatur koneksi database

// Cek apakah form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $suggestion = $_POST['suggestion'];

    // Panggil fungsi untuk menyimpan saran ke database
    if (saveFeedback($name, $email, $suggestion)) {
        echo "<script>alert('Saran Anda telah berhasil dikirim. Terima kasih!');window.history.back()</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan. Mohon coba lagi.');</script>";
    }
}

function saveFeedback($name, $email, $suggestion) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO feedback (name, email, suggestion) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $suggestion);
    return $stmt->execute();
}

?>
