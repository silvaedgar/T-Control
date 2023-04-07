function LoadCoins() {
    coin = {}
    // ---- Moneda Actual
    currency = SearchCoin(document.getElementById('coin_id').value)
    coin.current = currency[0];
    // Moneda de calculo
    currency = SearchCoin(collections.calcCoin.id)
    coin.calculation = currency[0];
    // Moneda Base
    currency = SearchCoin(collections.baseCoin.id)
    coin.base = currency[0];
    return coin;
}

function SearchCoin(id) {
    return collections.coins.filter(currency => currency.id == id)
}

function SearchIdPayment(variable) {
    let variableId = document.getElementById(variable).value;
    let rate = document.getElementById('rate').value
    var table = document.getElementById('details-table');
    coin = LoadCoins()
    DeleteTable(table);
    let query = urlBase + '/api/search_invoice_' + variable.substr(0, variable.length - 3) + "/"
    query += variableId + "/" + coin.calculation.id + '/' + coin.base.id
    if (variableId > 0) {
        fetch(query).then(datos => { return datos.json(); })
            .then(data => {
                let balance = data[0].balance;
                CreateTable(table, data, variable == 'supplier_id' ? 'Compra' : 'Venta');
                if (variable == 'supplier_id') {
                    document.getElementById('message_title').innerHTML = "Saldo: " +
                        parseFloat(balance).toFixed(2) + ' ' + coin.calculation.symbol;
                }
                else {
                    let count_in_bs = data[0].count_in_bs;
                    document.getElementById('message_subtitle').innerHTML = "";
                    document.getElementById('message_title').innerHTML = "Saldo: " + parseFloat(balance).toFixed(2)
                        + " " + (count_in_bs == 'N' ? coin.calculation.symbol : coin.base.symbol)
                    if (coin.calculation.id != coin.base.id && count_in_bs == 'N') {
                        document.getElementById('message_title').innerHTML += " -- " +
                            parseFloat(balance * rate).toFixed(2) + coin.base.symbol;
                    }
                }
            })
    }
    else {
        let message = "Seleccione el " + variable == 'client_id' ? "Cliente" : "Proveedor"
        alert(message);
    }
}

function JumpRateExchange() {
    let coin_id = document.getElementById("coin_id").value;
    coin = LoadCoins()
    // let calc_currency_id = document.getElementById("calc_currency_id").value  tambien por aqui
    if (coin_id == coin.calculation.id) {   // moneda de calculo es la misma de la factura la tasa es 1
        if (document.getElementById('observations'))
            document.getElementById('observations').focus()
        else
            document.getElementById('mount').focus()

    }
}


