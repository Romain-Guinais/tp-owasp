<?php
require_once('functions.php');
$user = null;
if(isset($_GET['id'])) {
    $users = getUser($_GET['id']);
    if(!empty($users)) {
        $user = $users[0];
    }
}
?>

<?php if($user): ?>
<h1>Information de l'utilisateur <?= $user->email ?></h1>
<table>
    <tr>
        <td>id</td>
        <td><?= $user->id ?></td>
    </tr>
    <tr>
        <td>username</td>
        <td><?= $user->username ?></td>
    </tr>
    <tr>
        <td>email</td>
        <td><?= $user->email ?></td>
    </tr>
</table>
<?php else: ?>
    L'utilisateur recherché n'existe pas
<?php endif; ?>
<br/>
<br/>
<a href="index.php">Accueil</a>
