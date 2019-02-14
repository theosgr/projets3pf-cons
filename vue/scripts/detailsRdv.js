window.addEventListener('load', () => {

  const showDetails = document.querySelectorAll('.boutonDetails');

  showDetails.forEach(
    function(currentValue){
      currentValue.addEventListener('click', () =>{
        //Si le motif n'est pas affiché alors on l'affiche, sinon on le masque
        if(currentValue.nextSibling.nextSibling.style.display == "none")
        {
          currentValue.nextSibling.nextSibling.style.display = "inline";
          currentValue.textContent ="Masquer les détails";
        }
        else {
          currentValue.nextSibling.nextSibling.style.display = "none";
          currentValue.textContent ="Afficher les détails";
        }
      });
    }
  )
});
