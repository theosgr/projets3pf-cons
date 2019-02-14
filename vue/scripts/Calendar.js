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
      previousButton.disabled = "true";
      previousButton.style.color = "#E5E5E5";
      $(previousButton).hover(function(){
        $(this).css("cursor", "not-allowed");
        $(this).css("color", "#E5E5E5");
      });

      //Création des divs d'entête du calendrier (Jour+date)
      let day1 = document.createElement('div');
      day1.classList.add('day');
      let day2 = document.createElement('div');
      day2.classList.add('day');
      let day3 = document.createElement('div');
      day3.classList.add('day');
      let day4 = document.createElement('div');
      day4.classList.add('day');

      //Ajout dans le DOM
      header.appendChild(day1);
      header.appendChild(day2);
      header.appendChild(day3);
      header.appendChild(day4);

      //Initialisation des dates (on commence à aujourd'hui)
      this.d1 = new Date(this.today);

      this.d2 = new Date(this.today);
      this.d2.setTime(this.today.getTime()+(1* 24 * 60 * 60 * 1000));

      this.d3 = new Date(this.today);
      this.d3.setTime(this.today.getTime()+(2* 24 * 60 * 60 * 1000));

      this.d4 = new Date(this.today);
      this.d4.setTime(this.today.getTime()+(3* 24 * 60 * 60 * 1000));

      day1.innerHTML = "<b>"+this.dayList[this.d1.getDay()]+"</b> <br>"+this.d1.getDate()+" "+this.monthList[this.d1.getMonth()];
      day2.innerHTML = "<b>"+this.dayList[this.d2.getDay()]+"</b> <br>"+this.d2.getDate()+" "+this.monthList[this.d2.getMonth()];
      day3.innerHTML = "<b>"+this.dayList[this.d3.getDay()]+"</b> <br>"+this.d3.getDate()+" "+this.monthList[this.d3.getMonth()];
      day4.innerHTML = "<b>"+this.dayList[this.d4.getDay()]+"</b> <br>"+this.d4.getDate()+" "+this.monthList[this.d4.getMonth()];

      //Ajout du header au DOM
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
        //Mise à jour des jours affichés (utilisation de getTime et non getDate)
        this.d1.setTime(this.d1.getTime()-(4* 24 * 60 * 60 * 1000));
        this.d2.setTime(this.d1.getTime()+(1* 24 * 60 * 60 * 1000));
        this.d3.setTime(this.d1.getTime()+(2* 24 * 60 * 60 * 1000));
        this.d4.setTime(this.d1.getTime()+(3* 24 * 60 * 60 * 1000));

        day1.innerHTML = "<b>"+this.dayList[this.d1.getDay()]+"</b> <br>"+this.d1.getDate()+" "+this.monthList[this.d1.getMonth()];
        day2.innerHTML = "<b>"+this.dayList[this.d2.getDay()]+"</b> <br>"+this.d2.getDate()+" "+this.monthList[this.d2.getMonth()];
        day3.innerHTML = "<b>"+this.dayList[this.d3.getDay()]+"</b> <br>"+this.d3.getDate()+" "+this.monthList[this.d3.getMonth()];
        day4.innerHTML = "<b>"+this.dayList[this.d4.getDay()]+"</b> <br>"+this.d4.getDate()+" "+this.monthList[this.d4.getMonth()];

        //Si le jour le plus à gauche est égal à la date d'aujourd'hui, on interdit de cliquer sur le bouton "précédent"
        if(this.d1.getDate() == this.today.getDate())
        {
          previousButton.disabled = "true";
          previousButton.style.color = "#E5E5E5";
          $(previousButton).hover(function(){
            $(this).css("cursor", "not-allowed");
            $(this).css("color", "#E5E5E5");
          });
        }

        this.loadHoraires();
      });

      nextButton.addEventListener('click', () =>{
        this.d1.setTime(this.d1.getTime()+(4* 24 * 60 * 60 * 1000));
        this.d2.setTime(this.d1.getTime()+(1 * 24 * 60 * 60 * 1000));
        this.d3.setTime(this.d1.getTime()+(2 * 24 * 60 * 60 * 1000));
        this.d4.setTime(this.d1.getTime()+(3 * 24 * 60 * 60 * 1000));

        day1.innerHTML = "<b>"+this.dayList[this.d1.getDay()]+"</b> <br>"+this.d1.getDate()+" "+this.monthList[this.d1.getMonth()];
        day2.innerHTML = "<b>"+this.dayList[this.d2.getDay()]+"</b> <br>"+this.d2.getDate()+" "+this.monthList[this.d2.getMonth()];
        day3.innerHTML = "<b>"+this.dayList[this.d3.getDay()]+"</b> <br>"+this.d3.getDate()+" "+this.monthList[this.d3.getMonth()];
        day4.innerHTML = "<b>"+this.dayList[this.d4.getDay()]+"</b> <br>"+this.d4.getDate()+" "+this.monthList[this.d4.getMonth()];

        previousButton.disabled = "";
        previousButton.style.color = "";
        $(previousButton).hover(function(){
          $(this).css("cursor", "pointer");
          $(this).css("color", "orange");
        }, function(){
          $(this).css("color", "inherit");
        });

        this.loadHoraires();
      });

      // On charge les horaires
      this.loadHoraires();
    }

    loadHoraires()
    {
        //Initialisation de la date en cours d'affichage (la date la plus à gauche est toujours la référence)
        let dateEnCours = new Date(this.d1);
        let plagesHPro;
        let plagesProDecodeJson;

        //On réinitialise la hauteur des divs contenant les horaires à leur hauteur initiale (au cas où on a appuyer sur voirPlus auparavant)
        this.plageHorairesD1.style.height = "280px";
        this.plageHorairesD2.style.height = "280px";
        this.plageHorairesD3.style.height = "280px";
        this.plageHorairesD4.style.height = "280px";

        //On vide toujours le contenu des divs contenant les horaires
        this.plageHorairesD1.innerHTML = "";
        this.plageHorairesD2.innerHTML = "";
        this.plageHorairesD3.innerHTML = "";
        this.plageHorairesD4.innerHTML = "";

        const parent = this.domElement.parentNode;

        //Si il y a 3 fils dans le parent, cela veut dire que le bouton "voirPlus" a été rajouté précédemment. On le supprime donc.
        if(parent.children.length == 3)
        {
          parent.removeChild(parent.lastChild);
        }

        //On met contenuCache à false, c'est à dire qu'il n'y a pas besoin du bouton "voirPlus" car toutes les plages horaires sont visibles (tiennent dans 280px de haut)
        let contenuCache = false;

        // Création des cellules contenant les horaires des plages horaires du professionnel
        for(let i=1; i<=4; i++)
        {
          //Conversion de la date en cours en String
          dateEnCours = dateEnCours.toDateString();

          //Ici on crée un JSON contenant les plages horaires du professionnel concerné
          plagesHPro = this.creerobjet("modele/dao/recupPlagesHoraires.php?idPro="+this.idPro+"&date="+dateEnCours);

          //On parse le JSON en tableau
          plagesProDecodeJson = JSON.parse(plagesHPro);

          //Variable qui permet de savoir les plages horaires ajoutées au DOM
          let plagesHAjoutees = 0;

          //Ici on parcourt le tableau des plages horaires que l'on vient de parse
          for(let j=0; j<plagesProDecodeJson.length;j++)
          {
            //Avant d'ajouter une plage horaire, on vérifie si elle n'est pas passée (à 16h00 on ne peut pas avoir accès à une plage horaire qui est à 8h00 le jour même)
            let heureDebut = plagesProDecodeJson[j][1];
            let heureDebutSplit = heureDebut.split(":");
            let heures = heureDebutSplit[0];
            let minutes = heureDebutSplit[1];

            //On crée un objet Date pour la comparaison
            let dateComp = new Date();
            dateComp.setHours(heures);
            dateComp.setMinutes(minutes);

            //Si le premier jour est aujourd'hui alors on a besoin de comparer pour savoir si l'heure est passée, sinon non
            if(this.d1.getDate() == this.today.getDate() && i==1)
            {
              if(this.today < dateComp)
              {
                let cell = document.createElement('a');
                cell.classList.add('cell');
                cell.textContent = plagesProDecodeJson[j][1];

                //On ne peut pas ajouter de manière visible plus de 8 plages horaires
                if(plagesHAjoutees>=8)
                {
                  cell.style.display = "none";
                  contenuCache = true; //Cette variable permet de créer un bouton "voir plus"
                }

                cell.href = "index.php?idProC="+this.idPro+"&idPlageHoraireC="+plagesProDecodeJson[j][0];

                //On ajoute la cellule au div qui correspond (le div du jour actuel)
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
                plagesHAjoutees++;
              }
            }
            else {
              let cell = document.createElement('a');
              cell.classList.add('cell');
              cell.textContent = plagesProDecodeJson[j][1];

              if(plagesHAjoutees>=8)
              {
                cell.style.display = "none";
                contenuCache = true;
              }

              cell.href = "index.php?idProC="+this.idPro+"&idPlageHoraireC="+plagesProDecodeJson[j][0];

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
              plagesHAjoutees++;
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

        //Si il y a des plages horaires cachées car trop nombreuses, alors on crée un bouton "Voir plus"
        const voirPlus = document.createElement('button');
        if(contenuCache)
        {
          voirPlus.classList.add('voirPlus');
          voirPlus.innerText = "VOIR PLUS D'HORAIRES";

          let cellulesD1 = this.plageHorairesD1.children;
          let cellulesD2 = this.plageHorairesD2.children;
          let cellulesD3 = this.plageHorairesD3.children;
          let cellulesD4 = this.plageHorairesD4.children;
          let a;
          //Ajout du listener sur voir plus qui affiche la totalité des plages horaires
          voirPlus.addEventListener("click", () => {

            this.plageHorairesD1.style.height = "auto";
            for(a=0;a<cellulesD1.length;a++)
            {
              cellulesD1.item(a).style.display="inline-block";
            }

            this.plageHorairesD2.style.height = "auto";
            for(a=0;a<cellulesD2.length;a++)
            {
              cellulesD2.item(a).style.display="inline-block";
            }

            this.plageHorairesD3.style.height = "auto";
            for(a=0;a<cellulesD3.length;a++)
            {
              cellulesD3.item(a).style.display="inline-block";
            }

            this.plageHorairesD4.style.height = "auto";
            for(a=0;a<cellulesD4.length;a++)
            {
              cellulesD4.item(a).style.display="inline-block";
            }

            voirPlus.style.display = "none";
          });

          //ajout du bouton au DOM
          this.domElement.parentNode.appendChild(voirPlus);
        }
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
