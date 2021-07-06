

function validar(){
// valores  conseguidos con getElementByid
id = document.getElementById("id").value;


// validacion de formulario  imput nombre de zona 

if( id == null || id.length == 0 || /^\s+$/.test(id) ) {

    swal("Campo id oblagatorio !");
return false; 


}

nombre = document.getElementById("nombre").value;


// validacion de formulario  imput nombre de zona 

if( nombre == null || nombre.length == 0 || /^\s+$/.test(nombre) ) {

    swal("Campo nombre oblagatorio !");
return false; 


}





}
