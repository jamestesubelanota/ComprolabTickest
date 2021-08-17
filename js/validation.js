

function validar() {
    // valores  conseguidos con getElementByid


    nombre = document.getElementById("nombre").value;


    // validacion de formulario  imput nombre de zona 

    if (nombre == null || nombre.length == 0 || /^\s+$/.test(nombre)) {

        swal("Campo nombre oblagatorio !");
        return false;


    }
// validacion zona 
  zona = document.getElementById("zona").value;

  if( zona  == null || zona == 0 ){
       
    swal("Campo zona   obligatorio  !");

    return false;


}
// validacion asignar cargo
enc = document.getElementById("enc").value;

  if( enc  == null || enc == 0 ){
       
    swal("Campo cargo  obligatorio  !");

    return false;

}

// funcion de validar formulario 2

}
