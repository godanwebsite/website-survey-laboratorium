<html>
<head>
    <title>Login</title>
    <script type="text/javascript" src="jq.js"></script>
    <link rel="stylesheet" type="text/css" href="style_login.css">
</head>

<body class="bg-login">
    <img src="gedunguq.png" alt="gedunguq">
    <div class="kotak-login">
        <form action="login_act.php" method="post">
            <table>
                <tr>
                    <td colspan="2"><p class="tulisan-login" style="text-align:center;">
                    Silahkan Login untuk Masuk</p></td>
                </tr>
                <tr>
                    <td>Username</td>
                    <td><input type="text" name="username"></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type="password" name="password"></td>
                </tr>
            </table>
            <input class="tombol-login" type="submit" name="submit" value="Login">
            <a href="index.php" class="tombol-kembali">Kembali</a>
        </form>
    </div>
</body>
</html>