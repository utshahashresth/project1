const pword = document.getElementById("pword")
const cpword = document.getElementById("cpword")
let msg = document.getElementById("msg")


cpword.addEventListener("input",check)

function check() {
    if (pword.value==cpword.value) {
        msg.innerText="password matched succesfully"
        msg.classList.add("sucess")
    }else{
        msg.innerText="password incorrect"
        msg.classList.add("error")
    }
    
}