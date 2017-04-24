

function closeThickbox() {
	tb_remove();
}


$(document).ready(function(){
	
	// Tabs activation
	$("#tab-step1").click();
	//?fc=module&module=dietconfiguration&controller=ajax&action=imc&age=32&height=190&weight=120

	// form submit
	$(".getIMC").click(function() {

		var sex 	= $("input[name='sex']").val();
		var age		= $("#age").val();
		var weight 	= $("#weight").val();
		var height 	= $("#height").val();
		var id_cat  = $("#id_category").val();
		var dataz   = "sex="+sex+"&age="+age+"&weight="+weight+"&height="+height+"&id_category="+id_cat;

		$.ajax({
			type: 'GET',
			headers: { "cache-control": "no-cache" },
			url: baseUri + '?fc=module&module=dietconfiguration&controller=ajax&action=calculIMC&'+dataz,
			//async: false,
			cache: false,
			dataType : "json",
			//data: '&controller=diet&action=getBoxyContent&ajax=true&'+dataz,
			success: function(htmlData)
			{
				console.log(htmlData);
				if ( htmlData.success == "1" ) {
					makeChart(htmlData.imc);
					$("#legend-chart").hide();
					$("#tab-step2").click();
				}
			}
		});
	});
});


function makeChart(imc) {	

	if (imc>=55)imc = 55;
	
		
		$(function () {				
			$('#chart').highcharts({

				chart: {
			        type: 'gauge',
			        plotBackgroundColor: null,
			        plotBackgroundImage: null,
			        plotBorderWidth: 0,
			        plotShadow: true,
			        width: 180,
			        height: 180,
		            spacingTop: 0,
		            spacingLeft: 0,
		            spacingRight: 0,
		            spacingBottom: 0
			    },
			    credits: {
			        enabled: false
			    },
			    title: {
			        text: ' '
			    },
			    pane: {
			        startAngle: -150,
			        endAngle: 150,
			        background: [{
			            backgroundColor: {
			                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
			                stops: [
			                    [0, '#FFF'],
			                    [1, '#333']
			                ]
			            },
			            borderWidth: 0,
			            outerRadius: '109%'
			        },		        
			        {
			            backgroundColor: {
			                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
			                stops: [
			                    [0, '#333'],
			                    [1, '#FFF']
			                ]
			            },
			            borderWidth: 0,
			            outerRadius: '107%'
			        },		         
			        {
			            backgroundColor: '#DDD',
			            borderWidth: 0,
			            outerRadius: '105%',
			            innerRadius: '103%'
			        }]
			    },

			    // the value axis
			    yAxis: {
			        min: 0,
			        max: 55,
			        minorTickInterval: 'auto',
			        minorTickWidth: 1,
			        minorTickLength: 10,
			        minorTickPosition: 'inside',
			        minorTickColor: '#666',
			        tickPixelInterval: 30,
			        tickWidth: 2,
			        tickPosition: 'inside',
			        tickLength: 10,
			        tickColor: '#666',
			        labels: {
			            step: 2,
			            rotation: 'auto'
			        },
			        title: {
			            text: ' '
			        },
			        plotBands: [{
			            from: 0,
			            to: 18.4,
			            color: '#55BF3B'
			        }, 
			        {
			            from: 18.5,
			            to: 24.9,
			            color: '#C17E43'
			        }, 
			        {
			            from: 25,
			            to: 29.9,
			            color: '#F99999'
			        }, 
			        {
			            from: 30,
			            to: 34.9,
			            color: '#F99999'
			        },
			        {
			            from: 35,
			            to: 39.9,
			            color: '#FD5B5B'
			        }, 
			        {
			            from: 40,
			            to: 55,
			            color: '#FD0303'
			        }]
			    },
			    series: [{
			        name: 'Speed',
			        data: [imc],
			        tooltip: {
			            valueSuffix: ' '
			        }
			    }]
			});		    
		});
}
