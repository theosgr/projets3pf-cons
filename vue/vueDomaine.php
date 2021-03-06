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
	public function genereVueRecherche($domaine, $listeSpecialistes,$ville){
		?>
		<!DOCTYPE html>
		<html lang="fr">
		<head>
			<title>Recherche</title>
			<?php include 'includes/headHTML.php' ?>
		</head>
		<body onload="">

			<input id="ville" type="hidden" value="<?php echo $ville ?>">
			<input id="loca" type="hidden" value="">

			<script>
			// function geolocalisation() {
				var apikey = '3f3b618fe28844949b1341a5341bd5e0';
				var adresse = document.getElementById("ville").getAttribute("value");
				var api_url = 'https://api.opencagedata.com/geocode/v1/json';
				var request_url = api_url
					+ '?'
					+ 'key=' +encodeURIComponent(apikey)
					+ '&q=' + encodeURIComponent(adresse)
					+ '&countrycode=' + "fr"
					+ '&pretty=1'
					+ '&no_annotations=1';

				// see full list of required and optional parameters:
				// https://opencagedata.com/api#forward

				var request = new XMLHttpRequest();
				request.open('GET', request_url, true);

				request.onload = function() {
				// see full list of possible response codes:
				// https://opencagedata.com/api#codes

					if (request.status == 200){
						// Success!
						var data = JSON.parse(request.responseText);
						document.getElementById("loca").value = data.results[0].geometry["lat"]+", "+data.results[0].geometry["lng"];
						console.log("Coordonnées entrées");
					} else if (request.status <= 500){
					// We reached our target server, but it returned an error
						console.log("unable to geocode! Response code: " + request.status);
						var data = JSON.parse(request.responseText);
						console.log(data.status.message);
					} else {
						console.log("server error");
					}
				};

				request.onerror = function() {
					// There was a connection error of some sort
					console.log("unable to connect to server");
				};

				request.send();  // make the request
			// }
			</script>

		<input id="locationphp" type="hidden" value="<?php

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
				?>"/>

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
						$allLocations = array();
						foreach ($listeSpecialistes as $row) {
							?>
							<div class="pro" id="<?php echo ucwords(mb_strtolower($row['location'])); ?>">
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
									<?php array_push($allLocations,ucwords(mb_strtolower($row['location'])));?>
									<div class="boutons">
										<form action="index.php?idPro=<?php echo $row['id'];?>" method="post">
											<input class="boutonRdv" type="submit" value="Prendre rendez-vous"/>
											<input class="boutonQuestion" type="button" value="Poser une question"/>
											<input class="question" type="text" name="question" style="visibility:hidden;"/>
											<button class="boutonSoumettre" type="submit" name="envoiQuestion" style="visibility:hidden;" formaction="index.php?question=1&domaine=<?php echo $domaine ?>&mailPro=<?php echo $row['mail']?>">Envoyer</button>
                    </form>
									</div>
								</div>
                <div id="calendrier">
  								<div class="calendar" style="text-align:center;">Chargement en cours...</div>
  								<input type="hidden" value="<?php echo($row['id']); ?>"/>
                </div>
							</div>
							<?php
						}
						?>
					</div>

					<div id="fixed" style="position:fixed; width: 100%; height: 500px;">
						<p id="map">
							<!-- Carte Google maps gérée par le script maps.js -->
							<script>// On initialise la latitude et la longitude (centre de la carte)
								// Fonction d'initialisation de la carte

								function initMap() {
									console.log("Initilisation de la map");
									var lat = 48;
									var lon = 3;
									var map = null;
									if ((document.getElementById("loca").getAttribute("value")) == false) {
										var location = document.getElementById("locationphp").getAttribute("value");
									} else {
										var location = document.getElementById("loca").getAttribute("value");
									}
									var locationSplit = location.split(", ");
									let tabMarker = new Array;
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

									<?php foreach ($allLocations as $row): ?>
										var locationPro = "<?php echo $row ?>";
										var locationProSplit = locationPro.split(", ");
										var iconOrange = {
									    url: './vue/img/markerOrange.png', // url
									    scaledSize: new google.maps.Size(25, 40), // scaled size
										};
										var iconRed = {
											url: './vue/img/markerRed.png', // url
											scaledSize: new google.maps.Size(25, 40), // scaled size
										};
										var marker = new google.maps.Marker({
											// A chaque boucle, la latitude et la longitude sont lues dans le tableau
											position: {lat: parseFloat(locationProSplit[0]), lng: parseFloat(locationProSplit[1])},
											map: map,
											icon: iconOrange
										});
										marker.set("id",locationPro);
										tabMarker.push(marker);
									<?php endforeach; ?>

									function newLocation(newCenter){
										var newCenterSplit = newCenter.split(", ");
										var center = new google.maps.LatLng(parseFloat(newCenterSplit[0]), parseFloat(newCenterSplit[1]));
										console.log(parseFloat(newCenterSplit[0]))
										map.setCenter(center);
									};

									for (var i = 0; i < tabMarker.length; i++) {
										(function(){
											var j =i;

											$('.pro').each(function(){
												var id = $(this).attr('id');
												tabMarker[i].addListener('click', function() {
													if (id == tabMarker[j].id) {
														var pro = document.getElementById(id);
														// on modifie son style
														$("html, body").animate({ scrollTop: $(pro).position().top }, 800);
													}
												})

												tabMarker[i].addListener('mouseover', function() {
													if (id == tabMarker[j].id) {
														var pro = document.getElementById(id);
														// on modifie son style
														tabMarker[j].setIcon(iconRed);
														pro.style.border = "solid orange 2px";
													}
												})

												tabMarker[i].addListener('mouseout', function() {
													if (id == tabMarker[j].id) {
														var pro = document.getElementById(id);
														// on modifie son style
														pro.style.border = "";
														tabMarker[j].setIcon(iconOrange);
													}
												})
											})
										}())
									}

									$('.pro').each(function(index, item){
										var id = item.id;
										item.addEventListener('click', function() {
											for (var marker in tabMarker) {
												if (id == tabMarker[marker].id) {
													var pro = document.getElementById(id);
													// on modifie son style
													map.zoom = 13;
													newLocation(tabMarker[marker].id);
												}
											}
										})

										item.addEventListener('mouseover', function() {
											for (var marker in tabMarker) {
												if (id == tabMarker[marker].id) {
													var pro = document.getElementById(id);
													// on modifie son style
													tabMarker[marker].setIcon(iconRed);
												}
											}
										})

										item.addEventListener('mouseout', function() {
											for (var marker in tabMarker) {
												if (id == tabMarker[marker].id) {
													var pro = document.getElementById(id);
													// on modifie son style
													tabMarker[marker].setIcon(iconOrange);
												}
											}
										});
									});
								};

								window.addEventListener('load', () => {
									// // Fonction d'initialisation qui s'exécute lorsque le DOM est chargé
									// let fonction1 = function() {
									// 	let r = $.Deferred();
									// 	console.log("1");
									//
									// 	return r;
									// };
									//
									// let fonction2 = function() {
									// 	console.log("2");
									// };
									//
									// fonction1().done( fonction2() );
									//
									// geolocalisation().done( initMap() );
									// $.when($.ajax(geolocalisation())).then(function() {

									initMap();
									// });
								}, false);
							</script>
						</p>
					</div>
				</div>
			</div>

			<!--  FOOTER -->
			<?php  include 'includes/footer.php' ?>

		</body>
		</html>
		<?php
	}

	public function genereVueConnexionQuestion($domaine, $mailPro)
	{
		?>
		<!DOCTYPE html>
		<html lang="fr">
		<head>
			<title>Connexion</title>
			<?php include 'includes/headHTML.php' ?>

			<!-- reCaptchav3 -->
			<script type="text/javascript" src="https://www.google.com/recaptcha/api.js?render=6Ld2OJEUAAAAAH-Okg308sOkTm7WkUiWdqaoiJ4C"></script>
			<script>
			        grecaptcha.ready(function () {
			            grecaptcha.execute('6Ld2OJEUAAAAAH-Okg308sOkTm7WkUiWdqaoiJ4C', { action: 'connexion' }).then(function (token) {
			                var recaptchaResponse = document.getElementById('recaptchaResponse');
			                recaptchaResponse.value = token;
			            });
			        });
			</script>

		</head>
		<body>
			<!--  HEADER-->
			<?php  include 'includes/header.php' ?>

			<!--  CONTENT -->
			<div id="content">
				<div class="container_form connexion_form">
					<?php // Check if form was submitted:
				if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recaptcha_response'])) {

				    // Build POST request:
				    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
				    $recaptcha_secret = '6Ld2OJEUAAAAAJbSfk-ugX-DPE8szoOgK0V5vcxR';
				    $recaptcha_response = $_POST['recaptcha_response'];

				    // Make and decode POST request:
				    $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
				    $recaptcha = json_decode($recaptcha);

				    // Take action based on the score returned:
						if(isset($recaptcha->score))
						{
					    if ($recaptcha->score >= 0.5) {
					       header('Location:index.php?connexion=1');
					    } else {
								 header('Location:index.php?connexion&robot=1');
					    }
						}
				}
				?>
					<form action="index.php?connexionQuestion=1&domaine=<?php echo $domaine ?>&mailPro=<?php echo $mailPro ?>" method="post" onsubmit="return verifFormModifInfos(this)">
						<!--  BLOC IDENTIFIANT -->
						<div>
							<input type="text" name="login" placeholder="Identifiant" required size="25" />
						</div>
						<div>
							<input type="password" name="mdp" placeholder="Mot de passe" required size="25" />
						</div>
						<hr>

						<!--  BLOC VALIDATION -->
						<div>
							<input name="send" class="submit-btn" type="submit" value="Se connecter" />
							<input type="hidden" name="recaptcha_response" id="recaptchaResponse"/>
						</div>
					</form>
					<div class="formline">
						<p>Pas encore membre ? <a class="lien_visible" href="index.php?inscription=user">Inscrivez-vous</a> gratuitement. </p>
					</div>
					<div class="formline">
						<p><a class="lien_visible" href="index.php?reset">Mot de passe oublié ?</a></p>
					</div>
				</div>
			</div>

			<!--  SLIDESHOW-->
			<?php  include 'includes/slideshow.php' ?>

			<!--  FOOTER -->
			<?php  include 'includes/footer.php' ?>

		</body>
		</html>
		<?php
	}
}
?>
