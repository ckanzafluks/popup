<div class="line">
	<p id="" class="buttons_bottom_block">
    	<a data-toggle="modal" data-target="#myModal" id="goIMC" class="exclusive button btn_add_cart" href="Javascript:void(0);"> 
    		<span>{l s='Calculer mon IMC'}</span>
    	</a>
    </p>
</div>

<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
    	<!-- Modal content-->
    	<div class="modal-content">

    		<ul class="nav nav-tabs">
			  <li class="active"><a href="#">Home</a></li>
			  <li><a href="#">Menu 1</a></li>
			  <li><a href="#">Menu 2</a></li>
			  <li><a href="#">Menu 3</a></li>
			</ul>

    		
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">{l s='IMC calculator'}</h4>
			</div>

			<div class="modal-body">
				XXXXXX
				<div id="contentBody" class="thickbox">
					<div class="bloc-left first">
						<div class="circle" style="width: 20px; height: 20px;float:left;font-weight:bolder;-webkit-border-dius: 25px;-moz-border-radius: 25px;border-radius: 25px;background-color:#629F01;color:white;">
							1
						</div>
						<span class="title">
							{l s='Veuillez remplir votre profil afin d\'obtenir une estimation de la perte de votre poids'}
						</span>
						<form name="form-imc" id="form-imc">
							<div class="line">
								<label>{l s='Homme'}</label>&nbsp;<input type="radio" name="sex" value="male">
								<label>{l s='Femme'}</label>&nbsp;<input type="radio" name="sex" value="female">
							</div>
							<div class="line">
								<label>{l s='Age'}</label>
								<input type="text" name="age" id="age" value="" />	
							</div>				
							<div class="line">
								<label>{l s='Poids (en Kg)'}</label>
								<input type="text" name="weight" id="weight" value="" />	
							</div>
							<div class="line">
								<label>{l s='Taille (en cm)'}</label>
								<input type="text" name="height" id="height" value="" />	
							</div>
							<div class="line">
								<p class="buttons_bottom_block boxy">
							     	<a class="getIMC exclusive button btn_add_cart" href="Javascript:void(0);">
							     		<span>{l s='Calculer'}</span>
							     	</a>
							    </p>
							</div>
						</form>
					</div>
					
					<div class="bloc-left control-overlay control-overlay-error-box">
						<div class="overlay"></div>	
						<div class="circle">2</div>
						<span class="title">{l s='Résultat de calcul de votre IMC'}</span>
						<div class="line">
							<div id="error-box"></div>
							<div id="chart" style="margin: 0 auto"><div class='how'>?</div></div>
						</div>
						
						<div id="legend-chart">
							<div class="line">
								<div class="rectangle"></div>
								<div class="text"></div>					
							</div>
						</div>
					</div>
					
					<div class="bloc-left control-overlay">
						<div class="overlay"></div>
						<div class="circle">3</div>
						<span class="title">{l s='Traitement'}</span>			
						<div class="linex">
							<div class="image-doctor">
								<img src="{$path}/img/front/happy-doctor4.jpg?v=1" alt="{l s='Image repas'}" class="icon" />
							</div>
						</div>	
						<div class="line">
							<div class="text-podologie">
								{l s='1 capsule de garcinia cambogia avant chaque repas pendant 3 mois.'}
							</div>
						</div>
									
						<div class="linex" >
							<div class="image-food" id="image-ajax">
								<img src="{$path}/img/front/plat.jpg" alt="{l s='Image repas'}" class="icon" />
							</div>
							<div class="plus">+</div>
							<div class="image-food">
								<img src="{$path}/img/front/pills.jpg" alt="{l s='Image gellule'}" class="icon" />
							</div>
						</div>
						
						<div class="line">
							<div class="text-podologie" style="color:red;">
								{l s='Perte de poids estimée :'}
								<div id="estimation">
									<div class='how'>x</div>
									<div class='content'></div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="bloc-left control-overlay">
						<div class="overlay"></div>
						<div class="circle">4</div>
						<span class="title">{l s='Votre achat'}</span>
						<div id="text-link-prod"></div>			
					</div>
					
				</div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">
					{l s='Close IMC calculator'}
				</button>
			</div>
    	</div>
	</div>
</div>


