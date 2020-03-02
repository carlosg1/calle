function change() {
    if (request.readyState === 4) {
        if (request.status === 200) {
            // verificar si el usuario esta validado
            alert(this.responseText);
        } else {
            alert('Se produjo un error al validar el usuario');
        }
    }
}

function validar(){
    let username = document.getElementById('inputUsername').value;
    let password = document.getElementById('inputPassword').value;

    if(username==''){
        alert('Ingrese un usuario para continuar...');
        return false;
    }
    if(password==''){
        alert('Ingrese una contraseña para continuar...');
        return false;
    }

    let request = new XMLHttpRequest();
    request.onreadystatechange = function(){
        if (request.readyState === 4) {
            if (request.status === 200) {
                // verificar si el usuario esta validado
                if(request.responseText=='SI'){
                    return true;
                } else {
                    alert('Usuario o Clave incorrecta');
                    return false;
                }
                
            } else {
                alert('Se produjo un error al validar el usuario');
                return false;
            }
        }
    }
    request.open('GET', 'validar.php?usuario='+username+'&password='+password, false);
    request.send(null);
}