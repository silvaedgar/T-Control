var url_base = "/t-control/public/"

// ----------------------- Variables usadas para la creacion del detalle de tablas compras ventas pagos -----
var item = 0;
var total = 0;
var totaltax = 0;
var subtotal = [];
var price_base;
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
        if ($('#calc_currency').length > 0) {  // en jquery verifica si existe
            LoadCoins();  // usado aqui como api por si en la BD no esta la relacion 1 a 1 de la moneda base y de calculo
        }
    }

    if ($('#client_id').length > 0) {
        $('#client_id').select2({
            placeholder : 'Seleccione un Cliente ...'

        });
        let calc_coin = document.getElementById('calc_currency')
        if (calc_coin){
            LoadCoins();  // usado aqui como api por si en la BD no esta la relacion 1 a 1 de la moneda base y de calculo
        }   // en javascript verifica si existe
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


$('#delete-item').submit(function(e) {
    e.preventDefault();
    let message = document.getElementById('message-item-delete').value
    Swal.fire({
        title: 'Esta Seguro de Eliminar? ',
        text: message,
        icon: 'question',
        showCancelButton: true,
        cancelButtonText: 'No',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si'
        }).then((result) => {
        if (result.value) {
          this.submit();
        }
    })
})

// Funcion que muestra la imagen(usada solo en productos hasta los moemntos Abril 2022)
function preview(event) {
    let reader = new FileReader()
    reader.onload =  (e) => {
        document.getElementById('display-image').innerHTML = "<img src='" + e.target.result +
            "' height = '150px' width = '150px' />";
    }
    reader.readAsDataURL(event.target.files[0])
}

