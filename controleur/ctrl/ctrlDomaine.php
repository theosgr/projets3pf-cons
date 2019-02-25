<?php
  require_once PATH_VUE."/vueDomaine.php";
  require_once PATH_MODELE."/dao/dao.php";

/* CONTROLEUR DOMAINE : gestion de la recherche */
  class ControleurDomaine {
    private $vue;
    private $modele;

    /* Constructeur de la classe. */
    public function __construct(){
      $this->vue = new vueDomaine();
      $this->modele = new dao();
    }

    /* Affichage de la page pour rechercher */
    public function domaine($domaine) {
      $this->vue->genereVueDomaine($domaine);
    }

    /* Affichage des specialistes de la recherche */
    public function rechercheSpe($domaine) {
      $listeSpecialistes = $this->modele->rechercheSpe($domaine);
      $ville = $this->modele->rechercheVille($domaine);
      $this->vue->genereVueRecherche($domaine, $listeSpecialistes, $ville);
    }

    /* Envoi d'une question quand l'utilisateur est connecté*/
    public function sendQuestionConnecte($domaine) {
      // envoi des messages
      if(isset($_SESSION['questionSvg']))
      {
        $question = $_SESSION['questionSvg'];
        unset($_SESSION['questionSvg']);
      }
      else {
        $question = $_POST['question'];
      }

      //ICI IL FAUDRA ENVOYER LE MAIL////////////////////

      $_SESSION['validite'] = "ok";
      $_SESSION['message'] = "La question a bien été envoyée avec le mail ".$_SESSION['id'];
      $this->vue->genereVueDomaine($domaine);
    }

    /* Envoi d'une question est déconnecté*/
    public function sendQuestionDeconnecte($domaine) {
      $_SESSION['validite'] = "ko";
      $_SESSION['message'] = "Veuillez vous connecter pour continuer";
      $_SESSION['questionSvg'] = $_POST['question'];
      $this->vue->genereVueConnexionQuestion($domaine);
    }

  }
?>
