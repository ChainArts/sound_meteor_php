<?php
include "functions.php";
$pagetitle = "Forgot Password";

$resetErr = $passErr = $mailErr = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST["email"])) {
        $mailErr = "Enter an email address!";
    } else {
        try {
            $sth = $dbh->prepare("SELECT user_id, email FROM users WHERE email = ?");
            $sth->execute(array($_POST['email']));
            $isValidMail = $sth->fetch();
            if (empty($isValidMail)) {
                $mailErr = "Email is not registered!";
            } else {
                try {
                    $length = 32;
                    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_';
                    $code = '';
                    $charCount = strlen($characters);

                    for ($i = 0; $i < $length; $i++) {
                        $code .= $characters[random_int(0, $charCount - 1)];
                    }
                    $dbh->beginTransaction();

                    $sth = $dbh->prepare("INSERT INTO reset_password(user_id, email, res_code) VALUES (?,?,?)");
                    $sth->execute(array($isValidMail->user_id, $_POST['email'], $code));

                    $email = $isValidMail->email;

                    $subject = "Sound Meteor | Password Reset";
                    $text = '
    <html>
    <head>
    <title>Sound Meteor | Password Reset</title>
    </head>
    <body>
      <h1>Sound Meteor | Password Reset</h1>
      <p>Click the link below to reset your password:</p>
      <a href="https://users.multimediatechnology.at/~fhs49272/mmp1/reset.php?token='. $code .'&uid='. $isValidMail->user_id .'&mail='. $isValidMail->email . '">Reset Password</a>
    </body>
    </html>';
    
                    $headers = "MIME-Version: 1.0" . "\r\n" .
                        "From: No-Reply <no-reply@soundmeteor.at>" . "\r\n" .
                        "Content-type: text/html; charset=UTF-8" . "\r\n".
                        'X-Mailer: PHP/' . phpversion();

                    $result = 

                    $dbh->commit();
                    if(@mail(
                        $email,
                        $subject,
                        $text,
                        $headers
                    )){
                        header("Location: ". $_SERVER['PHP_SELF'] . '?status=res_succ');
                    }else{
                        header("Location: ". $_SERVER['PHP_SELF'] . '?status=res_fail');
                    }
                    
                } catch (Exception $e) {
                    $resetErr = "Something went wrong";
                    $dbh->rollBack();
                }
            }
        } catch (Exception $e) {
            $resetErr = "No connection to database";
        }
    }
}
    include "header.php";
    if (isset($_GET['status'])) {
        switch ($_GET['status']) {
            case 'res_succ':
                $msg = "Reset email sent successfully";
                break;
            case 'res_fail':
                    $msg = "Reset email could not be sent!";
                    break;
            case 'invalid_token':
                $msg = "Could not reset password, please try again!";
                break;
            default:
                echo "Oboi u fucked up mate";
                break;
        }
        echo "<div class=\"state-box hidden\">
                <span>$msg</span>
              </div>";
    }

?>

<form class="form-wrapper" method="post" id="form" autocomplete="off">
    <span class="form-title" style="font-size: 2rem; font-weight: 700; margin-bottom: -2.1rem">Reset Password</span>
    <div class="input-wrapper">
        <span class="input-label">Email<span class="error"><?= $mailErr ?></span></span>
        <input class="input-box" type="email" name="email" placeholder="Enter email" value="<?php if (isset($_POST['email'])) echo html_entity_decode($_POST['email']); ?>" required>
        <i class="fa-solid fa-envelope focus-input"></i>
    </div>
    <div style="display: flex; justify-content: center">
        <input id="reset" type="submit" name="reset" value="RESET PASSWORD" class="button">
    </div>
    <span style="width: 100%; text-align: center; margin-top: -1.5rem"><a class="navLink" href="./login.php">Cancel</a></span>
</form>

<?php
include "footer.php";
?>