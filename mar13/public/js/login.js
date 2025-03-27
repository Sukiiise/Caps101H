function login()
{
            varemail = document.getElementById("email").value;
            varpassword = document.getElementById("password").value;
            
            Swal.fire({
                title: 'Loading...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                showCancelButton: false,
                showConfirmButton: false
            });

            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            }
            else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }

            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    //document.getElementById("message").innerHTML = this.responseText;   
                    Swal.close();
                    if(this.responseText=="Success"){
                        Swal.fire({
                            title: "Success!",
                            text: "Login Successful!",
                            icon: "success"
                        }).then(function() {
                            Swal.close();
                        },5000);

                        window.location.assign('/admin/dashboard');
                    }
                    else {
                        Swal.fire({
                            title: "Error!",
                            text: "Invalid Username or Password!",
                            icon: "error"
                        }).then(function() {
                            Swal.close();
                        });
                    }
                }
            };

            xmlhttp.open("POST", "/processlogin", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            // Include CSRF token
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            xmlhttp.setRequestHeader("X-CSRF-TOKEN", csrfToken);

            xmlhttp.send("user=" + encodeURIComponent(varemail) + "&pass=" + encodeURIComponent(varpassword));
}

function showErrorMessage(message) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: message,
        confirmButtonColor: 'rgb(190, 100, 100)'
    });
}

function showSuccessMessage(message) {
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: message,
        confirmButtonColor: 'rgb(190, 100, 100)'
    });
}
