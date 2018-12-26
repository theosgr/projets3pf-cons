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

    	public function ajouterRdv($idPlageHoraire, $idProfessionnel, $idPatient, $motif, $idProche)
      {
        $patient = $this->modele->getIdUser($idPatient);
        $idP = $patient['id'];
        if($idProche == $idPatient)
        {
            $prenomPa = $patient['prenom'];
            $nomPa = $patient['nom'];
        }
        else
        {
            $prenomPa = $this->modele->getInfosProche($idProche)['prenom'];
            $nomPa = $this->modele->getInfosProche($idProche)['nom'];
        }

        $plageH = $this->modele->getPlageHoraireById($idPlageHoraire);
        $date = $plageH['date'];
        $heureD = $plageH['heureDebut'];
        $heureF = $plageH['heureFin'];

        /*if($idProfessionnel != $idP || $idProche != $idPatient)
        {*/
            $this->modele->addRdv($idProfessionnel,$heureD, $heureF, $date, $idP, $prenomPa, $nomPa, $motif, $idPlageHoraire);
            $this->modele->setPlageHorairePrise($idPlageHoraire);
            $_SESSION['validite'] = "ok";
            $_SESSION['message'] = "Le rendez-vous a été ajouté";
            unset($_SESSION['idProfessionnel']); //au cas où l'utilisateur venait de se connecter, on supprime les variables de session
            unset($_SESSION['motif']);
            unset($_SESSION['idPlageHoraire']);
            $this->ctrlCompte->pageMonCompte();    
        /*}
        else
        {
            $_SESSION['validite'] = "ko";
            $_SESSION['message'] = "Vous ne pouvez pas prendre rendez-vous avec vous-même";
            unset($_SESSION['idProfessionnel']);
            unset($_SESSION['motif']);
            unset($_SESSION['idPlageHoraire']);
            $this->ctrlCompte->pageMonCompte();
        }  */
            
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