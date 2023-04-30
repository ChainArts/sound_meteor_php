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
                    <li><a href="genres.php" <?php if ($pagetitle == "Genres") {
                                                    echo "class=\"current-page\"";
                                                } ?>>Genres</a></li>
                    <li><a href="moods.php" <?php if ($pagetitle == "Moods") {
                                                echo "class=\"current-page\"";
                                            } ?>>Moods</a></li>
                    <li><a href="pref.php" <?php if ($pagetitle == "Preferences") {
                                                echo "class=\"current-page\"";
                                            } ?>>Preferences</a></li>
                    <?php if($_SESSION['USER'] == "admin") : ?>
                    <li><a href="usrmngmt.php" <?php if ($pagetitle == "Users") {
                                                    echo "class=\"current-page\"";
                                                } ?>>Users</a></li>
                    <?php endif; ?>
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
                   " . $_SESSION['USER'] . "
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
    if ($pagetitle == "Login" || $pagetitle == "Add User" || $pagetitle == "Edit User") {
        echo "<main style=\"justify-content: center\">";
    } else {
        echo "<main>";
    }
    ?>