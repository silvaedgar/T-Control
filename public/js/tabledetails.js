function UpdateMountPayment() {
    let mount = document.getElementById('mount').value
    let rate = document.getElementById('rate_exchange').value
    coin = LoadCoins()
    if (mount != 0) {
        if (coin.current.id == coin.calculation.id) { // Moneda de Pago o Factura igual a moneda de calculo
            if (coin.current.id != coin.base.id) { /// pago igual base(Bs)
                rate = document.getElementById('rate').value
                document.getElementById('mount').value = parseFloat(mount / rate).toFixed(2)
                document.getElementById('rate_exchange').value = 1
            }            // ojo fa el else de aqui
        }
        else {
            if (coin.current.id == coin.base.id)  /// Son Bs la Factura o el pago
                document.getElementById('mount').value = parseFloat(mount * rate).toFixed(2)
        }
        document.getElementById('message_subtitle').innerHTML = "Monto en " + (coin.current.id != coin.base.id ? coin.base.symbol : coin.calculation.symbol) + ": " + parseFloat(mount).toFixed(2)
    }
}

function CalculateMountOtherCoin() {

    coin = LoadCoins()
    let count_in_bs = (document.getElementById('count_in_bs') ? document.getElementById('count_in_bs').value : 'N')
    let mount_current = document.getElementById('mount').value
    let rate = (document.getElementById("rate_exchange").value > 1 ? document.getElementById("rate_exchange").value : document.getElementById("rate").value)
    if (count_in_bs != 'S') {
        if (coin.current.id == coin.calculation.id) { // Divisa de pago o factura igual a la de calculo
            let monto = (mount_current * rate).toFixed(2)
            document.getElementById('message_subtitle').innerHTML = (coin.calculation.id == coin.base.id ? '' :
                "Monto: " + cpf(monto.toString(), false) + coin.base.symbol)
        }
        else {
            if (coin.current.id == coin.base.id) // Divisa de pago o factura igual a la de calculo
            {
                document.getElementById('message_subtitle').innerHTML = "Monto: " +
                    cpf((parseFloat(mount_current) / parseFloat(rate)).toFixed(2).toString(), false) + coin.calculation.symbol
            }
            else {
                document.getElementById('message_subtitle').innerHTML = "Monto: " + cpf((parseFloat(mount_current) / parseFloat(rate)).toFixed(2).toString(), false)
                    + " " + coins.calc_coin_symbol + " -- " + cpf((parseFloat(mount_current) * parseFloat(rate)).toFixed(2).toString(), false) + coin.base.symbol

            }
        } // ojo la ultima instruccion hay que verificar cuando se paga o factyura en moneda que no es no de calculo no base
    }
    else {
        if (coin.current.id != coin.base.id)  // pagando en otra moneda no Bs para paola y livia
            document.getElementById('message_subtitle').innerHTML = cpf((document.getElementById("mount").value * document.getElementById("rate").value).toString(), false);

        // document.getElementById('message_subtitle').innerHTML = parseFloat(document.getElementById("mount").value * document.getElementById("rate").value).toFixed(2);
    }
    document.getElementById('last_rate').value = document.getElementById('rate_exchange').value
}

function CalcInvoice(deleteItem, itemactual) {
    if (deleteItem) {
        subtotal.splice(itemactual - 1, 1)
    }

    total = subtotal.reduce((a, b) => a + b.monto, 0)
    
    total = total + Number(document.getElementById('associated_costs').value)
    totalTax = 0   // calcular el monto del tax esta el porcentaje
    let coin = SearchCoin(document.getElementById('coin_id').value);
    document.getElementById('message_title').innerHTML = cpf(total.toFixed(2).toString()) + " " + coin[0].symbol;
    document.getElementById('mount').value = total
    document.getElementById('tax').value = totalTax;
    CalculateMountOtherCoin();
}

