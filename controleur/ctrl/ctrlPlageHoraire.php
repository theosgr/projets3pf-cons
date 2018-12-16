<?php
 require_once PATH_VUE."/vuePlageHoraire.php";
 require_once PATH_MODELE."/dao/dao.php";
 require_once PATH_VUE."/vueAuthentification.php";
 require_once "ctrlCompte.php";

	/* CONTROLEUR PLAGE HORAIRE : gestion des disponibilités */
	class ControleurPlageHoraire {
    	private $vuePlageHoraire;
    	private $modele;
      private $vueAuthentification;
      private $ctrlCompte;

    	/* Constructeur de la classe */
    	public function __construct(){
    		$this->vuePlageHoraire = new vuePlageHoraire();
    		$this->modele = new dao();
        $this->vueAuthentification = new VueAuthentification();
        $this->ctrlCompte = new ControleurCompte();
    	}

    	/* Affichage de la page pour connaitre les disponibilités  */
    	public function plageHoraire($idProfessionnel) {
     		$this->vuePlageHoraire->genereVuePlageHoraire($idProfessionnel);
    	}

      //Affichage de la vue pour permettre au professionnel d'ajouter des plages horaires
      public function affichageModifPlageHoraire($idProfessionnel)
      {
        $this->vuePlageHoraire->genereVueModifPlageHoraire($idProfessionnel);
      }

      //Affichage de la vue pour permettre au professionnel d'ajouter des plages horaires par jour
      public function affichageModifPlageHoraireJourParJour($idProfessionnel)
      {
        $this->vuePlageHoraire->genereVueModifPlageHoraireJourParJour($idProfessionnel);
      }

      //Permet de traiter les données de modification d'une plage horaire pour faire les modifications dans la base de données
      public function ajouterPlageHoraire($mailPro, $dureeRdv, $debutServ, $finServ, $debutPause, $finPause, $dateDebut, $dateFin, $jour)
      {
        $this->modele->addPlageHoraire($mailPro, $dureeRdv, $debutServ, $finServ, $debutPause, $finPause, $dateDebut, $dateFin, $jour);
        $_SESSION['validite'] = "ok";
        $_SESSION['message'] = "Les plages horaires ont été ajoutées";
        $this->vuePlageHoraire->genereVueModifPlageHoraire($mailPro);
      }

      //Permet au professionnel de signaler qu'il sera remplacé sur une plage horaire
      public function modifierRemplacant($id,$nomRpl,$civiliteRpl)
      {
        $this->modele->updateRemplacant($id,$nomRpl,$civiliteRpl);
        $_SESSION['modifRemplacant'] = ""; //On vide la variable de session
        unset($_SESSION['modifRemplaçant']); //On l'unset()
        $_SESSION['validite'] = "ok";
        $_SESSION['message'] = "Le remplaçant a été modifié";
        $this->ctrlCompte->pageMonCompte();
      }

      /* Affichage des disponibiltés - les rdv déjà pris */
      public function listeHeure ($idProfessionnel,$daterdv) {
        $listeHoraire = $this->modele->getPlageHoraireProDate($idProfessionnel,$daterdv);
        $this->vuePlageHoraire->genereVueSelectionHoraire($listeHoraire);
      }
}

?>