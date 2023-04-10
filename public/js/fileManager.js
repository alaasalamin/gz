const filesTable = document.querySelector('#filesTable > tbody');
function loadFolder(parentId){


    var url = "http://127.0.0.1:8000/api/getFolders/"+parentId;

    const xhr = new XMLHttpRequest();
    xhr.open("GET", url, true);

    xhr.onload = function () {
       const data = JSON.parse(xhr.response);
       fillFilesTable(data);
    };
    xhr.onerror = function () {
        // ...handle/report error...
    };

    xhr.send();
}
function fillFilesTable(data){
    while (filesTable.firstChild){
        filesTable.removeChild(filesTable.firstChild);
    }


    data.forEach((row) => {
        const tr = document.createElement("tr");

        const td1 = document.createElement("td");
        const td2 = document.createElement("td");
        const td3 = document.createElement("td");
        const td4 = document.createElement("td");

        if(row['objectType'] === "folder"){
            td1.innerHTML = "<img draggable='true'  src='style/images/folder-2.png' width='40' class='mr-3 subFolder' > <a onclick='changeFolder("+row["objectId"]+")' style='color: #000; font-weight: bold' href='#'>"+ row["objectName"]+"</a>";
            td2.textContent = row["updated_at"];
            td3.textContent = "-";
            td4.innerHTML = "   <button class=\"\" data-toggle=\"dropdown\">\n" +
                "                                            ...\n" +
                "                                        </button>\n" +
                "                                        <ul class=\"dropdown-menu \">\n" +
                "                                            <li class=\"list-group\">\n" +
                "                                                <a class=\"p-1\" href=\"#\" onclick=\"deleteFolder("+row["objectId"]+" )\">Löschen</a>\n" +
                "                                            </li>\n" +
                "                                        </ul></div>";

        }else{
            if(row['fileType'] === "pdf"){
                td1.innerHTML = "<img src='style/images/pdf-file.png' width='40' class='mr-3' >"+ row["objectName"];

            }else if(row['fileType'] === "png" || row['fileType'] === "jpeg" || row['fileType'] === "jpg" || row['fileType'] === "gif"){


            td1.innerHTML = "<img src='style/images/image.png' width='40' class='mr-3' >"+ row["objectName"];

        }else{


            td1.innerHTML = "<img src='style/images/google-docs.png' width='40' class='mr-3' >"+ row["objectName"];

        }
            td2.textContent = row["updated_at"];
            td3.textContent = row["objectDownloads"];
            td4.innerHTML = "   <button class=\"\" data-toggle=\"dropdown\">\n" +
                "                                            ...\n" +
                "                                        </button>\n" +
                "                                        <ul class=\"dropdown-menu \">\n" +
                "                                            <li class=\"list-group\">\n" +
                "                                                <a class=\"p-1\" href=\"#\" onclick=\"deleteFile("+row["objectId"]+")\">Delete</a>\n" +
                "                                            </li>\n" +
                "                                        </ul>";
        }

        tr.appendChild(td1);
        tr.appendChild(td2);
        tr.appendChild(td3);
        tr.appendChild(td4);

        filesTable.appendChild(tr);
    });

    if(parentId === 0){
        const tr = document.createElement("tr");
        const td1 = document.createElement("td");
        const td2 = document.createElement("td");
        const td3 = document.createElement("td");
        const td4 = document.createElement("td");
        td1.innerHTML = "<img src='style/images/bin.png' width='40' class='mr-3'><a href='#' style=\"color: #000; font-weight: bold\" onclick='deletedFiles()'> Gelöschte Dateien</a>";
        td2.textContent = "-";
        td3.textContent = "-";
        td4.textContent = "-";
        tr.appendChild(td1);
        tr.appendChild(td2);
        tr.appendChild(td3);
        tr.appendChild(td4);
        filesTable.appendChild(tr);
    }

    if(data.length == 0){
        const tr = document.createElement("tr");

        tr.innerHTML = "<td>Dieser Ordner ist leer</td>";
        filesTable.appendChild(tr);
    }
}

document.addEventListener("DOMContentLoaded", () => { loadFolder(0); });



function deleteFolder(folderId){
    if (confirm('Möchten Sie diesen Ordner wirklich löschen?')) {
        // var xmlHttp = new XMLHttpRequest();
        var API = "http://127.0.0.1:8000/api/deleteFolder/" + folderId;
        const xhr = new XMLHttpRequest();

        xhr.open("GET", API);

        xhr.onload = function () {
            console.log(xhr.response);

        };
        xhr.onerror = function () {
            // ...handle/report error...
        };

        xhr.send();
        alert("Ordner wurde gelöscht");
        loadFolder(parentId);
    }
}


function deleteFile(fileId){
    if (confirm('Möchten Sie diese Datei löschen?')) {
        var API = "http://127.0.0.1:8000/api/deleteFile/" + fileId;
        const xhr = new XMLHttpRequest();

        xhr.open("GET", API);

        xhr.onload = function () {
            console.log(xhr.response);
        };
        xhr.onerror = function () {
            // ...handle/report error...
        };

        xhr.send();
        alert("Datei wurde gelöscht");
        loadFolder(parentId);
    }
}

