<?php
include "functions.php";
$pagetitle = "Preferences";

include "header.php";
?>

<h1><?= $pagetitle ?></h1>
<div class="pref-container">
<div class="pref-menu">
    <ul>
        <li>
            <a <?php if(isset($_GET['pref'])){
                if($_GET['pref'] != "genre" && $_GET['pref'] != "mood" && $_GET['pref'] == "general")
                    {echo "class=\"pref-active\""; }} else if (!isset($_GET['pref'])) {echo "class=\"pref-active\"";}?> href="?pref=general"><i class="fa-solid fa-gear"></i>General&ensp;&gt;</a>
        </li>
        <li>
            <a <?php if(isset($_GET['pref'])){ if($_GET['pref'] == "genre"){echo "class=\"pref-active\""; }}?> href="?pref=genre"><i class="fa-solid fa-music"></i>Genres&ensp;&gt;</a>
        </li>
        <li>
            <a <?php if(isset($_GET['pref'])){ if($_GET['pref'] == "mood"){echo "class=\"pref-active\""; }}?> href="?pref=mood"><i class="fa-solid fa-masks-theater"></i>Moods&ensp;&gt;</a>
        </li>
    </ul>
</div>
<div class="pref-select">
    <div class=""></div>
</div>
</div>

<?php
include "footer.php";
?>