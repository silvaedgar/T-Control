// function SearchCoinBaseOld(tipo) {
//     let coin_id = document.getElementById('coin_id').value;
//     let calc_currency = document.getElementById('calc_currency').value
//     var table = document.getElementById('details-table');

// // moneda de calculo es la misma de la factura la tasa es 1
//     if (coin_id == calc_currency) {
//         document.getElementById("last_rate").value = document.getElementById("rate_exchange").value;
//         document.getElementById("rate_exchange").value = 1
//         document.getElementById("factor").value = "/"
//         document.getElementById('message_subtitle').innerHTML = ""
//         // document.getElementById("rate_exchange").disabled = true
//         if (table.rows.length > 0) {
//             let tasa = document.getElementById("last_rate").value
//             RecalculateInvoice(tasa,table.rows);  // cambio la tasa recalcula factura si hay renglones
//         }
//     }
//     else {
//         try {
//             fetch( url_base +'api/currencyvalues/'+ coin_id  +'/rate_exchange')
//             .then(datos =>{
//                 return datos.json();
//             })
//             .then (data => {
//                 console.log("data ",data)
//                 document.getElementById("last_rate").value = (tipo=="Venta" ? data.sale_price : data.purchase_price);
//                 document.getElementById("factor").value = ( coin_id ==1 ? "*" : "/");
//     // La linea anterior es asi porque tengo fijo Bs en Id 1 y $ en Id 2 debe agregarse un factor en la relacion y tomarlo de alli
//                 document.getElementById("rate_exchange").value = (tipo=="Venta" ? data.sale_price : data.purchase_price);
//                 // document.getElementById("rate_exchange").disabled = false
//                 if (table.rows.length > 0) {
//                     RecalculateInvoice(tipo=="Venta" ? data.sale_price : data.purchase_price,table.rows);  // cambio la tasa recalcula factura si hay renglones
//                 }
//                 CalculateMountOtherCoin();
//             })
//         }
//         catch(err) {
//             document.getElementById("rate_exchange").value = 0
//             alert("Error leyendo tasa de cambio de la moneda");
//         }
//     }
//     // OJO hay que verificar si hay error en la conexion
// }

function SearchCoinBase(type,option) {
    let coin = document.getElementById('coin_id');
    let coin_id = coin.options[coin.selectedIndex].value;
    let calc_currency = document.getElementById('calc_currency_id').value

    if (coin_id == calc_currency) {
        document.getElementById("last_rate").value = 1;
        document.getElementById("factor").value = "/"
        // document.getElementById("rate_exchange").disabled = true


        if (option=="Invoice") {
            RecalculateInvoice(true);  // cambio la tasa recalcula factura si hay renglones
        }
        else  // en recalclate hace el llamado a Other coin
            UpdateMountPayment();
        document.getElementById("rate_exchange").value = 1
    }
    else {
        try {
            fetch( url_base +'api/rate_exchange/' + coin_id)
            .then(datos =>{
                return datos.json();
            })
            .then (data => {
                console.log("total y  data en coin base ",total,data)
                document.getElementById("last_rate").value = (type=="Sale" || type=="PaymentClient" ? data.sale_price : data.purchase_price);
                document.getElementById("factor").value = ( coin_id ==1 ? "*" : "/");
            // La linea anterior es asi porque tengo fijo Bs en Id 1 y $ en Id 2 debe agregarse un factor en la relacion y tomarlo de alli
                document.getElementById("rate_exchange").value = (type=="Sale" || type=="PaymentClient" ? parseFloat(data.sale_price).toFixed(2) : parseFloat(data.purchase_price).toFixed(2));
                if (option=="Invoice") {
                    document.getElementById("last_rate").value = 1
                    RecalculateInvoice(true);  // cambio la tasa recalcula factura si hay renglones
                }
                else  // en recalclate hace el llamado a Other coin
                    UpdateMountPayment();
            })
        }
        catch(err) {
                document.getElementById("rate_exchange").value = 0
                alert("Error leyendo tasa de cambio de la moneda");
            }
    }
// OJO hay que verificar si hay error en la conexion
}

function SearchProductPrice(tipo) {
    let tasa = (document.getElementById('rate_exchange').value == 1 ? document.getElementById('rate').value : document.getElementById('rate_exchange').value) ;
    let coin_id = document.getElementById('coin_id').value;
    let base_coin_id = document.getElementById('base_currency_id').value
    let calc_coin_id = document.getElementById('calc_currency_id').value

    let precio = 0
    if (tasa > 0) {
        let id = document.getElementById("pidproduct").value;
        if (id > 0) {
            fetch( url_base +'api/product_price/' + id)
            .then(datos =>{
                return datos.json();
            })
            .then (data =>{
                console.log("tipo ",tipo, '   price: ',data)
                if (tipo == "Sale") {
                    precio = (coin_id == calc_coin_id ? parseFloat(data.sale_price).toFixed(2) : parseFloat(data.sale_price * tasa).toFixed(2));
                }
                else { // ojo aqui es precio costo del producto
                    precio = (coin_id == calc_coin_id ? parseFloat(data.cost_price).toFixed(2) : parseFloat(data.cost_price * tasa).toFixed(2));
                }
                document.getElementById("pprecio").value = precio
                document.getElementById("ptax").value =  data.percent;
                document.getElementById("ptax_id").value = data.tax_id;
                CalcSubtotal('Price')                               
            })
        }
    }
    else {
        alert("Debe seleccionar la moneda de la Factura de Compra")
    }
}

