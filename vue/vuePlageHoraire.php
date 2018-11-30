<?php

// Gestion des Plages Horaires des professionnels
class vuePlageHoraire {

	public function genereVuePlageHoraire ($plageHoraire){
		?>
		<!DOCTYPE html>
		<html lang="fr">
		<head>
			<title> Disponibilites </title>
			<!--HEAD-->
			<?php include 'includes/headHTML.php' ?>
		</head>
		<body>
			<!--HEADER-->
			<?php  include 'includes/header.php' ?>

			<!--CONTENT-->
			<div>
				<form action="index.php" method="post">
					<label>Rentrer la date de votre rendez-vous</label>
					<input type="date" name="daterdv" placeholder="jj/mm/aaaa"/>
					<input type="submit" value="Suivant"/>
				</form>
			</div>
			<!--  FOOTER -->
			<?php  include 'includes/footer.php' ?>

		</body>
		</html>
	<?php
	}

	public function genereVueSelectionHoraire ($plageHoraire){
		?>
		<!DOCTYPE html>
		<html lang="fr">
		<head>
			<title> Selection horaire </title>
			<!--HEAD-->
			<?php include 'includes/headHTML.php' ?>
		</head>
		<body>
			<!--HEADER-->
			<?php  include 'includes/header.php' ?>

			<!--CONTENT-->
			<div>
				<form action="index.php" method="post">
					<label>Sélectionner votre plage horaire</label>
					<input type="date" placeholder="jj/mm/aaaa"/>
					Motif du rendez-vous:
					</br>
					<textarea name="motif" rows="8" placeholder="Décrivez brièvement la raison de votre prise de rendez-vous"resize="none" cols="50"></textarea>
					</br>
					
					<input type="submit" value="Valider le rendez-vous"/>
				</form>
			</div>
			<!--  FOOTER -->
			<?php  include 'includes/footer.php' ?>

		</body>
		</html>
	<?php
	}

	public function genereVueModifPlageHoraire($idPro)
	{
		?>
		<!DOCTYPE html>
		<html lang="fr">
		<head>
			<title> Selection horaire </title>
			<!--HEAD-->
			<?php include 'includes/headHTML.php' ?>
		</head>
		<body>
			<!--HEADER-->
			<?php  include 'includes/header.php' ?>

			<form method="POST" action="index.php">
				<label>Durée d'un rendez-vous</label><input type="time" name="dureeRdv" placeholder="hh:mm"/>
				<label>Heure de début de service</label><input type="time" name="debutServ" placeholder="hh:mm"/>
				<label>Heure de fin de service</label><input type="time" name="finServ" placeholder="hh:mm"/>
				<label>Heure de début de pause</label><input type="time" name="debutPause" placeholder="hh:mm"/>
				<label>Heure de fin de pause</label><input type="time" name="finPause" placeholder="hh:mm"/>
				<label>Date à partir de laquelle ce planning est valable</label><input type="date" placeholder="jj/mm/aaaa" name="debutPlanning"/>
				<label>Date à partir de laquelle ce planning n'est plus valable</label><input type="date" placeholder="jj/mm/aaaa" name="finPlanning"/>
				<input type="submit" value="Valider"/>
			</form>
			<!--  FOOTER -->
			<?php  include 'includes/footer.php' ?>
		</body>
		</html>
		<?php
	}
}

?>
