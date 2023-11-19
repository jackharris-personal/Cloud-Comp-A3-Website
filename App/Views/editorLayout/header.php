<?php
use App\Framework\Facades\App;
use App\Framework\Http\View;

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo App::Env("APP_NAME")?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo App::Env("AWS_CLOUD_FRONT")."/static/css/document-editor.css"?>" id="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark no-print" style="position: fixed; width: 100vw; z-index: 1">
    <div class="container-fluid">
        <ul class="navbar-nav" style="width: 100%">
            <li class='nav-item'>
                <a class='nav-link active' href='#'>✍️ Editor / <?php echo View::Bag()["document"]["name"];?></a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                   File
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <li><a class="dropdown-item" href="javascript:saveChanges()">Close & Save Changes</a></li>
                    <li><a class="dropdown-item" href="/documents">Close & Discard Changes</a></li>
                    <li><a class="dropdown-item" href="javascript:Editor.instance.models['scriptEditor'].toggle()">Script Editor</a></li>
                    <li><a class="dropdown-item" href="javascript:Editor.instance.exportAsPDF()">Export as PDF</a></li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Edit
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <li><a class="dropdown-item" href="javascript:Editor.instance.cutSelectedComponent()">Cut</a></li>
                    <li><a class="dropdown-item" href="javascript:Editor.instance.copySelectedComponent()">Copy</a></li>
                    <li><a class="dropdown-item" href="javascript:Editor.instance.pasteSelectedComponent()">Paste</a></li>
                    <li><a class="dropdown-item" href="javascript:Editor.instance.models['deleteComponent'].toggle()">Delete</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark no-print" style="position: fixed; width: 100vw; margin-top: 50px; background-color: #ff8f00!important;">
    <div class="container-fluid">
        <ul class="navbar-nav" style="width: 100%">
            <li class="property-dropdown">
                <label for="myInput"></label><input type="text" placeholder="Search.." id="property-dropdown-input" style="width: 256px">
                <div id="property-dropdown-selector" style="display: none">
                </div>
            </li>
            <li class='nav-item'>
                <p><strong>=</strong></p>
            </li>
            <li class='nav-item' style="width: 100%">
                <input type="text" style="width: inherit" id="property-editor">
                <div id="property-editor-selection-suggestion">
            </li>

            <li id="component-location-selector">
                <a href="javascript:Editor.instance.moveComponent(-1)">⬆️</a>
                <a href="javascript:Editor.instance.moveComponent(1)">⬇️</a>
                <a href="javascript:Editor.instance.saveComponent()">💾</a>
                <a href="javascript:Editor.instance.models['deleteComponent'].toggle()">🗑️</a>
            </li>
        </ul>
    </div>
</nav>

<script>
    function saveChanges() {
        console.log("saving!")
    }

</script>

<div class="container-fluid">
    <div class="row flex-nowrap">


