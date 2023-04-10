<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

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
        $deletedFolders = Folder::where("status", "deleted")->count();
        $deletedFolders += File::where("status", "deleted")->count();
        $user = Auth::user();
        auth()->user()->tokens()->delete();
        $token = $user->createToken('accessToken')->plainTextToken;

        return view('home.home', [
            "deletedCounter" => $deletedFolders,
            "accessToken" => $token,
        ]);
    }
}
