<!-- Mise en place du menu responsive -->
<script>
function setResponsive() {
  var x = document.getElementsByClassName("nav");
  for (var i = 0; i < x.length; i++) {
    if (x[i].className == "nav") {
      x[i].className += " responsive";
    } else {
      x[i].className = "nav";
    }
  }
}
</script>

<!-- Utilisateur non connecté -->
<?php
  if (!isset($_SESSION['user']) || $_SESSION['user'] == 'ko') {
?>
  <header>
    <div class="nav">
      <ul>
        <li><a href="?inscription=user">S'inscrire</a></li>
        <li class="dot"></li>
        <li><a href="index.php?domaine=medical">Médical</a></li>
      </ul>
    </div>
    <div class="logo-nav"><a href="index.php"><img src="vue/img/main-logo.png"/></a></div>
    <div class="nav">
      <ul>
        <li><a href="index.php?domaine=juridique">Juridique</a></li>
        <li><div class="dot"></div></li>
        <li><a href="index.php?connexion">Connexion</a></li>
      </ul>
    </div>
    <a href="javascript:void(0);" class="icon" onclick="setResponsive()">&#9776;</a>
  </header>

<!-- Utilisateur connecté -->
<?php
  } else {
?>
  <header>
    <div class="nav">
      <ul>
        <li><a href="?monCompte">Mon compte</a></li>
        <li class="dot"></li>
        <li><a href="?domaine=medical">Médical</a></li>
      </ul>
    </div>
    <div class="logo-nav"><a href="index.php"><img src="vue/img/main-logo.png"/></a></div>
    <div class="nav">
      <ul>
        <li><a href="?domaine=juridique">Juridique</a></li>
        <li><div class="dot"></div></li>
        <li><a href="?deconnexion">Déconnexion</a></li>
        <?php if($_SESSION['categorie']==3)
        echo("<li><div class=\"dot\"></div></li>
        <li><a href=\"?admin\">Gestion du site</a></li>"); ?>
      </ul>
    </div>
    <a href="javascript:void(0);" class="icon" onclick="setResponsive()">&#9776;</a>
  </header>

<!-- Affichage des messages d'informations -->
<?php
  }
  if (isset($_SESSION['message'])) {
?>
  <div id="msg" class="<?php echo $_SESSION['validite']; ?>">
    <p><?php echo $_SESSION['message']; ?></p>
  </div>
<?php
  unset($_SESSION['message']);
}
?>
