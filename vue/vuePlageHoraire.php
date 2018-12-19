<?php

// Gestion des Plages Horaires des professionnels
class vuePlageHoraire {

	public function genereVuePlageHoraire ($idPro){
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
				<form action="index.php?idPro2=<?php echo $idPro;?>" method="post">
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
					<?php if(!empty($plageHoraire))
					{
						?>
					<label>Sélectionner votre plage horaire</label>
					<select name="listeHoraires">
						<?php
							foreach($plageHoraire as $row)
							{
								?>
								<option value="<?php echo($row['id']);?>">
									<?php 
										if($row['estRemplace']==0)
										{
											echo($row['1']." - ".$row['2']);
										}
										else
										{
											echo($row['1']."  -  ".$row['2']);
											echo("   (");
											echo("Le médecin sera remplacé par ".$row['civiliteRemplacant']." ".$row['nomRemplacant'].")");
										}
									?>
								</option>
								<?php
							}
						?>
					</select>
					<label>Motif du rendez-vous:</label>
					<textarea name="motif" rows="8" placeholder="Décrivez brièvement la raison de votre prise de rendez-vous" resize="none" cols="50"></textarea>
					</br>
					<input type="hidden" name="idPro" value="<?php echo($_GET['idPro2']);?>"/>

					<input type="submit" value="Suivant"/>
					<?php
				}
				else
				{
					echo("Le médecin ne propose pas de rendez-vous pour cette date");
				} ?>
				</form>
			</div>
			<!--  FOOTER -->
			<?php  include 'includes/footer.php' ?>

		</body>
		</html>
	<?php
	}

