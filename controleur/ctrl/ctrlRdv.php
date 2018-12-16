<?php
 require_once PATH_VUE."/vuePlageHoraire.php";
 require_once PATH_MODELE."/dao/dao.php";
 require_once PATH_VUE."/vueAuthentification.php";
 require_once "ctrlCompte.php";

	/* CONTROLEUR PLAGE HORAIRE : gestion des disponibilités */
	class ControleurRdv {
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

    	public function ajouterRdv($idPlageHoraire, $idProfessionnel, $idPatient, $motif)
      {
        $patient = $this->modele->getIdUser($idPatient);
        $idP = $patient['id'];
        $prenomPa = $patient['prenom'];
        $nomPa = $patient['nom'];

        $plageH = $this->modele->getPlageHoraireById($idPlageHoraire);
        $date = $plageH['date'];
        $heureD = $plageH['heureDebut'];
        $heureF = $plageH['heureFin'];

        $this->modele->addRdv($idProfessionnel,$heureD, $heureF, $date, $idP, $prenomPa, $nomPa, $motif, $idPlageHoraire);
        $this->modele->setPlageHorairePrise($idPlageHoraire);
        $_SESSION['validite'] = "ok";
        $_SESSION['message'] = "Le rendez-vous a été ajouté";
        $this->ctrlCompte->pageMonCompte();
      }

      public function annulerRdv($idRdv)
      {
        $rdv = $this->modele->getRdvById($idRdv);
        $idPlageHoraire = $rdv['idPlageHoraire'];
        $this->modele->annulerRdv($idRdv);
        $this->modele->setPlageHoraireLibre($idPlageHoraire);
        $_SESSION['validite'] = "ok";
        $_SESSION['message'] = "Le rendez-vous a été annulé";
        $this->ctrlCompte->pageMonCompte();
      }
}

?>