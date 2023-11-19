<?php
/**
 * Copyright Jack Harris
 * Peninsula Interactive - policyManager
 * Last Updated - 18/11/2023
 */

namespace App\Controllers;

use App\Framework\Http\View;
use App\Models\Project;
use App\Models\Session;

class DocumentController
{

    public function home(){

        Session::validateCurrentOrRedirect();

        return View::load("home.php");
    }

    public function newDocument(){

        return View::load("newDocument.php");
    }

    public function edit($id): View{

        Session::validateCurrentOrRedirect();
        View::setLayout("editorLayout");
        View::Bag()["document"] = Project::getDocument($id);

        return View::load("editor.php");
    }

}