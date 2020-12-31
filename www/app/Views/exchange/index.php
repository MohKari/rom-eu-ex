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

	<!-- Chart JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" integrity="sha512-d9xgZrVZpmmQlfonhQUvTR7lMPtO7NkZMkA0ABN3PHCbKA5nqylQ/yWlFAyY6hYgdF1Qh6nYiuADWwKB4C2WSw==" crossorigin="anonymous"></script>


</head>
<body>

* Note: Bug when you open your second chart, it doesnt properly destroy the old one, so sometimes when you hover, it flicks to the last chart, its annoying :(

<div class="container">


	<div class="row">

		<div class="card card-body bg-light">

			<table id="main" class="table table-striped table-bordered">
			    <thead>
			        <tr>
			            <th><i class="fas fa-star" style="color:goldenrod;"></i></th>
			            <th>Name</th>
			            <th>Lowest</th>
			            <th>Current</th>
			            <!-- <th>Stock</th> -->
			            <th>Bus %</th>
			            <th>Last Scan</th>
			            <th>History</th>
			        </tr>
			    </thead>
			    <tbody>

		    		<?php

		    			// loop through all items...
						foreach($items as $item){

							if($item['MinPrice'] == 0 || $item['Enabled'] == 0){
								continue;
							}

							// $link = 'https://europe.poporing.life/?search=:' . $item['name'];

							$checked = in_array($item['ItemId'], $favs) ? 'checked' : '';
							$flag = in_array($item['ItemId'], $favs) ? 1 : 0;

							if ($item['CurrentPrice'] == 0 || $item['CurrentPrice'] == 0){
								$buss = "-";
							}else{
								$buss = round($item['CurrentPrice'] / $item['MinPrice'], 2);
							}

							?>

								<tr>
									<td class='text-right'><input <?= $checked; ?> type="checkbox" class="fav-selector" id="fav_<?= $item['ItemId'] ?>" value="<?= $item['ItemId'] ?>"><span style="display:none;" id="span_id_<?= $item['ItemId']; ?>"><?= $flag; ?><span></td>
									<td><?= $item['DisplayName'] ?></td>
									<td class='text-right'><?= number_format($item['MinPrice']) ?></td>
									<td class='text-right'><?= number_format($item['CurrentPrice']) ?></td>
									<!-- <td class='text-right'> - </td> -->
									<td class='text-right'><?= $buss ?></td>
									<td class='text-right date-time'><?= $item['LastUpdate'] ?></td>
									<td class='text-right' onClick='getChart(<?= $item['ItemId']; ?>)'><button type="button" class="btn btn-primary"><i class="fas fa-chart-line"></i></button></td>
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
	Data thanks to BORF!</a>
</div>
</body>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Item Name</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<canvas id="myChart" width="800" height="250"></canvas>
      	<canvas id="myChart2" width="800" height="250"></canvas>
      </div>
    </div>
  </div>
</div>

</html>

<script>

	function getChart(id){

		$.ajax({
		    url: "/history/" + id,
		}).done(function(data_) {

			var data = data_.data;
			var name = data_.name;

			var zenys = [];
			var volumes = [];
			var times = [];

			// draw chart from this data
			for (var i = data.length - 1; i >= 0; i--) {

				var entry = data[i];

				if(entry.Zeny != 0 && entry.Volume != 0){

					zenys.push(entry.Zeny);
					volumes.push(entry.Volume);

					var unixTimestamp = entry.Timestamp
					var milliseconds = unixTimestamp * 1000 // 1575909015000
					var dateObject = new Date(milliseconds)
					var humanDateFormat = dateObject.toLocaleString() //2019-12-9 10:30:15

					times.push(humanDateFormat);

				}

			}

			var ctx = $('#myChart');
			var myChart = new Chart(ctx, {
			    type: 'line',
				  data: {
				    labels: times,
				    datasets: [{
				        data: zenys,
				        label: "Zeny",
				        borderColor: "#3e95cd",
				        fill: false
				      }
				    ]
				  },
				  options: {
				  }
				});

			var ctx2 = $('#myChart2');
			var myChart2 = new Chart(ctx2, {
			    type: 'line',
				  data: {
				    labels: times,
				    datasets: [{
				        data: volumes,
				        label: "Volume",
				        borderColor: "#8e5ea2",
				        fill: false
				      }
				    ]
				  },
				  options: {
				  }
				});

			$("#myModal .modal-title").html(name);
			$('#myModal').modal('show');

		});

	}

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

		var dDisplay = d > 0 ? d + (d == 1 ? "d " : "d ") : "";
		var hDisplay = h > 0 ? h + (h == 1 ? "h " : "h ") : "";
		var mDisplay = m > 0 ? m + (m == 1 ? "m " : "m ") : "";
		// var sDisplay = s > 0 ? s + (s == 1 ? "s" : "s") : "";
		// return dDisplay + hDisplay + mDisplay + sDisplay;

		return dDisplay + hDisplay + mDisplay;

	}

</script>
