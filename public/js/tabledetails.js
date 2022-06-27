//
function UpdateMountPayment() {
    let mount = document.getElementById('mount').value
    let rate = document.getElementById('rate_exchange').value
    let base_coin_id = document.getElementById('base_currency_id').value;
    let base_coin_symbol = document.getElementById('base_currency_symbol').value;

    let coin_id = document.getElementById('coin_id').value;
    let calc_currency = document.getElementById('calc_currency_id').value
    let symbol = document.getElementById('calc_currency_symbol').value

    if (mount != 0) {
        if (coin_id == calc_currency) { // Moneda de Pago o Factura igual a moneda de calculo
            if (coin_id != base_coin_id)  { /// Son $ la Factura o el pago
                document.getElementById('mount').value = parseFloat(mount / rate).toFixed(2)
                // document.getElementById('message_subtitle').innerHTML = ""
                document.getElementById('rate_exchange').value =  1
            }
        }
        else {
            if (coin_id == base_coin_id)  /// Son Bs la Factura o el pago
                document.getElementById('mount').value = parseFloat(mount * rate).toFixed(2)
        }
        document.getElementById('message_subtitle').innerHTML = "Monto en " + (coin_id != base_coin_id ? base_coin_symbol : symbol) + ": " + parseFloat(mount).toFixed(2)
    }

}

function CalculateMountOtherCoin() {

    let coin_id = document.getElementById('coin_id').value
    let base_coin_id = document.getElementById('base_currency_id').value
    let base_coin_symbol = document.getElementById('base_currency_symbol').value
    let calc_coin_id = document.getElementById('calc_currency_id').value
    let calc_coin_symbol = document.getElementById('calc_currency_symbol').value

    let count_in_bs = (document.getElementById('count_in_bs') ? document.getElementById('count_in_bs').value : 'N')
    let mount_current = document.getElementById('mount').value
    let rate =  (document.getElementById("rate_exchange").value > 1 ? document.getElementById("rate_exchange").value : document.getElementById("rate").value)
    let mount_calc_coin = 0

    if (count_in_bs != 'S') {
        if (coin_id == calc_coin_id) { // Divisa de pago o factura igual a la de calculo
           document.getElementById('message_subtitle').innerHTML = (calc_coin_id == base_coin_id) ? '' :
               "Monto en " + base_coin_symbol + " " +parseFloat(mount_current * rate).toFixed(2)
        }
        else {
            if (coin_id == base_coin_id) // Divisa de pago o factura igual a la de calculo
                document.getElementById('message_subtitle').innerHTML = "Monto en " + calc_coin_symbol + " " + 
                        parseFloat(mount_current / rate).toFixed(2)
            else
                document.getElementById('message_subtitle').innerHTML = "Monto: " + parseFloat(mount_current / rate).toFixed(2) + "(" + calc_coin_symbol + ")" +
                        " -- " + parseFloat(mount_current * rate).toFixed(2) + "(" + base_coin_symbol + ")"
        } // ojo la ultima instruccion hay que verificar cuando se paga o factyura en moneda que no es no de calculo no base
    }
    else {
        symbol = base_coin_symbol
        if (coin_id !=  base_coin_id)  // pagando en otra moneda no Bs para paola y livia
             document.getElementById('message_subtitle').innerHTML = parseFloat(document.getElementById("mount").value * document.getElementById("rate").value).toFixed(2);
    }
    document.getElementById('last_rate').value = document.getElementById('rate_exchange').value
}

function CalcInvoice (itemactual,operacion) {
    if (operacion == "suma") {
        total += subtotal[itemactual].monto;      // total y total tx son variable globales
        totaltax += subtotal[itemactual].tax;
    }
    else {
        total -= subtotal[itemactual - 1].monto;
        totaltax -= subtotal[itemactual - 1].tax;
        document.getElementById('totalrenglones').innerHTML = parseInt(item-1);

        for (let i=itemactual - 1; i < item-1 ; i++) {  // cambia los montos para el indice anterior
            subtotal[i].monto = subtotal[i + 1].monto
            subtotal[i].tax = subtotal[i + 1].tax
        }
    }
    let symbol = LoadSymbolCoin();
    document.getElementById('message_title').innerHTML = parseFloat(total).toFixed(2) + symbol[1];
    document.getElementById('mount').value = parseFloat(total).toFixed(2);
    document.getElementById('tax').value = parseFloat(totaltax).toFixed(2);
    CalculateMountOtherCoin();
}

