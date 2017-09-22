<?php
header("Content-Type:text/html; charset=utf-8");
$handle = fopen("http://data.ntpc.gov.tw/api/v1/rest/datastore/382000000A-000831-001", "rb");
$contents = stream_get_contents($handle);
//echo $contents;
fclose($handle);
$people_arr=json_decode($contents, true);
//print_r($people_arr);

/*
Field1:年;
Field2: 人口結構比-幼年-0-14歲 男;
Field3: 人口結構比-幼年-0-14歲 女;
Field4: 人口結構比-青壯年-15-64歲 男;
Field5: 人口結構比-青壯年-15-64歲 女;
Field6: 人口結構比-老年-65歲以上 男;
Field7: 人口結構比-老年-65歲以上 女;
Field8: 平均壽命 男;
Field9: 平均壽命 女
*/

?>
<html>
<head>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
	  google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart_M);
	  google.charts.setOnLoadCallback(drawChart_F);
	  google.charts.setOnLoadCallback(drawVisualization_age);

      function drawChart_M() {
        var data = google.visualization.arrayToDataTable([
          ['Year', '0-14歲 男', '15-64歲 男', '65歲以上 男'],
		  <?php
		  for($i=0;$i<count($people_arr['result']['records']);$i++)
		  {
			if($i==count($people_arr['result']['records'])-1)
			{
				echo "['".$people_arr['result']['records'][$i]['Field1']."',".$people_arr['result']['records'][$i]['Field2'].",".$people_arr['result']['records'][$i]['Field4'].",".$people_arr['result']['records'][$i]['Field6']."]";
			}
			else
			{
				echo "['".$people_arr['result']['records'][$i]['Field1']."',".$people_arr['result']['records'][$i]['Field2'].",".$people_arr['result']['records'][$i]['Field4'].",".$people_arr['result']['records'][$i]['Field6']."],";
			}
		  }
		  ?>
        ]);

        var options = {
          chart: {
            title: '人口比',
            subtitle: '男性',
          }
        };

        var chart = new google.charts.Bar(document.getElementById('columnchart_material_M'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
	  
	     function drawChart_F() {
        var data = google.visualization.arrayToDataTable([
          ['Year', '0-14歲 女', '15-64歲 女', '65歲以上 女'],
		  <?php
		  for($i=0;$i<count($people_arr['result']['records']);$i++)
		  {
			if($i==count($people_arr['result']['records'])-1)
			{
				echo "['".$people_arr['result']['records'][$i]['Field1']."',".$people_arr['result']['records'][$i]['Field3'].",".$people_arr['result']['records'][$i]['Field5'].",".$people_arr['result']['records'][$i]['Field7']."]";
			}
			else
			{
				echo "['".$people_arr['result']['records'][$i]['Field1']."',".$people_arr['result']['records'][$i]['Field3'].",".$people_arr['result']['records'][$i]['Field5'].",".$people_arr['result']['records'][$i]['Field7']."],";
			}
		  }
		  ?>
        ]);

        var options = {
          chart: {
            title: '人口比',
            subtitle: '女性',
          }
        };

        var chart = new google.charts.Bar(document.getElementById('columnchart_material_F'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
	  
	  	     
	   function drawVisualization_age() {
        var data = google.visualization.arrayToDataTable([
         ['Year', '平均壽命 男', '平均壽命 女', '平均'],
       	 <?php
		  for($i=0;$i<(count($people_arr['result']['records'])-1);$i++)
		  {
			if($i==count($people_arr['result']['records'])-2)
			{
				echo "['".$people_arr['result']['records'][$i]['Field1']."',".$people_arr['result']['records'][$i]['Field8'].",".$people_arr['result']['records'][$i]['Field9'].",".($people_arr['result']['records'][$i]['Field8']+$people_arr['result']['records'][$i]['Field9'])/2 ."]";
			}
			else
			{
				echo "['".$people_arr['result']['records'][$i]['Field1']."',".$people_arr['result']['records'][$i]['Field8'].",".$people_arr['result']['records'][$i]['Field9'].",".($people_arr['result']['records'][$i]['Field8']+$people_arr['result']['records'][$i]['Field9'])/2 ."],";
			}
		  }
		  ?>
      ]);

    var options = {
      title : '平均壽命',
      vAxis: {title: '年齡'},
      hAxis: {title: '年'},
      seriesType: 'bars',
      series: {2: {type: 'line'}}
    };

    var chart = new google.visualization.ComboChart(document.getElementById('columnchart_material_age'));
    chart.draw(data, options);
  }
    </script>
	
</head>
<body>
<div id="columnchart_material_M" style="width: 1280; height: 500px;"></div>
<div id="columnchart_material_F" style="width: 1280; height: 500px;"></div>
<div id="columnchart_material_age" style="width: 1280; height: 500px;"></div>
<table border="1">
<tr><td>年</td><td>人口結構比-幼年-0-14歲 男</td><td>人口結構比-幼年-0-14歲 女</td><td>人口結構比-青壯年-15-64歲 男</td><td>人口結構比-青壯年-15-64歲 女</td><td>人口結構比-老年-65歲以上 男</td><td>人口結構比-老年-65歲以上 女</td><td>平均壽命 男</td><td>平均壽命 女</td></tr>
<?php
	for($i=0;$i<(count($people_arr['result']['records']));$i++)
	{
		echo "<tr>";
		for($j=0;$j<count($people_arr['result']['records'][$i]);$j++)
		{
			$str="Field".($j+1);
			echo "<td>".$people_arr['result']['records'][$i][$str]."</td>";
		}
		echo "</tr>";
	}
?>
</table>
</body>
</html>