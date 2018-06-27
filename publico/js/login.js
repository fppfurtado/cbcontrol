function loginClickHandler(event) {

    var uname = document.getElementsByName("uname")[0];
    var psw = document.getElementsByName("psw")[0];
    
    if(uname.value === "discipuladomarco" && psw.value === "iasd3590") {
        console.log("logado!");
        document.getElementsByClassName("modal")[0].style.display = 'none';
    } else {
        console.log(" acesso não permitido!");
    }

}