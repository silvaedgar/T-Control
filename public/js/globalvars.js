var url_base = "/t-control/public/";
// var url_base = "http://t-control.infinityfreeapp.com/";

// falla en el edit de product
function get_url_base() {
    let loc = window.location;
    let  pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/'));
    pathName = pathName.substring(0, pathName.lastIndexOf('/') + 1);
    return pathName;
    // return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
}

// ----------------------- Variables usadas para la creacion del detalle de tablas compras ventas pagos -----
var item = 0;
var total = 0;
var totaltax = 0;
var subtotal = [];
var price_base;
var product_price = 0     // estas dos variables se utilizan para minizar el error de decimales
var product_price_other = 0 // en el calculo del producto usados en calcsubtoal y limpiado despues de agregado el item

// -----------------------------------------------------------------------------------------------------

$(document).ready(function() {

    if ($('#pidproduct').length > 0)
        $('#pidproduct').select2({
            placeholder : 'Seleccione un Producto ...'
        });

    if ($('#supplier_id').length > 0) {
        $('#supplier_id').select2({
            placeholder : 'Seleccione un Proveedor ...'

        });
        // if ($('#calc_currency').length > 0) {  // en jquery verifica si existe
        //     LoadCoins();  // usado aqui como api por si en la BD no esta la relacion 1 a 1 de la moneda base y de calculo
        // }
    }

    if ($('#client_id').length > 0) {
        $('#client_id').select2({
            placeholder : 'Seleccione un Cliente ...'

        });
        // let calc_coin = document.getElementById('calc_currency')
        // if (calc_coin){
        //     LoadCoins();  // usado aqui como api por si en la BD no esta la relacion 1 a 1 de la moneda base y de calculo
        // }   // en javascript verifica si existe
    }

    let categories = document.getElementById('category-id');
    if (categories) {   // esta en productos
        LoadCategories();
    }
    $('#data-table').DataTable({
            stateSave: true,
            scrollX: true,
            lengthMenu : [[6,10,20,-1],[6,10,20,"Todos los"]],
            autoWidth : true,
            language : {
                search: "Buscar: ",
                emptyTable: "No hay registros que mostrar",
                info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                infoEmpty: "",
                lengthMenu: "Mostrar _MENU_ registros x pagina",
                infoFiltered: "(Filtrado de un total de _MAX_  registros)",
                paginate: {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Sig",
                    "previous": "Ant"
                },
            }

        })
});


// Funcion que muestra la imagen(usada solo en productos hasta los moemntos Abril 2022)
function preview(event) {
    let reader = new FileReader();
    reader.onload =  (e) => {
        document.getElementById('display-image').innerHTML = "<img src='" + e.target.result +
            "' height = '150px' width = '150px' />";
    }
    reader.readAsDataURL(event.target.files[0])
}

