<?php
use App\Framework\Facades\App;
use App\Framework\Http\View;
use App\Models\Session;

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo View::Bag()["title"]; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>


<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#" style="padding-left: 8px"><?php echo App::Env("APP_NAME")?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav" style="width: 100%">
            <li class="nav-item active">
                <a class="nav-link" href="/documents">📃 Documents</a>
            </li>
            <?php if(Session::isValid()){

                echo '  <li class="nav-item"  style="margin-left: auto">
                <a class="nav-link" href="javascript:logout()">🔓 Logout</a>
                </li>';

            }?>
        </ul>
    </div>
</nav>

<script>
    function logout() {
        let uri = "<?php echo App::Env("AUTH_API")?>/auth/logout/<?php echo $_COOKIE["session"]?>";

        fetch(uri,{
            method: "GET",
        }).then(response => response.json())
            .then(data => {
                if(data.outcome){
                    console.log(data)
                    document.cookie = "session="+data.content.session+"; expires=Sun, 1 Jan 1970 00:00:00 UTC; path=/";
                    window.location.href = "/login?alert=success&message=Successfully logged out.";
                }
            })
    }
</script>

<div id="page-wrapper" style="padding-top: 2.5%;">
    <div class="container">
