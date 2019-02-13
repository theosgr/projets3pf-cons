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

      //Permet au professionnel de supprimer une plage horaires
      public function supprimerPlageHoraire($idPlageHoraire)
      {
        $this->modele->delPlageHoraire($idPlageHoraire);
        $_SESSION['validite'] = "ok";
        $_SESSION['message'] = "La plage horaire a été supprimée";
        $this->ctrlCompte->pageMonCompte();

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
        $dateAjd = new DateTime();
        $dateAjd = $dateAjd->format("Y-m-d");
        if($dateAjd <= $daterdv)
        {
          $_SESSION['dateRdv'] = $daterdv;
          $listeHoraire = $this->modele->getPlageHoraireProDate($idProfessionnel,$daterdv);
          $this->vuePlageHoraire->genereVueSelectionHoraire($listeHoraire);
        }
        else
        {
          $_SESSION['message'] = "La date choisie est passée";
          $_SESSION['validite'] = "ko";
          $this->vuePlageHoraire->genereVuePlageHoraire($idProfessionnel);
        }

      }

      public function AfficheListeProcheConnecte()
      {
        $_SESSION['idPlageHoraire'] = $_POST['listeHoraires'];
        $_SESSION['motif'] = $_POST['motif'];
        $_SESSION['idProfessionnel'] = $_POST['idPro'];

        $idUser = $this->modele->getIdUser($_SESSION['id'])[0];
        $listeProche = $this->modele->getProches($idUser);
        $this->vuePlageHoraire->genereVueSelectionProche($listeProche);
      }

      public function AfficheListeProcheDeconnecte()
      {
        $_SESSION['idPlageHoraire'] = $_POST['listeHoraires'];
        $_SESSION['motif'] = $_POST['motif'];
        $_SESSION['idProfessionnel'] = $_POST['idPro'];

        $this->vueAuthentification->genereVueConnexionRdv();
      }

      public function affichageChoixMotifCalendrier()
      {
        $this->vuePlageHoraire->genereVueChoixMotifCalendrier();
      }
}

?>
