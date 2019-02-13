class Calendar {
  constructor(element)
  {
      // On récupère l'élément DOM passé en paramètre ou bien on prend .calendar par défaut

      this.domElement = element;

      this.domElement.innerHTML = "";
      this.domElement.style = "";

      // Renvoit une erreur si l'élément n'éxiste pas
      if(!this.domElement) throw "Calendar - L'élément spécifié est introuvable";

      // Liste des mois
      this.monthList = new Array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');

      // Liste des jours de la semaine
      this.dayList = new Array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');

      // Date actuelle
      this.today = new Date();

      // Mois actuel
      this.currentMonth = new Date(this.today.getFullYear(), this.today.getMonth(), 1);

      //Id du professionnel concerné par le calendrier
      //On atteint le champ caché qui contient la valeur de l'id du professionnel
      this.idPro = this.domElement.nextSibling.nextSibling.value;

      // On créé le div qui contiendra l'entête de notre calendrier
      let header = document.createElement('div');
      header.classList.add('header');
      header.style.width = "100%";

      // Bouton "précédent"
      let previousButton = document.createElement('button');
      previousButton.setAttribute('data-action', '-4');
      previousButton.textContent = '\u003c';
      header.appendChild(previousButton);

      let day1 = document.createElement('div');
      day1.classList.add('day');
      let day2 = document.createElement('div');
      day2.classList.add('day');
      let day3 = document.createElement('div');
      day3.classList.add('day');
      let day4 = document.createElement('div');
      day4.classList.add('day');

      header.appendChild(day1);
      header.appendChild(day2);
      header.appendChild(day3);
      header.appendChild(day4);

      this.d1 = new Date(this.today);

      this.d2 = new Date(this.today);
      this.d2.setTime(this.today.getTime()+(1* 24 * 60 * 60 * 1000));

      this.d3 = new Date(this.today);
      this.d3.setTime(this.today.getTime()+(2* 24 * 60 * 60 * 1000));

      this.d4 = new Date(this.today);
      this.d4.setTime(this.today.getTime()+(3* 24 * 60 * 60 * 1000));

      //Test
      day1.innerHTML = this.dayList[this.d1.getDay()]+"<br>"+this.d1.getDate()+" "+this.monthList[this.d1.getMonth()];
      day2.innerHTML = this.dayList[this.d2.getDay()]+"<br>"+this.d2.getDate()+" "+this.monthList[this.d2.getMonth()];
      day3.innerHTML = this.dayList[this.d3.getDay()]+"<br>"+this.d3.getDate()+" "+this.monthList[this.d3.getMonth()];
      day4.innerHTML = this.dayList[this.d4.getDay()]+"<br>"+this.d4.getDate()+" "+this.monthList[this.d4.getMonth()];

      this.domElement.appendChild(header);

      // Bouton "suivant"
      let nextButton = document.createElement('button');
      nextButton.setAttribute('data-action', '4');
      nextButton.textContent = '\u003e';
      header.appendChild(nextButton);

      // On créé les divs qui contiendront les horaires
      this.plageHorairesD1 = document.createElement('div');
      this.plageHorairesD2 = document.createElement('div');
      this.plageHorairesD3 = document.createElement('div');
      this.plageHorairesD4 = document.createElement('div');

      this.plageHorairesD1.classList.add('pHoraires');
      this.plageHorairesD2.classList.add('pHoraires');
      this.plageHorairesD3.classList.add('pHoraires');
      this.plageHorairesD4.classList.add('pHoraires');

      this.domElement.appendChild(this.plageHorairesD1);
      this.domElement.appendChild(this.plageHorairesD2);
      this.domElement.appendChild(this.plageHorairesD3);
      this.domElement.appendChild(this.plageHorairesD4);

      //Les listeners des boutons
      previousButton.addEventListener('click', () =>{
        this.d1.setTime(this.d1.getTime()-(4* 24 * 60 * 60 * 1000));
        this.d2.setTime(this.d1.getTime()+(1* 24 * 60 * 60 * 1000));
        this.d3.setTime(this.d1.getTime()+(2* 24 * 60 * 60 * 1000));
        this.d4.setTime(this.d1.getTime()+(3* 24 * 60 * 60 * 1000));

        day1.innerHTML = this.dayList[this.d1.getDay()]+"<br>"+this.d1.getDate()+" "+this.monthList[this.d1.getMonth()];
        console.log(this.d1);
        day2.innerHTML = this.dayList[this.d2.getDay()]+"<br>"+this.d2.getDate()+" "+this.monthList[this.d2.getMonth()];
        console.log(this.d2);
        day3.innerHTML = this.dayList[this.d3.getDay()]+"<br>"+this.d3.getDate()+" "+this.monthList[this.d3.getMonth()];
        console.log(this.d3);
        day4.innerHTML = this.dayList[this.d4.getDay()]+"<br>"+this.d4.getDate()+" "+this.monthList[this.d4.getMonth()];
        console.log(this.d4);

        this.loadHoraires();
      });

      nextButton.addEventListener('click', () =>{
        this.d1.setTime(this.d1.getTime()+(4* 24 * 60 * 60 * 1000));
        this.d2.setTime(this.d1.getTime()+(1 * 24 * 60 * 60 * 1000));
        this.d3.setTime(this.d1.getTime()+(2 * 24 * 60 * 60 * 1000));
        this.d4.setTime(this.d1.getTime()+(3 * 24 * 60 * 60 * 1000));

        day1.innerHTML = this.dayList[this.d1.getDay()]+"<br>"+this.d1.getDate()+" "+this.monthList[this.d1.getMonth()];
        console.log(this.d1);
        day2.innerHTML = this.dayList[this.d2.getDay()]+"<br>"+this.d2.getDate()+" "+this.monthList[this.d2.getMonth()];
        console.log(this.d2);
        day3.innerHTML = this.dayList[this.d3.getDay()]+"<br>"+this.d3.getDate()+" "+this.monthList[this.d3.getMonth()];
        console.log(this.d3);
        day4.innerHTML = this.dayList[this.d4.getDay()]+"<br>"+this.d4.getDate()+" "+this.monthList[this.d4.getMonth()];
        console.log(this.d4);

        this.loadHoraires();
      });

      // On charge les horaires
      this.loadHoraires();
    }

    loadHoraires()
    {
        let dateEnCours = new Date(this.d1);
        let plagesHPro;
        let plagesProDecodeJson;

        this.plageHorairesD1.innerHTML = "";
        this.plageHorairesD2.innerHTML = "";
        this.plageHorairesD3.innerHTML = "";
        this.plageHorairesD4.innerHTML = "";

        // Création des cellules contenant les horaires des plages horaires du professionnel
        for(let i=1; i<=4; i++)
        {
          dateEnCours = dateEnCours.toDateString();
          // console.log(dateEnCours);
          plagesHPro = this.creerobjet("modele/dao/recupPlagesHoraires.php?idPro="+this.idPro+"&date="+dateEnCours);
          // console.log(plagesHPro);

          plagesProDecodeJson = JSON.parse(plagesHPro);
          // console.log(plagesProDecodeJson);

          for(let j=0; j<plagesProDecodeJson.length;j++)
          {
            let cell = document.createElement('a');
            cell.classList.add('cell');
            // cell.textContent = "08:15";
            cell.textContent = plagesProDecodeJson[j][1];
            cell.href = "index.php?idProC="+this.idPro+"&idPlageHoraireC="+plagesProDecodeJson[j][0];

            // console.log(cell);
            switch (i) {
              case 1:
                this.plageHorairesD1.innerHTML = this.plageHorairesD1.innerHTML+"<br>"+cell.outerHTML;
                break;
              case 2:
                this.plageHorairesD2.innerHTML = this.plageHorairesD2.innerHTML+"<br>"+cell.outerHTML;
                break;
              case 3:
                this.plageHorairesD3.innerHTML = this.plageHorairesD3.innerHTML+"<br>"+cell.outerHTML;
                break;
              case 4:
                this.plageHorairesD4.innerHTML = this.plageHorairesD4.innerHTML+"<br>"+cell.outerHTML;
              break;
            }
          }

          //On change la date courante à la date du jour suivant, on convertit les timestamp en objets Date
          switch (i) {
            case 1:
              dateEnCours = new Date(this.d2);
              break;
            case 2:
              dateEnCours = new Date(this.d3);
              break;
            case 3:
              dateEnCours = new Date(this.d4);
              break;
            case 4:
            break;
          }
        }
        console.log("Initialisation terminée.");
  }

  //Fonction qui permet de récuperer le résultat de l'affichage d'une page php
  creerobjet(fichier)
	{
		if(window.XMLHttpRequest) // FIREFOX
		  var xhr_object = new XMLHttpRequest();
		else if(window.ActiveXObject) // IE
		  var xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
		else
			return(false);
		xhr_object.open("POST", fichier, false);
		xhr_object.send(null);
		if(xhr_object.readyState == 4)
			return(xhr_object.responseText);
		else
			return(false);
	}
}
