<?php
require('require/class.Connection.php');
require('require/class.Spotter.php');

$manufacturer = ucwords(str_replace("-", " ", $_GET['aircraft_manufacturer']));

$spotter_array = Spotter::getSpotterDataByManufacturer($manufacturer,"0,1", $_GET['sort']);

if (!empty($spotter_array))
{
  $title = 'Most Common Airlines by Country from '.$manufacturer;
	require('header.php');
  
  date_default_timezone_set('America/Toronto');
  
  print '<div class="select-item">';
	print '<form action="'.$globalURL.'/manufacturer" method="post">';
		print '<select name="aircraft_manufacturer" class="selectpicker" data-live-search="true">';
      print '<option></option>';
      $all_manufacturers = Spotter::getAllManufacturers();
      foreach($all_manufacturers as $all_manufacturer)
      {
        if($_GET['aircraft_manufacturer'] == strtolower(str_replace(" ", "-", $all_manufacturer['aircraft_manufacturer'])))
        {
          print '<option value="'.strtolower(str_replace(" ", "-", $all_manufacturer['aircraft_manufacturer'])).'" selected="selected">'.$all_manufacturer['aircraft_manufacturer'].'</option>';
        } else {
          print '<option value="'.strtolower(str_replace(" ", "-", $all_manufacturer['aircraft_manufacturer'])).'">'.$all_manufacturer['aircraft_manufacturer'].'</option>';
        }
      }
    print '</select>';
	print '<button type="submit"><i class="fa fa-angle-double-right"></i></button>';
	print '</form>';
  print '</div>';
	
	print '<div class="info column">';
  	print '<h1>'.$manufacturer.'</h1>';
  print '</div>';

  include('manufacturer-sub-menu.php');
  
  print '<div class="column">';
  	print '<h2>Most Common Airlines by Country</h2>';
  	print '<p>The statistic below shows the most common airlines by Country of origin of flights from <strong>'.$manufacturer.'</strong>.</p>';

	  $airline_array = Spotter::countAllAirlineCountriesByManufacturer($manufacturer);
      
      print '<div id="chartCountry" class="chart" width="100%"></div>
      	<script> 
      		google.load("visualization", "1", {packages:["geochart"]});
          google.setOnLoadCallback(drawChart);
          function drawChart() {
            var data = google.visualization.arrayToDataTable([
            	["Country", "# of Times"], ';
              foreach($airline_array as $airline_item)
    					{
    						$country_data .= '[ "'.$airline_item['airline_country'].'",'.$airline_item['airline_country_count'].'],';
    					}
    					$country_data = substr($country_data, 0, -1);
    					print $country_data;
            print ']);
    
            var options = {
            	legend: {position: "none"},
            	chartArea: {"width": "80%", "height": "60%"},
            	height:500,
            	colors: ["#8BA9D0","#1a3151"]
            };
    
            var chart = new google.visualization.GeoChart(document.getElementById("chartCountry"));
            chart.draw(data, options);
          }
          $(window).resize(function(){
    			  drawChart();
    			});
      </script>';

      if (!empty($airline_array))
      {
        print '<div class="table-responsive">';
            print '<table class="common-country table-striped">';
              print '<thead>';
              	print '<th></th>';
                print '<th>Country</th>';
                print '<th># of times</th>';
              print '</thead>';
              print '<tbody>';
              $i = 1;
                foreach($airline_array as $airline_item)
                {
                  print '<tr>';
                  	print '<td><strong>'.$i.'</strong></td>';
                    print '<td>';
                      print '<a href="'.$globalURL.'/country/'.strtolower(str_replace(" ", "-", $airline_item['airline_country'])).'">'.$airline_item['airline_country'].'</a>';
                    print '</td>';
                    print '<td>';
                      print $airline_item['airline_country_count'];
                    print '</td>';
                  print '</tr>';
                  $i++;
                }
               print '<tbody>';
            print '</table>';
        print '</div>';
      }
  print '</div>';
  
  
} else {

	$title = "Manufacturer";
	require('header.php');
	
	print '<h1>Error</h1>';

   print '<p>Sorry, the aircraft manufacturer does not exist in this database. :(</p>'; 
}


?>

<?php
require('footer.php');
?>