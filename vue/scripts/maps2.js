function initialiser() {
	var latlng = new google.maps.LatLng(48.85341, 2.3488);
	
	var options = {
		center: latlng, // choix du centre de la carte
		zoom: 19, // agrandissement de la carte
		mapTypeId: google.maps.MapTypeId.ROADMAP // type de la carte
	};
	
	var carte = new google.maps.Map(document.getElementById("carte"), options); // affichage de la carte
	
	/****************Nouveau code****************/

	//création du marqueur
	var marqueur = new google.maps.Marker({
		position: new google.maps.LatLng(48.85341, 2.3488), // position
		map: carte // carte à afficher
	});
    
    google.maps.event.addListener(marqueur, 'click', function() {
		alert("Le marqueur a été cliqué.");//message d'alerte
	});


}
