window.addEventListener('load', () => {

  console.log("Initialisation du calendrier...");

  domElements = document.getElementsByClassName('calendar');

  for(i=0;i<this.domElements.length;i++)
  {
    const calendar = new Calendar(this.domElements.item(i));
  }
});
