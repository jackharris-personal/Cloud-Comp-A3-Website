<?php
use App\Framework\Facades\App;
use App\Views\Alert;

?>
<h1>New Document</h1>
<br>
<form action="javascript:newDocument()" method="post" id="newDocument">
    <?php Alert::render();?>
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" aria-describedby="emailHelp" placeholder="My Document" name="name" id="name" required
        <small id="emailHelp" class="form-text text-muted">The name of your document</small>
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <input type="text" class="form-control" id="description" aria-describedby="emailHelp" placeholder="Branding guidelines for my new company" name="description" required>
    </div>
    <br>
    <button type="submit" class="btn btn-primary">Create</button>
    <a href="/documents" class="btn btn-danger">Cancel</a>
</form>

<script>
    function newDocument() {
        let uri = "<?php echo App::Env("AWS_API_GATEWAY")?>/editor/document/new";

        fetch(uri,{
            method: "POST",
            body: new URLSearchParams({
                'name': document.getElementById("name").value,
                'description': document.getElementById("name").value
            }),
            headers: {
                'Authorization': 'Bearer <?php echo $_COOKIE["session"]?>',
                'Content-Type': 'application/x-www-form-urlencoded'
            }

        }).then(response => response.json())
        .then(data => {
            if(data.outcome){
                window.location.href = "/documents/?alert=success&message=Document successfully created.";
            }else{
                window.location.href = "/documents/new?alert=danger&message="+data.message;
            }
        });
    }


</script>