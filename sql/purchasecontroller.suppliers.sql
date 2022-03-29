select suppliers.balance, suppliers.name, coins.symbol, purchases.* FROM suppliers LEFT JOIN purchases ON suppliers.id = purchases.supplier_id 
LEFT JOIN coins ON purchases.coin_id = coins.id
WHERE suppliers.id = 2 ;


select purchases.*,suppliers.balance,coins.symbol FROM suppliers LEFT JOIN purchases ON suppliers.id = purchases.supplier_id 
INNER JOIN coins ON purchases.coin_id = coins.id
WHERE (suppliers.id = 1 and (purchases.status = 'Pendiente' or purchases.status = 'Parcial'))
