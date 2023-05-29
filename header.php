<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Sound Meteor | <?= $pagetitle ?></title>
    <link rel="icon" href="./media/meteor.svg">
    <link rel="stylesheet" href="./style/style.css">
    <script src="https://kit.fontawesome.com/d1ca790695.js" crossorigin="anonymous"></script>

</head>

<body>
    <header>
        <nav>
            <div class="logo" style="margin-right: 3rem"><a href="index.php"><img src="./media/SoundMeteor.svg" alt="Logo"></a></div>
            <?php if (isset($_SESSION['USER'])) :
            ?>

                <ul class="mobile-hide">
                    <li><a href="meteor.php" <?php if ($pagetitle == "Meteor") {
                                                echo "class=\"current-page\"";
                                            } ?>>Meteor</a></li>
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
                <div class=\"profile-name mobile-hide\">
                   " . htmlspecialchars($_SESSION['USER']) . "
                </div>
                <div class=\"profile-img\" onclick=toggleUserOpen()>
                    <img src=\"./media/logo.jpg\">
                </div>
                <form action=\"logout.php\" method=\"post\" class=\"mobile-hide\">
                    <button type=\"submit\"><i class=\"fa-solid fa-right-from-bracket\"></i></button>      
                </form>
                <div class=\"hamburger-menu mobile-show\" onclick=toggleMenuOpen()>
                <div class=\"menu-toggle-icon menu-toggle-closed\" id=\"menu-icon\">
                    <div class=\"bar\"></div>
                    <div class=\"bar\"></div>
                    <div class=\"bar\"></div>
                </div>
            </div>
                
                
                ";
            }
            ?>
        </div>
        <div class="nav-overlay">
            <nav class="nav-overlay-container">
                <div class="logo"><a href="index.php"><img src="./media/SoundMeteor.svg" alt="Logo"></a></div>
                <ul>
                    <li><a href="meteor.php" <?php if ($pagetitle == "Meteor") {
                                                echo "class=\"current-page\"";
                                            } ?>>Meteor</a></li>
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
            </nav>
        </div>
        <div class="usr-backdrop" onclick=toggleUserOpen()></div>
        <div class="usr-overlay">
            <div class="usr-overlay-container">
                <div class="usr-overlay-close" onclick=toggleUserOpen()>
                    <i class="fa-regular fa-circle-xmark"></i>
                </div>
                <span class="usr-title">User Settings</span>
                <div class="usr-details">
                    <img src="./media/logo.jpg">
                    <span><?= htmlspecialchars($_SESSION['USER']) ?></span>
                </div>

            </div>
        </div>
    </header>

    <?php
    if ($pagetitle == "Login" || str_contains($pagetitle, "Add") || str_contains($pagetitle, "Edit")) {
        echo "<main style=\"justify-content: center\">";
    } else {
        echo "<main>";
    }
    ?>