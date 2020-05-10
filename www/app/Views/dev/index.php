<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Stuff</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Include JQuery -->
	<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

	<!-- Include datatables -->
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">  
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>

</head>
<body>

	<table id="main">
	    <thead>
	        <tr>
	            <td>Name</td>
	            <td>Current Price</td>
	            <td>Current Stock</td>
	            <td>Current Accurate As</td>
	            <td>If 0 Price</td>
	            <td>If 0 Stock</td>
	            <td>If 0 Accurate As</td>
	        </tr>
	    </thead>
	    <tbody>
    		<?php

				foreach($items as $item){

					if($item['r_stock'] == null){
						continue;
					}

					echo "<tr>";
					echo "<td>" . $item['name'] . "</td>";
					echo "<td>" . $item['price'] . "</td>";
					echo "<td>" . $item['stock'] . "</td>";
					echo "<td>" . $item['accurate'] . "</td>";
					echo "<td>" . $item['r_price'] . "</td>";
					echo "<td>" . $item['r_stock'] . "</td>";
					echo "<td>" . $item['r_accurate'] . "</td>";
					echo "</tr>";
				}

			?>
	    </tbody>
	</table>
	
</body>
</html>

<script>
	
	$(document).ready( function () {
        $('#main').DataTable();
    } );

</script>
