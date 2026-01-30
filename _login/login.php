
<?php
    session_start();
    if (isset($_SESSION['username']) && isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true) {
        header("Location: admin.php");
    }
    include "connect.php";
    $error = "";
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $pass = $_POST['pass'] ?? '';
        $pass = $conn->real_escape_string($pass);
        $username = $_POST['username'] ?? '';
        $username = $conn->real_escape_string($username);
    
            // check if username exists
            $stmt = $conn->prepare("SELECT count(username) FROM users WHERE username = ?");
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            //$stmt->free_result();
            $stmt->close();
            if ($count == 1){
                // check if email exists

                    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
                    $stmt->bind_param("s", $username);
                    $stmt->execute();
                    $stmt->bind_result($hashed);
                    if ($stmt->fetch()) {
                        if (password_verify($pass, $hashed)){
                            $_SESSION['loggedIn'] = true;
                            $_SESSION['username'] = $username;
                            echo "SUCCESSFULLY LOGGED IN";
                            header('Location: admin.php');
                            exit;
                        } else {
                            $error = "Wrong email or password.";
                        }
                    } else {
                        $error = "Wrong email or password.";
                    }
                } else {
                    $error = "Wrong email or password.";
                }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
    <h1>Connect</h1>
</header>
<div class='log-container'>
    
    <form action="" id="usrform" method="POST" class=''>
    <p>
    <label for="username">Username:</label>
    <input type="username" id="username" name="username">
    </p>
    <p>
    <label for="pass">Password:</label>
    <input type="password" id="pass" name="pass">
    </p>
    <p>
    <input type="submit" id="usrsubmit" value="Register">    
    </p>    
</form>

</div>
<?php
    if ($error) {
    echo "<p class='error'>$error</p>";
    }
    ?>
</body>
</html>