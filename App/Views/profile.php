<?php

use app\core\Alert;
use app\core\View;
use app\services\Auth;


if(View::Bag()["user"]->GetLoginId() == Auth::GetCurrentUser()->GetEmail()){
    $disabled = "";
    $h1 = "My Profile";
}else{
    $disabled = "readonly";
    $h1 = View::Bag()["user"]->GetUsername()."'s Profile";
}

if(isset($_GET["message"])){

    $alert = new Alert($_GET["message"],$_GET["type"]);
}

?>

<?php View::Breadcrumbs();?>

<h1><?php echo $h1?></h1>

<?php if(isset($alert)){$alert->render();}?>

<img src="<?php echo View::Bag()["user"]->GetProfilePicture()?>" alt="User Profile Image" width="128px">

<form class="row g-3" action="/api/updateuser/<?php echo View::Bag()["user"]->GetLoginId()?>" method="post">
    <div class="col-md-6">
        <label for="inputEmail4" class="form-label" >Login ID</label>
        <input type="text" class="form-control" id="inputEmail4" name="id" value="<?php echo View::Bag()["user"]->GetLoginId()?>" readonly>
    </div>
    <div class="col-md-6">
        <label for="inputPassword4" class="form-label">Username</label>
        <input type="text" class="form-control" id="inputPassword4" name="username" value="<?php echo View::Bag()["user"]->GetUsername()?>" readonly>
    </div>

    <?php if($h1 == "My Profile") {

        echo '
        <div class="col-md-6">
        <label for="inputPassword4" class="form-label">Password</label>
        <input type="password" class="form-control" name="password" id="inputPassword4" placeholder="********" required>
        </div>';

        echo ' <div class="col-md-6">
        <label for="inputPassword4" class="form-label">Confirm Password</label>
        <input type="password" class="form-control" name="passwordConfirmation" id="inputPassword4" placeholder="********" required>
        </div>';

        echo ' <div class="col-md-6">
        <label for="currentPassword" class="form-label">Current Password</label>
        <input type="password" class="form-control" name="currentPassword" id="currentPassword" placeholder="********" required>
        </div>';

        echo ' <div class="col-12">
        <button type="submit" class="btn btn-primary">Update Password</button>
    </div>';
    }
        ?>

</form

<h2></h2>
<br>

<a href="/forum/posts/<?php echo View::Bag()["user"]->GetLoginId()?>" class="btn btn-primary" role="button">Views Posts</a>