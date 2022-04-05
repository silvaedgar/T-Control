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

