<?php

// Gestion de l'accueil, de l'inscription et de la connexion
class vueAuthentification {

	/* Vue d'accueil du site. */
	public function genereVueAccueil(){
		?>
		<!DOCTYPE html>
		<html lang="fr">
		<head>
			<title>Accueil</title>
			<?php include 'includes/headHTML.php' ?>
		</head>
		<body>
			<!--  HEADER-->
			<?php  include 'includes/header.php' ?>

			<!--  CONTENT -->
			<div id="content">
				<div class="container-index">
					<h2 class="logo-header">Je recherche un professionnel dans le domaine : </h2>
					<div class="flex-container">
						<a href="index.php?domaine=medical" class="submit-btn-med">MEDICAL</a>
						<a href="index.php?domaine=juridique" class="submit-btn-jur">JURIDIQUE</a>
					</div>
				</div>
			</div>

			<!--  SLIDESHOW-->
			<?php  include 'includes/slideshow.php' ?>

			<!--  FOOTER -->
			<?php  include 'includes/footer.php' ?>

			<!--  FUNCTION -->
		</body>
		</html>

		<?php
	}
	/* Vue de connexion du site */
	public function genereVueConnexion(){
		?>
		<!DOCTYPE html>
		<html lang="fr">
		<head>
			<title>Connexion</title>
			<?php include 'includes/headHTML.php' ?>
		</head>
		<body>
			<!--  HEADER-->
			<?php  include 'includes/header.php' ?>

			<!--  CONTENT -->
			<div id="content">
				<div class="container_form connexion_form">
					<form action="index.php?connexion=1" method="post" onsubmit="return verifFormModifInfos(this)">
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
	/* Vue d'inscription du site */
	public function genereVueInscription($listeSousSpecialite){
		?>
		<!DOCTYPE html>
		<html lang="fr">
		<head>
			<title>Inscription</title>
			<?php include 'includes/headHTML.php' ?>
		</head>
		<body>
			<!--  HEADER-->
			<?php  include 'includes/header.php' ?>

			<!--  CONTENT -->
			<div id="content">
				<div class="container_form">
					<!--  BLOC ONGLET -->
					<div class="block">
						<p>Je suis :</p>
						<?php
						if (isset($_GET['inscription'])) {
							if ($_GET['inscription'] == "user") {
								$onglet1 = "onglet_on" ;
								$onglet2 = "";
								$type = 1;
							}
							if ($_GET['inscription'] == "pro") {
								$onglet1 = "";
								$onglet2 = "onglet_on";
								$type = 2;
							}
						}
						?>
						<div class="onglet">
							<a id="onglet1" class="onglet <?php echo $onglet1;?>" href="index.php?inscription=user">Utilisateur</a>
							<a id="onglet2" class="onglet <?php echo $onglet2;?>" href="index.php?inscription=pro">Professionnel</a>
						</div>
					</div>
					<hr>

					<form id="formInscription" action="index.php?inscription=<?php echo $type ?>" method="post" >
						<!--  BLOC CIVILITE-->
						<div class="block">
							<label>Civilité :</label>
							<div>
								<select name="civilite" >
									<option disabled selected>Je suis...</option>
									<option name="civilite" value="M." <?php if(isset($_POST['civilite'])&& $_POST['civilite'] == "M.") { echo "selected";}?>>Un homme</option>
									<option name="civilite" value="Mme" <?php if(isset($_POST['civilite'])&& $_POST['civilite'] == "Mme") { echo "selected";}?>>Une femme</option>
									<option name="civilite" value="Autre" <?php if(isset($_POST['civilite'])&& $_POST['civilite'] == "Autre") { echo "selected";}?>>Autre</option>
								</select>
								<input id="prenom" type="text" name="prenom" placeholder="Prénom" size="15" required value="<?php if(isset($_POST['prenom'])) { echo htmlspecialchars($_POST['prenom']);}?>"/>
								<input id="nom" type="text" name="nom" placeholder="Nom" size="15" required value="<?php if(isset($_POST['nom'])) { echo htmlspecialchars($_POST['nom']);}?>"/>
							</div>
						</div>
						<hr>

						<!-- BLOC INFO DE CONNEXION -->
						<div class="block">
							<label>Informations de connexion :</label>
							<div>
								<input id="mail" type="email" name="mail" placeholder="Adresse mail" required  value="<?php if(isset($_POST['mail'])) { echo htmlspecialchars($_POST['mail']);}?>"/>
							</div>
							<div>
								<input id="mdp" type="password" name="mdp" placeholder="Mot de passe" required  />
								<input id="mdpConfirm" type="password" name="MdpConfirm" placeholder="Confirmer mot de passe"  required />
							</div>
						</div>
						<hr>

						<!-- BLOC AUTRES INFOS -->
						<div class="block">
							<label name="Birth">Date de Naissance : </label>
							<div>
								<input type="date" id="ddn" name="ddn" placeholder="DD/MM/YYYY"  maxlength="10" value="<?php if(isset($_POST['ddn'])) { echo htmlspecialchars($_POST['ddn']);}?>" />
							</div>
							<label>N° de téléphone mobile : </label>
							<div>
								<input id="tel" type="tel" name="tel" placeholder="N° tel" maxlength="10" value="<?php if(isset($_POST['tel'])) { echo htmlspecialchars($_POST['tel']);}?>" />
							</div>
						</div>
						<hr>

						<!-- BLOC ADRESSE -->
						<div class="block">
							<label>Adresse : </label>
							<div>
								<input id="adresse" type="text" name="adresse" placeholder="Adresse" required value="<?php if(isset($_POST['adresse'])) { echo htmlspecialchars($_POST['adresse']);}?>"/>
							</div>

							<div>
								<input id="cp" class="ui-autocomplete-input" type="text" name="cp" placeholder="Code Postal" required maxlength="5" value="<?php if(isset($_POST['cp'])) { echo htmlspecialchars($_POST['cp']);}?>"/>
								<ul class="ui-autocomplete ui-menu ui-widget ui-widget-content ui-corner-all" role="listbox" aria-activedescendant="ui-active-menuitem" style="z-index: 1; top: 0px; left: 0px; display: none;"></ul>
								<input id="ville" type="text" name="ville" placeholder="Ville" required value="<?php if(isset($_POST['ville'])) { echo htmlspecialchars($_POST['ville']);}?>"/>
							</div>

							<div style="display:none">
								<input id="location" type="text" name="location" value="" readonly>
							</div>
						</div>
						<hr>

						<!-- BLOC SPECIALITE -->
						<?php
						if (isset($_GET['inscription']) AND $_GET['inscription'] == "pro") {
							?>
							<div id="blocSpecialite" class="block">
								<label>Spécialité : </label>
								<div>
									<select id="specialite" name="sous_specialite">
										<?php
										foreach ($listeSousSpecialite as $row) {
											?>
											<option value="<?php echo $row['nom']; ?>"><?php echo ucwords(mb_strtolower($row['nom'],'UTF-8')); ?></option>
											<?php
										}
										?>
									</select>
								</div>
							</div>
							<hr>
							<?php
						}
						?>

						<!--  BLOC SUBMIT -->
						<div class="block">
							<div>
								<input name="send" class="submit-btn" type="submit" value="S'inscrire" />
							</div>
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

	public function genereVueAdmin($listeDomaine, $listeSpecialite)
	{
		?>
		<!DOCTYPE html>
		<html lang="fr">
		<head>
			<title>Gestion du site</title>
			<?php include 'includes/headHTML.php' ?>
		</head>
		<body id="gestionSite">
			<!--  HEADER-->
			<?php  include 'includes/header.php' ?>
			<div class="creationSite">
				<h3>Créer un domaine</h3>
					<form method="post" action="index.php">
					<input type="text" name="domaineCree" placeholder="Entrer le nom du domaine" />
					<input type="submit" value="Confirmer" class="boutonCreation"/>
				</form>
			</div>
			<div class="creationSite">
				<h3>Créer une spécialité</h3>
				<form method="post" action="?ajouterSpecialite=<?php if(isset($_POST['specialiteCree'])){echo $_POST['specialiteCree'];} ?>">
					<label>Domaine : </label>
					<select id="domaine" name="domaine">
						<?php
							foreach ($listeDomaine as $row) {
						?>
							<option value="<?php echo $row['nom']; ?>"><?php echo ucwords(mb_strtolower($row['nom'],'UTF-8')); ?></option>
						<?php
								}
						?>
					</select>
					<label>Spécialité : </label><input type="text" name="specialiteCree" placeholder="Entrer le nom de la spécialité"/>
					<input type="submit" value="Confirmer" class="boutonCreation"/>
				</form>
			</div>
			<div class="creationSite" id="dernierDivAdmin">
				<h3>Créer une sous-spécialité</h3>
				<form method="post" action="?ajouterSousSpecialite=<?php if(isset($_POST['sousSpecialiteCree'])){echo $_POST['sousSpecialiteCree'];} ?>">
					<label>Spécialité : </label>
					<select id="specialite" name="specialite">
						<?php
							foreach ($listeSpecialite as $row) {
						?>
							<option value="<?php echo $row['nom']; ?>"><?php echo ucwords(mb_strtolower($row['nom'],'UTF-8')); ?></option>
						<?php
								}
						?>
					</select>

					<label>Sous-spécialité : </label><input type="text" name="sousSpecialiteCree" placeholder="Entrer le nom de la sous-spécialité"/>
					<input type="submit" value="Confirmer" class="boutonCreation"/>
				</form>
			</div>
			<!-- FOOTER -->
			<?php include 'includes/footer.php'; ?>
		</body>
		</html>

		<?php
	}
}
?>
