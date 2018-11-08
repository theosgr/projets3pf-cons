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

////////////////// CHECK
    /* Méthode permettant de voir si un utilisateur est deja inscrit */
    public function estInscrit($mail){
      try {
        $stmt = $this->connexion->prepare("SELECT * FROM Utilisateurs WHERE mail = ?;");
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
          $stmt = $this->connexion->prepare("SELECT * FROM Utilisateurs WHERE mail = ?;");
          $stmt->bindParam(1,$mail);
          $stmt->execute();
          $mdpUtilisateur = $stmt->fetch();
          $mdpUser = $mdpUtilisateur["mdp"];
          if (crypt($mdp, $mdpUser) == $mdpUser) { // remplacer crypt par password_hash
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
          $stmt = $this->connexion->prepare('SELECT * FROM Utilisateurs WHERE mail = ?;');
          $stmt->bindParam(1,$_POST['login']);
          $stmt->execute();
          $tabResult = $stmt->fetch();
          if ($tabResult != NULL) {
            return (ucfirst(strtolower($tabResult['prenom'])) . " " . $tabResult['nom']);
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
            $_SESSION['message'] = "Veuillez entrer une date valide";
            return false;
          }
          if ($year >= date(Y) && $month >= date(m) && $day >= date(d)) {
            $_SESSION['message'] = "Veuillez entrer une date valide";
            return false;
          }
        } else
        // Champ sous la forme jj/mm/aaaa
        if (preg_match("/^[0-31][/|.|-][01-12][/|.|-][0-9]{4}$/",$_POST['ddn'])) {
          list($day, $month, $year) = split('[/.-]', $_POST['ddn']);
          if ($year < date(Y)-100) {
            $_SESSION['message'] = "Veuillez entrer une date valide";
            return false;
          }
          if ($year >= date(Y) && $month >= date(m) && $day >= date(d)) {
            $_SESSION['message'] = "Veuillez entrer une date valide";
            return false;
          }
        }
        $_SESSION['message'] = "Veuillez compléter votre date de naissance";
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
            $_SESSION['message'] = "Veuillez entrer une date valide";
            return false;
          }
          if ($year >= date(Y) && $month >= date(m) && $day >= date(d)) {
            $_SESSION['message'] = "Veuillez entrer une date valide";
            return false;
          }
        } else
        // Champ sous la forme jj/mm/aaaa
        if (preg_match("/^[0-31][/|.|-][01-12][/|.|-][0-9]{4}$/",$_POST['ddn'])) {
          list($day, $month, $year) = split('[/.-]', $_POST['ddn']);
          if ($year < date(Y)-100) {
            $_SESSION['message'] = "Veuillez entrer une date valide";
            return false;
          }
          if ($year >= date(Y) && $month >= date(m) && $day >= date(d)) {
            $_SESSION['message'] = "Veuillez entrer une date valide";
            return false;
          }
        }
        $_SESSION['message'] = "Veuillez compléter votre date de naissance";
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
        $_SESSION['message'] = "Code postal incorrect";
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
        $_SESSION['message'] = "Champ civilité incorrect";
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
      $_POST['nom'] = htmlspecialchars($_POST['nom']);

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
            $_SESSION['message'] = "Veuillez entrer une date valide";
            return false;
          }
          if ($year >= date(Y) && $month >= date(m) && $day >= date(d)) {
            $_SESSION['message'] = "Veuillez entrer une date valide";
            return false;
          }
        } else
        // Champ sous la forme jj/mm/aaaa
        if (preg_match("/^[0-31][/|.|-][01-12][/|.|-][0-9]{4}$/",$_POST['ddn'])) {
          list($day, $month, $year) = split('[/.-]', $_POST['ddnP']);
          if ($year < date(Y)-100) {
            $_SESSION['message'] = "Veuillez entrer une date valide";
            return false;
          }
          if ($year >= date(Y) && $month >= date(m) && $day >= date(d)) {
            $_SESSION['message'] = "Veuillez entrer une date valide";
            return false;
          }
        }
        $_SESSION['message'] = "Veuillez compléter votre date de naissance";
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
        $_SESSION['message'] = "Code postal incorrect";
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

