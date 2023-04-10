<!doctype html>
<html lang="en">
@include('layouts/head')


<body>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>

@include('layouts/header')

<div class="headerNav">
    <img src="style/images/logoGZ.png" style="position: absolute; top: 100px; left: 20px;" width="350" alt="">
<div style="position: absolute; right: 20px; top: 100px;">
    <form style="width: 240px; " class="form-inline searchBar">
        <input type="search" placeholder="Search..." id="fileSearchInput" onkeyup="quickSearch()">
        <button type="submit">Search</button>
    </form>
</div>
</div>
<section class="mainSection position-relative">
    <nav class="navbar mt-5 mb-3 navbar-light justify-content-between">
        <div class="container">
        <a class="navbar-brand">

        </a>

        </div>
    </nav>


    <div class="container-fluid">




        <div class="row">
            <div class="col-xl-3 col-md-3 col-xs-12">
                <div class="card mt-4 ">
                    <div style="background: #000;" class="card-header text-center text-light">
                        <a style="font-weight: bold"> Nav</a>
                    </div>
                    <ul class="list-group list-group-flush">
                       <ul class="list-group-item">
                           <a href="#" style="color: #000; font-weight: bold" class="nav-item" onclick="loadFolder(0)">Home</a>
                           <li id="currentPath" class="list-group-item">

                           </li>
                       </ul>
                    </ul>
                </div>
            </div>
            <div class="col-xl-6 col-md-6 col-xs-12">
                <div class="card mt-4">
                    <table id="filesTable" class="table">
                        <thead class="" style="background: #000; color: #fff;">
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Modified</th>
                            <th scope="col">Downloads</th>
                            <th scope="col">-</th>
                        </tr>
                        </thead>

                        <tbody>


                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-xl-3 col-md-3 col-xs-12">
                <div class="card mt-4">
                    <div style="background: #000;" class="card-header text-light text-center">
                        <a style="font-weight: bold">Daten hochladen</a>
                    </div>

                        @if($errors->any())
                        <div style="width: 90%" class="alert alert-danger mt-2 ml-auto mr-auto">
                            @foreach ($errors->all() as $error)
                                <li class="">{{$error}}</li>
                            @endforeach
                        </div>
                        @endif
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"> <a class="text-decoration-none" style="color: #000; font-weight: bold" onclick="newFolder()" href="#">Neuer Ordner</a></li>
                        <li id="newFolderForm" class="list-group-item" style="display: none">
                                <input onkeypress="folderNameDeclared(event, this.value)" id="folderName" type="text" class="form-control" placeholder="Ordnernamen">
                                <p id="folderNameError" style="display: none" class="py-2 text-danger text-center"></p>
                                <input id="userId" type="hidden" name="user_id" value="{{Auth::user()->id}}">
                                <input id="parentFolder" type="hidden" name="parentFolder" value="0">
                                <input type="hidden" name="token" value="{{csrf_token()}}">
                        </li>

                        <li class="list-group-item" "><a href="#" onclick="newFile()" style="color: #000; font-weight: bold">Neue Datei</a></li>
                        <li id="newFileForm" class="list-group-item" style="display: none">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div id="drag_drop">Drag & Drop Dateien hier</div>
                                        </div>
                                    </div>
                            </div>
                                <div class="progress w-75 ml-auto mr-auto mb-2" id="progress_bar" style="display:none; height:50px;">
                                    <div class="progress-bar bg-success" id="progress_bar_process" role="progressbar" style="width:0%; height:50px;">0%

                                    </div>
                                </div>
                                <div id="uploaded_image" class="row mt-2 w-75"></div>
                                <input id="fileParentFolder" type="hidden" name="parentFolder" value="0">
{{--                            <form action="{{url('filesUpload')}}" method="POST" enctype="multipart/form-data">--}}
{{--                                @csrf--}}
{{--                                <input multiple name="files[]" type="file" class="form-control">--}}
{{--                                <input type="hidden" name="user_id" value="{{Auth::user()->id}}">--}}
{{--                                <input id="fileParentFolder" type="hidden" name="parentFolder" value="0">--}}
{{--                                <button class="btn btn-dark mt-2 end-0">Hochladen</button>--}}
{{--                            </form>--}}
                        </li>

                        <li class="list-group-item" "><a href="#" onclick="deletedFiles()" style="color: #000; font-weight: bold">Gelöschte Dateien</a></li>

                    </ul>
                </div>
            </div>
        </div>
    </div>


    <input type="hidden" id="accessToken" value="{{$accessToken}}">

