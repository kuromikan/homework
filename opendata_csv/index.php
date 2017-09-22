<?php
header("Content-Type:text/html; charset=utf-8");

if($_GET['year']==103)
{
	$handle = fopen("http://stats.moe.gov.tw/files/investigate/high_graduate/103/103_1-2-2.csv", "rb");
}
else
{
	$handle = fopen("http://stats.moe.gov.tw/files/investigate/high_graduate/104/104_1-2-2.csv", "rb");
}
$contents = stream_get_contents($handle);
$data=explode("\n",$contents);
$data_arr=array();
foreach($data as $row)
{
	$data_arr[]=explode(",",$row);
}
$title=$data[0]."".$data[1]."".$data[2];
$title_arr=explode(",",$title);


fclose($handle);
?>

<html>
<head>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['line']});
	  google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart_rate);
	  google.charts.setOnLoadCallback(drawChart_type);
	  google.charts.setOnLoadCallback(drawChart_sex);

    function drawChart_rate() {

      var data = new google.visualization.DataTable();
      data.addColumn('string', '分類');
      data.addColumn('number', '畢業人數');
      data.addColumn('number', '就業人數');

      data.addRows([
	  <?php
	  for($i=3;$i<count($data_arr)-1;$i++)
	  {
		$sum=0;
		for($j=4;$j<23;$j++)
		{
			$sum+=$data_arr[$i][$j];
		}
		$type=$data_arr[$i][0]."/".$data_arr[$i][1]."/".$data_arr[$i][2];
		if($i==(count($data_arr)-2))
		{
			echo "['$type',".$data_arr[$i][3].",$sum]";
		}
		else
		{
			echo "['$type',".$data_arr[$i][3].",$sum],";
		}
	  }

	  ?>

      ]);

      var options = {
        chart: {
          title: '高中職畢業人數及就業人數',
          subtitle: ''
        },
        width: 1280,
        height: 720,
		hAxis: {
				textStyle: {fontSize: 12},
				showTextEvery: 1,
				slantedTextAngle: 90
			}
      };

      var chart = new google.charts.Line(document.getElementById('line_rate'));

      chart.draw(data, google.charts.Line.convertOptions(options));
    }
	
	function drawChart_type() {

        var data = google.visualization.arrayToDataTable([
          ['類別', '人數'],
		<?php
		 for($i=3;$i<count($data_arr)-1;$i++)
	    {
			$sum=0;
			for($j=4;$j<23;$j++)
			{
				$sum+=$data_arr[$i][$j];
			}
			$type=$data_arr[$i][0]."/".$data_arr[$i][1]."/".$data_arr[$i][2];
			if($i==(count($data_arr)-2))
			{
				echo "['$type',$sum]";
			}
			else
			{
				echo "['$type',$sum],";
			}
	    }
		?>

        ]);

        var options = {
          title: 'My Daily Activities'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_type'));

        chart.draw(data, options);
      }
	
	function drawChart_sex() {

        var data = google.visualization.arrayToDataTable([
          ['性別', '人數'],
		  <?php
			$sex_sum=array();
			$sex_sum[0]=0;
			$sex_sum[1]=0;

			  for($i=3;$i<count($data_arr)-1;$i++)
			  {
				$sum=0;
				for($j=4;$j<23;$j++)
				{
					$sum+=$data_arr[$i][$j];
				}
				if($data_arr[$i][2]=="男")
				{
					$sex_sum[0]+=$sum;
				}
				else if($data_arr[$i][2]=="女")
				{
					$sex_sum[1]+=$sum;
				}
			  }
			  
			  echo "['男',$sex_sum[0]],['女',$sex_sum[1]]";
		  ?>

        ]);

        var options = {
          title: 'My Daily Activities'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_sex'));

        chart.draw(data, options);
      }
	
	
  </script>
</head>
<body>
<?php
echo $_GET['year']
?>
<div id="line_rate"></div>
<div id="piechart_type" style="width: 900px; height: 500px;"></div>
<div id="piechart_sex" style="width: 900px; height: 500px;"></div>
<table border="1">
<tr>
<?php
for($i=0;$i<count($title_arr);$i++)
{
	echo "<td>".$title_arr[$i]."</td>";
}
?>
</tr>
<?php
for($i=3;$i<count($data_arr);$i++)
{
echo "<tr>";
	for($j=0;$j<count($data_arr[$i]);$j++)
	{
		echo "<td>".$data_arr[$i][$j]."</td>";
	}
echo "</tr>";
}
?>
</table>
<a href="index.php?year=103">103學年高級中等學校應屆畢業生就業概況</a>
<a href="index.php?year=104">104學年高級中等學校應屆畢業生就業概況</a>
</body>
</html>