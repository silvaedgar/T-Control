function ShowMountCurrencyCalc () {
    document.getElementById("payment_mount").innerHTML = "";
    let id = document.getElementById("coin_id").value;
    let calc_currency = document.getElementById("calc_currency").value
    if (id != calc_currency) {   //
        // console.log(document.getElementById("rate_exchange").value)
        let mount_payment = document.getElementById("mount").value / document.getElementById("rate_exchange").value;
        let venta = document.getElementById('client_balance')
        document.getElementById("payment_mount").innerHTML = "Monto en " + (venta ? 'Bs' : '$')  + " " + parseFloat(mount_payment).toFixed(2);
    }
}

function SearchCoinBase(tipo) {
    let id = document.getElementById("coin_id").value;
    let calc_currency = document.getElementById("calc_currency").value
    var table = document.getElementById('details-table');
    if (id == calc_currency) {   // moneda de calculo es la misma de la factura la tasa es 1
        document.getElementById("rate_exchange").value = 1
        if (table.rows.length > 0) {
            RecalculateInvoice(1,table.rows);  // cambio la tasa recalcula factura si hay renglones
        }

    }
    else {
        try {
            fetch('/t-control/public/api/currencyvalues/'+ id  +'/rate_exchange')
            .then(datos =>{
                return datos.json();
            })
          .then (data =>{
                document.getElementById("rate_exchange").value = (tipo=="Venta" ? data.sale_price : data.purchase_price);
                if (table.rows.length > 0) {
                    RecalculateInvoice(tipo=="Venta" ? data.sale_price : data.purchase_price,table.rows);  // cambio la tasa recalcula factura si hay renglones
                }
                ShowMountCurrencyCalc();
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
            fetch('/t-control/public/api/purchases/'+ id  +'/product_price')
            .then(datos =>{
                return datos.json();
            })
            .then (data =>{
                console.log(tipo);
                if (tipo == "Venta")
                    document.getElementById("pprecio").value = data.sale_price * tasa;
                else
                    document.getElementById("pprecio").value = data.cost_price * tasa;
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
        fetch('/t-control/public/api/purchases/' + supplier_id + '/suppliers')
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
        fetch('/t-control/public/api/sales/' + client_id + '/clients')
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

function CreateTable(table,data,tipo) {

    let item = 0;
    let balance = 0;
    data.forEach(element => {
        if (element.status == 'Pendiente' || element.status == 'Parcial' ) {
            var row = table.insertRow(item + 1);
            row.id = "fila" + item;
            row.style = "font-size: smaller; background: white; text-align: left "
            CreateElementCell("hidden","item[]",element.id,item + 1, 0,row)
            if (tipo == "Venta")
                CreateElementCell("hidden","sale_date[]",'',element.sale_date, 1,row)
            else
                CreateElementCell("hidden","purchase_date[]",'',element.purchase_date, 1,row)
            CreateElementCell("hidden","invoice[]",'',element.invoice,2,row)
            CreateElementCell("hidden","amount[]",'',parseFloat(element.mount).toFixed(2) + element.symbol,3,row)
            CreateElementCell("hidden","tax_mount[]",'',parseFloat(element.tax_mount).toFixed(2) + element.symbol,4,row)
            CreateElementCell("hidden","balance[]",'',parseFloat(element.mount - element.paid_mount).toFixed(2)+ element.symbol,5,row)
            item ++;
            balance += (element.mount - element.paid_mount);
        }
    });
    // if (item == 0) {
    //     let row = table.insertRow(1);
    //     row.style = "font-size: small; background: white; text-align: left "
    //     row.appendChild(celda);
    // }
}

function DeleteTable(table) {
    console.log(table);
    var filas = table.rows.length;
    try {
        for (let i=1; i < filas;) {
            table.deleteRow(i);
            filas--;
        }   // elimina las celdas existentes comienza en uno para no eliminar el encabezado
    } catch (e) {
        alert (e);
    }
}

function LoadCoins() {
    var coin_id = document.getElementById("calc_currency").value;
    var array_coin_base = (document.getElementById("base_calc_name").innerHTML).split(':');
    var find_coin_base = false;
    fetch('/t-control/public/api/coins/'+ coin_id +'/loadcoins')
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
    })
}