function RecalculateInvoice(isChangeCoin, event) {
    let calc_coin_id = collections.calcCoin.id
    coin = SearchCoin(document.getElementById('coin_id').value);
    last_rate = document.getElementById('last_rate').value
    tasa = document.getElementById('rate_exchange').value == 1 ? last_rate : document.getElementById('rate_exchange').value
    if (!isChangeCoin) {
        event.target.value = cpf(event.target.value, false)
    }
    else {
        let costos = document.getElementById("associated_costs").value
        subTotal = coin[0].id == calc_coin_id ? costos / tasa : costos * tasa
        document.getElementById("associated_costs").value = subTotal.toFixed(2)
    }


    subtotal.forEach(element => {
        if (isChangeCoin) {
            element.monto = coin[0].id == calc_coin_id ? element.monto / tasa : element.monto * tasa
            element.precio = coin[0].id == calc_coin_id ? element.precio / tasa : element.precio * tasa
            element.precio_other = coin[0].id == calc_coin_id ? element.precio_other * tasa : element.precio_other / tasa
        }
        else {
            dif = event.target.value - last_rate
            element.monto = element.monto + (dif * element.quantity * element.precio_other)
            element.precio = element.precio + (dif * element.precio_other)
        }
    });

    // vuelve a generar la tabla detalle
    let rows_table = document.getElementById('details-table').rows;
    let rowactual = 0
    for (const element of rows_table) {
        if (rowactual > 0) { // salta el encabezado
            element.cells[3].innerHTML = parseFloat(subtotal[rowactual - 1].precio).toFixed(2);
            element.cells[4].innerHTML = parseFloat(subtotal[rowactual - 1].tax).toFixed(2);
            element.cells[5].innerHTML = parseFloat(subtotal[rowactual - 1].monto).toFixed(2);
            // el innerHTML elimina los input
            let pricearray = CreateElementInput("hidden", "price[]", subtotal[rowactual - 1].precio);
            let taxarray = CreateElementInput("hidden", "tax[]", subtotal[rowactual - 1].tax);
            let taxidarray = CreateElementInput("hidden", "tax_id[]", subtotal[rowactual - 1].tax_id);
            element.cells[3].appendChild(pricearray);
            element.cells[4].appendChild(taxarray);
            element.cells[5].appendChild(taxidarray);
        }
        rowactual++;
    }

    CalcInvoice()
    if (document.getElementById('pidproduct').value > 0) {
        dif = event.target.value - last_rate
        productPrice = parseFloat(document.getElementById("productPrice").value)
        productPriceOther = parseFloat(document.getElementById("productPriceOther").value)
        productPrice += dif * productPriceOther
        document.getElementById("productPrice").value = cpf(productPrice.toFixed(2).toString(10), false)

        let montototal = parseFloat(document.getElementById("productQty").value) * productPrice + productPrice * parseFloat(document.getElementById("ptax").value) / 100;
        document.getElementById("productSubTotal").innerHTML = cpf(montototal.toFixed(2).toString(10), false)
    }
}

function CreateElementInput(type, name, value) {
    var elemento = document.createElement("input");  // elemto del product id
    elemento.type = type;
    elemento.name = name
    elemento.value = value
    return elemento;
}

function CreateElementCell(type, name, value, content, cell, row) {
    var table = document.getElementById('details-table');
    var celda = row.insertCell(cell);  // celda del item
    let elemento = CreateElementInput(type, name, value)
    if (type == 'button') {
        elemento.onclick = function () {
            CalcInvoice(true, row.cells[0].innerHTML)
            // subtotal.splice(parseInt(row.cells[0].innerHTML) - 1, 1)
            table.deleteRow(row.cells[0].innerHTML);
            RenumberItems();
            document.getElementById('totalrenglones').innerHTML = parseInt(item);
        }
    }
    else
        celda.innerHTML = content;
    celda.appendChild(elemento);
}

