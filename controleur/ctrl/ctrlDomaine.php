<?php
  require_once PATH_VUE."/vueDomaine.php";
  require_once PATH_MODELE."/dao/dao.php";

/* CONTROLEUR DOMAINE : gestion de la recherche */
  class ControleurDomaine {
    private $vue;
    private $modele;
    private $ctrlMail;

    /* Constructeur de la classe. */
    public function __construct(){
      $this->vue = new vueDomaine();
      $this->modele = new dao();
      $this->ctrlMail = new ControleurMail();
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
    public function sendQuestionConnecte($domaine, $mailPro) {
      // envoi des messages
      if(isset($_SESSION['questionSvg']))
      {
        $question = $_SESSION['questionSvg'];
        unset($_SESSION['questionSvg']);
      }
      else {
        $question = $_POST['question'];
      }

      $this->ctrlMail->envoiMailQuestion($question, $mailPro);

      $_SESSION['validite'] = "ok";
      $_SESSION['message'] = "La question a bien été envoyée";
      $this->vue->genereVueDomaine($domaine);
    }

    /* Envoi d'une question est déconnecté*/
    public function sendQuestionDeconnecte($domaine, $mailPro) {
      $_SESSION['validite'] = "ko";
      $_SESSION['message'] = "Veuillez vous connecter pour continuer";
      $_SESSION['questionSvg'] = $_POST['question'];
      $this->vue->genereVueConnexionQuestion($domaine, $mailPro);
    }

  }
?>
