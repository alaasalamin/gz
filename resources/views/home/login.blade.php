<!doctype html>
<html lang="en">
@include('layouts/head')
<body>

@include('layouts/header')

<section class="loginSection">



    <div class="loginBox">
        <div class="logo" style="">
            <img src="style/images/Logo_Black.png" height="350" width="400" alt="">
        </div>
        <div class="loginBlackBox">

            <div class="loginForm">
                <form action="login" method="POST">
                    @csrf
                <div class="row">
                    <div class="col-12">

                                <p id="ErrorLoginMessage" style="display: none" class="text-success">Login Failed</p>

                        <label for="email" class="text-light">BENUTZERNAME</label>
                    </div>
                    <div class="col-12">
                        <input type="email" name="email" id="email" class="form-control">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <label for="password" class="text-light">PASSWORT</label>
                    </div>
                    <div class="col-12">
                        <input type="password" name="password" id="password" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 my-3">
                        <button class="loginBtn">EINLOGGEN</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>

</section>


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<script>

//    function login(){
//        let username = document.getElementById("email").value;
//        let password = document.getElementById("password").value;
//
//        const url = "http://127.0.0.1:8000/api/login";
//
//        var xhr = new XMLHttpRequest();
//        xhr.open("POST", url, true);
//        xhr.setRequestHeader('Content-Type', 'application/json');
//        xhr.send(JSON.stringify({
//            email: username,
//            password: password
//        }));
//
//        xhr.onreadystatechange = function () {
//            if (xhr.readyState === 4) {
//                let loginStatus = xhr.status;
//                if(loginStatus === 201){
//                    console.log(xhr.response);
//                }else{
//                    document.getElementById("ErrorLoginMessage").style.display = "block";
//                }
//            }};
//
//    }
</script>

</body>
</html>