	public function genereVueSelectionProche($listeProche)
	{
		?>
		<!DOCTYPE html>
		<html>
			<!--HEAD-->
			<?php include 'includes/headHTML.php' ?>
			<body>
				<!--HEADER-->
				<?php  include 'includes/header.php' ?>
				<form action="index.php" method="POST">
				<label>Sélectionner la personne concernée</label>
					<select name="listeProche">
						<option value="<?php echo $_SESSION['id']?>">Vous</option>
						<?php
							foreach($listeProche as $row)
							{
								?>
								<option value="<?php echo($row['id']);?>">
									<?php 
											echo($row['prenom']." ".$row['nom']);
									?>
								</option>
								<?php
							}
						?>
					</select>

					<input type="submit" value="Valider le rendez-vous"/>
				</form>
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
	public function genereVueModifPlageHoraireJourParJour($idPro)
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

			<form method="POST" action="index.php?jour=1">
				<h4>Lundi</h4>
				<label>Durée d'un rendez-vous (Maximum 59 minutes)</label><input type="time" name="dureeRdv" placeholder="hh:mm"/>
				<label>Heure de début de service</label><input type="time" name="debutServ" placeholder="hh:mm"/>
				<label>Heure de fin de service</label><input type="time" name="finServ" placeholder="hh:mm"/>
				<label>Heure de début de pause</label><input type="time" name="debutPause" placeholder="hh:mm"/>
				<label>Heure de fin de pause</label><input type="time" name="finPause" placeholder="hh:mm"/><br>
				<label>Date à partir de laquelle ce planning est valable</label><input type="date" placeholder="jj/mm/aaaa" name="debutPlanning"/>
				<label>Date à partir de laquelle ce planning n'est plus valable</label><input type="date" placeholder="jj/mm/aaaa" name="finPlanning"/>
				<input type="hidden" value="Monday" name="jour"/>
				<input type="submit" value="Valider"/>
			</form>

			<form method="POST" action="index.php?jour=1">
				<h4>Mardi</h4>
				<label>Durée d'un rendez-vous (Maximum 59 minutes)</label><input type="time" name="dureeRdv" placeholder="hh:mm"/>
				<label>Heure de début de service</label><input type="time" name="debutServ" placeholder="hh:mm"/>
				<label>Heure de fin de service</label><input type="time" name="finServ" placeholder="hh:mm"/>
				<label>Heure de début de pause</label><input type="time" name="debutPause" placeholder="hh:mm"/>
				<label>Heure de fin de pause</label><input type="time" name="finPause" placeholder="hh:mm"/><br>
				<label>Date à partir de laquelle ce planning est valable</label><input type="date" placeholder="jj/mm/aaaa" name="debutPlanning"/>
				<label>Date à partir de laquelle ce planning n'est plus valable</label><input type="date" placeholder="jj/mm/aaaa" name="finPlanning"/>
				<input type="hidden" value="Thursday" name="jour"/>
				<input type="submit" value="Valider"/>
			</form>

			<form method="POST" action="index.php?jour=1">
				<h4>Mercredi</h4>
				<label>Durée d'un rendez-vous (Maximum 59 minutes)</label><input type="time" name="dureeRdv" placeholder="hh:mm"/>
				<label>Heure de début de service</label><input type="time" name="debutServ" placeholder="hh:mm"/>
				<label>Heure de fin de service</label><input type="time" name="finServ" placeholder="hh:mm"/>
				<label>Heure de début de pause</label><input type="time" name="debutPause" placeholder="hh:mm"/>
				<label>Heure de fin de pause</label><input type="time" name="finPause" placeholder="hh:mm"/><br>
				<label>Date à partir de laquelle ce planning est valable</label><input type="date" placeholder="jj/mm/aaaa" name="debutPlanning"/>
				<label>Date à partir de laquelle ce planning n'est plus valable</label><input type="date" placeholder="jj/mm/aaaa" name="finPlanning"/>
				<input type="hidden" value="Wednesday" name="jour"/>
				<input type="submit" value="Valider"/>
			</form>

			<form method="POST" action="index.php?jour=1">
				<h4>Jeudi</h4>
				<label>Durée d'un rendez-vous (Maximum 59 minutes)</label><input type="time" name="dureeRdv" placeholder="hh:mm"/>
				<label>Heure de début de service</label><input type="time" name="debutServ" placeholder="hh:mm"/>
				<label>Heure de fin de service</label><input type="time" name="finServ" placeholder="hh:mm"/>
				<label>Heure de début de pause</label><input type="time" name="debutPause" placeholder="hh:mm"/>
				<label>Heure de fin de pause</label><input type="time" name="finPause" placeholder="hh:mm"/><br>
				<label>Date à partir de laquelle ce planning est valable</label><input type="date" placeholder="jj/mm/aaaa" name="debutPlanning"/>
				<label>Date à partir de laquelle ce planning n'est plus valable</label><input type="date" placeholder="jj/mm/aaaa" name="finPlanning"/>
				<input type="hidden" value="Tuesday" name="jour"/>
				<input type="submit" value="Valider"/>
			</form>

			<form method="POST" action="index.php?jour=1">
				<h4>Vendredi</h4>
				<label>Durée d'un rendez-vous (Maximum 59 minutes)</label><input type="time" name="dureeRdv" placeholder="hh:mm"/>
				<label>Heure de début de service</label><input type="time" name="debutServ" placeholder="hh:mm"/>
				<label>Heure de fin de service</label><input type="time" name="finServ" placeholder="hh:mm"/>
				<label>Heure de début de pause</label><input type="time" name="debutPause" placeholder="hh:mm"/>
				<label>Heure de fin de pause</label><input type="time" name="finPause" placeholder="hh:mm"/><br>
				<label>Date à partir de laquelle ce planning est valable</label><input type="date" placeholder="jj/mm/aaaa" name="debutPlanning"/>
				<label>Date à partir de laquelle ce planning n'est plus valable</label><input type="date" placeholder="jj/mm/aaaa" name="finPlanning"/>
				<input type="hidden" value="Friday" name="jour"/>
				<input type="submit" value="Valider"/>
			</form>

			<form method="POST" action="index.php?jour=1">
				<h4>Samedi</h4>
				<label>Durée d'un rendez-vous (Maximum 59 minutes)</label><input type="time" name="dureeRdv" placeholder="hh:mm"/>
				<label>Heure de début de service</label><input type="time" name="debutServ" placeholder="hh:mm"/>
				<label>Heure de fin de service</label><input type="time" name="finServ" placeholder="hh:mm"/>
				<label>Heure de début de pause</label><input type="time" name="debutPause" placeholder="hh:mm"/>
				<label>Heure de fin de pause</label><input type="time" name="finPause" placeholder="hh:mm"/><br>
				<label>Date à partir de laquelle ce planning est valable</label><input type="date" placeholder="jj/mm/aaaa" name="debutPlanning"/>
				<label>Date à partir de laquelle ce planning n'est plus valable</label><input type="date" placeholder="jj/mm/aaaa" name="finPlanning"/>
				<input type="hidden" value="Saturday" name="jour"/>
				<input type="submit" value="Valider"/>
			</form>

			<form method="POST" action="index.php?jour=1">
				<h4>Dimanche</h4>
				<label>Durée d'un rendez-vous (Maximum 59 minutes)</label><input type="time" name="dureeRdv" placeholder="hh:mm"/>
				<label>Heure de début de service</label><input type="time" name="debutServ" placeholder="hh:mm"/>
				<label>Heure de fin de service</label><input type="time" name="finServ" placeholder="hh:mm"/>
				<label>Heure de début de pause</label><input type="time" name="debutPause" placeholder="hh:mm"/>
				<label>Heure de fin de pause</label><input type="time" name="finPause" placeholder="hh:mm"/><br>
				<label>Date à partir de laquelle ce planning est valable</label><input type="date" placeholder="jj/mm/aaaa" name="debutPlanning"/>
				<label>Date à partir de laquelle ce planning n'est plus valable</label><input type="date" placeholder="jj/mm/aaaa" name="finPlanning"/>
				<input type="hidden" value="Sunday" name="jour"/>
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
