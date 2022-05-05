<script type="text/javascript" src="https://code.jquery.com/jquery-1.7.1.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

<style type="text/css">
	body, td, th {
		font-family: sans-serif;
		position: relative;
		font-size: 0.8rem;
	}
	#table td, th {
		text-align: center;
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
		font-size: 0.9rem;
	}
	button, .back_top {
		border-radius: 6px;
		border: none;
		background-color: #4CAF50;
		color: white;
		padding: 12px 28px;
		cursor: pointer;
	}
	select {
  		position: relative;
  		width: 150px;
  		padding: 10px 1px;
	}
	.back_top {
		width: 70px;
		margin: 0 auto;
	}
	#table.dataTable tbody tr:hover {
	  background-color: #ffa;
	}
	#table.dataTable thead th {
	  background-color: #e0e0e0;
	}
</style>

<title> Weather App </title>

<head>
	<h3>WEATHER APP</h3>
	<link rel="icon" type="image/png" sizes="96x96" href="/icon-stormy-weather.png">
</head>

<body>

	<div style="float: right; margin-top: -30px;">
		<img src="icons/icon-stormy-weather.png" height="50rem" alt="icon_weather">
	</div>

	<p>Welcome, Traveller! Use this app to check the weather from the cities listed below.</p><hr>
	<p></p>
	<a name="top" id="top"></a>

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
	                <th rowspan=2>Date</th>
	                <th rowspan=2>Time</th>
	                <th colspan=9 style="background-color: #fcf4dd">Main</th>
	                <th colspan=2 style="background-color: #daeaf6">Weather</th>
	                <th rowspan=2>Clouds</th>
	                <th colspan=3 style="background-color: #ddedea">Wind</th>
	                <th rowspan=2>Visibility</th>
	                <th rowspan=2>Precipitation</th>
	            </tr>
				<tr>
	                <th>Temperature</th>
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
	                <th>Speed</th>
	                <th>Degrees</th>
	                <th>Gust</th>
	            </tr>
	        </thead>
            <tbody id = 'list'> </tbody>
		</table>
		<p>&nbsp;</p>

		<div class='back_top'><a href="#top" style="text-decoration: none; color: white;">Back to top</a></div>

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

			// OpenWeather API
			// API key: f398aaebc61d5c7cf2b6f337660a7a10
			$.ajax({
				type: 'get', 
				url: 'https://api.openweathermap.org/data/2.5/forecast?q='+city+',JP&appid=f398aaebc61d5c7cf2b6f337660a7a10', 
				success: function(result){
					
		   			$("#list").empty();

		        	// Iterating through objects
		        	var row = '';
		            $.each(result.list, function (key, value) {

						var someTableDT = $("#table").on("draw.dt", function () {
						    $(this).find(".dataTables_empty").parents('tbody').empty();
						}).DataTable();
						var table = $('#table').DataTable();
						table.clear().draw();

		            	var precip = '';
		            	if ( value.pop == 1 ) {
		            		precip = '100%';
		            	} else if ( value.pop == 0 ) {
		            		precip = '0%';
		            	}

		            	var date = new Date(value.dt_txt);
		    			const yyyy = date.getFullYear();
						let mm = date.toLocaleString('default', { month: 'long' });
						let dd = date.getDate();

						var time = date.toLocaleString('default', { hour: '2-digit', minute: '2-digit' });

						date = mm + ' ' + dd + ' ,' + yyyy;

						var weather = value.weather[0].description;
						var img_weather = '';
						if ( weather == 'clear sky' ) {
							img_weather = 'icons-sun';
						} else if ( weather == 'light rain' ) {
							img_weather = 'icons-light-rain';
						} else if ( weather == 'moderate rain' ) {
							img_weather = 'icons-moderate-rain';
						} else if ( weather == 'heavy intensity rain' ) {
							img_weather = 'icons-heavy-rain';
						} else if ( weather == 'few clouds' ) {
							img_weather = 'icons-cloud-few';
						} else if ( weather == 'scattered clouds' ) {
							img_weather = 'icons-cloud-scattered';
						} else if ( weather == 'broken clouds' ) {
							img_weather = 'icons-cloud-broken';
						} else if ( weather == 'overcast clouds' ) {
							img_weather = 'icons-cloud-overcast';
						}

						// Convert kelvin to celsius
						var temp = Math.round(value.main.temp - 273.15);
						var feels_like = Math.round(value.main.feels_like - 273.15);
						var temp_min = Math.round(value.main.temp_min - 273.15);
						var temp_max = Math.round(value.main.temp_max - 273.15);

		                // Display JSON objects as table rows
		                row += '<tr>' + 
		                	'<td style="white-space: nowrap;">' + date + '</td>' + 
		                	'<td style="white-space: nowrap;">' + time + '</td>' + 
		                	'<td>' + temp + '°C </td>' + 
		                	'<td>' + feels_like + '°C </td>' + 
		                	'<td>' + temp_min + '°C </td>' + 
		                	'<td>' + temp_max + '°C </td>' + 
		                	'<td>' + value.main.pressure + '</td>' + 
		                	'<td>' + value.main.sea_level + '</td>' + 
		                	'<td>' + value.main.grnd_level + '</td>' + 
		                	'<td>' + value.main.humidity + '</td>' + 
		                	'<td>' + value.main.temp_kf + '</td>' + 
		                	'<td><img src="icons/' + img_weather + '.png" height="30rem" alt="weather"></td>' + 
		                	'<td>' + value.weather[0].description + '</td>' + 
		                	'<td>' + value.clouds.all + '</td>' + 
		                	'<td>' + value.wind.speed + '</td>' + 
		                	'<td>' + value.wind.deg + '</td>' + 
		                	'<td>' + value.wind.gust + '</td>' + 
		                	'<td>' + value.visibility  + '</td>' + 
		                	'<td>' + precip + '</td>' + 
		                '</tr>';
		            });
		              
		            // Inserting rows to table
		            $("#table tbody#list").append(row);

				}, 
				error: function(error){
					console.log(error);
				}
			});

			// Geoapify API
			// API Key: e636200219714983aebc177f4d02dd69
			// Only get place_id, & pass to function
			fetch("https://api.geoapify.com/v1/geocode/search?city="+city+"&country=Japan&format=json&apiKey=e636200219714983aebc177f4d02dd69", requestOptions)
			  .then(response => response.json())
			  .then(result => {
			  	getPlaceDetails(result.results[0].place_id)
			  })
			  .catch(error => console.log('error', error));

		}

	});

	function getPlaceDetails(place_id) {
		// Geoapify API
		// API Key: e636200219714983aebc177f4d02dd69
		// Get selected city details
		fetch("https://api.geoapify.com/v2/place-details?id="+place_id+"&apiKey=e636200219714983aebc177f4d02dd69", requestOptions)
		  .then(response => response.json())
		  .then(result => { 
		  		// Display JSON objects as text
		  		$('#res_city').text(result.features[0].properties.city);
		  		$('#res_country').text(result.features[0].properties.country);
		  		$('#res_countrycode').text(result.features[0].properties.country_code);
		  		$('#res_address').text(result.features[0].properties.formatted);
		  		$('#res_latitude').text(result.features[0].properties.lat);
		  		$('#res_longitude').text(result.features[0].properties.lon);
		  		if ( !result.features[0].properties.website == false ) {
		  			$('#res_website').html('<a href="'+result.features[0].properties.website+'">'+result.features[0].properties.website+'</a>');
		  		} else { $('#res_website').text(''); }
		  		$('#res_sourcename').text(result.features[0].properties.datasource.sourcename);
		  		if ( !result.features[0].properties.datasource.url == false ) {
		  			$('#res_sourceurl').html('<a href="'+result.features[0].properties.datasource.url+'">'+result.features[0].properties.datasource.url+'</a>');
		  		} else { $('#res_sourceurl').text(''); }
		  	})
		  .catch(error => console.log('error', error));
	}


</script>
