<?php
 require_once PATH_VUE."/vuePlageHoraire.php";
 require_once PATH_MODELE."/dao/dao.php";
 require_once PATH_VUE."/vueAuthentification.php";

	/* CONTROLEUR PLAGE HORAIRE : gestion des disponibilités */
	class ControleurPlageHoraire {
    	private $vuePlageHoraire;
    	private $modele;
      private $vueAuthentification;

    	/* Constructeur de la classe */
    	public function __construct(){
    		$this->vuePlageHoraire = new vuePlageHoraire();
    		$this->modele = new dao();
        $this->vueAuthentification = new VueAuthentification();
    	}

    	/* Affichage de la page pour connaitre les disponibilités  */
    	public function plageHoraire($idProfessionnel) {
     		$this->vuePlageHoraire->genereVuePlageHoraire($idProfessionnel);
    	}

      /* Affichage des disponibiltés - les rdv déjà pris */
      /*public function listeHeure () {
        $listeHoraire = $this->modele->Dispo($plageHoraire);
        $this->vue->genereVuePlageHoraire($plageHoraire, $listeHoraire);
      } */ 

      //Affichage de la vue pour permettre au professionnel d'ajouter des plages horaires
      public function affichageModifPlageHoraire($idProfessionnel)
      {
        $this->vuePlageHoraire->genereVueModifPlageHoraire($idProfessionnel);
      }

      public function ajouterPlageHoraire($mailPro, $dureeRdv, $debutServ, $finServ, $debutPause, $finPause, $dateDebut, $dateFin)
      {
        $this->modele->addPlageHoraire($mailPro, $dureeRdv, $debutServ, $finServ, $debutPause, $finPause, $dateDebut, $dateFin);
        $_SESSION['validite'] = "ok";
        $_SESSION['message'] = "Les plages horaires ont été ajoutées";
        $this->vueAuthentification->genereVueAccueil();
      }
}

?>