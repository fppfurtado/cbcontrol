function loginClickHandler(event) {

    //var uname = document.getElementsByName("uname")[0];
    var psw = document.getElementsByName("psw")[0];
    
    if(psw.value === "iasd3590") {
        window.alert("Bem vindo!");
        document.getElementsByClassName("modal")[0].style.display = 'none';
    } else {
        window.alert("Senha incorreta!");
    }

}