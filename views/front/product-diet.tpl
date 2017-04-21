
<div class="text-center">
	<button class="demo btn btn-primary btn-large" data-toggle="modal" href="#responsive">
		{l s='Calculer mon IMC'}
	</button>
</div>

<div id="responsive" class="modal hide fade buttons_bottom_block" tabindex="-1" data-width="760">
	<div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>{l s='IMC calculator'}</h3>
    </div>
    <div class="modal-body">
        <div class="row-fluid">          
            <ul class="nav nav-tabs" role="tablist">
				<li class="nav-item">
					<a id="tab-step1" class="nav-link active" data-toggle="tab" href="#step1" role="tab">Step 1</a>
				</li>
				<li class="nav-item">
					<a id="tab-step2" class="nav-link" data-toggle="tab" href="#step2" role="tab">Step 2</a>
				</li>
				<li class="nav-item">
					<a id="tab-step3" class="nav-link" data-toggle="tab" href="#step3" role="tab">Step 3</a>
				</li>
			</ul>

			<!-- Tab panes -->
			<div class="tab-content">
				<div class="tab-pane active" id="step1" role="tabpanel">
					<form name="form-imc" id="form-imc">
						<div class="btn-group" data-toggle="buttons">
							<label class="btn btn-primary active">
								<input type="radio" name="sex" id="option1" checked value="male"> {l s='Homme'}
							</label>
							<label class="btn btn-primary">
								<input type="radio" name="sex" id="option2" value="female"> 
								{l s='Femme'}
							</label>
						</div>
						<div class="form-group">
							<label for="age" class="col-2 col-form-label">{l s='Age'}*</label>
						  	<div class="col-10">
						  		<input class="form-control" type="text" value="" id="age" name="age">
						  	</div>
						</div>
						<div class="form-group">
							<label for="weight" class="col-2 col-form-label">{l s='Poids (en Kg)'}*</label>
						  	<div class="col-10">
						  		<input class="form-control" type="text" value="" id="weight" name="weight">
						  	</div>
						</div>
						<div class="form-group">
							<label for="height" class="col-2 col-form-label">{l s='Taille (en cm)'}*</label>
						  	<div class="col-10">
						  		<input class="form-control" type="text" value="" id="height" name="height">
						  	</div>
						</div>

						<div class="line">
							<p class="buttons_bottom_block boxy">
						     	<a class="getIMC exclusive button btn_add_cart" href="Javascript:void(0);">
						     		<span>{l s='Calculer'}</span>
						     	</a>
						     	<a class="button btn_add_cart" href="Javascript:closeThickbox();">
						     		<span>{l s='Close'}</span>
						     	</a>
						    </p>
						</div>
						<input type="hidden" name="id_category" id="id_category" value="{$id_category}">
					</form>
			  	</div>
			  

				<div class="tab-pane" id="step2" role="tabpanel">
					<div class="col-xs-6 col-md-4">
						<div id="chart" style="background-color:red;float:left;"></div>
					</div>
  					<div class="col-xs-6 col-md-4">
  						<div class="bloc-left">
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
										<div class='content'></div>
									</div>
								</div>
							</div>
						</div>  						

  					</div>

					
				</div>
				<div class="tab-pane" id="step3" role="tabpanel">step3</div>
			</div>        
        </div>
    </div>
    <!--  
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn">{l s='Fermer'}</button>
        <button type="button" class="btn btn-primary">{l s='Sauvegarder'}</button>
	</div> 
	-->
</div>



<div id="hiddenModalContent" style="display:none;">
	<h4 class="modal-title"></h4>
	<span class="error" style="display:none;">
		{l s='Merci de bien vouloir renseigner tous les champs'}
	</span>
</div>





