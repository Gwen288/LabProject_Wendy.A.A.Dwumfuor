document.getElementById('myButton').addEventListener('click', Validate)
 
function Validate(){
    var password=document.getElementById("center1").value;
    var c_password=document.getElementById("c_pass").value;

    if (password===c_password){
            Swal.fire({
              title: 'Password Matched!',
              text: "Passsword confirmed",
              icon: "success",
              timer: 2000,
              timerProgressBar: true
          });
          
    }else{
        Swal.fire({
            title: "Password Mismatch!",
            text: "Entered wrong password. Try again",
            icon: 'error',
            timer: 2000,
            timerProgressBar: true
        });
    }
}






