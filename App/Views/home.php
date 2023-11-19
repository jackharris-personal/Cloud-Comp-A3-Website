<?php
/**
 * Copyright Jack Harris
 * Peninsula Interactive - policyManager
 * Last Updated - 19/11/2023
 */

use App\Models\Project;
use App\Views\Alert;

?>
<style>
    .document-preview{
        text-decoration: none;
    }

    .document-preview div{
        background-color: #363636;
        width: 100px;
        border-radius: 8px;
        padding: 4px;
        text-align: center;
    }

    .document-preview div span{
        font-size: 32px;
    }

    .document-preview div p{
        color: white
    }
</style>
<h1>My Documents</h1>
<br>
<?php Alert::render();?>
<a class="btn btn-primary" href="/documents/new">New Document</a>
<div style="display: flex; flex-direction: row; flex-wrap: wrap; gap: 8px; margin-top: 16px">
<?php

$documents = Project::getDocuments();

if($documents !== null){
    foreach ($documents as $document){

        echo '<a class="document-preview" href="/documents/'.$document[0].'/editor"><div><span>📄</span><p>'.$document[1].'</p></div></a>';
    }
}



?></div>


