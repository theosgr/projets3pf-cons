// On initialise la latitude et la longitude (centre de la carte)
	// Fonction d'initialisation de la carte
	// function initMap() {
	// 	var lat = 48;
	// 	var lon = 3;
	// 	var map = null;
	// 	var location = document.getElementById("locationphp").value;
	// 	var locationText = location.innerText;
	// 	console.log(locationText);
	// 	var locationString = locationText.toString();
	// 	console.log(locationString);
	// 	var locationSplit = locationString.split(", ");
	// 	console.log(locationSplit[1]);
	//
	//
	// 	// Créer l'objet "map" et l'insèrer dans l'élément HTML qui a l'ID "map"
	// 	map = new google.maps.Map(document.getElementById("map"), {
	// 			center: new google.maps.LatLng(locationSplit[0], locationSplit[1]),
	// 			zoom: 11,
	// 			mapTypeId: google.maps.MapTypeId.ROADMAP,
	// 			mapTypeControl: true,
	// 			scrollwheel: false,
	// 			mapTypeControlOptions: {
	// 			style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR
	// 		},
	// 		navigationControl: true,
	// 		navigationControlOptions: {
	// 		// Comment ces options doivent-elles s'afficher
	// 			style: google.maps.NavigationControlStyle.ZOOM_PAN
	// 		}
	// 	});
	// }
	// window.onload = function(){
	// 	// Fonction d'initialisation qui s'exécute lorsque le DOM est chargé
	// 	initMap();
	// };
