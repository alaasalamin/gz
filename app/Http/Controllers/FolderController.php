<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use Illuminate\Http\Request;
use function GuzzleHttp\Promise\all;

class FolderController extends Controller
{
    //
    public function __construct()
    {

    }

    public function validateFolderName(Request $request){
        $parentId = $request->parentFolder;
        $folderName = $request->folderName;
        $foldersInsideSameName = Folder::where("parentFolder", $parentId)->where("folderName", $folderName)->count();
        if($foldersInsideSameName == 0){
            return 1;
        }else{
            return 0;
        }
    }

    public function create(Request $request){
        $validate = $request->validate([
            "folderName" => "unique:folders|required"
        ]);

        Folder::create($request->all());

        return redirect()->back();

    }

    public function getFolders($parentId){
        $folders = Folder::where("parentFolder", $parentId)->where("status", "shown")->get();
        return $folders;
    }
    public function getWhatInIt($parentId){
        $folders = Folder::where("status", "shown")->where("parentFolder", $parentId)->get();
        $files = File::where("parentFolder", $parentId)->where("status", "shown")->get();
        $allIn = array();
        for($i=0; $i<sizeof($folders); $i++){
            $allIn [] = [
                "objectType" => "folder",
                "objectName" => $folders[$i]["folderName"],
                "updated_at" => $folders[$i]["updated_at"]->format('Y-m-d H:m:s'),
                "objectId" => $folders[$i]["id"]
            ];
        }
        for($i=0; $i<sizeof($files); $i++){
            $allIn [] = [
                "objectType" => "file",
                "objectName" => $files[$i]["fileName"],
                "updated_at" => $files[$i]["updated_at"]->format('Y-m-d H:m:s'),
                "objectId" => $files[$i]["id"],
                "fileType" => $files[$i]["fileType"],
                "objectDownloads" => $files[$i]["downloads"]
            ];
        }
        return $allIn;
    }

    public function createNewFolder(Request $request){

        $createFolder = Folder::create($request->all());
        if($createFolder){
            return 1;
        }else{
            return 0;
        }

    }

    public function deleteFolder($id){
         $folder = Folder::find($id);

         $folderName = $folder->folderName.".deleted(".$folder->id.")";

         $folder->update(['status' => 'deleted', 'folderName' => $folderName]);

    }

    public function getDeletedFiles(){
        $folders = Folder::where("status", "deleted")->orderBy("folderName", "DESC")->get();
        $files = File::where("status", "deleted")->orderBy("fileName", "DESC")->get();
        $allIn = array();
        for($i=0; $i<sizeof($folders); $i++){
            $allIn [] = [
                "objectType" => "folder",
                "objectName" => $folders[$i]["folderName"],
                "updated_at" => $folders[$i]["updated_at"]->format('Y-m-d H:m:s'),
                "objectId" => $folders[$i]["id"]
            ];
        }
        for($i=0; $i<sizeof($files); $i++){
            $allIn [] = [
                "objectType" => "file",
                "objectName" => $files[$i]["fileName"],
                "updated_at" => $files[$i]["updated_at"]->format('Y-m-d H:m:s'),
                "objectId" => $files[$i]["id"],
                "fileType" => $files[$i]["fileType"],
                "objectDownloads" => $files[$i]["downloads"]
            ];
        }
        return $allIn;
    }

    public function deleteFolderPermanently($id){
        $folder = Folder::find($id);
        $folder->destroy($id);
    }

}
