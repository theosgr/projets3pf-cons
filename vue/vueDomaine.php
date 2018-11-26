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
			<title id="titre">Recherche</title>
			<?php include 'includes/headHTML.php' ?>
		</head>
		<body>







       	<p id="locationphp" value="<?php

					require_once "./config/config.php";

				//	header("Access-Control-Allow-Origin: *");

					try {
							$chaine = "mysql:host=".HOST.";dbname=".BD.";charset=UTF8";
							$db = new PDO($chaine,LOGIN,PASSWORD);
							$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
					} catch (PDOException $e) {
							throw new PDOException("Erreur de connexion");
					}
					if (!isset($_SESSION['id'])) {
						$Paris = "48.866667, 2.333333";
						echo $Paris;
					} else {
						$ID = $_SESSION['id'];
						$sql_get_location = "SELECT location FROM Utilisateurs WHERE mail=:id";
						$sth = $db->prepare($sql_get_location);
						$sth->bindParam(":id", $ID);
						$sth->execute();
						$reponse = $sth->fetch(PDO::FETCH_ASSOC);

						foreach($reponse as $result) {
								echo $result;
						}
					}
				?>"></p>

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
							<div class="pro" onclick="afficherDetailsPro(this);">
								<div class="coordonnes">
									<div class="entete">
										<h4><?php echo ucwords(mb_strtolower($row['prenom'])) . " " . $row['nom']; ?> :</h4>
										<p><?php echo ucwords($row['sous_specialite']) ?></p>
									</div>
									<div class="adresse">
										<div>
											<h6><i class="material-icons">&#xE7F1;</i></h6>
										</div>
										<div>
											<p><?php echo ucwords(mb_strtolower($row['adresse']));?></p>
											<p><?php echo ucwords($row['cp'] . " " . $row['ville']); ?></p>
										</div>
									</div>
									<p class="tel"><i class="material-icons">&#xE0CD;</i><?php echo $row['tel'] ;?></p>
								</div>
								<div class="calendrier">
										<input type="date" id="ddn" name="ddn" placeholder="DD/MM/YYYY"  maxlength="10" value="<?php if(isset($_POST['ddn'])) { echo htmlspecialchars($_POST['ddn']);}?>" />
									</div>
							</div>
							<?php
						}
						?>
					</div>

					<div id="map">


						<script>// On initialise la latitude et la longitude (centre de la carte)
							// Fonction d'initialisation de la carte
							function initMap() {
								var lat = 48;
								var lon = 3;
								var map = null;
								var location = document.getElementById("locationphp").getAttribute("value");
								var locationSplit = location.split(", ");

								// Créer l'objet "map" et l'insèrer dans l'élément HTML qui a l'ID "map"
								map = new google.maps.Map(document.getElementById("map"), {
										center: new google.maps.LatLng(locationSplit[0], locationSplit[1]),
										zoom: 11,
										mapTypeId: google.maps.MapTypeId.ROADMAP,
										mapTypeControl: true,
										scrollwheel: false,
										mapTypeControlOptions: {
										style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR
									},
									navigationControl: true,
									navigationControlOptions: {
									// Comment ces options doivent-elles s'afficher
										style: google.maps.NavigationControlStyle.ZOOM_PAN
									}
								});
							}
							window.onload = function(){
								// Fonction d'initialisation qui s'exécute lorsque le DOM est chargé
								initMap();
							};
						</script>
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
