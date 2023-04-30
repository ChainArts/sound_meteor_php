<?php
include "functions.php";
if (!isset($_SESSION["USER"])) {
    if($_SESSION["USER"] != "admin")
    {
        header('Location: schlingel.php?msg=no-access');
    }
    header('Location: schlingel.php');
}
$pagetitle = "Users";

include "header.php";

if (isset($_GET['status'])) {
    switch ($_GET['status']) {
        case 'del_success':
            $msg = "User was deleted successfully";
            break;
        case 'add_success':
            $msg = "User was added successfully";
            break;
        case 'edit_success':
            $msg = "User was edited successfully";
            break;
        case 'del_fail':
            $msg = "User was not deleted!";
            break;
        case 'add_fail':
            $msg = "User was not added!";
            break;
        case 'edit_fail':
            $msg = "User could not be edited";
            break;
        case 'display_fail':
            $msg = "User could not be displayed";
            break;
        default:
            $msg = "Oboi u fucked up mate";
            break;
    }
    echo "<div class=\"state-box hidden\">
            <span>$msg</span>
          </div>";
}
?>
<h1><?= $pagetitle ?></h1>
<div class="button" style="margin-bottom: 2rem">
    <a href="user_new.php"><span>Add User</span></a>
</div>
<div class="table-wrapper">
    <table>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Username</th>
            <th>Email</th>
        </tr>

        <?php
        try {
            $sth = $dbh->prepare("SELECT * FROM users ORDER BY user_id");
            $sth->execute(array());
            $usr = $sth->fetchAll();

            if (empty($usr)) {
                echo "<tr><td colspan = '5'>No users found.</td></tr>";
            } else {
                foreach ($usr as $user) {
                    echo "
                        <tr onclick=\"window.location='user_edit.php?id=" . htmlspecialchars($user->user_id) . "' \">
                            <td>" . htmlspecialchars($user->user_id) . "</td>
                            <td>" . htmlspecialchars($user->f_name) . "</td>
                            <td>" . htmlspecialchars($user->l_name) . "</td>
                            <td>" . htmlspecialchars($user->username) . "</td>
                            <td>" . htmlspecialchars($user->email) . "</td>
                        </tr>";
                }
            }
        } catch (Exception $e) {
            //echo $e->getMessage();
        }
        ?>
    </table>
</div>

<?php
include "footer.php";
?>