////////////////// AJOUT / SUPPRESSION
    /** Méthode permettant d'ajouter un utilisateur */
    public function addUser($categorie) {
      try {
        if (!$this->estInscrit($_POST['mail'])) {
          $stmt = $this->connexion->prepare('INSERT INTO Utilisateurs VALUES(NULL,?,?,?,?,?,?,?,?,?,?,?,?,?);');
          $stmt->bindParam(1,$_POST['civilite']);
          $tmpPrenom = strtoupper($_POST['prenom']);
          $stmt->bindParam(2,$tmpPrenom);
          $tmpNom = strtoupper($_POST['nom']);
          $stmt->bindParam(3,$tmpNom);
          $stmt->bindParam(4,$_POST['mail']);
          $tmpMDP = password_hash($_POST['mdp']); // password_hash est plus sécurisée que crypt
          $stmt->bindParam(5,$tmpMDP); 
          $stmt->bindParam(6,$_POST['ddn']);
          $stmt->bindParam(7,$_POST['tel']);
          $tmpAdresse = strtoupper($_POST['adresse']);
          $stmt->bindParam(8,$tmpAdresse);
          $stmt->bindParam(9,$_POST['cp']);
          $tmpVille = strtoupper($_POST['ville']);
          $stmt->bindParam(10,$tmpVille);
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
                  $idSpe = $this->getIdSpecialite(strtolower($_POST['speMedecine']));
                } else if ($_POST['domaine'] == "JURIDIQUE") {
                  $idSpe = $this->getIdSpecialite(strtolower($_POST['speJuridique']));
                }
                // Vérification qu'elle n'existe pas déjà
                if ($this->getIdSousSpecialite(strtolower($_POST['newSpe'])) == NULL) {
                  $this->insertSousSpecialite(strtolower($_POST['newSpe']), $idSpe['id']);
                }
              }
              $spe = strtolower($_POST['newSpe']);
            // Sous spécialite existante
            } else {
              $spe = strtolower($_POST['sous_specialite']);
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
            $stmt = $this->connexion->prepare('INSERT into Proche values(NULL,?,?,?,?,?,?,?,?,?,?);');
          $stmt->bindParam(1, $user['id']);
          $stmt->bindParam(2,$_POST['civiliteP']);
          $tmpPrenomP = strtoupper($_POST['prenomP']);
          $stmt->bindParam(3,$prenomP);
          $tmpNomP = strtoupper($_POST['nomP']);
          $stmt->bindParam(4,$tmpNomP);
          $stmt->bindParam(5,$_POST['ddnP']);
          $stmt->bindParam(6,$_POST['telP']);
          $tmpAdresseP = strtoupper($_POST['adresseP']);
          $stmt->bindParam(7,$tmpAdresseP);
          $stmt->bindParam(8,$_POST['cpP']);
          $tmpVilleP = strtoupper($_POST['villeP']);
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
          $stmt = $this->connexion->prepare('DELETE FROM Utilisateurs WHERE mail = ?;');
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
          $stmt = $this->connexion->prepare('DELETE FROM Proche WHERE id = ?');
          $stmt->bindParam(1,$id);
          $stmt->execute();
        }
      } catch (PDOException $e) {
        $this->destroy();
        throw new PDOException("Erreur d'accès à la table Proche");
      }
    }