function CalcSubtotal(field) {
    let totales = {'quantity': 0,'price':0, 'tax':0, price_other: 0};
    let coin_id = document.getElementById("coin_id").value
    let calc_coin_id = document.getElementById("calc_currency_id").value
    // let base_coin_id = document.getElementById("base_currency_id").value
    let tasa =  (document.getElementById("rate_exchange").value > 1 ? document.getElementById("rate_exchange").value : document.getElementById("rate").value)
    totales.quantity = document.getElementById("pcantidad").value;
    totales.price = document.getElementById("pprecio").value;
    totales.price_other = document.getElementById("pprecioother").value;
    switch (field) {
        case 'PriceOther' :  document.getElementById("pprecio").value =  (coin_id == calc_coin_id ? parseFloat(totales.price_other / tasa).toFixed(2) : parseFloat(totales.price_other * tasa).toFixed(2))                        
                break;
        case 'Price' :  document.getElementById("pprecioother").value =  (coin_id == calc_coin_id ? parseFloat(totales.price * tasa).toFixed(2) : parseFloat(totales.price / tasa).toFixed(2))
                console.log(" div ",totales.price / tasa)                
        default : break;
    }
    totales.price = document.getElementById("pprecio").value;
    totales.price_other = document.getElementById("pprecioother").value;
    totales.tax = document.getElementById("ptax").value;
    if (totales.quantity > 0 && totales.price > 0) {
        let montototal = parseFloat(totales.quantity) * (parseFloat(totales.price) + parseFloat(totales.price) *  parseFloat(totales.tax)/100);
        document.getElementById("psubtotal").innerHTML =  montototal.toFixed(2);
    }
}

function RecalculateInvoice(change_coin) {

    let rows_table =  document.getElementById('details-table').rows;
    let factor = document.getElementById("factor").value;
    let rowactual = 0
    let last_rate = document.getElementById("last_rate").value
    let tasa = (document.getElementById("rate_exchange").value > 1 ? document.getElementById("rate_exchange").value : 1)
    if (rows_table.length > 0 && last_rate != tasa && tasa != 1) {
        total = 0;    // variable globales que llevan el total
        totaltax = 0;
        let tasa_diff = tasa - last_rate
        for (const element of rows_table) {
            if (rowactual > 0) {  // salta el encabezado
                if (change_coin) {
                    subtotal[rowactual - 1].precio = (factor=="*" ? subtotal[rowactual - 1].precio * tasa : subtotal[rowactual - 1].precio / tasa);
                    subtotal[rowactual - 1].tax = (factor == "*" ? subtotal[rowactual - 1].tax * tasa :  subtotal[rowactual - 1].tax / tasa) ;
                    subtotal[rowactual - 1].monto = (factor == "*" ? subtotal[rowactual - 1].monto * tasa : subtotal[rowactual - 1].monto / tasa);
                }
                else {
                    subtotal[rowactual - 1].precio = subtotal[rowactual - 1].precio + (subtotal[rowactual - 1].precio / last_rate * tasa_diff)  ;
                    subtotal[rowactual - 1].tax = subtotal[rowactual - 1].tax + (subtotal[rowactual - 1].tax_percent /100 * tasa_diff * subtotal[rowactual - 1].precio)  ;
                    subtotal[rowactual - 1].monto = (subtotal[rowactual - 1].precio + subtotal[rowactual - 1].tax) * subtotal[rowactual - 1].quantity ;
                }
                element.cells[3].innerHTML = parseFloat(subtotal[rowactual - 1].precio).toFixed(2);
                element.cells[4].innerHTML = parseFloat(subtotal[rowactual - 1].tax).toFixed(2);
                element.cells[5].innerHTML = parseFloat(subtotal[rowactual - 1].monto).toFixed(2);
                let pricearray = CreateElementInput("hidden","price[]",subtotal[rowactual-1].precio);
                let taxarray = CreateElementInput("hidden","tax[]",subtotal[rowactual-1].tax);
                let taxidarray = CreateElementInput("hidden","tax_id[]",subtotal[rowactual-1].tax_id);
                element.cells[3].appendChild(pricearray);
                element.cells[4].appendChild(taxarray);
                element.cells[5].appendChild(taxidarray);
                CalcInvoice(rowactual - 1,"suma")
            }
            rowactual++;
        }
        CalculateMountOtherCoin()
        document.getElementById("last_rate").value = document.getElementById("rate_exchange").value
    }
}

function CreateElementInput(type,name,value) {
    var elemento  = document.createElement("input");  // elemto del product id
    elemento.type = type;
    elemento.name= name
    elemento.value= value
    return elemento;
}

