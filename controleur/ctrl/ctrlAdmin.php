<?php
  require_once PATH_VUE."/vueAuthentification.php";
  require_once PATH_MODELE."/dao/dao.php";

/* CONTROLEUR ADMIN : gestion des données du site */
  class ControleurAdmin {
    private $vue;
    private $modele;

    /* Constructeur de la classe. */
    public function __construct(){
      $this->vue = new vueAuthentification();
      $this->modele = new dao();
    }

    //Méthode qui convertit les lettres accentuées en lettres sans accents
    public function strToNoAccent($var) {
    $var = str_replace(
      array(
        'à', 'â', 'ä', 'á', 'ã', 'å',
        'î', 'ï', 'ì', 'í', 
        'ô', 'ö', 'ò', 'ó', 'õ', 'ø', 
        'ù', 'û', 'ü', 'ú', 
        'é', 'è', 'ê', 'ë', 
        'ç', 'ÿ', 'ñ',
        'À', 'Â', 'Ä', 'Á', 'Ã', 'Å',
        'Î', 'Ï', 'Ì', 'Í', 
        'Ô', 'Ö', 'Ò', 'Ó', 'Õ', 'Ø', 
        'Ù', 'Û', 'Ü', 'Ú', 
        'É', 'È', 'Ê', 'Ë', 
        'Ç', 'Ÿ', 'Ñ', 
      ),
      array(
        'a', 'a', 'a', 'a', 'a', 'a', 
        'i', 'i', 'i', 'i', 
        'o', 'o', 'o', 'o', 'o', 'o', 
        'u', 'u', 'u', 'u', 
        'e', 'e', 'e', 'e', 
        'c', 'y', 'n', 
        'A', 'A', 'A', 'A', 'A', 'A', 
        'I', 'I', 'I', 'I', 
        'O', 'O', 'O', 'O', 'O', 'O', 
        'U', 'U', 'U', 'U', 
        'E', 'E', 'E', 'E', 
        'C', 'Y', 'N', 
      ),$var);
    return $var;
  }

    //Méthode qui gère la création de domaine
    public function creationDomaine($domaine)
    {
      if(!empty($domaine)) //On regarde si le domaine rentré n'est pas vide
      {
        $domaines = $this->modele->getDomaine();

        $domaine=$this->strToNoAccent($domaine);
        $domaine=mb_strtoupper($domaine,'UTF-8');

        foreach($domaines as $row) //On s'assure que le domaine n'existe pas déjà
        {
          if($domaine == $row['nom'])
          {
            $_SESSION['validite']="ko";
            $_SESSION['message']="Le domaine existe déjà";
            $this->vue->genereVueAdmin($this->modele->getDomaine(), $this->modele->getSpecialite());
            return;
          }
        }

        $this->modele->insertDomaine($domaine);
        $_SESSION['validite']="ok";
        $_SESSION['message']="Le domaine a bien été ajouté";
        $this->vue->genereVueAdmin($this->modele->getDomaine(), $this->modele->getSpecialite());
    }
    else
    {
      $_SESSION['validite']="ko";
      $_SESSION['message']="Le domaine est vide";
      $this->vue->genereVueAdmin($this->modele->getDomaine(), $this->modele->getSpecialite());
    }

  }

  //Méthode qui gère la création de sous-spécialité
  public function creationSousSpecialite($specialite,$sousSpecialite)
    {
      if(!empty($sousSpecialite)) //On regarde si la sous spécialité rentrée n'est pas vide
      {
        $sousSpecialites = $this->modele->getSousSpecialite();

        $sousSpecialite= $this->strToNoAccent($sousSpecialite);        

        foreach($sousSpecialites as $row) //On s'assure que la sous spécialité n'existe pas déjà
        {
          if($sousSpecialite == $this->strToNoAccent($row['nom']))
          {
            $_SESSION['validite']="ko";
            $_SESSION['message']="La sous spécialité existe déjà";
            $this->vue->genereVueAdmin($this->modele->getDomaine(), $this->modele->getSpecialite());
            return;
          }
        }

        $idS=$this->modele->getIdSpecialite($specialite);
        foreach($idS as $row)
        {
          $idSpecialite = $row;
        }

        $this->modele->insertSousSpecialite($sousSpecialite,$idSpecialite);
        $_SESSION['validite']="ok";
        $_SESSION['message']="La sous spécialité a bien été ajouté";
        $this->vue->genereVueAdmin($this->modele->getDomaine(), $this->modele->getSpecialite());
    }
    else
    {
      $_SESSION['validite']="ko";
      $_SESSION['message']="La sous spécialité est vide";
      $this->vue->genereVueAdmin($this->modele->getDomaine(), $this->modele->getSpecialite());
    }

  }

  //Méthode qui gère la création de spécialité
  public function creationSpecialite($domaine,$specialite)
    {
      if(!empty($specialite)) //On regarde si la spécialité rentrée n'est pas vide
      {

        // $domaine=$this->strToNoAccent($domaine);
        // $domaine=mb_strtoupper($domaine,'UTF-8');

        $specialites = $this->modele->getSpecialite();

        $specialite=$this->strToNoAccent($specialite);
        $specialite = ucfirst($specialite);

        // $specialite=mb_strtoupper($specialite,'UTF-8');

        foreach($specialites as $row) //On s'assure que la spécialité n'existe pas déjà
        {
          if($specialite == $row['nom'])
          {
            $_SESSION['validite']="ko";
            $_SESSION['message']="La spécialité existe déjà";
            $this->vue->genereVueAdmin($this->modele->getDomaine(), $this->modele->getSpecialite());
            return;
          }
        }

        $id=$this->modele->getIdDomaine($domaine);
        
        foreach ($id as $row) {
          $idDomaine=$row;
        }

        $this->modele->insertSpecialite($idDomaine,$specialite);
        $_SESSION['validite']="ok";
        $_SESSION['message']="La spécialité a bien été ajouté";
        $this->vue->genereVueAdmin($this->modele->getDomaine(), $this->modele->getSpecialite());
    }
    else
    {
      $_SESSION['validite']="ko";
      $_SESSION['message']="La spécialité est vide";
      $this->vue->genereVueAdmin($this->modele->getDomaine(), $this->modele->getSpecialite());
    }

  }
}

?>