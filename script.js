document.getElementById('myButton').addEventListener('click', Validate)
 
function Validate(){
    let password=document.getElementById("center1").value.trim(); ;
    let c_password=document.getElementById("c_pass").value.trim();
    let email=document.getElementById("center").value.trim();
    let e_pattern= /^[A-Za-z][A-Za-z0-9._]*@ashesi\.edu\.gh$/;
    let s_pass=/([A-Za-z])\w*(?=\d{1,})/;

    if(!e_pattern.test(email)){
        Swal.fire({
            title: "Invalid Email",
            text:"check your email address",
            icon:"error"
        })
        return;
    }
    if(!s_pass.test(password)|| c_password.length<6){
        Swal.fire({
            title: "Weak Password",
            text:"Password must start with a letter, be at least 6 characters long and include at least one number.",
            icon:"warning"
        });
        return;

    }
     if (password===c_password){
            Swal.fire({
              title: 'Password Matched!',
              text: "Password confirmed",
              icon: "success",
              timer: 1000,
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






