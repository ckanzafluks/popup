
//makeChart(45);
function removeOverlayBoxError() {
	$(".control-overlay-error-box").find(".overlay").remove();
}

function addOverlays() {
	if ( $(".control-overlay").find(".overlay").length == 0 )
		$(".control-overlay").append("<div class='overlay'></div>");
}
function removeOverlays(){
	$(".control-overlay").find(".overlay").remove();
}
function hideLegendChart() {
	$("#legend-chart").hide();
}
function writeLegendChart(imc,color,text) {
	$("#legend-chart").show();
	$("#legend-chart .rectangle").html(imc);
	$("#legend-chart .rectangle").css("background-color",color);
	$("#legend-chart .text").html(eval(text));
}
String.prototype.format = function() {
    var formatted = this;
    for (var i = 0; i < arguments.length; i++) {
        var regexp = new RegExp('\\{'+i+'\\}', 'gi');
        formatted = formatted.replace(regexp, arguments[i]);
    }
    return formatted;
};
function hideEffect() {
	$("#estimation .how").show();
	$("#estimation .content").html(" ");
}
function writeEffect(min,max) {
	$("#estimation .how").hide();
	$("#estimation .content").html(DATABOX_ERROR_WEIGHT_LOSS.format(min, max));
}
function getContentBoxy() {
	var htmlContent = "";
	$.ajax({
		type: 'GET',
		headers: { "cache-control": "no-cache" },
		url: baseUri + '?rand=' + new Date().getTime(),
		async: false,
		cache: false,
		dataType : "html",
		data: '&controller=diet&action=getBoxyContent&ajax=true',
		success: function(htmlData)
		{
			htmlContent = htmlData;
		}
	});
	return htmlContent;
}

function checkAllFields() {
		
	var errorMsg='';
	if ( !$('input[name="sex"]').is(':checked') ) {
		errorMsg += "<p>" + DATABOX_ERROR_SEX + "</p>"; 
	}	
	
	if ( $("#age").val().length == "0" ) {
		errorMsg += "<p>" + DATABOX_ERROR_AGE + "</p>"; 
	}
	if ( $("#weight").val().length == "0" ) {
		errorMsg += "<p>" + DATABOX_ERROR_WEIGHT + "</p>"; 
	}
	if ( $("#height").val().length == "0" ) {
		errorMsg += "<p>" + DATABOX_ERROR_HEIGHT; 
	}
	
	// if has errors
	if ( errorMsg.length > 0 ) {		
		$("#error-box").show();
		addOverlays();
		removeOverlayBoxError();
		$("#chart").html(" ");
		$("#error-box").html(errorMsg);
		hideLegendChart();
		hideEffect();
		return false;
	} 
	else {
		removeOverlays();
		$("#error-box").html(" ");
		$("#error-box").hide();
	}
	//alert(errorMsg);
	return true;
}

function openFancyBox() {
	//if (!!$.prototype.fancybox)
	    $.fancybox.open([
	    {
	        type: 'inline',
	        title: "test",
	        autoScale: true,
	        titleShow : true,
	        /*minHeight: 30,*/
	        width: 800,
	        height: 800,
	        /*overlayColor: ""*/
	        modal: false,
	        content: $("#hiddenModalContent").html()
	    }],
		{
	        padding: 10
	    });
}

