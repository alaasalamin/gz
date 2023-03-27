<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    //
    public function __construct()
    {

    }

    public function create(Request $request){
        $validate = $request->validate([
            "folderName" => "unique:folders|required"
        ]);

        Folder::create($request->all());

        return redirect()->back();

    }
}
