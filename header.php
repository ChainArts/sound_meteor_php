<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Sound Meteor | <?= $pagetitle ?></title>
    <link rel="icon" href="./media/meteor.svg">
    <link rel="stylesheet" href="./style/fontawesome/all.min.css">
    <link rel="stylesheet" href="./style/style.css">
    <link rel="stylesheet" href="./style/simplebar/simplebar.css">
    <script src="script/api.js"></script>
</head>

<body>
    <header>
        <nav>
            <div class="logo" style="margin-right: 3rem"><a href="./"><img src="./media/SoundMeteor.svg" alt="Logo"></a></div>
            <ul class="mobile-hide">
            <?php
            if (isset($_SESSION['USER'])) :
            ?>

                
                    <li><a href="meteor.php" <?php if ($pagetitle == "Meteor") {
                                                    echo "class=\"current-page\"";
                                                } ?>>Meteor</a></li>
                    <li><a href="comets.php" <?php if ($pagetitle == "Comets") {
                                                    echo "class=\"current-page\"";
                                                } ?>>Comets</a></li>
                    <li><a href="pref.php" <?php if ($pagetitle == "Preferences") {
                                                echo "class=\"current-page\"";
                                            } ?>>Preferences</a></li>
            <?php endif; ?>
                    <li><a href="about.php" <?php if ($pagetitle == "About") {
                                                echo "class=\"current-page\"";
                                            } ?>>About</a></li>
                </ul>
            
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
                <div class="logo"><a href="/"><img src="./media/SoundMeteor.svg" alt="Logo"></a></div>
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
        <?php if($pagetitle != "Login" && isset($_SESSION['USER'])){ ?>
        <div class="usr-backdrop" onclick="toggleUserOpen(); cleanupUserEdit();"></div>
        <div class="usr-overlay">
            <div class="usr-overlay-container">
                <div class="usr-overlay-close" onclick="toggleUserOpen(); cleanupUserEdit();">
                    <i class="fa-regular fa-circle-xmark"></i>
                </div>
                <span class="usr-title">User Settings</span>
                <div class="usr-details">
                    <img src="./media/logo.jpg">
                    <span><?= htmlspecialchars($_SESSION['USER']) ?></span>
                </div>
                <form class="edit-form" method="get" id="edit-form" autocomplete="off">
                    <label class="edit-form-label">Username</label>
                    <div class="edit-field" id="usr-edit">
                        <div class="edit-content">
                            <span id="usr-name-edit"><?= htmlspecialchars($_SESSION['USER']) ?></span>
                            <input id="usr-name-input"type="text" class="edit-box hiddenform" value="<?= htmlspecialchars($_SESSION['USER']) ?>">
                        </div>
                        <div class="edit-buttons">
                            <div id="edit-btn" class="button" onClick="toggleEdit('usr-edit', true)"><span>Edit</span></div>
                            <div id="ok-btn" class="button hiddenform" onClick="toggleEdit('usr-edit', false)"><span>OK</span></div>
                        </div>
                    </div>
                    <label class="edit-form-label">Email</label>
                    <div class="edit-field" id="email-edit">
                        <div class="edit-content">
                            <span id="usr-mail-edit"><?= htmlspecialchars($_SESSION['EMAIL']) ?></span>
                            <input id="usr-mail-input" type="email" class="edit-box hiddenform" value="<?= htmlspecialchars($_SESSION['EMAIL']) ?>">
                        </div>
                        <div class="edit-buttons">
                            <div id="edit-btn" class="button" onClick="toggleEdit('email-edit', true)"><span>Edit</span></div>
                            <div id="ok-btn" class="button hiddenform" onClick="toggleEdit('email-edit', false)"><span>OK</span></div>
                        </div>
                    </div>
                    <label class="edit-form-label">Password</label>
                    <div class="edit-field" id="pwd-edit">
                        <div class="edit-content">
                            <span>********</span>
                        </div>
                        <div class="edit-buttons">
                            <a href="./change.php"><div id="edit-btn" class="button"><span>Edit</span></div></a>
                        </div>
                    </div>
                    <div class="edit-button hiddenform">
                        <div class="button" onclick=checkUserEdit();>
                            <span>save</span>
                        </div>
                    </div>
                </form>

            </div>
        </div>
        <?php } ?>
    </header>

    <?php
    if ($pagetitle == "Login" || $pagetitle == "Forgot Password" || $pagetitle == "Reset Password" || $pagetitle == "Change Password"){
        echo "<main style=\"justify-content: center\">";
    } else {
        echo "<main> ";
    }
    ?>