</section>
<iframe id="my_iframe" style="display:none;"></iframe>
<div class="footer">

</div>
<script src="js/fileManager.js"></script>
<script>

    let accessToken = document.getElementById("accessToken").value;
    var Path = "/";

    var parentId = 0;

    document.getElementById("currentPath").innerHTML = Path;

    function newFolder(){
        if(document.getElementById("newFolderForm").style.display === "block"){
            document.getElementById("newFolderForm").style.display = "none";
        }else{
            document.getElementById("newFolderForm").style.display = "block";
        }
    }
    function newFile(){
        if(document.getElementById("newFileForm").style.display === "block"){
            document.getElementById("newFileForm").style.display = "none";
        }else{
            document.getElementById("newFileForm").style.display = "block";
        }
    }


    var input = document.getElementById("fileSearchInput");
    var  filter, table, tr, i, txtValue;
    function quickSearch() {
        filter = input.value.toUpperCase();
        table = document.getElementById("filesTable");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }



    function folderNameDeclared(e, folderName){
        let input = document.getElementById("folderName");
            var code = (e.keyCode ? e.keyCode : e.which);
            if(code == 13) { //Enter keycode
                if(folderName === ""){
                    input.style.border = "2px solid red";
                }else{


                const url = "http://127.0.0.1:8000/api/validateFolderName";
                var xhr = new XMLHttpRequest();
                xhr.open("POST", url, true);
                xhr.setRequestHeader('Content-Type', 'application/json');
                xhr.send(JSON.stringify({
                    folderName: folderName,
                    parentFolder: parentId
                }));
                xhr.onload = function() {
                    var data = this.responseText;
                    if(data === "1"){
                        document.getElementById("folderNameError").style.display = "none";
                        createNewFolder(folderName);
                    }else{

                        input.style.border = "2px solid red";
                        document.getElementById("folderNameError").style.display = "block";
                        document.getElementById("folderNameError").innerText = folderName+": ist bereits vergeben";
                    }
                }
            }
            }

    }



    function _(element)
    {
        return document.getElementById(element);
    }

    _('drag_drop').ondragover = function(event)
    {
        this.style.borderColor = '#333';
        return false;
    }

    _('drag_drop').ondragleave = function(event)
    {
        this.style.borderColor = '#ccc';
        return false;
    }


    _('drag_drop').ondrop = function(event)
    {
        event.preventDefault();

        var form_data  = new FormData();

        var image_number = 1;

        var error = '';

        var drop_files = event.dataTransfer.files;

        for(var count = 0; count < drop_files.length; count++)
        {
            if(!['application/pdf', 'image/png', 'video/mp4'].includes(drop_files[count].type))
            {
                error += '<div class="alert alert-danger"><b>'+image_number+'</b> Selected File must be .jpg or .png Only.</div>';
            }
            else
            {
                form_data.append("files[]", drop_files[count]);
                form_data.append("user_id", 1);
                form_data.append("parentFolder", parentId);
            }

            image_number++;
        }

        if(error != '')
        {
            _('uploaded_image').innerHTML = error;
            _('drag_drop').style.borderColor = '#ccc';
        }
        else
        {
            _('progress_bar').style.display = 'block';

            var ajax_request = new XMLHttpRequest();

            const url = "http://127.0.0.1:8000/api/uploadMultipleFiles";
            ajax_request.open("post", url);

            ajax_request.upload.addEventListener('progress', function(event){

                var percent_completed = Math.round((event.loaded / event.total) * 100);

                _('progress_bar_process').style.width = percent_completed + '%';

                _('progress_bar_process').innerHTML = percent_completed + '% completed';

            });

            ajax_request.addEventListener('load', function(event){


                loadFolder(parentId);

            });

            ajax_request.send(form_data);
        }
    }



</script>




<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>
