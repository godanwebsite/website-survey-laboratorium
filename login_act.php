<html>
<body>
    <?php
    session_start();
    $username = $_POST['username'];
    $password = $_POST['password'];
    if($username=='admin' && $password=='admin123'){
        $_SESSION['login']=$username;
        $_SESSION['level']="admin";
        echo "Login User " . $username . " Berhasil<br>";
        echo " level: " . $_SESSION['level'];
        header("location:admin.php");
        //echo "<p>Klik <a href='index.php'>halaman1</a> untuk menuju Halaman berikutnya</p>";
    }
?>
</body>
</html>