SELECT purchase_date, mount, rate_exchange, symbol, suppliers.name, 'Compras', mount / rate_exchange as abonado, balance_initial  
FROM suppliers INNER JOIN purchases ON suppliers.id = purchases.supplier_id 
INNER JOIN coins ON purchases.coin_id = coins.id WHERE supplier_id = 11
UNION 
SELECT payment_date, mount, rate_exchange, symbol, suppliers.name, 'Pagos', mount / rate_exchange as abonado, balance_initial
FROM suppliers INNER JOIN payment_suppliers ON suppliers.id = payment_suppliers.supplier_id 
INNER JOIN coins ON payment_suppliers.coin_id = coins.id WHERE supplier_id = 11 order by 1;


SELECT sale_date, mount, rate_exchange, symbol, clients.names,'Sales', mount * rate_exchange as abonado 
FROM clients INNER JOIN sales ON clients.id = sales.client_id 
INNER JOIN coins ON sales.coin_id = coins.id WHERE client_id = 9
UNION 
SELECT payment_date, mount, rate_exchange, symbol, clients.names, 'Pagos', mount * rate_exchange as abonado 
FROM clients INNER JOIN payment_clients ON clients.id = payment_clients.client_id 
INNER JOIN coins ON payment_clients.coin_id = coins.id WHERE client_id = 9 order by 1