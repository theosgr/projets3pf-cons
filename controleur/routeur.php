<?php

// CHARGEMENT DES CONTROLEURS
  require_once 'ctrl/ctrlAuthentification.php';
  require_once 'ctrl/ctrlMail.php';
  require_once 'ctrl/ctrlCompte.php';
  require_once 'ctrl/ctrlDomaine.php';
  require_once 'ctrl/ctrlAdmin.php';
  require_once 'ctrl/ctrlPlageHoraire.php';
  
/* ROUTEUR : redirection des requêtes vers les contrôleurs */
  class Routeur {
    private $ctrlAuthentification;
    private $ctrlMail;
    private $ctrlCompte;
    private $ctrlDomaine;
    private $ctrlAdmin;
    private $ctrlPlageHoraire;

/** CONSTRUCTEUR DU ROUTEUR **/
    public function __construct() {
      $this->ctrlAuthentification = new ControleurAuthentification();
      $this->ctrlMail = new ControleurMail();
      $this->ctrlCompte = new ControleurCompte();
      $this->ctrlDomaine = new ControleurDomaine();
      $this->ctrlAdmin = new ControleurAdmin();
      $this->ctrlPlageHoraire = new ControleurPlageHoraire();
    }

    public function routerRequete() {
// GESTION PAGES DOMAINES
      if (isset($_GET['domaine'])) {
    // DOMAINE MEDICAL
        if ($_GET['domaine'] == "medical") {
          $this->ctrlDomaine->domaine(1);
          return;
        }
    // DOMAINE JURIDIQUE
        if ($_GET['domaine'] == "juridique") {
          $this->ctrlDomaine->domaine(2);
          return;
        }
      }
// RECHERCHE
      if (isset($_GET['search'])) {
    // DOMAINE MEDICAL
        if ($_GET['search'] == 1) {
          $this->ctrlDomaine->rechercheSpe(1);
          return;
        }
    // DOMAINE JURIDIQUE
        if ($_GET['search'] == 2) {
          $this->ctrlDomaine->rechercheSpe(2);
          return;
        }
      }

// GESTION INSCRIPTION
      if (isset($_GET['inscription'])) {
    // CHARGER PAGE INSCRIPTION
        if ($_GET['inscription'] == "user" || $_GET['inscription'] == "pro") {
          $this->ctrlAuthentification->inscription();
          return;
        }
    // INSCRIPTION UTILISATEUR
        if ($_GET['inscription'] == "1" || $_GET['inscription'] == "2") {
          if ($this->ctrlAuthentification->inscriptionUser($_GET['inscription'])) {
            // $this->ctrlMail->envoiMailInscription();
            $this->ctrlAuthentification->accueil();
          }
          return;
        }
      }

// GESTION CONNEXION // DECONNEXION
      if (isset($_GET['connexion'])) {
    // CONNEXION UTILISATEUR
        if ($_GET['connexion'] == 1) {
          $this->ctrlAuthentification->connexionUser();
          return;
        }
    // CHARGE PAGE CONNEXION
        if ($_GET['connexion'] == "") {
          $this->ctrlAuthentification->connexion();
          return;
        }
      }
    // DECONNEXION UTILISATEUR
      if (isset($_GET['deconnexion'])) {
        $this->ctrlAuthentification->deconnexionUser();
        return;
      }

// GESTION COMPTE UTILISATEUR
    // MODIFICATION INFOS PERSONNELLES
      if (isset($_GET['monCompte'])) {
        if ($_GET['monCompte'] == 1) {
          $this->ctrlCompte->modifCompte();
          return;
        }
    // GESTION DES PROCHES
        // AJOUT D'UN PROCHE
        if ($_GET['monCompte'] == 2) {
          $this->ctrlCompte->ajoutProche();
          return;
        }
        // SUPPRESSION D'UN PROCHE
        if ($_GET['monCompte'] == 3) {
          $this->ctrlCompte->suppressionProche();
          return;
        }
    // SUPPRESSION DE SON COMPTE
        if ($_GET['monCompte'] == 4) {
          $this->ctrlCompte->suppressionCompte();
          $this->ctrlAuthentification->accueil();
          return;
        }

        $this->ctrlCompte->pageMonCompte();
        return;
      }

    // MOT DE PASSE OUBLIE
      if (isset($_GET['reset'])) {
        if ($_GET['reset'] == 1) {
          if ($this->ctrlCompte->resetMdp()) {
            $this->ctrlMail->envoiMailReset($_SESSION['mdpProv']);
          }
          $this->ctrlAuthentification->accueil();
          return;
        }
        $this->ctrlCompte->afficherReset();
        return;
      }
      
//ACCES A L'INTERFACE DE GESTION DU SITE
      if(isset($_GET['admin'])){
        $this->ctrlAuthentification->gestionAdmin();
        return;
      }

      if(isset($_POST['domaineCree']))
      {
        $this->ctrlAdmin->creationDomaine($_POST['domaineCree']);
        return;
      }

      if(isset($_POST['specialiteCree']) && isset($_POST['domaine']))
      {
        $this->ctrlAdmin->creationSpecialite($_POST['domaine'], $_POST['specialiteCree']);
        return;
      }

      if(isset($_POST['sousSpecialiteCree']) && isset($_POST['specialite']))
      {
        $this->ctrlAdmin->creationSousSpecialite($_POST['specialite'], $_POST['sousSpecialiteCree']);
        return;
      }

// PLAGE HORAIRE
      if(isset($_GET['idPro']))
      {
        $this->ctrlPlageHoraire->plageHoraire($_GET['idPro']);
        return;
      }

      if(isset($_GET['idPro3']) && isset($_GET['ajouterPlageHoraire']))
      {
        $this->ctrlPlageHoraire->affichageModifPlageHoraire($_GET['idPro3']);
        return;
      }

      if(isset($_POST['dureeRdv']) && isset($_POST['debutServ']) && isset($_POST['finServ']) && isset($_POST['debutPause']) && isset($_POST['finPause']) && isset($_POST['debutPlanning']) && isset($_POST['finPlanning']))
      {
        $this->ctrlPlageHoraire->ajouterPlageHoraire($_SESSION['id'], $_POST['dureeRdv'], $_POST['debutServ'], $_POST['finServ'], $_POST['debutPause'], $_POST['finPause'], $_POST['debutPlanning'], $_POST['finPlanning']);
        return;
      }

      if (isset($_GET['horaire'])) { // vérifiction de l'horaire
      // DISPONIBILITES        
          $this->ctrlPlageHoraire->plageHoraire();
          // 12 = le nombre de disponibilités dans une journée (à modifier plus tard)
          //$this->ctrlPlageHoraire->plageHoraire(12);
          return;
        }

// DEFAULT
      $this->ctrlAuthentification->accueil();
      return;
    }
  }
?>
