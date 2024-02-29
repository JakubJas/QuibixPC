function validaFormularioObl() {

    var exito = true;

    var controles = document.getElementsByClassName("obligatorio");
    var ncontroles = controles.length;
    for (var i = 0; i < ncontroles; i++) {
        if (controles[i].value == "") {

            exito = false;
            controles[i].parentNode.classList.add("error1");
        }
        else {
            controles[i].parentNode.classList.remove("error1");
        }
    }
    return exito;
}

function validaFormularioReg(){

    var txtUr = document.myForm.email.value;

    var UserErr  = true;

    var regexU = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;

    regexU.test(txtUr) === false  ? printError("EuserErr", "Formato no valido") : printError("UserErr", ""), UserErr = false;

    if(( UserErr || MovilErr) == true){
        return false;
    }

}