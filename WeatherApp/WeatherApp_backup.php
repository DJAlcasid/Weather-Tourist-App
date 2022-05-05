<script type="text/javascript" src="https://code.jquery.com/jquery-1.7.1.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

<style type="text/css">
	body, td, th {
		font-family: sans-serif;
		position: relative;
		font-size: 0.8em;
	}
	fieldset {
		border-radius:8px;
	}
	fieldset td, legend {
		font-weight: bold;
	}
	fieldset span {
		font-weight: normal;
	}
	legend {
		font-style: italic;
		font-size: 1.1em;
	}
	button {
		border-radius:8px;
		background-color: blue;
	}
</style>

<title> Weather App </title>

<head>
	<h3>WEATHER APP</h3>
</head>

<body>

	<p>Welcome, Traveller! Please use this app to check the weather from the cities listed below.&nbsp;</p><hr>
	<p></p>
	<form>

		<div>
			<select id='city'>
				<option selected disabled>Select a City</option>
				<option value="Tokyo">Tokyo</option>
				<option value="Kyoto">Kyoto</option>
				<option value="Osaka">Osaka</option>
				<option value="Sapporo">Sapporo</option>
				<option value="Nagoya">Nagoya</option>
			</select>
			<button id='submit'>SUBMIT</button>
			<div id='restrictions'></div>
		</div>

		<p></p>

		<fieldset>
			<legend>City Information:</legend>
			<div>
				<table>
					<tr>
						<td width='150'>City: </td>
						<td width='150'><span id="res_city"></span> </td>
						<td width='150'>Address: </td>
						<td width='300'><span id="res_address"></span> </td>
						<td width='150'>Website: </td>
						<td width='200'><span id="res_website"></span> </td>
					</tr>
					<tr>
						<td>Country: </td>
						<td><span id="res_country"></span> </td>
						<td>Latitude: </td>
						<td><span id="res_latitude"></span> </td>
						<td>Source Name: </td>
						<td><span id="res_sourcename"></span> </td>
					</tr>
					<tr>
						<td>Country Code: </td>
						<td><span id="res_countrycode"></span> </td>
						<td>Longitude: </td>
						<td><span id="res_longitude"></span> </td>
						<td>Source URL: </td>
						<td><span id="res_sourceurl"></span> </td>
					</tr>
				</table>
			</div>
		</fieldset>

		<p></p>
		<table id='table'>
			<thead>
				<tr>
	                <th rowspan=2>Date & Time</th>
	                <th rowspan=2>Part of the Day</th>
	                <th colspan=9 style="background-color: #fcf4dd">Main</th>
	                <th colspan=2 style="background-color: #daeaf6">Weather</th>
	                <th rowspan=2>Clouds</th>
	                <th colspan=3 style="background-color: #ddedea">Wind</th>
	                <th rowspan=2>Visibility</th>
	                <th rowspan=2>Precipitation</th>
	            </tr>
				<tr>
	                <th>Temperature (K)</th>
	                <th>Temp. Feels Like</th>
	                <th>Min. Temp.</th>
	                <th>Max. Temp.</th>
	                <th>Pressure</th>
	                <th>Sea Level</th>
	                <th>Ground Level</th>
	                <th>Humidity</th>
	                <th>Temp. KF</th>
	                <th>Weather</th>
	                <th>Description</th>
	                <!-- <th>Icon</th> -->
	                <th>Speed</th>
	                <th>Degrees</th>
	                <th>Gust</th>
	            </tr>
	        </thead>
            <tbody id = 'list'> </tbody>
		</table>
		
	</form>
</body>


