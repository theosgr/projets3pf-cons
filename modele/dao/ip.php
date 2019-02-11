<?php

  require_once PATH_MODELE."/bean/Utilisateur.php";

   class ip {
    private $connexion;

///////// BASE DE DONNEES
    /* Connexion à la base de données */
    public function __construct(){
      try {
        $chaine = "mysql:host=".HOST.";dbname=".BD.";charset=UTF8";
        $pdo_options[PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES utf8';
        $this->connexion = new PDO($chaine,LOGIN,PASSWORD,$pdo_options);
        $this->connexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $e) {
        throw new PDOException("Erreur de connexion");
      }
    }

    /* Deconnexion de la base de données */
    public function destroy(){
      $this->connexion = NULL;
    }
       
    // remet à zero la table connexion afin de pouvoir se connecter à nouveau
    // utilisation de la commande : at 15:35 /Every:l,ma,me,j,v,s,d "C:
    public function delIp (){
        try
        {
            $nettoyage = $this->connexion->exec('DELETE FROM connexion;');
            $nettoyage->closeCursor();
        } 
        catch (PDOException $e)
        {
            $this->destroy();
            throw new PDOException("Erreur d'accès a la table connexion (Sécurité)");
        }
    }
       
}
?>