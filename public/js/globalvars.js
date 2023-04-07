var url_base = "/t-control/public/";
// var url_base = "http://t-control.infinityfreeapp.com/";

// falla en el edit de product
function get_url_base() {
    let loc = window.location;
    let pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/'));
    pathName = pathName.substring(0, pathName.lastIndexOf('/') + 1);
    return pathName;
    // return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
}

function cpf(e, isEvent) {
    if (isEvent)
        v = e.target.value;
    else
        v = e

    // vNumber = parseFloat(v);
    // v = vNumber.toString(10);

    // console.log("VALOR V ", v, typeof v, vNumber)

    // console.log("VALOR NRO ", v)

    v = v.replace(/([^0-9\.]+)/g, '');
    v = v.replace(/^[\.]/, '');
    v = v.replace(/[\.][\.]/g, '');
    v = v.replace(/\.(\d)(\d)(\d)/g, '.$1$2');
    v = v.replace(/\.(\d{1,2})\./g, '.$1');

    v = v.toString().split('').reverse().join('').replace(/(\d{3})/g, '$1,');
    v = v.split('').reverse().join('').replace(/^[\,]/, '');
    // console.log("VALOR NRO ", v)
    if (isEvent)
        e.target.value = v
    else
        return v
}

function numberMask(e, decimals) {

    numberText = e.target.value
    numberText = numberText.replace(/([^0-9\.]+)/g, '');
    let arrayNumber = numberText.split(".")
    let intPart = arrayNumber[0]
    numberText = intPart.toString().split('').reverse().join('').replace(/(\d{3})/g, '$1,')
    numberText = numberText.split('').reverse().join('').replace(/^[\,]/, '')
    numberText = numberText.replace(/0(\d)/, '$1')
    if (arrayNumber[1] != undefined) {
        let decimalPart = arrayNumber[1]
        switch (decimals) {
            case 4:
                decimalPart = decimalPart.replace(/(\d)(\d)(\d)(\d)(\d)*/g, '$1$2$3$4');
                break;
            case 3:
                decimalPart = decimalPart.replace(/(\d)(\d)(\d)(\d)*/g, '$1$2$3');
                break
            default:
                decimalPart = decimalPart.replace(/(\d)(\d)(\d)*/g, '$1$2');

        }
        numberText = numberText + "." + decimalPart
    }
    e.target.value = numberText
}



// ----------------------- Variables usadas para la creacion del detalle de tablas compras ventas pagos -----
var item = 0;
var total = 0;
var totaltax = 0;
var subtotal = [];
var price_base;
var product_price = 0     // estas dos variables se utilizan para minizar el error de decimales
var product_price_other = 0 // en el calculo del producto usados en calcsubtoal y limpiado despues de agregado el item
var collections = []


document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('data-table')) {
        $('#data-table').DataTable({
            stateSave: true,
            scrollX: true,
            lengthMenu: [[6, 10, 20, -1], [6, 10, 20, "Todos los"]],
            autoWidth: true,
            language: {
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
    }
})


// Funcion que muestra la imagen(usada solo en productos hasta los moemntos Abril 2022)
function preview(event) {
    let reader = new FileReader();
    reader.onload = (e) => {
        document.getElementById('display-image').innerHTML = "<img src='" + e.target.result +
            "' height = '120vh' width = '100%' />";
    }
    reader.readAsDataURL(event.target.files[0])
}

