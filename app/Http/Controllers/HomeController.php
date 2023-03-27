<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $folders = Folder::where("parentFolder", "0")->orderBy("folderName", "ASC")->get();
        $files = File::where("parentFolder", "0")->orderBy("fileName", "ASC")->get();
        return view('home.home', [
            "folders" => $folders,
            "files" => $files
        ]);
    }
}
