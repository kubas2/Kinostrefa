<?php
    error_reporting(0);
    ini_set('display_errors', 'Off');
    session_start();
    if (isset($_SESSION['username']) || $_SESSION['loggedIn'] == true) {
        header("Location: admin.php");
    }
    include "connect.php";
    $error = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'] ?? '';
        $email = $conn->real_escape_string($email);
        $pass = $_POST['pass'] ?? '';
        $pass = $conn->real_escape_string($pass);
        $confirmPass = $_POST['confirmPass'] ?? '';
        $confirmPass = $conn->real_escape_string($confirmPass);
        $username = $_POST['username'] ?? '';
        $username = $conn->real_escape_string($username);
        
        // check confirmed pass
        if ($pass !==  $confirmPass) {
            $error = "Passwords are different.";
        } else {
            // check if username exists
            $stmt = $conn->prepare("SELECT count(username) FROM users WHERE username = ?");
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            //$stmt->free_result();
            $stmt->close();
            if ($count == 0){
                // check if email exists
                $stmt = $conn->prepare("SELECT count(email) FROM users WHERE email = ?");
                $stmt->bind_param('s', $email);
                $stmt->execute();
                $stmt->bind_result($count);
                $stmt->fetch();
                //$stmt->free_result();
                $stmt->close();
                if ($count == 0) {
                    // INSERT DATA TO DB

                    $stmt = $conn->prepare("INSERT INTO users (username,password,email) VALUES(?,?,?);");
                    $hashed = password_hash($pass, PASSWORD_DEFAULT);
                    $stmt->bind_param('sss', $username, $hashed, $email);
                    $stmt->execute();
                    if ($stmt->affected_rows === 1){
                        $error = "SUCCESSFULLY REGISTERED";
                    } else {
                        $error = "Couldnt add data to db.";
                    }
                } else {
                    $error = "This email already exists.";
                }
            } else {
                $error = "This username already exists.";
            }
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
    <h1>Register</h1>
</header>
    
    <div class='log-container'>
    <form action="" id="usrform" method="POST">
    <p>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    </p>
    <p>
    <label for="username">Username:</label>
    <input type="username" id="username" name="username" required>
    </p>
    <label for="pass">Password:</label>
    <input type="password" id="pass" name="pass" required>
    <p>
    <label for="confirmPass">Confirm:</label>
    <input type="password" id="confirmPass" name="confirmPass" required>
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