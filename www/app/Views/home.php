<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Stuff</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Include JQuery -->
	<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

	<!-- Bootstrap -->
	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js" integrity="sha384-6khuMg9gaYr5AxOqhkVIODVIvm9ynTT5J4V1cfthmT+emCG6yVmEZsRHdxlotUnm" crossorigin="anonymous"></script>

	<!-- Include datatables -->
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">  
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>

	<!-- Font Awesome -->
	<script src="https://kit.fontawesome.com/4efc5f17e9.js" crossorigin="anonymous"></script>

</head>
<body>

<div class="container">


	<div class="row">

		<div class="card card-body bg-light">

			<table id="main" class="table table-striped table-bordered">
			    <thead>
			        <tr>
			            <th>Name</th>
			            <th>Price</th>
			            <th>Stock</th>
			            <th>Accurate As Of (UTC)</th>
			        </tr>
			    </thead>
			    <tbody>
		    		<?php

		    			// loop through all items...
						foreach($items as $item){

							// if item has NEVER had stock, ignore it
							if($item['r_stock'] == null || $item['r_stock'] == 0){
								continue;
							}

							// start to create some table rows
							?>
				

							<?php 
								// if item price is not 0, display current data
								if($item['price'] != 0){
							?>

								<tr>	
									<td><a href="<?= $item['link'] ?>" target="_blank"><i class="fas fa-external-link-alt"></i></a> <?= $item['name'] ?></td>
									<td class='text-right'><?= $item['price'] ?></td>
									<td class='text-right'><?= $item['stock'] ?></td>
									<td class='text-right date-time'><?= $item['accurate'] ?></td>
								</tr>

							<?php
								// else display most recent data that had values
								}else{
							?>

								<tr class='text-danger font-weight-bold'>
									<td><a href="<?= $item['link'] ?>" target="_blank"><i class="fas fa-external-link-alt"></i></a> <?= $item['name'] ?></td>
									<td class='text-right'><?= $item['r_price'] ?></td>
									<td class='text-right'><?= $item['r_stock'] ?></td>
									<td class='text-right date-time'><?= $item['r_accurate'] ?></td>
								</tr>

							<?php
								}
							?>

							<?php
						}
					?>
			    </tbody>
			</table>
		
		</div>

	</div>
</div>

</body>
</html>

<script>
	
	$(document).ready( function () {
        $('#main').DataTable({
        	"iDisplayLength" : 50,
        	"order": [ 1, "asc" ]
        });
    } );

	// $('.date-time').each(function( index ) {
	// 	var current = $(this).text();

	// 	var date = new Date(current+' UTC');
	// 	// console.log(date)
	// 	// var new_ = convertUTCDateToLocalDate(current);
 //  		// console.log(new_);
	// });

	// function convertUTCDateToLocalDate(date) {
	//     var newDate = new Date(date.getTime() - date.getTimezoneOffset()*60*1000);
	//     return newDate;   
	// }
</script>
