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

			<!--  FOOTER -->
			<?php  include 'includes/footer.php' ?>

		</body>
		</html>
	<?php
	}
}

?>