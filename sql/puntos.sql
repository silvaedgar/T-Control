select users.name, sum(points) as points from quiniela.users INNER JOIN quiniela.predictions
	ON users.id = predictions.player_id INNER JOIN quiniela.prediction_details ON predictions.id = prediction_details.prediction_id
    group by users.id