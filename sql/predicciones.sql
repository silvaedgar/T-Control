SELECT * FROM quiniela.prediction_details where matchup_id = 1 or matchup_id = 2 or matchup_id = 3;

select prediction_details.*, users.name from quiniela.users INNER JOIN quiniela.predictions
	ON users.id = predictions.player_id INNER JOIN quiniela.prediction_details ON predictions.id = prediction_details.prediction_id
    where matchup_id = 1 or matchup_id = 2 or matchup_id = 3;