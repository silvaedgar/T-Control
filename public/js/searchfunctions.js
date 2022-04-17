// function ShowMountCurrencyCalc () {
//     let id = document.getElementById("coin_id").value;
//     let calc_currency = document.getElementById("calc_currency").value
//     console.log('vienen ID SHOWMOUNTCALCCURRENCY');
//     console.log(id);
//     console.log(calc_currency)

//     if (id != calc_currency) {   //
//         let symbol_coin = document.getElementById("symbol_coin").value
//         document.getElementById("payment_mount").innerHTML = "";
//         let factor = document.getElementById("factor").value
//         let mount = document.getElementById("mount").value
//         let rate = document.getElementById("rate_exchange").value;
//         let mount_payment = (factor == '*' ? mount * rate : mount / rate)
//         let venta = document.getElementById('client_balance')
//         document.getElementById("payment_mount").innerHTML = "Monto en " + symbol_coin  + " " + parseFloat(mount_payment).toFixed(2);
//     }
// }

function SearchCoinBase(tipo) {
    let coin_id = document.getElementById("coin_id").value;
    let calc_currency = document.getElementById("calc_currency").value
    var table = document.getElementById('details-table');
    if (coin_id == calc_currency) {   // moneda de calculo es la misma de la factura la tasa es 1
        document.getElementById("rate_exchange").value = 1
        document.getElementById("factor").value = "/"
        document.getElementById('payment_mount').innerHTML = ""
        document.getElementById("symbol_coin").value = document.getElementById("symbol_coin_calc").value;
       if (table.rows.length > 0) {
            let tasa = document.getElementById("last_rate").value
            RecalculateInvoice(tasa,table.rows);  // cambio la tasa recalcula factura si hay renglones
        }

    }
    else {
        try {
            fetch( url_base +'api/currencyvalues/'+ coin_id  +'/rate_exchange')
            .then(datos =>{
                return datos.json();
            })
          .then (data =>{
                document.getElementById("last_rate").value = (tipo=="Venta" ? data.sale_price : data.purchase_price);
                document.getElementById("factor").value = ( coin_id ==1 ? "*" : "/");
// La linea anterior es asi porque tengo fijo Bs en Id 1 y $ en Id 2 debe agregarse un factor en la relacion y tomarlo de alli
                // document.getElementById("rate_exchange").disabled = false
                document.getElementById("rate_exchange").value = (tipo=="Venta" ? data.sale_price : data.purchase_price);
                document.getElementById("symbol_coin").value = data.symbol;
                if (table.rows.length > 0) {
                    RecalculateInvoice(tipo=="Venta" ? data.sale_price : data.purchase_price,table.rows);  // cambio la tasa recalcula factura si hay renglones
                }
                // OJO ShowMountCurrencyCalc hace lo mismo que CalculateMountOtherCoin parece
                CalculateMountOtherCoin();
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
    let tasa = document.getElementById('rate_exchange').value;
    if (tasa > 0) {
        let id = document.getElementById("pidproduct").value;
        if (id > 0) {
            fetch( url_base +'api/purchases/'+ id  +'/product_price')
            .then(datos =>{
                return datos.json();
            })
            .then (data =>{
                if (tipo == "Venta")
                    document.getElementById("pprecio").value = parseFloat(data.sale_price * tasa).toFixed(2);
                else
                    document.getElementById("pprecio").value = parseFloat(data.cost_price * tasa).toFixed(2);
                document.getElementById("ptax").value =  data.percent;
                document.getElementById("ptax_id").value = data.tax_id;
            })
        }
    }
    else {
        alert("Debe seleccionar la moneda de la Factura de Compra")
    }
}

function SearchPurchaseSuppliers() {   // usado para la lista de las facturas pendientes de los proveedores al momento de pagar
    let supplier_id = document.getElementById('supplier_id').value;
    var table = document.getElementById('details-table');
    DeleteTable(table);
    if (supplier_id > 0) {
        fetch( url_base + 'api/suppliers/' + supplier_id + '/balancesuppliers')
        .then(datos => {
            return datos.json();
        })
        .then (data => {
            let balance = data[0].balance;
            CreateTable(table,data,'Compra');
            document.getElementById('supplier_balance').innerHTML = "Saldo Proveedor: " + parseFloat(balance).toFixed(2) + "$";
            // EL $ del mensaje hay que cambiarlo debe existir una variable global con la moneda base de compra
    })
    }
    else {
        alert("Debe seleccionar un Proveedor")
    }
}

function SearchSaleClients() {   // usado para la lista de las facturas pendientes de los clientes al momento de pagar
    let client_id = document.getElementById('client_id').value;
    var table = document.getElementById('details-table');
    DeleteTable (table);
    if (client_id > 0) {
        fetch( url_base + 'api/sales/' + client_id + '/clients')
        .then(datos => {
            return datos.json();
        })
        .then (data => {
            let balance = data[0].balance;
            CreateTable(table,data,'Venta');
            document.getElementById('client_balance').innerHTML = "Saldo Cliente: " + parseFloat(balance).toFixed(2) + "BsD.";
        })
    }
    else {
        alert("Debe seleccionar el CLiente")
    }
}

function LoadCoins() {
    console.log("LOAD COINS");
    var coin_id = document.getElementById("calc_currency").value;
    var array_coin_base = (document.getElementById("base_calc_name").innerHTML).split(':');
    var find_coin_base = false;
    try {
        fetch(url_base + 'api/coins/'+ coin_id +'/loadcoins')
        .then(datos =>{
            return datos.json();
        })
        .then (data =>{
            var html_select_group = "";
            for (let element of data) {
                html_select_group += '<option value = "' + element.id + '"';
                if (element.id == coin_id) {
                    html_select_group += " selected ";
                    find_coin_base = true;
                }
                html_select_group += '> '+ element.name + '</option>';
            }
            if (!find_coin_base) {
                html_select_group += '<option value = "' + coin_id  + '" selected>'+ array_coin_base[1] + '</option>' ;
            }  // OJO HAY QUE PONER EL NOMBRE DE LA MONEA TOMADA DE ALGUN SITIO EN LA VISTA EN BASE COINS
            document.getElementById("coin_id").innerHTML = html_select_group;
            document.getElementById("rate_exchange").value = 1;
            // document.getElementById("rate_exchange").disabled = true;
            document.getElementById("factor").value = "*";

    })

    } catch (error) {
        document.getElementById("rate_exchange").value = 0
        alert("Error leyendo moneda base de calculo");
    }
}

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
    let calc_currency = document.getElementById("calc_currency").value
    if (coin_id == calc_currency) {   // moneda de calculo es la misma de la factura la tasa es 1
        document.getElementById('conditions').focus()
    }
}