////////////////// GESTION COMPTE
    /* Méthode permettant de récuper l'id d'un utilisateur */
    public function getIdUser($mail) {
      try {
        $stmt = $this->connexion->prepare('SELECT * FROM Utilisateurs WHERE mail = ?');
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
          $stmt = $this->connexion->prepare('SELECT * FROM Utilisateurs WHERE mail = ?;');
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
        $stmt = $this->connexion->prepare('SELECT * FROM Proche WHERE idliaisut = ?');
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
          $stmt = $this->connexion->prepare('SELECT * FROM Proche WHERE id = ?;');
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
          $stmt = $this->connexion->prepare('UPDATE Utilisateurs SET civilite = ? WHERE mail = ?');
          $stmt->bindParam(1,$_POST['civilite']);
          $stmt->bindParam(2,$_SESSION['id']);
          $stmt->execute();

          // modif nom
          $stmt = $this->connexion->prepare('UPDATE Utilisateurs SET nom = ? WHERE mail = ?');
          $tmpNom = strtoupper($_POST['nom']);
          $stmt->bindParam(1,$tmpNom);
          $stmt->bindParam(2,$_SESSION['id']);
          $stmt->execute();

          // nom prenom
          $stmt = $this->connexion->prepare('UPDATE Utilisateurs SET prenom = ? WHERE mail = ?');
          $tmpPrenom = strtoupper($_POST['prenom']);
          $stmt->bindParam(1,$tmpPrenom);
          $stmt->bindParam(2,$_SESSION['id']);
          $stmt->execute();

          // modif ddn
          $stmt = $this->connexion->prepare('UPDATE Utilisateurs SET ddn = ? WHERE mail = ?');
          $tmpDDN = strtoupper($_POST['ddn']);
          $stmt->bindParam(1,$tmpDDN);
          $stmt->bindParam(2,$_SESSION['id']);
          $stmt->execute();

          // modif tel
          $stmt = $this->connexion->prepare('UPDATE Utilisateurs SET tel = ? WHERE mail = ?');
          $tmpTelephone = strtoupper($_POST['tel']);
          $stmt->bindParam(1,$tmpTelephone);
          $stmt->bindParam(2,$_SESSION['id']);
          $stmt->execute();

          // modif adresse
          $stmt = $this->connexion->prepare('UPDATE Utilisateurs SET adresse = ? WHERE mail = ?');
          $tmpAdresse = strtoupper($_POST['adresse']);
          $stmt->bindParam(1,$tmpAdresse);
          $stmt->bindParam(2,$_SESSION['id']);
          $stmt->execute();

          // modif cp
          $stmt = $this->connexion->prepare('UPDATE Utilisateurs SET cp = ? WHERE mail = ?');
          $stmt->bindParam(1,$_POST['cp']);
          $stmt->bindParam(2,$_SESSION['id']);
          $stmt->execute();

          // modif ville
          $stmt = $this->connexion->prepare('UPDATE Utilisateurs SET ville = ? WHERE mail = ?');
          $tmpVille = strtoupper($_POST['ville']);
          $stmt->bindParam(1,$tmpVille);
          $stmt->bindParam(2,$_SESSION['id']);
          $stmt->execute();

          // modif location
          $stmt = $this->connexion->prepare('UPDATE Utilisateurs SET location = ? WHERE mail = ?');
          $stmt->bindParam(1,$_POST['location']);
          $stmt->bindParam(2,$_SESSION['id']);
          $stmt->execute();

          // modif mdp si modification
          if ($mdp == 1) {
            $stmt = $this->connexion->prepare('UPDATE Utilisateurs SET mdp = ? WHERE mail = ?');
            $tmpMDP = password_hash($_POST['mdp']);
            $stmt->bindParam(1,$tmpMDP);
            $stmt->bindParam(2,$_SESSION['id']);
            $stmt->execute();
          }

          // modif mail
          $stmt = $this->connexion->prepare('UPDATE Utilisateurs SET mail = ? WHERE mail = ?');
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
        $stmt = $this->connexion->prepare('SELECT u.nom, u.prenom, horaire, jour, nomPa, prenomPa FROM Rdv as r, Utilisateurs as u WHERE idpracticien = u.id and idpatient = ?');
        $stmt->bindParam(1,$idUser);
        $stmt->execute();
        return $stmt->fetchAll();
      } catch (PDOException $e) {
        $this->destroy();
        throw new PDOException("Erreur d'accès à la table Rdv");
      }
    }

    /* Méthode permettant de supprimer un rendez-vous */
    public function delRdv($idUser, $nomUser, $prenomUser) {
      try {
        $stmt = $this->connexion->prepare('UPDATE Rdv SET idpatient = NULL, nomPa = NULL, prenomPa = NULL WHERE idpatient= ? and nomPa=? and prenomPa=?');
        $stmt->bindParam(1,$idUser);
        $stmt->bindParam(2,$nomUser);
        $stmt->bindParam(3,$prenomUser);
        $stmt->execute();
      } catch (PDOException $e) {
        $this->destroy();
        throw new PDOException("Erreur d'accès à la table Rdv");
      }
    }