function CreateElementCell(type,name,value,content,cell,row) {
    var table = document.getElementById('details-table');
    var celda = row.insertCell(cell);  // celda del item
    let elemento = CreateElementInput(type,name,value)
    if (type == 'button') {
        elemento.onclick = function () {
            CalcInvoice(row.cells[0].innerHTML,"resta")
            subtotal.splice(parseInt(row.cells[0].innerHTML)-1,1)
            table.deleteRow(row.cells[0].innerHTML);
            RenumberItems();
            document.getElementById('totalrenglones').innerHTML = parseInt(item);
        }
    }
    else
        celda.innerHTML = content;
    celda.appendChild(elemento);
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
                var fecha = element.sale_date
            else
                var fecha = element.purchase_date
            CreateElementCell("hidden","purchase_date[]",'',fecha, 1,row)
            CreateElementCell("hidden","invoice[]",'',element.invoice,2,row)
            CreateElementCell("hidden","amount[]",'',parseFloat(element.mount).toFixed(2) + element.symbol,3,row)
            CreateElementCell("hidden","tax_mount[]",'',parseFloat(element.tax_mount).toFixed(2) + element.symbol,4,row)
            CreateElementCell("hidden","balance[]",'',parseFloat(element.mount - element.paid_mount).toFixed(2)+ element.symbol,5,row)
            item ++;
            balance += (element.mount - element.paid_mount);
        }
    });
}

function DeleteTable(table) {
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

function RenumberItems() {
    var table = document.getElementById('details-table');
    var rowCount = table.rows.length;
    for(var i=1; i<rowCount; i++) {
        var row = table.rows[i];
        row.cells[0].innerHTML = i
        // row.cells[0].appendChild(CreateElementInput("text","item[]",i))
    }
    item --;   // esta variable es global y se define en globalvars
}

function AddItem(){
    let tasa = parseFloat(document.getElementById('rate_exchange').value);
    let cantidad = parseFloat(document.getElementById('pcantidad').value);
    let precio = parseFloat(document.getElementById('pprecio').value);
    let porcentaje = parseFloat(document.getElementById("ptax").value)
    let sel = document.getElementById('pidproduct');
    if (sel.selectedIndex >= 0) // prevee la limpieza del select2 abajo que pone el indice en -1
        var product = sel.options[sel.selectedIndex].text;
    if (tasa > 0 && cantidad > 0 && precio > 0 ) {
        let tax =    cantidad * (precio * porcentaje /100);
        let monto = cantidad * precio + tax;
        let renglon = {"item": item+1,"product_id":document.getElementById('pidproduct').value,
            "producto":product,"quantity":cantidad,"precio":precio,"tax_id":document.getElementById('ptax_id').value,
            "tax": tax, 'tax_percent': porcentaje, "monto": monto  }
        subtotal.push(renglon);
        CalcInvoice(item,"suma");
        var table = document.getElementById('details-table');
        var row = table.insertRow(item + 1);
        row.id = "fila" + item;
        row.style = "font-size: small; background: white; text-align: left "
        var celda = row.insertCell(0);  // celda del item
        celda.innerHTML = item + 1;
        // CreateElementCell("hidden","item[]",item + 1,item + 1, 0,row) // si lo dejo asi pierdo la eliminacion no se como arreglarlo
        CreateElementCell("hidden","product_id[]",subtotal[item].product_id,subtotal[item].producto, 1,row)
        CreateElementCell("hidden","quantity[]",subtotal[item].quantity,subtotal[item].quantity.toFixed(2),2,row)
        CreateElementCell("hidden","price[]",subtotal[item].precio,subtotal[item].precio.toFixed(2),3,row)
        CreateElementCell("hidden","tax[]",subtotal[item].tax,subtotal[item].tax.toFixed(2),4,row)
        CreateElementCell("hidden","tax_id[]",subtotal[item].tax_id,subtotal[item].monto.toFixed(2),5,row)
        CreateElementCell("button","delete","X","",6,row)
//la 5 columna se muestra el subototal y se guarda el tax_id

        table.appendChild(row);

        item ++;

        document.getElementById('totalrenglones').innerHTML = parseInt(item);

        document.getElementById('pcantidad').value = 0;
        document.getElementById('pprecio').value = 0;
        document.getElementById("ptax").value =0;

        $('#pidproduct').select2("val",0);
        document.getElementById('pidproduct').focus();
    }
    else {
        alert("Verifique los datos de entrada.")
    }
}

