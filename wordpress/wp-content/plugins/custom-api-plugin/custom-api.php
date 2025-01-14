<?php
/*
Plugin Name: Custom Weather API Integration
Description: A plugin to display weather information based on location
Version: 2.5
Author: Baifumei
*/

/**
 * Weather shortcode usage
 * [weather] defaults to bramhall for location/city
 * [weather city="Bramhall"] shows weather to the specified city
 */

add_shortcode('weather','custom_weather_api_callback');

function custom_weather_api_callback($atts) {
    $attributes  = shortcode_atts( array(
		'city' => 'Bramhall',
	), $atts );

    //grab data for curl
    //https://api.weatherapi.com/v1/current.json?key=99ba0cea174e4420a9684346251401&q=stockport
    $key = '99ba0cea174e4420a9684346251401';
    $q = $attributes['city'];
    $url = 'https://api.weatherapi.com/v1/current.json?key='.$key.'&q='.$q;

    // curl call to grab json data from api
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json'));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $json = curl_exec($curl);
    curl_close($curl);
    $data = json_decode($json, true);

    $location = $data['location'];
    $current = $data['current'];

    ob_start();
    ?>
    <strong>City:</strong><?php echo $location['name'];?><br/>
    <strong>Region:</strong><?php echo $location['region'];?><br/>
    <strong>Country:</strong><?php echo $location['country'];?><br/>
    <strong>Latitude:</strong><?php echo $location['lat'];?>&nbsp;&nbsp;<strong>Longitude:</strong><?php echo $location['lon'];?>
    <br><br>
    <img src="<?php echo $current['condition']['icon'];?>"/><br/>
    <?php echo $current['condition']['text'];?><br/><br/>
    <strong>Temperature</strong>
        <?php echo $current['temp_c'];?>Celsius&nbsp;&nbsp;<?php echo $current['temp_f'];?>Fahrenheit
        <br/>
    <strong>Wind</strong>&nbsp;&nbsp;<?php echo $current['wind_mph'];?>mph&nbsp;&nbsp;<?php echo $current['wind_kph'];?>kph<br/>
    <strong>Wind Degree</strong>&nbsp;&nbsp;<?php echo $current['wind_degree'];?><br/>
    <strong>Wind Direction</strong>&nbsp;&nbsp;<?php echo $current['wind_dir'];?><br/>
    <strong>Pressure</strong>&nbsp;&nbsp;<?php echo $current['pressure_in'];?>in&nbsp;&nbsp;<?php echo $current['pressure_mb'];?>mb<br/>
    <strong>Prescipitation</strong>&nbsp;&nbsp;<?php echo $current['precip_in'];?>in&nbsp;&nbsp;<?php echo $current['precip_mm'];?>mm<br/>
    <strong>Humidity</strong>&nbsp;&nbsp;<?php echo $current['humidity'];?><br/>
    <strong>Cloud</strong>&nbsp;&nbsp;<?php echo $current['cloud'];?><br/>
    <strong>Feels like</strong>&nbsp;&nbsp;<?php echo $current['feelslike_c'];?>Celsius&nbsp;&nbsp;<br/>
    <strong>Heat Index</strong>&nbsp;&nbsp;<?php echo $current['heatindex_c'];?>Celsius&nbsp;&nbsp;<?php echo $current['heatindex_f'];?>Fahrenheit<br/>
    <strong>Dew Point</strong>&nbsp;&nbsp;<?php echo $current['dewpoint_c'];?>Celsius&nbsp;&nbsp;<?php echo $current['dewpoint_f'];?>Fahrenheit<br/>
    <strong>Gustiness</strong>&nbsp;&nbsp;<?php echo $current['gust_mph'];?>mph&nbsp;&nbsp;<?php echo $current['gust_kph'];?>kph
    <?php
    $string = ob_get_contents();
    ob_end_clean();

    return $string;

}