function SearchPurchaseSuppliers() {   // usado para la lista de las facturas pendientes de los proveedores al momento de pagar
    let supplier_id = document.getElementById('supplier_id').value;
    let calc_coin_symbol = document.getElementById('calc_currency_symbol').value
    let calc_coin_id = document.getElementById('calc_currency_id').value
    // let rate = document.getElementById('rate').value
    let base_coin_id = document.getElementById('base_currency_id').value
    let base_coin_symbol = document.getElementById('base_currency_symbol').value

    var table = document.getElementById('details-table')
    DeleteTable(table)
    if (supplier_id > 0) {
        fetch( url_base + 'api/search_invoice_supplier/' + supplier_id + '/' + calc_coin_id + '/' + base_coin_id)
        .then(datos => {
            return datos.json();
        })
        .then (data => {
            let balance = data[0].balance;
            CreateTable(table,data,'Compra');
            document.getElementById('message_title').innerHTML = "Saldo: " +
                                        parseFloat(balance).toFixed(2) + ' ' + calc_coin_symbol;
        })
    }
    else {
        alert("Debe seleccionar un Proveedor")
    }
}

function SearchSaleClients() {   // usado para la lista de las facturas pendientes de los clientes al momento de pagar
    let client_id = document.getElementById('client_id').value;
    let calc_coin_symbol = document.getElementById('calc_currency_symbol').value
    let rate = document.getElementById('rate').value
    let calc_coin_id = document.getElementById('calc_currency_id').value
    let base_coin_id = document.getElementById('base_currency_id').value
    let base_coin_symbol = document.getElementById('base_currency_symbol').value

    var table = document.getElementById('details-table');

    DeleteTable (table);
    if (client_id > 0) {
        fetch( url_base + 'api/search_invoice_client/'+ client_id + '/' + calc_coin_id + '/' + base_coin_id)
        .then(datos => {
            return datos.json();
        })
        .then (data => {
            let balance = data[0].balance;
            let count_in_bs = data[0].count_in_bs;
            document.getElementById('message_subtitle').innerHTML = "" ;
            CreateTable(table,data,'Venta');
            // document.getElementById('count_in_bs').innerHTML = count_in_bs
            // document.getElementById('count_in_bs').value = count_in_bs

            document.getElementById('message_title').innerHTML = "Saldo: " + parseFloat(balance).toFixed(2)
                        + " "  + (count_in_bs == 'N'  ? calc_coin_symbol : base_coin_symbol)

            if (calc_coin_id != base_coin_id && count_in_bs == 'N') {
               document.getElementById('message_title').innerHTML += " -- " +
                            parseFloat(balance * rate).toFixed(2) + base_coin_symbol  ;

            }

            console.log(count_in_bs)
        })
    }
    else {
        alert("Debe seleccionar el CLiente")
    }
}

// function LoadCoins() {
//     var coin_id = document.getElementById("calc_currency").value;
//     var array_coin_base = (document.getElementById("base_calc_name").innerHTML).split(':');
//     var find_coin_base = false;
//     alert(coin_id);
//     try {
//         fetch(url_base + 'api/coins/'+ coin_id +'/loadcoins')
//         .then(datos =>{
//             return datos.json();
//         })
//         .then (data =>{
//             // var html_select_group = "";
//             // for (let element of data) {
//             //     html_select_group += '<option value = "' + element.id + '"';
//             //     if (element.id == coin_id) {
//             //         html_select_group += " selected ";
//             //         find_coin_base = true;
//             //     }
//             //     html_select_group += '> '+ element.name + '</option>';
//             // }
//             // if (!find_coin_base) {
//             //     html_select_group += '<option value = "' + coin_id  + '" selected>'+ array_coin_base[1] + '</option>' ;
//             // }  // OJO HAY QUE PONER EL NOMBRE DE LA MONEA TOMADA DE ALGUN SITIO EN LA VISTA EN BASE COINS
//             // document.getElementById("coin_id").innerHTML = html_select_group;
//             document.getElementById("rate_exchange").value = 1;
//             document.getElementById("factor").value = "*";
//             // document.getElementById("rate_exchange").disabled = true;

//     })

//     } catch (error) {
//         document.getElementById("rate_exchange").value = 0
//         alert("Error leyendo moneda base de calculo");
//     }
// }

function LoadCategories () {

    var id = $('#group-id').val()
    var category = $('#category_id').val()
    $.get(url_base + 'api/products/'+ id +'/categories', function (data){
        var html_select_group = '<option value = 0> Seleccione la categoria ... </option>'
        for (var i=0; i < data.length; i++) {
            html_select_group += '<option value = "' + data[i].id + '"';
            if (category > 0 && data[i].id == category) {
                html_select_group += ' selected'
            }
            html_select_group += '> '+ data[i].description + '</option>';
        }
        $('#category-id').html(html_select_group);
    })
}

function VerifyGetFocusRateExchange() {
    let coin_id = document.getElementById("coin_id").value;
    let calc_currency = document.getElementById("calc_currency_id").value
    if (coin_id == calc_currency_id) {   // moneda de calculo es la misma de la factura la tasa es 1
        document.getElementById('conditions').focus()
    }
}

function LoadSymbolCoin() {
    // let idx  = document.getElementById('coin_id');
    var coin  = document.getElementById('coin_id').options[document.getElementById('coin_id').selectedIndex].text;
    return  coin.split('-');
}


