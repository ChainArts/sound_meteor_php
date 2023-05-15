<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Sound Meteor | <?= $pagetitle ?></title>
    <link rel="stylesheet" href="./style/style.css">
    <script src="https://kit.fontawesome.com/d1ca790695.js" crossorigin="anonymous"></script>

</head>

<body>
    <header>
        <nav>
            <div class="logo"><a href="index.php"><img src="./media/SoundMeteor.svg" alt="Logo"></a></div>
            <?php if (isset($_SESSION['USER'])) :
            ?>

                <ul>
                    <li><a href="music.php" <?php if ($pagetitle == "Music") {
                                                echo "class=\"current-page\"";
                                            } ?>>Music</a></li>
                    <li><a href="comets.php" <?php if ($pagetitle == "Comets") {
                                                    echo "class=\"current-page\"";
                                                } ?>>Comets</a></li>
                    <li><a href="pref.php" <?php if ($pagetitle == "Preferences") {
                                                echo "class=\"current-page\"";
                                            } ?>>Preferences</a></li>
                    <li><a href="about.php" <?php if ($pagetitle == "About") {
                                                echo "class=\"current-page\"";
                                            } ?>>About</a></li>
                </ul>
            <?php endif; ?>
        </nav>
        <div class="login">
            <?php
            if (!isset($_SESSION['USER'])) {
                if ($pagetitle != "Login") {
                    echo "
                    <div class=\"button\">
                        <a href=\"login.php\"><span>Login</span></a>
                    </div>";
                }
            } else {
                echo "
                <div class=\"profile-name\">
                   " . htmlspecialchars($_SESSION['USER']) . "
                </div>
                <div class=\"profile-img\">
                    <img src=\"./media/logo.jpg\">
                </div>
                <form action=\"logout.php\" method=\"post\">
                    <button type=\"submit\"><i class=\"fa-solid fa-right-from-bracket\"></i></button>      
                </form>";
            }
            ?>



        </div>
    </header>

    <?php
    if ($pagetitle == "Login" || str_contains($pagetitle, "Add")|| str_contains($pagetitle, "Edit")) {
        echo "<main style=\"justify-content: center\">";
    } else {
        echo "<main>";
    }
    ?>