function changeFolder(folderId){
    document.getElementById("parentFolder").value = folderId;
    document.getElementById("fileParentFolder").value = folderId;

    parentId = folderId;
    loadFolder(folderId);

}



function createNewFolder(folderName){

    let input = document.getElementById("folderName");

    let userId = document.getElementById("userId").value;
    const url = "http://127.0.0.1:8000/api/createNewFolder";
    var xhr = new XMLHttpRequest();
    xhr.open("POST", url, true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.send(JSON.stringify({
        folderName: folderName,
        parentFolder: parentId,
        user_id: userId
    }));
    xhr.onload = function() {
        var data = this.responseText;
        if(data === "1"){
            document.getElementById("folderNameError").style.display = "none";
            input.style.border = "2px solid green";
            input.value = "";
            loadFolder(parentId);
        }else{
            input.style.border = "2px solid red";
            document.getElementById("folderNameError").style.display = "block";
            document.getElementById("folderNameError").innerText = "POST API ERROR";
        }
    }
}


function deletedFiles(){
    var url = "http://127.0.0.1:8000/api/getDeletedFiles/";

    const xhr = new XMLHttpRequest();
    xhr.open("GET", url, true);

    xhr.onload = function () {
        const data = JSON.parse(xhr.response);
        while (filesTable.firstChild){
            filesTable.removeChild(filesTable.firstChild);
        }


        data.forEach((row) => {
            const tr = document.createElement("tr");

            const td1 = document.createElement("td");
            const td2 = document.createElement("td");
            const td3 = document.createElement("td");
            const td4 = document.createElement("td");

            if(row['objectType'] === "folder"){
                td1.innerHTML = "<img draggable='true'  src='style/images/folder-2.png' width='40' class='mr-3 subFolder' > <a onclick='changeFolder("+row["objectId"]+")' style='color: #000; font-weight: bold' href='#'>"+ row["objectName"]+"</a>";
                td2.textContent = row["updated_at"];
                td3.textContent = "-";
                td4.innerHTML = "   <button class=\"\" data-toggle=\"dropdown\">\n" +
                    "                                            ...\n" +
                    "                                        </button>\n" +
                    "                                        <ul class=\"dropdown-menu \">\n" +
                    "                                            <li class=\"list-group\">\n" +
                    "                                                <a class=\"p-1\" href=\"#\" onclick=\"deleteFolderPermanently("+row["objectId"]+" )\">Löschen</a>\n" +
                    "                                            </li>\n" +
                    "                                        </ul></div>";

            }else{
                if(row['fileType'] === "pdf"){
                    td1.innerHTML = "<img src='style/images/pdf-file.png' width='40' class='mr-3' >"+ row["objectName"];

                }else if(row['fileType'] === "png" || row['fileType'] === "jpeg" || row['fileType'] === "jpg" || row['fileType'] === "gif"){


                    td1.innerHTML = "<img src='style/images/image.png' width='40' class='mr-3' >"+ row["objectName"];

                }else{


                    td1.innerHTML = "<img src='style/images/google-docs.png' width='40' class='mr-3' >"+ row["objectName"];

                }
                td2.textContent = row["updated_at"];
                td3.textContent = row["objectDownloads"];
                td4.innerHTML = "   <button class=\"\" data-toggle=\"dropdown\">\n" +
                    "                                            ...\n" +
                    "                                        </button>\n" +
                    "                                        <ul class=\"dropdown-menu \">\n" +
                    "                                            <li class=\"list-group\">\n" +
                    "                                                <a class=\"p-1\" href=\"#\" onclick=\"deleteFilePermanently("+row["objectId"]+")\">Löschen</a>\n" +
                    "                                            </li>\n" +
                    "                                        </ul>";
            }

            tr.appendChild(td1);
            tr.appendChild(td2);
            tr.appendChild(td3);
            tr.appendChild(td4);

            filesTable.appendChild(tr);
        });



        if(data.length == 0){
            const tr = document.createElement("tr");

            tr.innerHTML = "<td>Dieser Ordner ist leer</td>";
            filesTable.appendChild(tr);
        }
    };
    xhr.onerror = function () {
        // ...handle/report error...
    };

    xhr.send();


}

function deleteFilePermanently(fileId){
    if (confirm('Möchten Sie diese Datei löschen?')) {
        var API = "http://127.0.0.1:8000/api/deleteFilePermanently/" + fileId;
        const xhr = new XMLHttpRequest();

        xhr.open("GET", API);

        xhr.onload = function () {
            console.log(xhr.response);
        };
        xhr.onerror = function () {
            // ...handle/report error...
        };

        xhr.send();
        alert("Datei wurde gelöscht");
        deletedFiles();
    }
}

function deleteFolderPermanently(folderId){
    if (confirm('Möchten Sie diese Datei löschen?')) {
        var API = "http://127.0.0.1:8000/api/deleteFolderPermanently/" + folderId;
        const xhr = new XMLHttpRequest();

        xhr.open("GET", API);

        xhr.onload = function () {
            console.log(xhr.response);
        };
        xhr.onerror = function () {
            // ...handle/report error...
        };

        xhr.send();
        alert("Datei wurde gelöscht");
        deletedFiles();
    }
}
