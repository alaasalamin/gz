<!doctype html>
<html lang="en">
@include('layouts/head')
<body>

@include('layouts/header')

<section class="mainSection position-relative">

    <nav class="navbar mt-5 mb-3 navbar-light justify-content-between">
        <div class="container">
        <a class="navbar-brand">GrillZimmer</a>
        <form class="form-inline searchBar">
                <input type="search" placeholder="Search..." id="fileSearchInput" onkeyup="quickSearch()">
                <button type="submit">Search</button>
        </form>
        </div>
    </nav>

    <div class="container-fluid">


        <div class="row">
            <div class="col-xl-3 col-md-3 col-xs-12">
                <div class="card mt-4 ">
                    <div class="card-header bg-dark text-center text-light">
                        Nav
                    </div>
                    <ul class="list-group list-group-flush">
                       <ul class="list-group-item">
                           Home
                           <li id="currentPath" class="list-group-item">

                           </li>
                       </ul>
                    </ul>
                </div>
            </div>
            <div class="col-xl-6 col-md-6 col-xs-12">
                <div class="card mt-4 ">
                    <table id="filesTable" class="table">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Modified</th>
                            <th scope="col">Downloads</th>
                            <th scope="col">-</th>
                        </tr>
                        </thead>

                        <tbody>
                        @if($folders->isEmpty())
                            <tr>
                                <td>Es gibt keine Ordner</td>
                            </tr>

                        @else
                            @foreach($folders as $folder)
                                <tr>
                                    <td scope="row">
                                        <img src="style/images/folder.png" width="40" class="mr-3" alt="">
                                        <a onclick="changeFolder({{$folder->id}}, '{{$folder->folderName}}')" href="#">{{$folder->folderName}}</a>
                                    </td>
                                    <td>{{$folder->updated_at}}</td>
                                    <td>{{$folder->downloads}}</td>
                                    <td>...</td>
                                </tr>
                            @endforeach
                        @endif

                        @foreach($files as $file)
                            <tr>
                                <td scope="row">
                                        @if($file->fileType == "png")
                                            <img src="style/images/photo.png" width="40" class="mr-3" alt="">
                                            @elseif($file->fileType == "jpg")
                                            <img src="style/images/photo.png" width="40" class="mr-3" alt="">
                                            @elseif($file->fileType == "csv")
                                            <img src="style/images/csv.png" width="40" class="mr-3" alt="">
                                            @elseif($file->fileType == "pdf")
                                            <img src="style/images/pdf.png" width="40" class="mr-3" alt="">
                                        @endif
                                    <a href="{{$file->downloadLink}}" download>{{$file->fileName}}</a>
                                </td>
                                <td>{{$file->updated_at}}</td>
                                <td>{{$file->downloads}}</td>
                                <td>...</td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-xl-3 col-md-3 col-xs-12">
                <div class="card mt-4">
                    <div class="card-header bg-dark text-light text-center">
                        Daten hochladen
                    </div>

                        @if($errors->any())
                        <div style="width: 90%" class="alert alert-danger mt-2 ml-auto mr-auto">
                            @foreach ($errors->all() as $error)
                                <li class="">{{$error}}</li>
                            @endforeach
                        </div>
                        @endif
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"> <a class="text-decoration-none" onclick="newFolder()" href="#">neuer Ordner</a></li>
                        <li id="newFolderForm" class="list-group-item" style="display: none">
                            <form action="{{url('newFolder')}}" method="POST">
                                @csrf
                                <input name="folderName" type="text" class="form-control" placeholder="Ordnernamen">
                                <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                                <input id="parentFolder" type="hidden" name="parentFolder" value="0">
                            </form>
                        </li>

                        <li class="list-group-item" "><a href="#" onclick="newFile()">neue Datei</a></li>
                        <li id="newFileForm" class="list-group-item" style="display: none">
                            <form action="{{url('filesUpload')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input multiple name="files[]" type="file" class="form-control">
                                <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                                <input id="fileParentFolder" type="hidden" name="parentFolder" value="0">
                                <button class="btn btn-dark mt-2 end-0">Hochladen</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>




</section>

<div class="footer">

</div>

<script>
    var Path = "/";

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
    function changeFolder(folderId, folderName){
        Path = Path +" "+ folderName +" /";
        document.getElementById("currentPath").innerHTML = Path;
        document.getElementById("parentFolder").value = folderId;
        document.getElementById("fileParentFolder").value = folderId;
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


</script>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>
