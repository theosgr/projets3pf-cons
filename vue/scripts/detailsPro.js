function afficherDetailsPro(div) {
        var adresse = div.getElementsByTagName('div')[2];
        var telephone = div.getElementsByTagName('p')[3];
        var calendrier = div.getElementsByTagName('div')[5];

        //Même si dans le css sa valeur est none, je comprends pas pourquoi mais au chargement de la page elle n'est pas égale à none pour js. Du coup on la remet à none ici.
        if(adresse.style.display !='none' && adresse.style.display !='flex')
        {
        	adresse.style.display = 'none';
        }

        if(adresse.style.display == 'none') {
            adresse.style.display = 'flex';
            telephone.style.display = 'flex';
            calendrier.style.display = 'flex';
            div.style.display= 'flex';
            
        } else {
            adresse.style.display = 'none';
            telephone.style.display = 'none';
            calendrier.style.display = 'none';
            div.style.display = 'auto';
        }
    }