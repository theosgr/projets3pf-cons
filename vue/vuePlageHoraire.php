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
					<input type="date" placeholder="jj/mm/aaaa"/>
					<input type="submit" value="Suivant"/>
				</form>
			</div>
			<!--  FOOTER -->
			<?php  include 'includes/footer.php' ?>

		</body>
		</html>
	<?php
	}
}

?>