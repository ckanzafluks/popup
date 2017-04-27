<?php

if (!defined('_PS_VERSION_'))
  exit;

require_once('commonpopup.php');

class Popup extends Commonpopup {
	
	
	/**
	 * 
	 */
	public function __construct(){
		

		$this->name 		 		  = 'popup';
		$this->tab     		 		  = 'popup';
		$this->version 		 		  = 1.0;
		$this->author 		 		  = 'KANZAFOX 5.0';
		$this->need_instance 		  = 0;
		$this->url 			 		  = $_SERVER["REQUEST_URI"];
		$this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.6');
	 
		parent::__construct();
	 	$this->displayName 	= $this->l("Module Popup");
		$this->description 	= $this->l("Module Popup. Powered by CKA");		
		$this->context 		= Context::getContext();
		$this->id_lang 		= $this->context->cookie->id_lang;

	}

	function hookDisplayFooter($params) {

				
		$this->context->controller->addJS($this->_path  . 'libs/thickbox/thickbox-compressed.js');
		$this->context->controller->addCSS($this->_path . 'libs/thickbox/thickbox.css');
		$this->context->controller->addJS($this->_path  . 'js/front/popup.js');
		

		$product = array();

		$this->context->smarty->assign(
			array(
				// 'gapi_mode' => $gapi_mode,
				// 'dashactivity_config_form' => $this->renderConfigForm(),
				//'id_category' => $product->getDefaultCategory(),
				'date_format' => $this->context->language->date_format_lite,
				'path'		  => $this->_path,
				'link'        => $this->context->link
			)
		);
		return $this->display(__FILE__, 'views/front/popup.tpl');
	}


	/**
	* Initialisation hookList
	**/
	private function _initHookList()
	{
		$hookList = Hook::getHooks();
		$html     = '<table class="table">
						<thead>
							<tr>
								<th> </th>
								<th>idHook</th>
								<th>name</th>
								<th>title</th>
								<th>description</th>
							</tr>
						</thead>
					 	<tbody>';

						foreach ( $hookList as $key => $row ) {
							$html .= '<tr>';
								$html .= '<td>';
									$html .= $key++;
								$html .= '</td>';
								$html .= '<td>';
									$html .= $row['id_hook'];
								$html .= '</td>';
								$html .= '<td>';
									$html .= $this->l($row['name']);
								$html .= '</td>';
								$html .= '<td>';
									$html .= $this->l($row['title']);
								$html .= '</td>';
								$html .= '<td>';
									$html .= $this->l($row['description']);
								$html .= '</td>';
							$html .= '</tr>';
						}
		$html     .= '</table>';
		return $html;
	}