////////////////// GESTION DOMAINE // SPECIALITE // SOUS SPECIALITE
    /* Méthode permettant de récupérer les domaines trié par nom */
    public function getDomaine(){
      try {
        $stmt = $this->connexion->prepare('SELECT * FROM Domaine order by nom');
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
        $stmt = $this->connexion->prepare('SELECT * FROM Specialite order by nom');
        $stmt->execute();
        return $stmt->fetchAll();
      } catch (PDOException $e) {
        $this->destroy();
        throw new PDOException("Erreur d'accès à la table Specialite");
      }
    }

    /** Méthode permettant de récuperer les sous specialité triée par nom */
    public function getSousSpecialite() {
      try {
        $stmt = $this->connexion->prepare('SELECT * FROM Sous_Specialite order by nom');
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
          $stmt = $this->connexion->prepare('SELECT id FROM Domaine WHERE nom = ?');
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
          $stmt = $this->connexion->prepare('SELECT * FROM Specialite WHERE nom = ?');
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
          $stmt = $this->connexion->prepare('SELECT * FROM Sous_Specialite WHERE nom = ?');
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
          $stmt = $this->connexion->prepare('INSERT into Sous_Specialite values (NULL,?,?);');
          $stmt->bindParam(1,$nom);
          $stmt->bindParam(2,$sousDomaine);
          $stmt->execute();
        } catch (PDOException $e) {
          $this->destroy();
          throw new PDOException("Erreur d'accès à la table Sous_Specialite");
        }
    }

////////////////// RECHERCHE
    /* Méthode permettant la recherche d'un professionel, d'un domaine ou d'une spécialité */
    public function rechercheSpe() {
      try {
        $elements = explode(" ",htmlspecialchars($_POST['specialiste']));
        $chaine = "SELECT civilite, prenom, u.nom, mail, tel, adresse, ville, cp, location, s1.nom specialite, s2.nom sous_specialite FROM Utilisateurs u, Specialite s1, Sous_Specialite s2, Domaine d WHERE type = 2 AND u.specialite = s2.id AND s2.sousDomaine = s1.id AND s1.domaine = d.id AND (specialite LIKE :element1 OR s2.nom LIKE :element1 OR u.nom LIKE :element1 OR prenom LIKE :element1 OR specialite LIKE :element2 OR s2.nom LIKE :element2 OR u.nom LIKE :element2 OR prenom LIKE :element2) AND ville LIKE :ville";
        $stmt = $this->connexion->prepare($chaine);
        $stmt->bindParam(":element1", $elements[0]);
        $stmt->bindParam(":element2", $elements[1]);
        $tmpVilleAccent = htmlspecialchars($_POST['ville']);
        $stmt->bindParam(":ville", $tmpVilleAccent);
        $stmt->execute();
        return $stmt->fetchAll();
      } catch (PDOException $e) {
        $this->destroy();
        throw new PDOException("Erreur d'accès à la table");
      }
    }
  }
?>
