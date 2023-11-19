<?php
use App\Framework\Facades\App;
use App\Views\Alert;

?>
<h1>Login</h1>
<br>
<form action="javascript:login()" method="post" id="login">
    <?php Alert::render();?>
    <div class="form-group">
        <label for="exampleInputEmail1">Email address</label>
        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" name="mail">
        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">Password</label>
        <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="password">
    </div>
    <br>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>

<script>
    function login() {
        let uri = "<?php echo App::Env("AUTH_API")?>/auth/login";
        let data = new FormData(document.getElementById("login"));

        fetch(uri,{
            method: "POST",
            body: data,


        }).then(response => response.json())
            .then(data => {
                if(data.outcome){
                    console.log(data)
                    document.cookie = "session="+data.content.session+"; expires=Sun, 1 Jan 2025 00:00:00 UTC; path=/";
                    window.location.href = "/documents"+data.content.redirect;
                }else{
                    window.location.href = "/login?alert=danger&message="+data.message;
                }
            });
    }


</script>