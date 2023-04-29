<?php
include "functions.php";
$pagetitle = "User";

include "header.php";
?>
<h1><?= $pagetitle ?></h1>

<form action="user_delete.php" method="post">
    <div class="button">
        <input type="hidden" value="<?php echo htmlspecialchars($_GET['id']); ?>" name="user_id">
        <input type="submit" name="delete" value="Delete" class="btn">
    </div>
</form>

<?php
include "footer.php";
?>