<script type="text/javascript">

	var requestOptions = { method: 'GET', };

	$(document).ready(function () {
        $('#table').DataTable({
        	searching: false, 
			paging: false,
			bInfo : false,
			sorting: false
        });
    });

	$('#submit').click(function(e){
		e.preventDefault()

		var city = $('#city').val()

		if ( !city ) {
			$id = 'restrictions';
			document.getElementById($id).style.display = 'inline'
			document.getElementById($id).style.color = 'red'
			document.getElementById($id).style.fontWeight = 'bold'
			document.getElementById($id).style.lineHeight = 'normal'
			document.getElementById($id).innerHTML = 'Please select a city.'
		} else {

			// call current data from OpenWeather API (returns in JSON)
			// API key: f398aaebc61d5c7cf2b6f337660a7a10
			// https://api.openweathermap.org/data/2.5/forecast?q={city name},{country code}&appid={API key}
			$.ajax({
				type: 'get', 
				// url: 'https://api.openweathermap.org/data/2.5/forecast?q=Tokyo,JP&appid=f398aaebc61d5c7cf2b6f337660a7a10', 
				url: 'https://api.openweathermap.org/data/2.5/forecast?q='+city+',JP&appid=f398aaebc61d5c7cf2b6f337660a7a10', 
				success: function(result){
					
					// var json = {
		   //              json: JSON.stringify(result),
		   //              delay: 1
		   //          };
		   //          console.log(json);

		   			$("#list").empty();

		        	// ITERATING THROUGH OBJECTS
		        	var student = '';
		            $.each(result.list, function (key, value) {

		            	var precip = '';
		            	if ( value.pop == 1 ) {
		            		precip = '100%';
		            	} else if ( value.pop == 0 ) {
		            		precip = '0%';
		            	}

		            	var day = '';
		            	if ( value.sys.pod == 'd' ) {
		            		day = 'Day';
		            	} else if ( value.sys.pod == 'n' ) {
		            		day = 'Night';
		            	}

		                //CONSTRUCTION OF ROWS HAVING
		                // DATA FROM JSON OBJECT
		                student += '<tr>' + 
		                	'<td>' + value.dt_txt + '</td>' + 
		                	'<td>' + day + '</td>' + 
		                	'<td>' + value.main.temp + '</td>' + 
		                	'<td>' + value.main.feels_like + '</td>' + 
		                	'<td>' + value.main.temp_min + '</td>' + 
		                	'<td>' + value.main.temp_max + '</td>' + 
		                	'<td>' + value.main.pressure + '</td>' + 
		                	'<td>' + value.main.sea_level + '</td>' + 
		                	'<td>' + value.main.grnd_level + '</td>' + 
		                	'<td>' + value.main.humidity + '</td>' + 
		                	'<td>' + value.main.temp_kf + '</td>' + 
		                	'<td>' + value.weather[0].main + '</td>' + 
		                	'<td>' + value.weather[0].description + '</td>' + 
		                	'<td>' + value.clouds.all + '</td>' + 
		                	'<td>' + value.wind.speed + '</td>' + 
		                	'<td>' + value.wind.deg + '</td>' + 
		                	'<td>' + value.wind.gust + '</td>' + 
		                	'<td>' + value.visibility  + '</td>' + 
		                	'<td>' + precip + '</td>' + 
		                '</tr>';
		            });
		              
		            //INSERTING ROWS INTO TABLE 
		            $("#table tbody#list").append(student);

				}, 
				error: function(error){
					console.log(error);
					// alert(error);
				}
			});


			// foursquare
			// API key: fsq30nKttA3u0GhW3D+Q2qw3nw8XyyfrW1Zyrrbp4UhduJw=
			// <?php
			// header('Access-Control-Allow-Origin: *');
			// header ("Access-Control-Expose-Headers: Content-Length, X-JSON");
			// header ("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
			// header ("Access-Control-Allow-Headers: Content-Type, Authorization, Accept, Accept-Language, X-Authorization");
			// header('Access-Control-Max-Age: 86400');
			// // header := w.Header()
			// // header.Add("Access-Control-Allow-Origin", "*")
			// // header.Add("Access-Control-Allow-Methods", "DELETE, POST, GET, OPTIONS")
			// // header.Add("Access-Control-Allow-Headers", "Content-Type, Authorization, X-Requested-With")

			// if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
			//     // The request is using the POST method
			//     header("HTTP/1.1 200 OK");
			//     return;
			// }

			// ?>

			// const options = {method: 'GET', 
			// 				headers: {Accept: 'application/json', 
			// 						Authorization: 'fsq30nKttA3u0GhW3D+Q2qw3nw8XyyfrW1Zyrrbp4UhduJw=' }};
			
			// fetch('https://api.foursquare.com/v2/venues/search?near=Tokyo,JP&limit=5&categoryId=4deefb944765f83613cdba6e&client_id=0JGJP4BDPELDOTZPMVNVSMXOTN0OAIPJDCOUFIGOJF0DOMKN&client_secret=YNPNW34REHBBABLYUG20W3KM3JWXRZQNRT3BBX5UFCZG5WFO&ll=40.7,-74&v=20220504', options) 	
			// // fetch('https://api.foursquare.com/v2/venues/search?near='+Tokyo+',JP&limit=5&categoryId=4deefb944765f83613cdba6e', options)
			// 	.then(response => response.json())
			// 	.then(response => console.log(response))
			// 	.catch(err => console.error(err));


			// Geoapify
			// API Key: e636200219714983aebc177f4d02dd69
			//get place_id
			fetch("https://api.geoapify.com/v1/geocode/search?city="+city+"&country=Japan&format=json&apiKey=e636200219714983aebc177f4d02dd69", requestOptions)
			  .then(response => response.json())
			  // .then(async (result) => { place_result = result.results[0].place_id;} )
			  .then(result => {
			  	// $('#place_id').val(result.results[0].place_id)
			  	getPlaceDetails(result.results[0].place_id)

			    // let place_result = result.results[0].place_id;

			  	// place_result
			  	// place_result = result;
			  })
			  .catch(error => console.log('error', error));

			// var place_id = $('#place_id').val();
			// console.log(place_id);
			// console.log(place_result);
			// console.log(result);
			// console.log('sample');
			// console.log(place_result.results[0].place_id);
			// var place_id = place_result.place_id;

		}

	});

function getPlaceDetails(place_id) {
	// console.log(place_id);

	fetch("https://api.geoapify.com/v2/place-details?id="+place_id+"&apiKey=e636200219714983aebc177f4d02dd69", requestOptions)
	// fetch("https://api.geoapify.com/v2/places?city="+city+"&country=Japan&categories=tourism.information&apiKey=e636200219714983aebc177f4d02dd69", requestOptions)
	  .then(response => response.json())
	  .then(result => {// console.log(result)
	  		$('#res_city').text(result.features[0].properties.city);
	  		$('#res_country').text(result.features[0].properties.country);
	  		$('#res_countrycode').text(result.features[0].properties.country_code);
	  		$('#res_address').text(result.features[0].properties.formatted);
	  		$('#res_latitude').text(result.features[0].properties.lat);
	  		$('#res_longitude').text(result.features[0].properties.lon);
	  		$('#res_website').text(result.features[0].properties.website);
	  		$('#res_sourcename').text(result.features[0].properties.datasource.sourcename);
	  		$('#res_sourceurl').text(result.features[0].properties.datasource.url);
	  	})
	  .catch(error => console.log('error', error));
}


</script>