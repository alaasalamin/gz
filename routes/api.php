<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\FolderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("getFolders/{id}", [FolderController::class, "getWhatInIt"]);
Route::post("createNewFolder", [FolderController::class, "createNewFolder"]);
Route::post("validateFolderName", [FolderController::class, "validateFolderName"]);
Route::get("deleteFolder/{id}", [FolderController::class, "deleteFolder"]);
Route::get("deleteFile/{id}", [FileController::class, "deleteFile"]);
Route::get("deleteFilePermanently/{id}", [FileController::class, "deleteFilePermanently"]);
Route::get("deleteFolderPermanently/{id}", [FolderController::class, "deleteFolderPermanently"]);
Route::get("getDeletedFiles", [FolderController::class, "getDeletedFiles"]);
Route::post("uploadMultipleFiles", [FileController::class, "filesUploadMultiple"]);
