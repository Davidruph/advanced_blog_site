// map options

var latitudelongtitude = { lat: 55.3781, lng: -3.4360}; // for united kingdom
var mapOptions = {
	center: latitudelongtitude,
	zoom: 7,
	mapTypeId: google.maps.MapTypeId.ROADMAP
};

//create map

var map = new google.maps.Map(document.getElementById("googleMap"), mapOptions);

//create a direction service object

var serviceDirection = new google.maps.DirectionsService();

//create a direction renderer objectto dosplay route

var displayDirection = new google.maps.DirectionsRenderer();

//bind directions renderer to the map
displayDirection.setMap(map);

//function 
 function calcRoute(){
     var fromVal = document.getElementById("from").value;
      var toVal = document.getElementById("to").value;
      if(fromVal.length === 0){
          Swal.fire(
                'Ooops Sorry!',
                'Pls enter an Origin to continue!',
                'error'
              );
      }else if(toVal.length === 0){
          Swal.fire(
                'Ooops Sorry!',
                'Pls enter a Destination to continue!',
                'error'
              );
      }else{

 	var unit = 'metric';
 	var request = {
 		origin: fromVal,
 		destination: toVal,
 		travelMode: google.maps.TravelMode.WALKING, 
 		unitSystem: unit == 'metric' ? google.maps.UnitSystem.METRIC : google.maps.UnitSystem.IMPERIAL
 	}

 	//pass the request to the route method
 	serviceDirection.route(request, (result, status) => {
    
 		if (status == google.maps.DirectionsStatus.OK) {
 			//get distance and time
 			const output_result = document.querySelector("#output");
 			output_result.innerHTML = "<b>Great job, you saved " + result.routes[0].legs[0].distance.text + "s of driving to " + document.getElementById("from").value + "</b>.<br /><b>The walking duration is </b><i class='fa fa-stopwatch'></i> : " + result.routes[0].legs[0].duration.text + ".";

 			//display route
 			displayDirection.setDirections(result);
 			document.getElementById("results").style.display = "block";
 			document.getElementById("maps").style.display = "block";
      $(function(){
            $('#saved_km').val(result.routes[0].legs[0].distance.text+'s');
            $('#log').trigger('click');
        });

 		}else{
 			//delete route from map
 			displayDirection.setDirections({routes: []});

 			//center map in spain
 			map.setCenter(latitudelongtitude);

 			//show eeror message
 			output_result.innerHTML = "<div class='alert-danger'><i class='fa fa-exclamation-triangle'></i> Could not retrieve walking distance.</div>";
 		}
 	});

  
      }

 }

//var autocomplete;
 autocomplete1 = new google.maps.places.Autocomplete(document.getElementById('from'), { types: [ 'geocode' ] });
google.maps.event.addListener(autocomplete1, 'place_changed', function() {
  fillInAddress();
});

autocomplete2 = new google.maps.places.Autocomplete(document.getElementById('to'), { types: [ 'geocode' ] });
google.maps.event.addListener(autocomplete2, 'place_changed', function() {
  fillInAddress();
});

function fillInAddress() {
  // Get the place details from the autocomplete object.
  //const place = autocomplete.getPlace();

}