	/**
	 * 
	 * @return string
	 */
	public function getContent()
	{



		// we add headers
		$this->_html = '';
		$this->_initHeaderHTML();


		$this->_html .= $this->_initButtonAdd();


				
		if ( Tools::isSubmit('submit') ) {

			$action = Tools::getValue('action');
			if ( $action == 'submitConfigure' ) {
								
				$libelle 	= Tools::getValue('libelle');
				$active  	= Tools::getValue('active');
				$link 	 	= Tools::getValue('link');
				$order	 	= Tools::getValue('order');
				$idCategory = Tools::getValue('id_category');
				$pathImage = $this->_moveFile(); //file treatment
				
				//var_dump($libelle, $active, $link, $idCategory);
				//die;
				// 
				if ( empty($libelle) || empty($active) || empty($link) ||empty($idCategory)  ) {					
					// error
					$this->_html .= $this->_displayError('Veuillez renseigner tous les champs');
				}
				// all is OK
				else {
					$request = "INSERT INTO `"._DB_PREFIX_. "diet_category_text` (
						`id_diet_category_text` ,
						`id_diet_category` ,
						`text` ,
						`url` ,
						`is_active` ,
						`order` ,
						`image` ,
						`datemaj`
					)
					VALUES (
						NULL ,  $idCategory,  '$libelle',  '$link', $active, $order, '$pathImage', CURRENT_TIMESTAMP
					)
					ON DUPLICATE KEY update `text`='$libelle', `url` = '$link', `is_active` = $active, `order` = $order, `image` = '$pathImage'";
					try {
						$result = Db::getInstance()->executeS($request);
					}
					catch(Exception $e) {
						var_dump($e);
					}
					if ( $result ) {
						$this->_html .= $this->_displaySuccess('Votre paramétrage a bien été ajouté');						
					}										
				}
				//$this->_html .= $this->_getBreadcrumbs('homepagecontent');
				//$this->_html .= $this->_displayFormCategoryAdd();
				//$this->_html .= $this->_displayHomepageContent();
				//$this->_html .= '<br />' . $this->_displaySubmitFormsRecord(Tools::getValue('id_category'));
				
			}
			
			elseif ( $action == "submitResultAlter" ) {
				
				$idCategory = Tools::getValue('id_category');
				$min 		= Tools::getValue('min');
				$max  		= Tools::getValue('max');
								
				$request = "INSERT INTO `"._DB_PREFIX_. "diet_category_result_imc_alter` (
								`diet_category_result_imc_alter_id` ,
								`id_category` ,
								`min` ,
								`max`
							)
							VALUES (
								NULL ,  $idCategory,  '$min',  '$max'
							)
							ON DUPLICATE KEY update `min`='$min', `max` = '$max'";
				
				$result = Db::getInstance()->executeS($request);
				if ( $result ) {
					
					
					// We check if a have been posted
					
					$pathImage = $this->_moveFile();
					if ( !empty($pathImage) ) {
						$sqlUpdate = "UPDATE `"._DB_PREFIX_. "diet_category_result_imc_alter` 
									  SET  `image` =  '$pathImage'
									  WHERE  `id_category` = $idCategory;";						
						$result = Db::getInstance()->executeS($sqlUpdate);
					}
					
					$this->_html .= $this->_displaySuccess('Votre paramétrage a bien été mis à jour');
				}
				
				
				$this->_html .= $this->_getBreadcrumbs('homepagecontent');
				$this->_html .= $this->_displayFormResultAlter();
				$this->_html .= $this->_displayFormCategoryAdd();
				$this->_html .= $this->_displayHomepageContent();
				$this->_html .= '<br />' . $this->_displaySubmitFormsRecord(Tools::getValue('id_category'));
				
				
			}
		}
		
		
		if ( $action = Tools::getValue('action') ) {
			
			switch ( $action ) {

				// Formulaire d'ajout d'une nouvelle PopUp
				case "add":
					
					$this->_html .= $this->_getBreadcrumbs('homepagecontent');
					$this->_html .= $this->_displayFormPopUpAdd();
					
					break;



				case "editParameter":
					//case "updatedietconfiguration":
					$this->_html .= $this->_getBreadcrumbs('homepagecontent');
					$this->_html .= $this->_displayFormCategoryAdd();
					break;
				
				case "deleteParameter":	
					
					$this->_deleleParameter(Tools::getValue('id_diet_category_text'));
					$this->_html .= $this->_displaySuccess('Suppression effectuée avec succès.');
					$this->_html .= $this->_getBreadcrumbs('homepagecontent');
					$this->_html .= $this->_displayFormResultAlter();
					$this->_html .= $this->_displayFormCategoryAdd();
					$this->_html .= $this->_displayHomepageContent();
					break;
					
				// add line configuration
				case "configure":
					
					$this->_html .= $this->_getBreadcrumbs('homepagecontent');
					$this->_html .= $this->_displayFormResultAlter();
					$this->_html .= $this->_displayFormCategoryAdd();
					$this->_html .= $this->_displayHomepageContent();
					$this->_html .= '<br />' . $this->_displaySubmitFormsRecord(Tools::getValue('id_category'));
					break;
			}
		}
		else {
			// Homepage
			$this->_html .= $this->_getBreadcrumbs('homepage');
			$this->_html .= $this->_displayHomepage();
		}
		//$this->_html .= $this->getListCategory();
		
		
		return $this->_html;
	}
	
	
	
	/**
	 * 
	 * @param unknown $idDietCategoryText
	 * @return Ambigous <multitype:, boolean, unknown, multitype:unknown >
	 */
	private function _deleleParameter($idDietCategoryText) {
		$sql = 'DELETE FROM `'._DB_PREFIX_. 'diet_category_text` WHERE id_diet_category_text =' . (int) $idDietCategoryText;
		return Db::getInstance()->executeS($sql);
	}
	
	
	/**
	 * 
	 * @return string
	 */
	/*
	private function _displayConfigure() {
		$id = Tools::getValue('id_category');
		$aCategory = $this->_getAllCategoriesInDB($id); 
		$aCurrentCategory = $aCategory[0]; 
		//var_dump($aCurrentCategory);die;
		$html = '<h2>Catégorie : '.$aCurrentCategory["name"].'</h2>';
		$html .= $this->_displayFormAddCategory();		
		// We get all existing parameters for each category
		$html .= $this->_displayFormListCategoriesSavedInDB();		
		return $html;
	}
	
	
	private function _displayFormListCategoriesSavedInDB() {
		$this->context->smarty->assign('idCategory',Tools::getValue('id_category'));
		$this->context->smarty->assign('listParameters',$this->_getParametersOnCategory(Tools::getValue('id_category')));
		return $this->display(__FILE__, 'listcategoriesindatabase.tpl');
	}
	*/
	
	/**
	 * 
	 * @param unknown $string
	 * @return string
	 */
	private function _displayError($string) {
		return '<div class="bootstrap">
					<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert">×</button>
						'. $string.'
					</div>
				</div>';
	}
	
	/**
	 * 
	 * @param unknown $string
	 * @return string
	 */
	private function _displaySuccess($string) {
		return '<div class="bootstrap">
					<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert">×</button>
						'. $string .'
					</div>
				</div>';
	}
	
	
 
	
  
  
  
}