<?php

// Gestion de la recherche
class vueDomaine {

	// Affichage de la page de recherche
	public function genereVueDomaine($domaine){
		?>
		<!DOCTYPE html>
		<html lang="fr">
		<head>
			<title>Recherche</title>
			<?php include 'includes/headHTML.php' ?>
		</head>
		<body>
			<!--  HEADER-->
			<?php  include 'includes/header.php' ?>

			<!--  CONTENT -->
			<div class="content">
				<?php if ($domaine == 1) {
					$class = "searchbar_med";
					$type = 1;
					$_SESSION['domaine'] = "MEDICAL";
				} else {
					$class = "searchbar_jur";
					$type = 2;
					$_SESSION['domaine'] = "JURIDIQUE";
				}
				?>

				<div class="searchbar <?php echo $class; ?>" >
					<form class="searchbar" action="index.php?search=<?php echo $type; ?>" method="post">
						<div id="search_dom1" class="search_dom">
							<i class="material-icons">&#xE8B6;</i>
							<input id="specialiste" type="text" name="specialiste" placeholder="<?php if($domaine == 1) {echo "Médecin, spécialité..."; } else { echo "Avocat, spécialité..."; }?>" >
						</div>
						<div id="search_dom2" class="search_dom">
							<i class="material-icons">&#xE0C8;</i>
							<input id="ville" type="text" name="ville" placeholder="Où ?" >
						</div>
						<div class="valid_dom">
							<input type="submit" value="Rechercher >" >
						</div>
					</form>
				</div>
			</div>

			<!--  FOOTER -->
			<?php  include 'includes/footer.php' ?>

		</body>
		</html>
		<?php
	}

	// Affichage des spécialistes recherchés
	public function genereVueRecherche($domaine, $listeSpecialistes){
		?>
		<!DOCTYPE html>
		<html lang="fr">
		<head>
			<title>Recherche</title>
			<?php include 'includes/headHTML.php' ?>
		</head>
		<body>
			<!--  HEADER-->
			<?php  include 'includes/header.php' ?>

			<!--  CONTENT -->
			<div class="content">
				<?php if ($domaine == 1) {
					$class = "searchbar_med";
					$type = 1;
				} else {
					$class = "searchbar_jur";
					$type = 2;
				}
				?>

				<div class="searchbar mini" >
					<form class="searchbar mini" action="index.php?search=<?php echo $type; ?>" method="post">
						<div id="search_dom1" class="search_dom">
							<i class="material-icons">&#xE8B6;</i>
							<input id="specialiste" type="text" name="specialiste" placeholder="<?php if($domaine == 1) {echo "Médecin, spécialité..."; } else { echo "Avocat, spécialité..."; }?>" value="<?php if(isset($_POST['specialiste'])) {echo $_POST['specialiste']; }?>">
						</div>
						<div id="search_dom2" class="search_dom">
							<i class="material-icons">&#xE0C8;</i>
							<input id="ville" type="text" name="ville" placeholder="Où ?" value="<?php if(isset($_POST['ville'])) {echo $_POST['ville']; }?>" >
						</div>
						<div class="valid_dom">
							<input type="submit" value="Rechercher >" >
						</div>
					</form>
				</div>

				<div class="recherche">
					<div class="listeSpecialistes">
						<?php
						foreach ($listeSpecialistes as $row) {
							?>
							<div class="pro">
								<div class="coordonnes">
									<div class="entete">
										<h4><?php echo ucwords(mb_strtolower($row['prenom'],'UTF-8')) . " " . $row['nom']; ?> :</h4>
										<p><?php echo ucwords($row['sous_specialite']) ?></p>
									</div>
									<div class="adresse">
										<div>
											<h6><i class="material-icons">&#xE7F1;</i></h6>
										</div>
										<div>
											<p><?php echo ucwords(mb_strtolower($row['adresse'],'UTF-8'));?></p>
											<p><?php echo ucwords($row['cp'] . " " . $row['ville']); ?></p>
										</div>
									</div>
									<p class="tel"><i class="material-icons">&#xE0CD;</i><?php echo $row['tel'] ;?></p>
								</div>
								<div class="dispo">
									// calendrier
								</div>
							</div>
							<?php
						}
						?>
					</div>
					<div id="map">
						<!-- Carte Google maps gérée par le script maps.js -->
					</div>
				</div>
			</div>

			<!--  FOOTER -->
			<?php  include 'includes/footer.php' ?>

		</body>
		</html>
		<?php
	}
}
?>