function CreateTable(table, data, tipo) {

    let item = 0;
    let balance = 0;
    data.forEach(element => {
        if (element.status == 'Pendiente' || element.status == 'Parcial') {
            var row = table.insertRow(item + 1);
            row.id = "fila" + item;
            row.style = "font-size: smaller; background: white; text-align: left "
            CreateElementCell("hidden", "item[]", element.id, item + 1, 0, row)
            if (tipo == "Venta")
                var fecha = (element.sale_date).substr(8, 2) + "-" + (element.sale_date).substr(5, 3) + (element.sale_date).substr(0, 4)
            else
                var fecha = (element.purchase_date).substr(8, 2) + "-" + (element.purchase_date).substr(5, 3) + (element.purchase_date).substr(0, 4)
            CreateElementCell("hidden", "purchase_date[]", '', fecha, 1, row)
            CreateElementCell("hidden", "invoice[]", '', element.invoice, 2, row)
            CreateElementCell("hidden", "amount[]", '', parseFloat(element.mount).toFixed(2) + element.symbol, 3, row)
            CreateElementCell("hidden", "tax_mount[]", '', parseFloat(element.tax_mount).toFixed(2) + element.symbol, 4, row)
            CreateElementCell("hidden", "balance[]", '', parseFloat(element.mount - element.paid_mount).toFixed(2) + element.symbol, 5, row)
            item++;
            balance += (element.mount - element.paid_mount);
        }
    });
}

function DeleteTable(table) {
    var filas = table.rows.length;
    try {
        for (let i = 1; i < filas;) {
            table.deleteRow(i);
            filas--;
        }   // elimina las celdas existentes comienza en uno para no eliminar el encabezado
    } catch (e) {
        alert(e);
    }
}

function RenumberItems() {
    var table = document.getElementById('details-table');
    var rowCount = table.rows.length;
    for (var i = 1; i < rowCount; i++) {
        var row = table.rows[i];
        row.cells[0].innerHTML = i
        // row.cells[0].appendChild(CreateElementInput("text","item[]",i))
    }
    item--;   // esta variable es global y se define en globalvars
}

function LoadItem(data) {
    subtotal.push(data);

    var table = document.getElementById('details-table');
    var row = table.insertRow(item + 1);
    row.id = "fila" + item;
    row.style = "font-size: small; background: white; text-align: left "
    var celda = row.insertCell(0);  // celda del item
    celda.innerHTML = item + 1;
    // CreateElementCell("hidden","item[]",item + 1,item + 1, 0,row) // si lo dejo asi pierdo la eliminacion no se como arreglarlo
    CreateElementCell("hidden", "product_id[]", data.product_id, data.producto, 1, row)
    CreateElementCell("hidden", "quantity[]", data.quantity, data.quantity.toFixed(2), 2, row)
    CreateElementCell("hidden", "price[]", data.precio, data.precio.toFixed(2), 3, row)
    CreateElementCell("hidden", "tax[]", data.tax, data.tax.toFixed(2), 4, row)
    CreateElementCell("hidden", "tax_id[]", data.tax_id, data.monto.toFixed(2), 5, row)
    CreateElementCell("button", "delete", "X", "", 6, row)
    //la 5 columna se muestra el subototal y se guarda el tax_id

    table.appendChild(row);
    item++;
    document.getElementById('totalrenglones').innerHTML = parseInt(item);
    CalcInvoice();
}

function AddItem() {
    let tasa = parseFloat(document.getElementById('rate_exchange').value);
    let cantidad = parseFloat(document.getElementById('productQty').value);
    let precio = parseFloat(document.getElementById('productPrice').value);
    let precio_other = parseFloat(document.getElementById('productPriceOther').value);
    let porcentaje = parseFloat(document.getElementById("ptax").value)
    let sel = document.getElementById('pidproduct');
    if (sel.selectedIndex >= 0) // prevee la limpieza del select2 abajo que pone el indice en -1
        var product = sel.options[sel.selectedIndex].text;
    if (tasa > 0 && cantidad > 0 && precio > 0) {
        let tax = cantidad * (precio * porcentaje / 100);
        let monto = cantidad * precio + tax;
        let renglon = {
            "item": item + 1, "product_id": document.getElementById('pidproduct').value,
            "producto": product, "quantity": cantidad, "precio": precio, "precio_other": precio_other,
            "tax_id": document.getElementById('ptax_id').value, "tax": tax, 'tax_percent': porcentaje, "monto": monto
        }
        LoadItem(renglon)

        document.getElementById('productQty').value = 0;
        document.getElementById('productPrice').value = 0;
        document.getElementById("ptax").value = 0;

        $('#pidproduct').select2("val", 0);
        document.getElementById('pidproduct').focus();
        // console.log("SUBTOTAL ADDITEM ", subtotal, document.getElementById('coin_id').value);
    }
    else {
        alert("Verifique los datos de entrada.")
    }
}