$(document).ready(function(){
	
	// we hide other steps ( step2 step3 )
	$("#step 2").hide();
	$("#step 3").hide();
	


	$("#goIMC").click(function(){
		//alert("cliqué");
		
		//checkAllFields();
	});

	return;


	if ( DATABOX_CAN_LOAD ) {
		//if ( DATABOX_AUTO_LOAD == "1" ) setTimeout( "$('#clickBoxy').click();",1000);
		setTimeout( "$('#clickBoxy').click();",1000);
	}
	
	$('.boxy').click(function() {
		openFancyBox();	
	});	
	
	$('.getIMC').live("click",function() { 
		
		if ( !checkAllFields() ) {
			return false;
		}
		
		try {	
			var data = $(this).parent().parent().parent().parent().find('#form-imc').serialize();
			$.ajax({
				type: 'GET',
				headers: { "cache-control": "no-cache" },
				url: baseUri + '?rand=' + new Date().getTime(),
				async: true,
				cache: false,
				dataType : "json",
				data: '&controller=diet&action=goIMC&ajax=true&' + data + 'category=' + DATABOX_CATEGORY + '&id_category=' + DATABOX_ID_CATEGORY,
				success: function(jsonData)
				{					
					//console.log(jsonData);
					if (jsonData.success === 1) {
						//console.log(jsonData);
						var imcReturned = parseInt(jsonData.imc);
						//var weightToLost = parseInt(jsonData.weightToLost);						
						makeChart(imcReturned);
						if ( imcReturned > 55 ) {
							imcReturned = ">" + 55;
						}
						writeLegendChart(imcReturned,jsonData.color,jsonData.text);
						writeEffect(jsonData.min,jsonData.max);
						$("#text-link-prod").html(jsonData.textLink.format(DATABOX_BUY));
					}
					else {
						if (!!$.prototype.fancybox) {
						    $.fancybox.open([
						        {
						            type: 'inline',
						            autoScale: true,
						            minHeight: 30,
						            content: '<p class="fancybox-error">' + DATABOX_ERROR + '</p>'
						        }
						    ], {
						        padding: 0
						    });
						}
					}					
				},
				error: function(returnx)
				{
					//var error = "";
					//alert(error);
					//console.log(returnx);
					
				}
			});	
			
		} catch (e) {
			console.log(e);
		}		
	});	
	
});

/*
console.log(DATABOX_IMC_INT);
console.log(decodeURIComponent(DATABOX_IMC_INT));
var tmp = decodeURIComponent(DATABOX_IMC_INT);
tmp = DATABOX_IMC_INT.replace("%3A",":");
tmp = tmp.replace("%255B","[");
tmp = tmp.replace("%257B","{");
tmp = tmp.replace("%253A",":");
tmp = tmp.replace("%253A",":");
tmp = tmp.replace("%252C",",");
*/



function makeChart(imc) {	

	if (imc>=55)imc = 55;
	try {
		
		$(function () {				
			$('#chart').highcharts({			
			    chart: {
			        type: 'gauge',
			        plotBackgroundColor: null,
			        plotBackgroundImage: null,
			        plotBorderWidth: 0,
			        plotShadow: false,
		            width: 225,
		            height: 225
			    },
			    
			    credits: {
			        enabled: false
			    },
			    
			    exporting: { 
			    	enabled: false 
			    },
			    
			    title: {
			        text: ''
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
			            borderWidth: 1,
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
			            text: ''
			        },
			        plotBands: eval(DATABOX_IMC_INT) 
			        
			    },
			
			    series: [{
			        name: DATABOX_IMC,
			        data: [imc],
			        tooltip: {
			            //valueSuffix: ' km/h'
			        	valueSuffix: ' '
			        }
			    }/*, {
		            showInLegend: true,
		            name: "Bad",
		            color: '#55BF3B' // green   
		        }, {
		            showInLegend: true,
		            name: "Good",
		            color: '#DDDF0D' // yellow  
		        }, {
		            showInLegend: true,
		            name: "Excellent",
		            color: '#DF5353' // red  
		        }*/]
			
			}, 
			// Add some life
			function (chart) {
				if (!chart.renderer.forExport) {
					/*
					console.log(imc);
					var maxIMC = 45;
					
					if ( imc >= maxIMC ) {
					    setInterval(function () {
					        var point = chart.series[0].points[0],
					            imc,
					            inc = Math.round((Math.random() - 0.5) * 20);
					        
					        var newVal = point.y + inc;
					        if (newVal < 0 || newVal > 200) {
					            newVal = point.y - inc;
					        }
					        
					        point.update(newVal);
					        
					    }, 3000);
					}*/
				}
			});  
			
		    
		});
	} catch(error) {
		alert(error);
	}	
}




