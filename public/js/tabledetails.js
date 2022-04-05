function CalculateMountOtherCoin() {

    let calc_coin = document.getElementById('calc_currency').value
    let coin_id = document.getElementById('coin_id').value
    if (calc_coin !=coin_id) {
        let factor = document.getElementById('factor').value;
        let mount_calc_coin = (factor == "*" ? document.getElementById("mount").value / document.getElementById("rate_exchange").value :
            document.getElementById("mount").value * document.getElementById("rate_exchange").value);
        document.getElementById('payment_mount').innerHTML = "Monto en " + document.getElementById("symbol_coin_calc").value + " " + parseFloat(mount_calc_coin).toFixed(2);
        document.getElementById('last_rate').value = document.getElementById('rate_exchange').value
    }
}

function CalcInvoice (itemactual,operacion) {

    if (operacion == "suma") {
        total += subtotal[itemactual].monto;
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
    document.getElementById('mountlabel').innerHTML = parseFloat(total).toFixed(2) + document.getElementById('symbol_coin').value;
    document.getElementById('mount').value = parseFloat(total).toFixed(2);
    document.getElementById('tax').value = parseFloat(totaltax).toFixed(2);
    let payment_mount = document.getElementById('payment_mount').innerHTML;
    if (payment_mount != ''){
        console.log("OTHER COIN en CALCINOICE")
        CalculateMountOtherCoin();
    }

}

function CalcSubtotal() {
    let totales = {'cantidad': 0,'precio':0, 'tax':0};
    totales.cantidad = document.getElementById("pcantidad").value;
    totales.precio = document.getElementById("pprecio").value;
    totales.tax = document.getElementById("ptax").value;
    if (totales.cantidad > 0 && totales.precio > 0) {
        let montototal = parseFloat(totales.cantidad) * (parseFloat(totales.precio) + parseFloat(totales.precio) *  parseFloat(totales.tax)/100);
        document.getElementById("psubtotal").innerHTML =  montototal.toFixed(2);
    }
}

function RecalculateInvoice(tasa,filas) {

    let factor = document.getElementById("factor").value;
    total = 0;
    totaltax = 0;
    let rowactual = 0
    for (const element of filas) {
        if (rowactual > 0) {  // salta el encabezado
            subtotal[rowactual - 1].precio = (factor=="*" ? subtotal[rowactual - 1].precio * tasa : subtotal[rowactual - 1].precio / tasa);
            subtotal[rowactual - 1].tax = (factor == "*" ? subtotal[rowactual - 1].tax * tasa :  subtotal[rowactual - 1].tax / tasa) ;
            subtotal[rowactual - 1].monto = (factor == "*" ? subtotal[rowactual - 1].monto * tasa : subtotal[rowactual - 1].monto / tasa);
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
    item --;
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
            "producto":product,"cantidad":cantidad,"precio":precio,"tax_id":document.getElementById('ptax_id').value,
            "tax": tax, "monto": monto  }
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
        CreateElementCell("hidden","quantity[]",subtotal[item].cantidad,subtotal[item].cantidad.toFixed(2),2,row)
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

