// Signup validation
document.getElementById("signupButton").addEventListener("click",Validate);

    function Validate(event){

        event.preventDefault();

        let f_name = document.getElementById("firstname").value.trim(); 
        let l_name = document.getElementById("lastname").value.trim();
        let email = document.getElementById("email").value.trim();
        let password = document.getElementById("password").value.trim();
        let c_password = document.getElementById("c_pass").value.trim();

        let e_pattern = /^[A-Za-z][A-Za-z0-9._]*@ashesi\.edu\.gh$/;
        let s_pass = /([A-Za-z])\w*(?=\d{1,})/;

        if (!f_name || !l_name || !email || !password || !c_password) {
            Swal.fire({ title: "Missing Information", text: "Please fill in all fields.", icon: "warning" });
            return;
        }

        if(!e_pattern.test(email)){
            Swal.fire({ title: "Invalid Email", text: "Must be your institutional email.", icon: "error" });
            return;
        }

        if(!s_pass.test(password) || password.length < 6){
            Swal.fire({ title: "Weak Password", text:"Password must start with a letter, include a number and be at least 6 characters.", icon: "warning" });
            return;
        }

        if(password === c_password){
            Swal.fire({ title: 'Password Matched!', text: "Account created", icon: "success", timer: 1000, timerProgressBar: true });
            document.getElementById("form").submit();
        } else {
            Swal.fire({ title: "Password Mismatch!", text:"Try again", icon: "error", timer:2000, timerProgressBar:true });
        }
    }
 