<?php
  require_once PATH_VUE."/vueAuthentification.php";
  require_once PATH_MODELE."/dao/dao.php";
  require_once PATH_VUE."/vuePlageHoraire.php";
  require_once "ctrlDomaine.php";
  require_once PATH_VUE."/vueDomaine.php";

/* CONTROLEUR AUTHENTIFICATION : gestion de l'inscription, connexion et deconnexion */
  class ControleurAuthentification {
    private $vue;
    private $modele;
    private $vuePlageHoraire;
    private $ctrlDomaine;
    private $vueDomaine;

    /* Constructeur de la classe. */
    public function __construct(){
      $this->vue = new vueAuthentification();
      $this->modele = new dao();
      $this->vuePlageHoraire = new vuePlageHoraire();
      $this->ctrlDomaine = new ControleurDomaine();
      $this->vueDomaine = new vueDomaine();
    }

    /* Affichage de la vue d'accueil. */
    public function accueil() {
      $this->vue->genereVueAccueil();
    }

    /* Affichage de page d'inscription. */
    public function inscription() {
      $this->vue->genereVueInscription($this->modele->getSousSpecialite());
    }

    /* Affichage de la vue de connexion. */
    public function connexion() {
      $this->vue->genereVueConnexion();
    }

    /* Inscription d'un utilisateur. */
    public function inscriptionUser($categorie) {
      // Verification des infos envoyées
      if ($this->modele->checkFormInscription()) {
        $_SESSION['inscription'] = $this->modele->addUser($categorie);
        if ($_SESSION['inscription'] == "ko") {
          $_SESSION['validite'] = "ko";
          $_SESSION['message'] = "Mail existant";
          if ($categorie == 1)
          {
            $_GET['inscription'] = "user";
          }
          else
          {
            $_GET['inscription'] = "pro";
          }
          // $this->inscription();
        } else {
          $_SESSION['validite'] = "ok";
          $_SESSION['message'] = "Vous êtes bien inscrit";
          return true;
        }
      }
      // Verification incorrecte
      $_SESSION['validite'] = "ko";
      if ($categorie == 1) {
        $_GET['inscription'] = "user";
      } else {
        $_GET['inscription'] = "pro";
      }
      $this-> inscription();
    }

    /* Connexion d'un utilisateur. */
    public function connexionUser() {
      $_SESSION['user'] = $this->modele->connexion();
      if ($_SESSION['user'] != "ko")
      { // connexion réussi
        $_SESSION['id'] = $_POST['login'];
        $_SESSION['validite'] = "ok";
        $donneesUser = $this->modele->getInfosUser();
        $_SESSION['categorie'] = $donneesUser[0]->getType();
        $_SESSION['message'] = "Bienvenue " . $_SESSION['user'];
        $this->vue->genereVueAccueil();
      } else { // echec connexion
        $_SESSION['validite'] = "ko";
        $_SESSION['message'] = "Combinaison utilisateur/mot de passe incorrect";
        $this->connexion();
      }
    }

    /*Cas robot*/
    public function robot(){
      $_SESSION['message'] = "Vous avez été détecté comme un robot";
      $_SESSION['validite'] = "ko";
    }

    /*Vérification que la personne voulant accéder à la gestion du site est admin*/
    public function gestionAdmin() {
      if(isset($_SESSION['categorie']))
      {
        if($_SESSION['categorie']==3)
        {

          $this->vue->genereVueAdmin($this->modele->getDomaine(), $this->modele->getSpecialite(), $this->modele->getSousSpecialite());
        }
        else
        {
          $_SESSION['validite'] = "ko";
          $_SESSION['message']="Vous n'avez pas l'autorisation";
        }
      }
      else
      {
        $_SESSION['validite'] = "ko";
        $_SESSION['message']="Vous n'avez pas l'autorisation";
      }
    }

    public function connexionRdv()
    {
      $_SESSION['user'] = $this->modele->connexion();
      if ($_SESSION['user'] != "ko")
      { // connexion réussi
        $_SESSION['id'] = $_POST['login'];
        $_SESSION['validite'] = "ok";
        $donneesUser = $this->modele->getInfosUser();
        $_SESSION['categorie'] = $donneesUser[0]->getType();
        $_SESSION['message'] = "Bienvenue " . $_SESSION['user'];

        $idUser = $this->modele->getIdUser($_SESSION['id'])[0];
        $listeProche = $this->modele->getProches($idUser);
        $this->vuePlageHoraire->genereVueSelectionProche($listeProche);

      } else { // echec connexion
        $_SESSION['validite'] = "ko";
        $_SESSION['message'] = "Combinaison utilisateur/mot de passe incorrect";
        $this->vue->genereVueConnexionRdv();
      }
    }

    public function connexionQuestion($domaine, $mailPro)
    {
      $_SESSION['user'] = $this->modele->connexion();
      if ($_SESSION['user'] != "ko")
      { // connexion réussie
        $_SESSION['id'] = $_POST['login'];
        $_SESSION['validite'] = "ok";
        $donneesUser = $this->modele->getInfosUser();
        $_SESSION['categorie'] = $donneesUser[0]->getType();
        $_SESSION['message'] = "Bienvenue " . $_SESSION['user'];

        $this->ctrlDomaine->sendQuestionConnecte($domaine, $mailPro);
        // $this->vueDomaine->genereVueConnexionQuestion($domaine);


      } else { // echec connexion
        $_SESSION['validite'] = "ko";
        $_SESSION['message'] = "Combinaison utilisateur/mot de passe incorrect";
        $this->vue->genereVueConnexionRdv();
      }
    }

    /* Deconnexion d'un utilisateur. */
    public function deconnexionUser() {
      unset($_SESSION['user']);
      session_destroy();
      $this->vue->genereVueAccueil();
    }
  }
?>
