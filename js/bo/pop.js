$(document).ready(function(){
	
	$("table tr td").click(function(event) {		
		//event.preventDefault();
		//alert("cliqué");
		//return false;
	});
	
	/*
	$( "input[type=submit], .button, button" ).button().click(function(event){
		alert("test");
		return false;
	});*/
	
});

/**
$(function() {
    $( "input[type=submit], button" ).button().click(function( event ) {
    	event.preventDefault();
    });
});

function displayBloc(idBloc) {
	var id = "#bloc_"+idBloc;
	$(id).toggle( "slow", function() {
		// Animation complete.
	});
}

var htmlForm = "<table>" +
					"<tr>" +
						"<td>" +
							"Libellé" +
						"</td>" +
						"<td>" +
							"Lien vers la catégorie" +
						"</td>" +
						"<td>" +
							"Status" +
						"</td>" +
						"<td>" +
							" " +
						"</td>" +
					"</tr>"+
					
					"<tr>" +
						"<td>" +
							"<input type='text' name='libelle' />" +
						"</td>" +
						"<td>" +
						"<input type='text' name='link' />" +
						"</td>" +
						"<td>" +
							"<input type='radio' name='active' value='1' checked='checked'/> Actif " +
							"<input type='radio' name='active' value='1' /> Inactif " +
						"</td>" +
						"<td>" +
							"<button class='submitLine'>Valider</button> <button class='deleteLine'>Supprimer</button>" +							
						"</td>" +
					"</tr>" +
			   "</table>";


function addLine(idBloc) {
	var id= "#lineFormContent_" + idBloc;
	$(id).append( "<form name='form_"+idBloc+"' id='form_"+idBloc+"' >" + htmlForm + "<input type='hidden' name='idcategory' value='" + idBloc + "' /></form>");
}

$(document).ready(function(){
	
	$(".deleteLine").live("click",function(event){
		$(this).parent().parent().parent().parent().remove();		
		return false;
	});
	$(".submitLine").live("click",function(event){
		return false;
	});
	
})
**/