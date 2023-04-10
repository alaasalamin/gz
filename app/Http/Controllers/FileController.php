<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use PharIo\Manifest\Url;
use PhpParser\Node\Expr\Array_;

class FileController extends Controller
{
    //


    public function filesUpload(Request $request){
        $files = $request->file('files');
        $request->validate([
            "files" => "required"
        ]);

        $TableInfo = Array();

        foreach($files as $file){
            $filename = time().$file->getClientOriginalName();
            $move = $file->move(public_path('files'), $filename);

            $downloadLink = url("/files/".$filename);
            $userId = $request->user_id;
            $TableInfo[] = [
                "fileName" => $file->getClientOriginalName(),
                "user_id" => $userId,
                "fileType" => $file->getClientOriginalExtension(),
                "downloadLink" => $downloadLink,
                "parentFolder" => $request->parentFolder
            ];



        }


        for ($i=0; $i<sizeof($TableInfo); $i++){
            File::create($TableInfo[$i]);
        }

        return redirect()->back();
    }



    public function filesUploadMultiple(Request $request){
        $files = $request->file('files');
        $TableInfo = array();
        foreach ($files as $file){
            $filename = "(".time().")".$file->getClientOriginalName();
            $move = $file->move(public_path('files'), $filename);

            $downloadLink = url("/files/".$filename);
            $userId = $request->user_id;
            $TableInfo[] = [
                "fileName" => $file->getClientOriginalName(),
                "user_id" => $userId,
                "fileType" => $file->getClientOriginalExtension(),
                "downloadLink" => $downloadLink,
                "parentFolder" => $request->parentFolder
            ];

        }


        for ($i=0; $i<sizeof($TableInfo); $i++){
            File::create($TableInfo[$i]);
        }

        return 1;
    }

    public function deleteFile($id){
        $file = File::find($id);

        $fileName = $file->fileName.".deleted(".$file->id.")";

        $file->update(['status' => 'deleted', 'fileName' => $fileName]);


    }
    public function deleteFilePermanently($id){
        $file = File::find($id);
        $file->destroy($id);
    }

    public function downloadFile($id){
        $file = File::find($id);

        $downloads = $file->downloads;
        $downloads++;
        $file->update(["downloads" => $downloads]);
        return response([
            'downloadLink' => $file->downloadLink,
            'fileName' => $file->fileName
        ], 201);
    }

}
