jQuery(document).ready(function ($) {
	var canvas = document.getElementById("salescanvas");
	var ctx = canvas.getContext('2d');
	var thismonth = jQuery('#thismonth').val();
	Chart.defaults.global.defaultFontColor = '#545455';
	Chart.defaults.global.defaultFontSize = 12;

	
	jQuery.ajax({
        url: college_bound_ajax_object.ajax_url,
        method: "GET",
        data: {
        	action: 'get_sales_chart_data',
        	thismonth: thismonth
        },
        dataType: "json",
        success: function(response) {

        	var months = response.months;
        	var price_values = response.price_values;

			var data = {
				labels: months,
			
				datasets: [{
					label: "Sales $",
					fill: false,
					borderColor: '#46C5F8',
					data: price_values,
					borderWidth: 6, 
					pointBorderWidth: 10,
					pointHoverRadius: 8,
					pointRadius: 4,
					pointStyle: 'circle',
		      		pointHitRadius: 10,
				}
				
				]
			};

			var options = {
				title: {
					display: true,
					text: '',
					position: 'bottom'
				},
				scales: {
					yAxes: [{
						ticks: {
							beginAtZero:true,
						},

					}],
					xAxes: [
					{
						gridLines: {
							display: false,
							drawBorder: false,
						},
					}],
				},
				legend: {
					display: true
				},
			};

			var myBarChart = new Chart(ctx, {
				type: 'line',
				data: data,
				options: options,
				responsive: true,
			});
		},
        error: function(error) {
        	console.log("error");
        }

    });
    
});