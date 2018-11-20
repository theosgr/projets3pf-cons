<?php
 require_once PATH_VUE."/vuePlageHoraire.php";
 require_once PATH_MODELE."/dao/dao.php";

	/* CONTROLEUR PLAGE HORAIRE : gestion des disponibilités */
	class ControleurPlageHoraire {
    	private $vue;
    	private $modele;

    	/* Constructeur de la classe */
    	public function __construct(){
    		$this->vue = new vuePlageHoraire();
    		$this->modele = new dao();
    	}

    	/* Affichage de la page pour connaitre les disponibilités  */
    	public function plageHoraire($idProfessionnel) {

     		$this->vue->genereVuePlageHoraire($idProfessionnel);
    	}

      /* Affichage des disponibiltés - les rdv déjà pris */
      public function listeHeure ($idProfessionnel) {
        //$listeHoraire = $this->modele->Dispo($plageHoraire);
        $this->vue->genereVueSelectionHoraire($idProfessionnel);
      }
}

?>
