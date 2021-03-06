<?php

  require_once PATH_MODELE."/bean/Utilisateur.php";

   class dao {
    private $connexion;

///////// BASE DE DONNEES
    /* Connexion à la base de données */
    public function __construct(){
      try {
        $chaine = "mysql:host=".HOST.";dbname=".BD.";charset=UTF8";
        $pdo_options[PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES utf8';
        $this->connexion = new PDO($chaine,LOGIN,PASSWORD,$pdo_options);
        $this->connexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $e) {
        throw new PDOException("Erreur de connexion");
      }
    }

    /* Deconnexion de la base de données */
    public function destroy(){
      $this->connexion = NULL;
    }


    /* Méthode permettant de modifier le mot de passe */

	public function modifierMdp($mdp){
  	try {

  		$mdp = password_hash($mdp, PASSWORD_DEFAULT);
  		$stmt = $this->connexion->prepare('update Utilisateurs SET mdp = ? where mail = ?');
        $stmt->bindParam(1,$_POST['mdp']);
        $stmt->bindParam(2,$_SESSION['id']);
        $stmt->execute();
  	}
  	catch(PDOException $e) {
    	$this->destroy();
    	throw new PDOException("Erreur d'accès à la table Utilisateurs");
  	}
	}

    /* Méthode permettant de voir si un utilisateur est deja inscrit */
    public function estInscrit($mail){
      try {
        $stmt = $this->connexion->prepare("select * from Utilisateurs where mail = ?;");
        $stmt->bindParam(1,$mail);
        $stmt->execute();
        $result=$stmt->fetch(PDO::FETCH_ASSOC);
        if ($result["mail"] != NUll) {
          return true;
        } else {
          return false;
        }
      } catch(PDOException $e) {
        $this->destroy();
        throw new PDOException("Erreur d'accès à la table Utilisateurs");
      }
    }

    /* Méthode permettant de vérifier le mot de passe */
    public function checkMdp($mail, $mdp) {
      try {
        if ($this->estInscrit($mail)) {
          $stmt = $this->connexion->prepare("select * from Utilisateurs where mail = ?;");
          $stmt->bindParam(1,$mail);
          $stmt->execute();
          $mdpUtilisateur = $stmt->fetch();
          $mdpUser = $mdpUtilisateur["mdp"];
          if (crypt($mdp, $mdpUser) == $mdpUser) {
            return true;
          } else {
            return false;
          }
        }
      } catch(PDOException $e) {
        $this->destroy();
        throw new PDOException("Erreur d'accès à la table Utilisateurs");
      }
    }

    /* Méthode permettant la connexion d'un utilisateur */
    public function connexion() {
      try {
        if ($this->checkMdp($_POST['login'],$_POST['mdp'])) {
          $stmt = $this->connexion->prepare('select * from Utilisateurs where mail = ?;');
          $stmt->bindParam(1,$_POST['login']);
          $stmt->execute();
          $tabResult = $stmt->fetch();
          if ($tabResult != NULL) {
            $tmpConnexion=mb_strtolower($tabResult['prenom'],'UTF-8') . " " . $tabResult['nom'];
            return ucfirst($tmpConnexion);
          }
        }
          return "ko";
      } catch (PDOException $e) {
        $this->destroy();
        throw new PDOException("Erreur d'accès à la table Utilisateurs");
      }
    }

    /* Méthode permettant de vérifier le formulaire d'inscription et formater les données envoyées par l'utilisateur */
    public function checkFormInscription() {
      // Verification civilite
      if (!isset($_POST['civilite']) || ($_POST['civilite'] != "M." && $_POST['civilite'] != "Mme" && $_POST['civilite'] != "Autre")) {
        $_SESSION['message'] = "Champ civilite incorrect";
        return false;
      }

      // Verification prenom
      if (!isset($_POST['prenom']) || strlen($_POST['prenom']) < 2 || strlen($_POST['prenom']) > 25 ) {
        $_SESSION['message'] = "Champ prénom incorrect";
        return false;
      }
      $_POST['prenom'] = htmlspecialchars($_POST['prenom']);

      // Verification nom
      if (!isset($_POST['nom']) || strlen($_POST['nom']) < 2 || strlen($_POST['nom']) > 25 ) {
        $_SESSION['message'] = "Champ nom incorrect";
        return false;
      }
      $_POST['nom'] = htmlspecialchars($_POST['nom']);

      // Verification adresse
      if (!isset($_POST['adresse']) || strlen($_POST['adresse']) < 2 || strlen($_POST['adresse']) > 50 ) {
        $_SESSION['message'] = "Champ adresse incorrect";
        return false;
      }
      $_POST['adresse'] = htmlspecialchars($_POST['adresse']);


      // Verification ville
      if (!isset($_POST['ville']) || strlen($_POST['ville']) < 2 || strlen($_POST['ville']) > 50 ) {
        $_SESSION['message'] = "Champ ville incorrect";
        return false;
      }
      $_POST['ville'] = htmlspecialchars($_POST['ville']);

      // Verification mail
      if (!isset($_POST['mail']) || !preg_match("/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/", $_POST['mail'])) {
        $_SESSION['message'] = "Champ mail incorrect";
        return false;
      }
      $_POST['mail'] = htmlspecialchars($_POST['mail']);

      // Verification mot de passe
      if (isset($_POST['mdp']) && isset($_POST['MdpConfirm'])) {
        if ($_POST['mdp'] == $_POST['MdpConfirm']) {
          if (strlen($_POST['mdp']) > 5 && strlen($_POST['mdp']) < 25) {
            // mot de passe correct
          } else {
            $_SESSION['message'] = "Le mot de passe doit comporter entre 5 et 25 caractères";
            return false;
          }
        } else {
          $_SESSION['message'] = "Les mots de passe doivent être égaux";
          return false;
        }
      } else {
        $_SESSION['message'] = "Les deux champs mots de passe doivent être complétés";
        return false;
      }

      // Verification date de naissance
      if (!isset($_POST['ddn']) || $_POST['ddn'] == "") {
        // Champ sous la forme aaaa-mm-jj
        if (preg_match("/^[0-9]{4}-[01-12]-[01-31]$/",$_POST['ddn'])) {
          list($year, $month, $day) = split('[/.-]', $_POST['ddn']);
          if ($year < date(Y)-100) {
            $_SESSION['message'] = "Veuillez entre une date valide";
            return false;
          }
          if ($year >= date(Y) && $month >= date(m) && $day >= date(d)) {
            $_SESSION['message'] = "Veuillez entre une date valide";
            return false;
          }
        } else
        // Champ sous la forme jj/mm/aaaa
        if (preg_match("/^[0-31][/|.|-][01-12][/|.|-][0-9]{4}$/",$_POST['ddn'])) {
          list($day, $month, $year) = split('[/.-]', $_POST['ddn']);
          if ($year < date(Y)-100) {
            $_SESSION['message'] = "Veuillez entre une date valide";
            return false;
          }
          if ($year >= date(Y) && $month >= date(m) && $day >= date(d)) {
            $_SESSION['message'] = "Veuillez entre une date valide";
            return false;
          }
        }
        $_SESSION['message'] = "Veuillez completer votre date de naissance";
        return false;
      }
      $_POST['ddn'] = htmlspecialchars($_POST['ddn']);

      // Verification n° de tel
      if (!isset($_POST['tel']) || !preg_match("/^0[1-9]([-. ]?[0-9]{2}){4}$/", $_POST['tel'])) {
        $_SESSION['message'] = "Numéro de téléphone incorrect";
        return false;
      }
      $_POST['tel'] = htmlspecialchars($_POST['tel']);

      // Verification du code postal
      if (!isset($_POST['cp']) || !preg_match("/^[0-9]{5,5}$/", $_POST['cp'])) {
        $_SESSION['message'] = "Code Postal incorrect";
        return false;
      }
      $_POST['cp'] = htmlspecialchars($_POST['cp']);

      // Vérification complétion du champ coordonnées
      if (!isset($_POST['location']) || $_POST['location'] == "") {
        $_SESSION['message'] = "Votre adresse n'a pas pu être géolocalisée";
        return false;
      }
      $_POST['location'] = htmlspecialchars(substr($_POST['location'], 1, -1));

      // Verification spécialité (si autre)
      if ($_GET['inscription'] == "2" && $_POST['sous_specialite'] == "autre") {
        if (!isset($_POST['newSpe'])) {
          $_SESSION['message'] = "Spécialité incorrect";
          return false;
        }
        $_POST['newSpe'] = htmlspecialchars($_POST['newSpe']);
      }
      return true;
    }

    /* Méthode permettant de vérifier le formulaire de modifications et formater les données envoyées par l'utilisateur */
    public function checkFormModifications() {
      // Verification civilite
      if (!isset($_POST['civilite']) || ($_POST['civilite'] != "M." && $_POST['civilite'] != "Mme" && $_POST['civilite'] != "Autre")) {
        $_SESSION['message'] = "Champ civilite incorrect";
        return false;
      }

      // Verification prenom
      if (!isset($_POST['prenom']) || strlen($_POST['prenom']) < 2 || strlen($_POST['prenom']) > 25 ) {
        $_SESSION['message'] = "Champ prénom incorrect";
        return false;
      }
      $_POST['prenom'] = htmlspecialchars($_POST['prenom']);

      // Verification nom
      if (!isset($_POST['nom']) || strlen($_POST['nom']) < 2 || strlen($_POST['nom']) > 25 ) {
        $_SESSION['message'] = "Champ nom incorrect";
        return false;
      }
      $_POST['nom'] = htmlspecialchars($_POST['nom']);

      // Verification adresse
      if (!isset($_POST['adresse']) || strlen($_POST['adresse']) < 2 || strlen($_POST['adresse']) > 50 ) {
        $_SESSION['message'] = "Champ adresse incorrect";
        return false;
      }
      $_POST['adresse'] = htmlspecialchars($_POST['adresse']);


      // Verification ville
      if (!isset($_POST['ville']) || strlen($_POST['ville']) < 2 || strlen($_POST['ville']) > 50 ) {
        $_SESSION['message'] = "Champ ville incorrect";
        return false;
      }
      $_POST['ville'] = htmlspecialchars($_POST['ville']);

      // Verification mail
      if (!isset($_POST['mail']) || !preg_match("/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/", $_POST['mail'])) {
        $_SESSION['message'] = "Champ mail incorrect";
        return false;
      }
      $_POST['mail'] = htmlspecialchars($_POST['mail']);

      // Verification mot de passe
      if (!isset($_POST['mdp']) && !isset($_POST['MdpConfirm'])) {
        if ($_POST['mdp'] == $_POST['MdpConfirm']) {
          if (strlen($_POST['mdp']) > 5 && strlen($_POST['mdp']) < 25) {
            // mot de passe correct
          } else {
            $_SESSION['message'] = "Le mot de passe doit comporter entre 5 et 25 caractères";
            return false;
          }
        } else {
          $_SESSION['message'] = "Les mots de passe doivent être égaux";
          return false;
        }
      }

      // Verification date de naissance
      if (!isset($_POST['ddn']) || $_POST['ddn'] == "") {
        // Champ sous la forme aaaa-mm-jj
        if (preg_match("/^[0-9]{4}-[01-12]-[01-31]$/",$_POST['ddn'])) {
          list($year, $month, $day) = split('[/.-]', $_POST['ddn']);
          if ($year < date(Y)-100) {
            $_SESSION['message'] = "Veuillez entre une date valide";
            return false;
          }
          if ($year >= date(Y) && $month >= date(m) && $day >= date(d)) {
            $_SESSION['message'] = "Veuillez entre une date valide";
            return false;
          }
        } else
        // Champ sous la forme jj/mm/aaaa
        if (preg_match("/^[0-31][/|.|-][01-12][/|.|-][0-9]{4}$/",$_POST['ddn'])) {
          list($day, $month, $year) = split('[/.-]', $_POST['ddn']);
          if ($year < date(Y)-100) {
            $_SESSION['message'] = "Veuillez entre une date valide";
            return false;
          }
          if ($year >= date(Y) && $month >= date(m) && $day >= date(d)) {
            $_SESSION['message'] = "Veuillez entre une date valide";
            return false;
          }
        }
        $_SESSION['message'] = "Veuillez completer votre date de naissance";
        return false;
      }
      $_POST['ddn'] = htmlspecialchars($_POST['ddn']);

      // Verification n° de tel
      if (!isset($_POST['tel']) || !preg_match("/^0[1-9]([-. ]?[0-9]{2}){4}$/", $_POST['tel'])) {
        $_SESSION['message'] = "Numéro de téléphone incorrect";
        return false;
      }
      $_POST['tel'] = htmlspecialchars($_POST['tel']);

      // Verification du code postal
      if (!isset($_POST['cp']) || !preg_match("/^[0-9]{5,5}$/", $_POST['cp'])) {
        $_SESSION['message'] = "Code Postal incorrect";
        return false;
      }
      $_POST['cp'] = htmlspecialchars($_POST['cp']);

      // Vérification complétion du champ coordonnées
      if (!isset($_POST['location']) || $_POST['location'] == "") {
        $_SESSION['message'] = "Votre adresse n'a pas pu être géolocalisée";
        return false;
      }
      $_POST['location'] = htmlspecialchars(substr($_POST['location'], 1, -1));

      return true;
    }

    /* Méthode permettant de vérifier le formulaire d'inscription de proche et formater les données envoyées par l'utilisateur */
    public function checkFormProche() {
      // Verification civilite
      if (!isset($_POST['civiliteP']) || ($_POST['civiliteP'] != "M." && $_POST['civiliteP'] != "Mme" && $_POST['civiliteP'] != "Autre")) {
        $_SESSION['message'] = "Champ civilite incorrect";
        return false;
      }

      // Verification prenom
      if (!isset($_POST['prenomP']) || strlen($_POST['prenomP']) < 2 || strlen($_POST['prenomP']) > 25 ) {
        $_SESSION['message'] = "Champ prénom incorrect";
        return false;
      }
      $_POST['prenomP'] = htmlspecialchars($_POST['prenomP']);

      // Verification nom
      if (!isset($_POST['nomP']) || strlen($_POST['nomP']) < 2 || strlen($_POST['nomP']) > 25 ) {
        $_SESSION['message'] = "Champ nom incorrect";
        return false;
      }
      $_POST['nomP'] = htmlspecialchars($_POST['nomP']);

      // Verification adresse
      if (!isset($_POST['adresseP']) || strlen($_POST['adresseP']) < 2 || strlen($_POST['adresseP']) > 50 ) {
        $_SESSION['message'] = "Champ adresse incorrect";
        return false;
      }
      $_POST['adresseP'] = htmlspecialchars($_POST['adresseP']);


      // Verification ville
      if (!isset($_POST['villeP']) || strlen($_POST['villeP']) < 2 || strlen($_POST['villeP']) > 50 ) {
        $_SESSION['message'] = "Champ ville incorrect";
        return false;
      }
      $_POST['villeP'] = htmlspecialchars($_POST['villeP']);

      // Verification date de naissance
      if (!isset($_POST['ddnP']) || $_POST['ddnP'] == "") {
        // Champ sous la forme aaaa-mm-jj
        if (preg_match("/^[0-9]{4}-[01-12]-[01-31]$/",$_POST['ddn'])) {
          list($year, $month, $day) = split('[/.-]', $_POST['ddnP']);
          if ($year < date(Y)-100) {
            $_SESSION['message'] = "Veuillez entre une date valide";
            return false;
          }
          if ($year >= date(Y) && $month >= date(m) && $day >= date(d)) {
            $_SESSION['message'] = "Veuillez entre une date valide";
            return false;
          }
        } else
        // Champ sous la forme jj/mm/aaaa
        if (preg_match("/^[0-31][/|.|-][01-12][/|.|-][0-9]{4}$/",$_POST['ddn'])) {
          list($day, $month, $year) = split('[/.-]', $_POST['ddnP']);
          if ($year < date(Y)-100) {
            $_SESSION['message'] = "Veuillez entre une date valide";
            return false;
          }
          if ($year >= date(Y) && $month >= date(m) && $day >= date(d)) {
            $_SESSION['message'] = "Veuillez entre une date valide";
            return false;
          }
        }
        $_SESSION['message'] = "Veuillez completer votre date de naissance";
        return false;
      }
      $_POST['ddnP'] = htmlspecialchars($_POST['ddnP']);

      // Verification n° de tel
      if (!isset($_POST['telP']) || !preg_match("/^0[1-9]([-. ]?[0-9]{2}){4}$/", $_POST['telP'])) {
        $_SESSION['message'] = "Numéro de téléphone incorrect";
        return false;
      }
      $_POST['telP'] = htmlspecialchars($_POST['telP']);

      // Verification du code postal
      if (!isset($_POST['cpP']) || !preg_match("/^[0-9]{5,5}$/", $_POST['cpP'])) {
        $_SESSION['message'] = "Code Postal incorrect";
        return false;
      }
      $_POST['cpP'] = htmlspecialchars($_POST['cpP']);

      // Vérification complétion du champ coordonnées
      if (!isset($_POST['locationP']) || $_POST['locationP'] == "") {
        $_SESSION['message'] = "Votre adresse n'a pas pu être géolocalisée";
        return false;
      }
      $_POST['locationP'] = htmlspecialchars(substr($_POST['locationP'], 1, -1));

      return true;
    }
/////////
///////// AJOUT / SUPPRESSION
    /** Méthode permettant d'ajouter un utilisateur */
    public function addUser($categorie) {
      try {
        if (!$this->estInscrit($_POST['mail'])) {
          $stmt = $this->connexion->prepare('insert into Utilisateurs values(NULL,?,?,?,?,?,?,?,?,?,?,?,?,?);');
          $stmt->bindParam(1,$_POST['civilite']);
          $tmpPrenomAddUser=strtoupper($_POST['prenom']);
          $stmt->bindParam(2,$tmpPrenomAddUser);
          $tmpNomAddUser=strtoupper($_POST['nom']);
          $stmt->bindParam(3,$tmpNomAddUser);
          $stmt->bindParam(4,$_POST['mail']);
          $tmpMdpAddUser=password_hash($_POST['mdp'], PASSWORD_DEFAULT); //Password_hash() est plus efficace que crypt()
          $stmt->bindParam(5,$tmpMdpAddUser);
          $stmt->bindParam(6,$_POST['ddn']);
          $stmt->bindParam(7,$_POST['tel']);
          $tmpAdresseAddUser=strtoupper($_POST['adresse']);
          $stmt->bindParam(8,$tmpAdresseAddUser);
          $stmt->bindParam(9,$_POST['cp']);
          $tmpVilleAddUser=strtoupper($_POST['ville']);
          $stmt->bindParam(10,$tmpVilleAddUser);
          $stmt->bindParam(11,$_POST['location']);
          // Utilisateur simple
          if ($categorie == 1) {
            $stmt->bindValue(12,1);
            $stmt->bindValue(13,NULL);
          // Specialiste
          } else {
            $stmt->bindValue(12,2);
            // Sous spécialite autre
            if ($_POST['sous_specialite'] == "autre") {
              if ($this->getIdSousSpecialite($_POST['newSpe']) == NULL) {
                if ($_POST['domaine'] == "MEDICAL") {
                  $idSpe = $this->getIdSpecialite(mb_strtolower($_POST['speMedecine'],'UTF-8'));
                } else if ($_POST['domaine'] == "JURIDIQUE") {
                  $idSpe = $this->getIdSpecialite(mb_strtolower($_POST['speJuridique'],'UTF-8'));
                }
                // Vérification qu'elle n'existe pas déjà
                if ($this->getIdSousSpecialite(mb_strtolower($_POST['newSpe'],'UTF-8')) == NULL) {
                  $this->insertSousSpecialite(mb_strtolower($_POST['newSpe'],'UTF-8'), $idSpe['id']);
                }
              }
              $spe = mb_strtolower($_POST['newSpe'],'UTF-8');
            // Sous spécialite existante
            } else {
              $spe = mb_strtolower($_POST['sous_specialite']);
            }
            $idSsSpe = $this->getIdSousSpecialite($spe);
            $stmt->bindParam(13, $idSsSpe['id']);
          }
          $stmt->execute();
          return "ok";
        }
        return "ko";
      } catch (PDOException $e) {
        $this->destroy();
        throw new PDOException("Erreur d'accès à la table Utilisateurs");
      }
    }

    /** Méthode permettant d'ajouter un proche */
    public function addProche() {
      try {
        if ($this->estInscrit($_SESSION['id'])) {
          // Recupération de l'id de l'utilisateur
          $user = $this->getIdUser($_SESSION['id']);
          // Inscription du proche
          $stmt = $this->connexion->prepare('insert into Proche values(NULL,?,?,?,?,?,?,?,?,?,?);');
          $stmt->bindParam(1, $user['id']);
          $stmt->bindParam(2,$_POST['civiliteP']);

          $tmpPrenomP = strtoupper($_POST['prenomP']); //Deux étapes nécessaires pour les versions récentes de php.
          $stmt->bindParam(3,$tmpPrenomP);

          $tmpNomP = strtoupper($_POST['nomP']); //Idem
          $stmt->bindParam(4,$tmpNomP);

          $stmt->bindParam(5,$_POST['ddnP']);
          $stmt->bindParam(6,$_POST['telP']);

          $tmpAdresseP=strtoupper($_POST['adresseP']); //Idem
          $stmt->bindParam(7,$tmpAdresseP);

          $stmt->bindParam(8,$_POST['cpP']);

          $tmpVilleP=strtoupper($_POST['villeP']); //Idem
          $stmt->bindParam(9,$tmpVilleP);

          $stmt->bindParam(10,$_POST['locationP']);
          $stmt->execute();
          return "ok";
        }
        return "ko";
      } catch (PDOException $e) {
        $this->destroy();
        throw new PDOException("Erreur d'accès à la table Proche");
      }
    }

    /** Méthode permettant de supprimer un utilisateur  */
    public function delUser() {
      try {
        if ($this->estInscrit($_SESSION['id'])) {
          // Recuperation de l'utilisateur
          $user = $this->getInfosUser($_SESSION['id']);
          // Suppression de ses proches et des rendez-vous des proches
          $proches = $this->getProches($user[0]->getId());
          $_SESSION['debug'] = $proches;
          foreach ($proches as $unProche) {
            $this->delProche($unProche['id']);
          }
          // Suppression de ses rendez-vous
          $this->delRdv($user[0]->getId(), $user[0]->getNom(), $user[0]->getPrenom());
          // Suppression de son compte
          $stmt = $this->connexion->prepare('delete from Utilisateurs where mail = ?;');
          $stmt->bindParam(1,$_SESSION['id']);
          $stmt->execute();
        }
      } catch (PDOException $e) {
        $this->destroy();
        throw new PDOException("Erreur d'accès à la table Utilisateurs");
      }
    }

    /** Méthode permettant de supprimer un proche */
    public function delProche($id) {
      try {
        if ($this->estInscrit($_SESSION['id'])) {
          // Suppression de ses rendez-vous
          $user = $this->getInfosProche($id);
          $this->delRdv($user['idliaisut'], $user['nom'], $user['prenom']);
          // Suppression du proche
          $stmt = $this->connexion->prepare('delete from Proche where id = ?');
          $stmt->bindParam(1,$id);
          $stmt->execute();
        }
      } catch (PDOException $e) {
        $this->destroy();
        throw new PDOException("Erreur d'accès à la table Proche");
      }
    }
/////////
///////// GESTION COMPTE
    /* Méthode permettant de récuper l'id d'un utilisateur */
    public function getIdUser($mail) {
      try {
        $stmt = $this->connexion->prepare('select * from Utilisateurs where mail = ?');
        $stmt->bindParam(1, $mail);
        $stmt->execute();
        return $stmt->fetch();
      } catch (PDOException $e) {
        $this->destroy();
        throw new PDOException("Erreur d'accès à la table Utilisateurs");
      }
    }

    /* Méthode permettant de récuperer les informations d'un utilisateur */
    public function getInfosUser() {
      try {
        if ($this->estInscrit($_SESSION['id'])) {
          $stmt = $this->connexion->prepare('select * from Utilisateurs where mail = ?;');
          $stmt->bindParam(1,$_SESSION['id']);
          $stmt->execute();
          return $stmt->fetchAll(PDO::FETCH_CLASS, "Utilisateur");
        }
      } catch (PDOException $e) {
        $this->destroy();
        throw new PDOException("Erreur d'accès à la table Utilisateurs");
      }
    }

    /* Méthode permettant de réupérer les proches d'un utilisateur */
    public function getProches($idUser){
      try {
        $stmt = $this->connexion->prepare('select * from Proche where idliaisut = ?');
        $stmt->bindParam(1,$idUser);
        $stmt->execute();
        return $stmt->fetchAll();
      } catch (PDOException $e) {
        $this->destroy();
        throw new PDOException("Erreur d'accès à la table Rdv");
      }
    }

    /* Méthode permettant de récuperer les informations d'un proche */
    public function getInfosProche($idProche) {
      try {
        if ($this->estInscrit($_SESSION['id'])) {
          $stmt = $this->connexion->prepare('select * from Proche where id = ?;');
          $stmt->bindParam(1,$idProche);
          $stmt->execute();
          return $stmt->fetch();
        }
      } catch (PDOException $e) {
        $this->destroy();
        throw new PDOException("Erreur d'accès à la table Proche");
      }
    }

    /* Méthode permettant de modifier les informations d'un compte utilisateur */
    public function modifInfosCompte($mdp) {
      try {
        if ($this->estInscrit($_SESSION['id'])) {
          // modif civilite
          $stmt = $this->connexion->prepare('update Utilisateurs SET civilite = ? where mail = ?');
          $stmt->bindParam(1,$_POST['civilite']);
          $stmt->bindParam(2,$_SESSION['id']);
          $stmt->execute();

          // modif nom
          $stmt = $this->connexion->prepare('update Utilisateurs SET nom = ? where mail = ?');
          $stmt->bindParam(1,strtoupper($_POST['nom']));
          $stmt->bindParam(2,$_SESSION['id']);
          $stmt->execute();

          // nom prenom
          $stmt = $this->connexion->prepare('update Utilisateurs SET prenom = ? where mail = ?');
          $stmt->bindParam(1,strtoupper($_POST['prenom']));
          $stmt->bindParam(2,$_SESSION['id']);
          $stmt->execute();

          // modif ddn
          $stmt = $this->connexion->prepare('update Utilisateurs SET ddn = ? where mail = ?');
          $stmt->bindParam(1,strtoupper($_POST['ddn']));
          $stmt->bindParam(2,$_SESSION['id']);
          $stmt->execute();

          // modif tel
          $stmt = $this->connexion->prepare('update Utilisateurs SET tel = ? where mail = ?');
          $stmt->bindParam(1,strtoupper($_POST['tel']));
          $stmt->bindParam(2,$_SESSION['id']);
          $stmt->execute();

          // modif adresse
          $stmt = $this->connexion->prepare('update Utilisateurs SET adresse = ? where mail = ?');
          $stmt->bindParam(1,strtoupper($_POST['adresse']));
          $stmt->bindParam(2,$_SESSION['id']);
          $stmt->execute();

          // modif cp
          $stmt = $this->connexion->prepare('update Utilisateurs SET cp = ? where mail = ?');
          $stmt->bindParam(1,$_POST['cp']);
          $stmt->bindParam(2,$_SESSION['id']);
          $stmt->execute();

          // modif ville
          $stmt = $this->connexion->prepare('update Utilisateurs SET ville = ? where mail = ?');
          $stmt->bindParam(1,strtoupper($_POST['ville']));
          $stmt->bindParam(2,$_SESSION['id']);
          $stmt->execute();

          // modif location
          $stmt = $this->connexion->prepare('update Utilisateurs SET location = ? where mail = ?');
          $stmt->bindParam(1,$_POST['location']);
          $stmt->bindParam(2,$_SESSION['id']);
          $stmt->execute();

          // modif mdp si modification
          if ($mdp == 1) {
            $stmt = $this->connexion->prepare('update Utilisateurs SET mdp = ? where mail = ?');
            $stmt->bindParam(1,password_hash($_POST['mdp']),PASSWORD_DEFAULT);
            $stmt->bindParam(2,$_SESSION['id']);
            $stmt->execute();
          }

          // modif mail
          $stmt = $this->connexion->prepare('update Utilisateurs SET mail = ? where mail = ?');
          $stmt->bindParam(1,$_POST['mail']);
          $stmt->bindParam(2,$_SESSION['id']);
          $stmt->execute();

          return "ok";
        }
        return "ko";
      } catch (PDOException $e) {
        $this->destroy();
        throw new PDOException("Erreur d'accès à la table Utilisateurs");
      }
    }

    /* Méthode permettant de récupérer les rendez-vous dun utilisateur */
    public function getRdv($idUser){
      try {
        $stmt = $this->connexion->prepare('SELECT r.id, u.nom, u.prenom, DATE_FORMAT(heureDebut,"%H:%i"), DATE_FORMAT(heureFin,"%H:%i"), DATE_FORMAT(jour,"%d/%m/%Y"), nomPa, prenomPa, motif FROM Rdv AS r, Utilisateurs AS u WHERE idpracticien = u.id AND idpatient = ?');
        $stmt->bindParam(1,$idUser);
        $stmt->execute();
        return $stmt->fetchAll();
      } catch (PDOException $e) {
        $this->destroy();
        throw new PDOException("Erreur d'accès à la table Rdv");
      }
    }

     /* Méthode permettant de récupérer les rendez-vous d'un professionnel */
    public function getRdvPro($idUser){
      try {
        $stmt = $this->connexion->prepare('SELECT r.id, u.nom, u.prenom, idpatient, idpracticien, DATE_FORMAT(heureDebut,"%H:%i"), DATE_FORMAT(heureFin,"%H:%i"), DATE_FORMAT(jour,"%d/%m/%Y"), nomPa, prenomPa, motif FROM Rdv AS r, Utilisateurs as u WHERE idpracticien = u.id AND (idpracticien = ? OR idpatient=?)');
        $stmt->bindParam(1,$idUser);
        $stmt->bindParam(2,$idUser);
        $stmt->execute();
        return $stmt->fetchAll();
      } catch (PDOException $e) {
        $this->destroy();
        throw new PDOException("Erreur d'accès à la table Rdv");
      }
    }

    public function getRdvById($idRdv)
    {
      $stmt= $this->connexion->prepare("SELECT * FROM rdv WHERE id=?");
      $stmt->bindParam(1,$idRdv);
      $stmt->execute();

      return $stmt->fetch();
    }

    public function addRdv($idPro, $heureDebut, $heureFin, $date, $idPatient, $prenomPatient, $nomPatient, $motif, $idPlageHoraire)
    {
      try{
        $stmt = $this->connexion->prepare('INSERT INTO rdv VALUES (id,?,?,?,?,?,?,?,?,?)');
      $stmt->bindParam(1,$idPro);
      $stmt->bindParam(2,$heureDebut);
      $stmt->bindParam(3,$heureFin);
      $stmt->bindParam(4,$date);
      $stmt->bindParam(5,$idPatient);
      $stmt->bindParam(7,$prenomPatient);
      $stmt->bindParam(6,$nomPatient);
      $stmt->bindParam(8,$motif);
      $stmt->bindParam(9,$idPlageHoraire);

      $stmt->execute();
      }
      catch(PDOException $e)
      {
        // throw new PDOException("Problème d'accès à la table");
        echo($e->getMessage());
      }
    }

    /* Méthode permettant de supprimer un rendez-vous */
    public function delRdv($idUser, $nomUser, $prenomUser) {
      try {
        $stmt = $this->connexion->prepare('update Rdv SET idpatient = NULL, nomPa = NULL, prenomPa = NULL where idpatient= ? and nomPa=? and prenomPa=?');
        $stmt->bindParam(1,$idUser);
        $stmt->bindParam(2,$nomUser);
        $stmt->bindParam(3,$prenomUser);
        $stmt->execute();
      } catch (PDOException $e) {
        $this->destroy();
        throw new PDOException("Erreur d'accès à la table Rdv");
      }
    }

    public function annulerRdv($idRdv)
    {
      try{
        $stmt = $this->connexion->prepare("DELETE from rdv WHERE id=?");
        $stmt->bindParam(1,$idRdv);
        $stmt->execute();
      }
      catch(PDOException $e)
      {
        throw new PDOException("problème d'accès à la table");
      }
    }

    /* Méthode retournant les plages horaires du professionnel concerné */
    public function getPlageHoraire($id)
    {
      try{
        $stmt = $this->connexion->prepare('SELECT id, DATE_FORMAT(date,"%d/%m/%Y"), DATE_FORMAT(heureDebut, "%H:%i"), estPrise, DATE_FORMAT(heureFin, "%H:%i"), estRemplace, civiliteRemplacant, nomRemplacant FROM plage_horaire WHERE idPro=? ;');
        $stmt->bindParam(1,$id);
        $stmt->execute();
        // var_dump($stmt->fetchAll());
        return $stmt->fetchAll();
      }
      catch(PEDOException $e)
      {
        $this->destroy();
        throw new PDOException("Erreur d'accès à la table plage_horaire");
      }
    }

    /* Méthode permettant d'ajouter plusieurs plages horaires à la fois pour le professionnel */
    public function addPlageHoraire($mail, $dureeRdv, $debutServ, $finServ, $debutPause, $finPause, $dateDebut, $dateFin, $jour)
    {
      try
      {
        $idPro = $this->getIdUser($mail)[0];
        $dateD = new DateTime($dateDebut); //Date où commence la mise en place des plages horaires
        $dateF = new DateTime($dateFin); //Date -1 jour où termine la mise en place des plages horaires
        $dateF->add(new DateInterval("P1D")); //On rajoute un jour à ma date de fin pour qu'elle soit comprise dans l'intervalle
        $heureD = new DateTime($debutServ); //Heure où commence le service du prestataire
        $heureF = new DateTime($finServ); //Heure où termine le service du prestataire
        $heureDPause = new DateTime($debutPause); //Heure de début de pause déjeuner du prestataire
        $heureFPause = new DateTime($finPause); //Heure de fin de pause déjeuner du prestataire
        $duree = new DateTime($dureeRdv); //Durée d'un rendez-vous
        $plageInseree = FALSE; //Indique si la plage horaire a été insérée (si on était dans une heure de pause ou pas au niveau de la boucle)

        foreach(new DatePeriod($dateD, new DateInterval('P1D'), $dateF) as $date)
        {
          if($jour != NULL)
          {
            if($date->format('l') == $jour)
            {
              while($heureD->format('H:i:s') < $heureF->format('H:i:s'))
              {
                //Si l'heure actuelle de la boucle n'est pas une heure de pause, alors on insère dans la base de données la plage horaire correspondante
                if($heureD->format('H:i:s')<$heureDPause->format('H:i:s') || $heureD->format('H:i')>=$heureFPause->format('H:i'))
                {
                  $heureDebutRdvCourant=$heureD->format('H:i:s');
                  $heureD->add(new DateInterval("PT".$duree->format('i')."M"));
                  $heureFinRdvCourant=$heureD->format('H:i:s');
                  $dateRdvCourant = $date->format('Y-m-d');

                  $stmt = $this->connexion->prepare("INSERT INTO plage_horaire VALUES(id,?,?,?,0,?,0,NULL,NULL)"); //On insert les valeurs dans la table
                  $stmt->bindParam(1,$heureDebutRdvCourant);
                  $stmt->bindParam(2,$heureFinRdvCourant);
                  $stmt->bindParam(3,$dateRdvCourant);
                  $stmt->bindParam(4,$idPro);
                  $stmt->execute();

                  $plageInseree = TRUE;

                }

                //Si l'heure était une heure de pause (alors heureD n'a pas été augmentée), on l'augmente ici
                if($plageInseree == FALSE)
                {
                  $heureD->add(new DateInterval("PT".$duree->format('i')."M"));
                }
                $plageInseree = FALSE;
              }
            }
          }
          else
          {
            if($date->format('l') != "Sunday" && $date->format('l') != "Saturday")
            {
              while($heureD->format('H:i:s') < $heureF->format('H:i:s'))
              {
                //Si l'heure actuelle de la boucle n'est pas une heure de pause, alors on insère dans la base de données la plage horaire correspondante
                if($heureD->format('H:i:s')<$heureDPause->format('H:i:s') || $heureD->format('H:i')>=$heureFPause->format('H:i'))
                {
                  $heureDebutRdvCourant=$heureD->format('H:i:s');
                  $heureD->add(new DateInterval("PT".$duree->format('i')."M"));
                  $heureFinRdvCourant=$heureD->format('H:i:s');
                  $dateRdvCourant = $date->format('Y-m-d');

                  $stmt = $this->connexion->prepare("INSERT INTO plage_horaire VALUES(id,?,?,?,0,?,0,NULL,NULL)"); //On insert les valeurs dans la table
                  $stmt->bindParam(1,$heureDebutRdvCourant);
                  $stmt->bindParam(2,$heureFinRdvCourant);
                  $stmt->bindParam(3,$dateRdvCourant);
                  $stmt->bindParam(4,$idPro);
                  $stmt->execute();

                  $plageInseree = TRUE;

                }

                //Si l'heure était une heure de pause (alors heureD n'a pas été augmentée), on l'augmente ici
                if($plageInseree == FALSE)
                {
                  $heureD->add(new DateInterval("PT".$duree->format('i')."M"));
                }
                $plageInseree = FALSE;
              }
            }
          }

          $heureD = new DateTime($debutServ); //On remet l'heureD
        }
      }
      catch(PEDOException $e)
      {
        $this->destroy();
        throw new PDOException("Erreur d'accès à la table plage_horaire");
      }
    }

    public function delPlageHoraire($idPlageHoraire)
    {
      $stmt = $this->connexion->prepare("DELETE from plage_horaire WHERE id=?");
      $stmt->bindParam(1,$idPlageHoraire);
      $stmt->execute();
    }

    public function getPlageHoraireById($idPlageHoraire)
    {
      $stmt = $this->connexion->prepare("SELECT * from plage_horaire WHERE id=?");
      $stmt->bindParam(1,$idPlageHoraire);
      $stmt->execute();
      return $stmt->fetch();
    }

    public function updateRemplacant($id,$nomR,$civR){
      try{
        if(empty($nomR) && empty($civR))
        {
          $stmt = $this->connexion->prepare('update plage_horaire set estRemplace = 0, nomRemplacant = ? ,civiliteRemplacant = ? where id = ? ;');
        }
        else
        {
          $stmt = $this->connexion->prepare('update plage_horaire set estRemplace = 1, nomRemplacant = ? ,civiliteRemplacant = ? where id = ? ;');
        }
        $stmt->bindParam(1, $nomR);
        $stmt->bindParam(2, $civR);
        $stmt->bindParam(3, $id);
        $stmt->execute();
      }
      catch (PDOException $e) {
        $this->destroy();
        throw new PDOException("Erreur d'accès à la table plage_horaire");
      }
    }

    //Méthode qui met 1 à estPrise pour dire que le plage horaire est prise
    public function setPlageHorairePrise($idPlageHoraire)
    {
      $stmt=$this->connexion->prepare("UPDATE plage_horaire SET estprise=1 WHERE id=?");
      $stmt->bindParam(1,$idPlageHoraire);
      $stmt->execute();
    }

    //Inverse de la précédente
    public function setPlageHoraireLibre($idPlageHoraire)
    {
      $stmt=$this->connexion->prepare("UPDATE plage_horaire SET estprise=0 WHERE id=?");
      $stmt->bindParam(1,$idPlageHoraire);
      $stmt->execute();
    }

    /* Méthode retournant les plages horaires disponibles du professionnel concerné pour le patient*/
    public function getPlageHoraireProDate($id,$date)
    {
      try{
        $stmt = $this->connexion->prepare('SELECT id, DATE_FORMAT(heureDebut,"%H:%i"), DATE_FORMAT(heureFin,"%H:%i"), estRemplace, civiliteRemplacant, nomRemplacant FROM plage_horaire WHERE idPro=? and date=? and estPrise=0;');
        $stmt->bindParam(1,$id);
        $stmt->bindParam(2,$date);
        $stmt->execute();
        return $stmt->fetchAll();
      }
      catch(PDOException $e)
      {
        $this->destroy();
        throw new PDOException("Erreur d'accès à la table plage_horaire");
      }
    }

    /*public function getHoraires($date,$nompro,$prenompro){
    try {
      $requete="select horaire from rdv where jour=$date and idpraticien=$idprofessionnel;";
      $statement=$this->connexion->query( $requete);
      $tabResult=$statement->fetchAll();
      $data= array();
      foreach ($tabResult as $row){
        $data[] = $row['horaire'];
      }
      return $data;
    } catch (PDOException $e) {
      $exception = new TableAccesException("problème d'accès à une table");
      throw $exception;
    }
}*/

public function getId($nompro,$prenompro){
  try {
    $requete="select id from utilisateurs where nompro=$nompro and prenompro=$prenompro;";
    $statement=$this->connexion->query( $requete);
    $tabResult=$statement->fetchAll();
    $data= array();
    foreach ($tabResult as $row){
      $data[] = $row['id'];
    }
    return $data;
  } catch (PDOException $e) {
    $exception = new TableAccesException("problème d'accès à une table");
    throw $exception;
  }
}

/////////
///////// GESTION DOMAINE // SPECIALITE // SOUS SPECIALITE
    /* Méthode permettant de récupérer les domaines trié par nom */
    public function getDomaine(){
      try {
        $stmt = $this->connexion->prepare('select * from Domaine order by nom');
        $stmt->execute();
        return $stmt->fetchAll();
      } catch (PDOException $e) {
        $this->destroy();
        throw new PDOException("Erreur d'accès à la table Domaine");
      }
    }

    /** Méthode permettant de récuperer les specialité triée par nom */
    public function getSpecialite() {
      try {
        $stmt = $this->connexion->prepare('select * from Specialite order by nom');
        $stmt->execute();
        return $stmt->fetchAll();
      } catch (PDOException $e) {
        $this->destroy();
        throw new PDOException("Erreur d'accès à la table Specialite");
      }
    }

    /** Méthode permettant de récuperer les specialité d'un domaine particulier triées par nom */
    public function getSpecialiteDomaine($domaine) {
      try {
        $stmt = $this->connexion->prepare("SELECT id from domaine WHERE nom=?");
        $stmt->bindParam(1,$domaine);
        $stmt->execute();
        $idDomaine = $stmt->fetch();

        foreach($idDomaine as $row)
        {
          $idDomaine=$row['0'];
        }

        $stmt = $this->connexion->prepare('SELECT * from Specialite  WHERE domaine=? order by nom');
        $stmt->bindParam(1,$idDomaine);
        $stmt->execute();
        return $stmt->fetchAll();
      } catch (PDOException $e) {
        $this->destroy();
        throw new PDOException("Erreur d'accès à la table Specialite ou Domaine");
      }
    }

    /** Méthode permettant de récuperer les sous specialité triée par nom */
    public function getSousSpecialite() {
      try {
        $stmt = $this->connexion->prepare('select * from Sous_Specialite order by nom');
        $stmt->execute();
        return $stmt->fetchAll();
      } catch (PDOException $e) {
        $this->destroy();
        throw new PDOException("Erreur d'accès à la table Sous_Specialite");
      }
    }

    /* Méthode permettant de récupérer l'id d'un domaine */
    public function getIdDomaine($nomDomaine){
        try {
          $stmt = $this->connexion->prepare('select id from Domaine where nom = ?');
          $stmt->bindParam(1,$nomDomaine);
          $stmt->execute();
          return $stmt->fetch();
        } catch (PDOException $e) {
          $this->destroy();
          throw new PDOException("Erreur d'accès à la table Domaine");
        }

      }

      public function getIdSpecialite($nom) {
        try {
          $nom = ucfirst($nom);
          $stmt = $this->connexion->prepare('select id from Specialite where nom = ?');
          $stmt->bindParam(1, $nom);
          $stmt->execute();
          return $stmt->fetch();
        } catch (PDOException $e) {
          $this->destroy();
          throw new PDOException("Erreur d'accès à la table Specialite");
        }
      }

      public function getIdSousSpecialite($nom) {
        try {
          $stmt = $this->connexion->prepare('select * from Sous_Specialite where nom = ?');
          $stmt->bindParam(1, $nom);
          $stmt->execute();
          return $stmt->fetch();
        } catch (PDOException $e) {
          $this->destroy();
          throw new PDOException("Erreur d'accès à la table Sous_Specialite");
        }
      }

      public function insertSousSpecialite($nom, $sousDomaine) {
        try {
          $stmt = $this->connexion->prepare('insert into Sous_Specialite values (NULL,?,?);');
          $stmt->bindParam(1,$nom);
          $stmt->bindParam(2,$sousDomaine);
          $stmt->execute();
        } catch (PDOException $e) {
          $this->destroy();
          throw new PDOException("Erreur d'accès à la table Sous_Specialite");
        }
      }

        public function insertDomaine($nom)
        {
          try{
            $stmt=$this->connexion->prepare('INSERT INTO domaine VALUES (NULL, ?);');
            $stmt->bindParam(1,$nom);
            $stmt->execute();
          }
          catch(PDOException $e)
          {
            $this->destroy();
            throw new PDOException("Problème de connexion à la table domaine");
          }
        }

        public function insertSpecialite($domaine, $nom)
        {
          try{
            $stmt=$this->connexion->prepare('INSERT INTO specialite VALUES (NULL, ?, ?);');
            $stmt->bindParam(1,$nom);
            $stmt->bindParam(2,$domaine);
            $stmt->execute();
          }
          catch(PDOException $e)
          {
            $this->destroy();
            throw new PDOException("Problème de connexion à la table specialite");
          }
        }

/////////
///////// RECHERCHE
    /* Méthode permettant la recherche d'un spécialiste, d'une spécialité ou d'une sous-spécialité */
  public function rechercheSpe($domaine) {
      try {
        $specialisteRecherche=mb_strtoupper($_POST['specialiste']);
        //On met tout en minuscule pour que le casse ne soit pas prise en compte (le prénom est stocké en majuscule dass la bdd mais visiblement cela n'a pas d'importance alors qu'elle a de l'importance pour la sous spécialité etc (donc si on tapait "Chirurgie", cela ne correspondra pas à "chirurgie" (stocké comme ceci dans la bdd))
        $villeRecherche = mb_strtoupper($_POST['ville']);
      //Si la recherche n'est pas vide, on affiche les prestataires qui se rapprochent de ce que la personne recherche
        if(!empty($specialisteRecherche) || !empty($villeRecherche)) {
          $elements = explode(" ",htmlspecialchars($specialisteRecherche)); //On découpe la recherche en plusieurs éléments (max2)
          $elements0=htmlspecialchars($elements[0], ENT_QUOTES);
          $ville=htmlspecialchars($_POST['ville'], ENT_QUOTES);
          $villeRecherche=mb_strtoupper($ville);

          if (isset($elements[1])) {
            $elements1=htmlspecialchars($elements[1], ENT_QUOTES);
          }
          
          $chainenom="SELECT u.nom from utilisateurs u, domaine d WHERE d.id=? AND type = 2;";
          $chaineprenom="SELECT u.prenom from utilisateurs u, domaine d WHERE d.id=? AND type = 2;";
          $chainespecialite="SELECT s1.nom from sous_specialite s1, domaine d WHERE d.id=?;";
          $chaineville="SELECT u.ville from utilisateurs u, domaine d WHERE d.id=? AND type = 2;";

          $resultnom=NUll;
          $resultprenom=NUll;
          $resultspe=NUll;
          $resultville=NUll;
          $lv=500;

          $stmt1 = $this->connexion->prepare($chainenom);
          $stmt2 = $this->connexion->prepare($chaineprenom);
          $stmt3 = $this->connexion->prepare($chainespecialite);
          $stmt4 = $this->connexion->prepare($chaineville);

          $stmt1->bindParam(1,$domaine);
          $stmt2->bindParam(1,$domaine);
          $stmt3->bindParam(1,$domaine);
          $stmt4->bindParam(1,$domaine);

          $stmt1->execute();
          $stmt2->execute();    
          $stmt3->execute();
          $stmt4->execute();

          if (isset($elements[0])) {
            while ($ligne=$stmt1->fetch()) {
        if (levenshtein($elements0, $ligne['nom'])<$lv && levenshtein($elements0, $ligne['nom'])<3 && levenshtein($elements0, $ligne['nom']!=0)) {
                    $lv=levenshtein($elements0, $ligne['nom']);
                    $resultnom=$ligne['nom'];
              }
            }
            while ($ligne=$stmt2->fetch()) {
        if (levenshtein($elements0, $ligne['prenom'])<$lv && levenshtein($elements0, $ligne['prenom'])<3 && levenshtein($elements0, $ligne['prenom'])!=0) {
                    $lv=levenshtein($elements0, $ligne['prenom']);
                    $resultprenom=$ligne['prenom'];
              }
            }
            while ($ligne=$stmt3->fetch()) {
        if (levenshtein($elements0, $ligne['nom'])<$lv && levenshtein($elements0, $ligne['nom'])<3 && levenshtein($elements0, $ligne['nom'])!=0) {
                  $lv=levenshtein($elements0, $ligne['nom']);
                  $resultspe=$ligne['nom'];
              }
            }
            if (isset($elements[1])) {
              while ($ligne=$stmt1->fetch()) {
        if (levenshtein($elements1, $ligne['nom'])<$lv && levenshtein($elements1, $ligne['nom'])<3 && levenshtein($elements1, $ligne['nom'])!=0) {
                    $lv=levenshtein($elements1, $ligne['nom']);
                    $resultnom=$ligne['nom'];
                }
              }
              while ($ligne=$stmt2->fetch()) {
        if (levenshtein($elements1, $ligne['prenom'])<$lv && levenshtein($elements1, $ligne['prenom'])<3 && levenshtein($elements1, $ligne['prenom'])!=0) {
                    $lv=levenshtein($elements1, $ligne['prenom']);
                    $resultprenom=$ligne['prenom'];
                }
              }
              while ($ligne=$stmt3->fetch()) {
        if (levenshtein($elements1, $ligne['nom'])<$lv && levenshtein($elements1, $ligne['nom'])<3 && levenshtein($elements1, $ligne['nom'])!=0) {
                  $lv=levenshtein($elements1, $ligne['nom']);
                  $resultspe=$ligne['nom'];
                }
              }
            }
            if (isset($_POST['ville'])) {
              while ($ligne=$stmt4->fetch()) {
        if (levenshtein($villeRecherche, $ligne['ville'])<$lv && levenshtein($villeRecherche, $ligne['ville'])<3 && levenshtein($villeRecherche, $ligne['ville'])!=0) {
                  $lv=levenshtein($villeRecherche, $ligne['ville']);
                  $resultville=$ligne['ville'];
                }
              }
            }
          }

        if (!is_null($resultprenom) && is_null($resultnom) && is_null($resultspe)) {
          $chaine= "SELECT u.id, civilite, prenom, u.nom, mail, tel, adresse, ville, cp, location, s1.nom specialite, s2.nom sous_specialite from utilisateurs u, specialite s1, sous_specialite s2, domaine d WHERE d.id=? AND type = 2 AND u.specialite = s2.id AND s2.sousDomaine = s1.id AND s1.domaine = d.id AND prenom= '".$resultprenom."';";
        }
        else if (!is_null($resultprenom) && !is_null($resultnom) && is_null($resultspe)) {
          $chaine= "SELECT u.id, civilite, prenom, u.nom, mail, tel, adresse, ville, cp, location, s1.nom specialite, s2.nom sous_specialite from utilisateurs u, specialite s1, sous_specialite s2, domaine d WHERE d.id=? AND type = 2 AND u.specialite = s2.id AND s2.sousDomaine = s1.id AND s1.domaine = d.id AND u.nom= '".$resultnom."' AND prenom='".$resultprenom."';";
        }
        else if (!is_null($resultprenom) && is_null($resultnom) && !is_null($resultspe)) {
          $chaine= "SELECT u.id, civilite, prenom, u.nom, mail, tel, adresse, ville, cp, location, s1.nom specialite, s2.nom sous_specialite from utilisateurs u, specialite s1, sous_specialite s2, domaine d WHERE d.id=? AND type = 2 AND u.specialite = s2.id AND s2.sousDomaine = s1.id AND s1.domaine = d.id AND prenom= '".$resultprenom."' AND s2.nom='".$resultspe."';";
        }
        else if (is_null($resultprenom) && !is_null($resultnom) && !is_null($resultspe)) {
          $chaine= "SELECT u.id, civilite, prenom, u.nom, mail, tel, adresse, ville, cp, location, s1.nom specialite, s2.nom sous_specialite from utilisateurs u, specialite s1, sous_specialite s2, domaine d WHERE d.id=? AND type = 2 AND u.specialite = s2.id AND s2.sousDomaine = s1.id AND s1.domaine = d.id AND u.nom= '".$resultnom."' AND s2.nom='".$resultspe."';";
        }
        else if (is_null($resultprenom) && !is_null($resultnom) && is_null($resultspe)) {
          $chaine= "SELECT u.id, civilite, prenom, u.nom, mail, tel, adresse, ville, cp, location, s1.nom specialite, s2.nom sous_specialite from utilisateurs u, specialite s1, sous_specialite s2, domaine d WHERE d.id=? AND type = 2 AND u.specialite = s2.id AND s2.sousDomaine = s1.id AND s1.domaine = d.id AND u.nom= '".$resultnom."';";
        }
        else if (is_null($resultprenom) && is_null($resultnom) && !is_null($resultspe)) {
          $chaine= "SELECT u.id, civilite, prenom, u.nom, mail, tel, adresse, ville, cp, location, s1.nom specialite, s2.nom sous_specialite from utilisateurs u, specialite s1, sous_specialite s2, domaine d WHERE d.id=? AND type = 2 AND u.specialite = s2.id AND s2.sousDomaine = s1.id AND s1.domaine = d.id AND s2.nom= '".$resultspe."';";
        }
        else if (!is_null($resultville)) {
          $chaine= "SELECT u.id, civilite, prenom, u.nom, mail, tel, adresse, ville, cp, location, s1.nom specialite, s2.nom sous_specialite from utilisateurs u, specialite s1, sous_specialite s2, domaine d WHERE d.id=? AND type = 2 AND u.specialite = s2.id AND s2.sousDomaine = s1.id AND s1.domaine = d.id AND ville= '".$resultville."';";
        }
        else {
          if(sizeof($elements)==2){
            $chaine = "SELECT u.id, civilite, prenom, u.nom, mail, tel, adresse, ville, cp, location, s1.nom specialite, s2.nom sous_specialite from utilisateurs u, specialite s1, sous_specialite s2, domaine d WHERE d.id=? AND type = 2 AND u.specialite = s2.id AND s2.sousDomaine = s1.id AND s1.domaine = d.id AND ((s2.nom LIKE '".$elements0."%' OR prenom LIKE '".$elements0."%' OR u.nom LIKE '".$elements0."%') OR (s2.nom LIKE '%".$elements1."%' OR prenom LIKE '".$elements1."%' OR u.nom LIKE '".$elements1."%')) AND ville LIKE '".$ville."%';";
          }
          else{
            $chaine= "SELECT u.id, civilite, prenom, u.nom, mail, tel, adresse, ville, cp, location, s1.nom specialite, s2.nom sous_specialite from utilisateurs u, specialite s1, sous_specialite s2, domaine d WHERE d.id=? AND type = 2 AND u.specialite = s2.id AND s2.sousDomaine = s1.id AND s1.domaine = d.id AND (s2.nom LIKE '%".$elements0."%' OR prenom LIKE '".$elements0."%' OR u.nom LIKE '".$elements0."%') AND ville LIKE '".$ville."%';";
          }
      }
          $stmt = $this->connexion->prepare($chaine);
          $stmt->bindParam(1,$domaine); //Le domaine dépend de la page de recherche
          $stmt->execute();
        }
        //Si la recherche est vide, on affiche tous les prestataires du domaine de la recherche
        else if(empty($villeRecherche)){
          $chaine = "SELECT u.id, civilite, prenom, u.nom, mail, tel, adresse, ville, cp, location, s1.nom specialite, s2.nom sous_specialite from Utilisateurs u, Specialite s1, Sous_Specialite s2, Domaine d WHERE type=2 AND d.id=? AND u.specialite = s2.id AND s2.sousDomaine = s1.id AND s1.domaine = d.id";
          $stmt=$this->connexion->prepare($chaine);
          $stmt->bindParam(1,$domaine); //Le domaine dépend de la page de recherche
          $stmt->execute();
        }
        return $stmt->fetchAll();
        
      } catch (PDOException $e) {
        $this->destroy();
        throw new PDOException("Erreur d'accès à la table");
      }
    }

  /* Méthode permettant d'obtenir la ville recherchée */
  public function rechercheVille($domaine) {
    try {
      $villeRecherche = mb_strtolower($_POST['ville']);

      return $villeRecherche;
    } catch (PDOException $e) {
      $this->destroy();
      throw new PDOException("Erreur d'accès à la table");
    }
  }
}

?>
