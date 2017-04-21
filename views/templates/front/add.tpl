<div id="dynamic-breadcrumbs" style="display:none;">
	<a class="home" href="{$link->getPageLink(null, true)|escape:'html':'UTF-8'}" title="{l s='Back to homepage' mod='register'}"><span class="glyphicon glyphicon-home"></span></a>
	<a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}">{l s='My account' mod='register' mod='register'}</a>	<span class="navigation-pipe">&gt;</span>
	<a href="{$link->getPageLink(null, true)|escape:'html':'UTF-8'}module/register/homepage">{l s='Manage my warranties' mod='register'}</a> <span class="navigation-pipe">&gt;</span>	
	<span class="navigation_page">{l s='Add a new product' mod='register'}</span>
</div>

<form id="form-add" style="margin: 20px 0;border: 1px solid #ddd;padding: 40px;" action="{$link->getPageLink(null, true)|escape:'html':'UTF-8'}module/register/submit" method="POST">
	<div class="form-group">
	    <label for="exampleInputEmail1">{l s='Serial number / order number' mod='register'}</label>
	    <input type="text" class="form-control" id="serial_number" name="serial_number">
  	</div>
  	<div class="form-group">
	    <label for="exampleInputEmail1">{l s='Product name' mod='register'}</label>
	    <input type="text" class="form-control" id="article_name" name="article_name">
  	</div>  	
  	<div class="form-group" style="margin-top:14px;">
	    <label for="exampleInputEmail1">{l s='Additional informations' mod='register'}</label>
	    <textarea class="validate form-control" id="description" name="description" cols="26" rows="3"></textarea>
  	</div>
  	<div class="form-group" style="margin-bottom:0px;">
	    <label for="exampleInputEmail1">{l s='Purshase date' mod='register'}</label>
  	</div>
  	<div style="margin-bottom:10px;" class="input-group date" data-provide="datepicker" data-date-format="mm/dd/yyyy" readonly="readonly">
	    <input type="text" class="form-control" id="purchase_date" name="purchase_date">
	    <div class="input-group-addon">
	        <span class="glyphicon glyphicon-th"></span>
	    </div>
	</div>	
	<div class="form-group">
	    <label for="address">{l s='Address' mod='register'}</label>
	    <input type="text" class="form-control" id="address" name="address">
  	</div>
	<div class="form-group">
	    <label for="exampleInputEmail1">{l s='Postal Code' mod='register'}</label>
	    <input type="text" class="form-control" id="postal-code" name="postal-code">
  	</div>
  	<div class="form-group">
	    <label for="exampleInputEmail1">{l s='City' mod='register'}</label>
	    <input type="text" class="form-control" id="exampleInputEmail1">
  	</div>  	
  	<button id="button-submit" type="button" class="btn btn-default">{l s='Submit' mod='register'}</button>
</div>
  