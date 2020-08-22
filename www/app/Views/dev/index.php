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
			            <th>Favourite</th>
			            <th>Name</th>
			            <th>Price</th>
			            <th>Stock</th>
			            <th>Data Age</th>
			        </tr>
			    </thead>
			    <tbody>

		    		<?php

		    			// loop through all items...
						foreach($items as $item){

							$link = 'https://europe.poporing.life/?search=:' . $item['name'];

							$checked = in_array($item['id'], $favs) ? 'checked' : '';
							$flag = in_array($item['id'], $favs) ? 1 : 0;

							?>

								<tr>
									<td class='text-right'><input <?= $checked; ?> type="checkbox" class="fav-selector" id="fav_<?= $item['id'] ?>" value="<?= $item['id'] ?>"><span id="span_id_<?= $item['id']; ?>"><?= $flag; ?><span></td>
									<td><a href="<?= $link ?>" target="_blank"><i class="fas fa-external-link-alt"></i></a> <?= $item['display_name'] ?></td>
									<td class='text-right'><?= number_format($item['price']) ?></td>
									<td class='text-right'><?= number_format($item['stock']) ?></td>
									<td class='text-right date-time'><?= $item['accurate_at'] ?></td>
								</tr>

							<?php

						}

					?>

			    </tbody>
			</table>

		</div>

	</div>
</div>
<div>
	Data thanks to <a href="https://europe.poporing.life/">Poporing.Life</a>
</div>
</body>
</html>

<script>

	$(document).ready( function () {
        $('#main').DataTable({
        	"iDisplayLength" : 50,
        	"order": [ 0, "desc" ],
        	"oSearch": {"sSearch": "Blueprint"}
        });
    } );

	// fav selected / unselected
	$('.fav-selector').change(function() {
		// on
        if(this.checked) {
        	$.ajax({
			    url: "/fav/add/"+this.value,
			});
			    $("#span_id_"+this.value).html("1");
        // off
        }else{
			$.ajax({
			    url: "/fav/remove/"+this.value,
			});
			    $("#span_id_"+this.value).html("0");
        }
    });

	$('.date-time').each(function( index ) {

		var now = new Date();

		// get current time that is put into field
		var current = $(this).text();

		// convert to date object, let date object know its a UTC timestamp
		var date = new Date(current+' UTC');

		// outputs 10/05/2020, 21:57:08
		var string = date.toLocaleString();

		var seconds = (now - date) / 1000

		var dhms = secondsToDhms(seconds);

		$(this).html(string);
		// $(this).html(seconds);
		$(this).html(dhms);

	});

	// convert seconds into days, hours, minutes, seconds
	function secondsToDhms(seconds) {

		seconds = Number(seconds);
		var d = Math.floor(seconds / (3600*24));
		var h = Math.floor(seconds % (3600*24) / 3600);
		var m = Math.floor(seconds % 3600 / 60);
		// var s = Math.floor(seconds % 60);

		var dDisplay = d > 0 ? d + (d == 1 ? "d" : "d") : "";
		var hDisplay = h > 0 ? h + (h == 1 ? "h" : "h") : "";
		var mDisplay = m > 0 ? m + (m == 1 ? "m" : "m") : "";
		// var sDisplay = s > 0 ? s + (s == 1 ? "s" : "s") : "";
		// return dDisplay + hDisplay + mDisplay + sDisplay;

		return dDisplay + hDisplay + mDisplay;

	}

</script>
