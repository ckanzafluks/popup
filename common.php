<?php

Abstract class Common extends Module {

	protected $_html = '';
	
	public $context;
	
	public $id_lang;
	
	public $url;
	
	protected $ext = array('jpg', 'jpeg', 'png', 'gif', 'bmp'); //Images
  
	
	
	########################### INSTALLATION METHODS #######################################################
	/**
	 *
	 * @return boolean
	 */
	public function install() {
	
		if (
			!parent::install() ||
			!$this->_installTables() ||
			!$this->registerHook('displayProductButtons')
		) {
			return false;
		}
		return true;
	}
	
	/**
	 *
	 * @return boolean
	 */
	private function _installTables() {
	
		$sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_. 'popup` (
				  `id_popup` int(11) NOT NULL AUTO_INCREMENT,
				  `name` varchar(100) NOT NULL,
				  `content` text DEFAULT NULL,
				  `is_active` int(1) DEFAULT 0,
				  `always_open` int(1) DEFAULT 1,
				  `order` int(11) NOT NULL,
				  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				  PRIMARY KEY (`id_popup`)
				) ENGINE=InnoDB;';
		
		$sql .= '
		CREATE TABLE IF NOT EXISTS  `'._DB_PREFIX_. 'popup_condition` (
		  `id_popup_condition` int(11) NOT NULL AUTO_INCREMENT,
		  `id_popup` int(11) NOT NULL,
		  `id_category` tinyint(1) NOT NULL,
		  `id_category_condition` tinyint(1) NOT NULL,
		  `url` int(11) NULL,
		  `url_condition` varchar(255) NULL,
		  `id_produit` INT(11) NULL,
		  `id_produit_condition` INT(11) NULL,
		  `abondoned_cart` tinyint(1) NULL,
		  `abondoned_cart_condition` varchar(255) NULL,
		  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  PRIMARY KEY (`id_popup_condition`)
		) ENGINE=InnoDB;';
				
		return Db::getInstance()->Execute($sql);
	}
	########################### END INSTALLATION METHODS ###################################################
	
	
	
	########################### UNINSTALLATION METHODS ######################################################
	/**
	 * 
	 */
	public function uninstall(){
	
		
		return ( parent::uninstall() && $this->_uninstallTables() );
		
		//return true;
	}
	
	/**
	 * 
	 * @return boolean
	 */
	private function _uninstallTables() {
		$sql1 = 'DROP TABLE  `'._DB_PREFIX_. 'popup`;';
		$sql2 = 'DROP TABLE `'._DB_PREFIX_. 'popup_condition`;';
		return Db::getInstance()->Execute($sql1) && Db::getInstance()->Execute($sql2);
	}
	########################### END UNINSTALLATION METHODS ###################################################
	
	
	##################################### HEADERS INIT ##########################################################
	protected  function _initHeaderHTML()
	{
		$this->context->controller->addJS($this->_path .'js/pop.js');
		$this->context->controller->addCSS($this->_path .'css/pop.css');	
	}
	##################################### END HEADERS INIT #######################################################
		
	
	##################################### CATEGORIES RENDER METHODS ##############################################

	/**
	 * 
	 */
	protected function _displayHomepage() {
		$listCategories 		= $this->_getAllCategoriesInDB();
		$helper 				= new HelperList();	
		$helper->title 			= $this->l('Cron tasks');
		$helper->table 			= $this->name;
		//$helper->table 		= 'merged';
		$helper->identifier 	= 'id_category';
		$helper->no_link 		= true;
		$helper->simple_header 	= false;
		$helper->show_toolbar 	= true;
		$helper->shopLinkType 	= '';
		$helper->actions 		= array('edit');
		
		//$helper->actions = array('edit');
	
		$values 			= $listCategories;
		$helper->listTotal 	= count($values);
		//$helper->tpl_vars 	= array('show_filters' => false);
	
		/*
			$helper->toolbar_btn['new'] = array(
					'href' => $this->context->link->getAdminLink('AdminModules', false)
					.'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name
					.'&newcronjobs=1&token='.Tools::getAdminTokenLite('AdminModules'),
					'desc' => $this->l('Add new task')
			);*/
	
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
		.'&action=configure&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
	
		$list = array(
			'is_active' 	=> array(
				'title' 	=> $this->l('Activer / Désactiver'),
				'type' 		=> 'text',
				//'type' => 'date',	
			),
			'id_category' 	=> array(
				'title' 	=> $this->l('Identifiant de la catégorie'),
				'type' 		=> 'text'
			),
			'name' => array(
				'title' 	=> $this->l('Description'),
				'type' 		=> 'text'
			)
		);
		
		
		return $helper->generateList($values, $list);	
	}
	
	/**
	 *
	 * @return array
	 */
	protected  function _getAllCategoriesInDB($id=false) {
		
		$sql = 'SELECT * FROM  `'._DB_PREFIX_.'category_lang` cl ';
		if ( $id ) {
			$sql .= 'WHERE id_category='. (int) $id;
		}
		$resultDB = Db::getInstance()->executeS($sql);
		
		$aReturnRow = array();
		// We check if at least one category has an active product
		if ( !empty($resultDB) ) {
			foreach ( $resultDB as $row ) {
				$idSQL = $row["id_category"];
				$subSQL = 'SELECT * FROM  `'._DB_PREFIX_.'diet_category_text` WHERE id_diet_category=' . (int) $idSQL . ' LIMIT 1';
				$resultDbSUBSQL = Db::getInstance()->executeS($subSQL);
				
				$tmp = array('is_active'=>0);
				if ( !empty($resultDbSUBSQL) ) {
					$tmp = array('is_active' => 1);
				}
				$aReturnRow[] = array_merge($row,$tmp);				
			}
		}		
		return $aReturnRow;
	}
	
	
	
	protected function _displayFormResultAlter()
	{
		
		
		$img 			= NULL;
		$paramsMinMax 	= $this->_loadParamsForResultImcAlter($_GET['id_category']);
		if (!empty($paramsMinMax) && !empty($paramsMinMax["image"]) ) {
			$baseUrl = Tools::getHttpHost(true).__PS_BASE_URI__;			
			$img = "$baseUrl/themes/theme686/img/diet/". Tools::getValue('id_category') . "/". $paramsMinMax["image"];			
		}
		
		$fields_form_1 = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Formulaire de modulation du résultat de perte de poids'),
					//'icon' => 'icon-cogs',
				),
				'input' => array(
					array(
						'label' 		=> $this->l('Influence estimation sur perte de poids minimale (Coefficient multiplicateur)'),
						'name' 			=> 'min',
						'type' 			=> 'text',
						'required'		=> true,
						'size'			=> 30,
						'placeholder' 	=> $this->l('Exemple 0.4'),
					),
					array(
						'label' 		=> $this->l('Influence estimation sur perte de poids maximale (Coefficient multiplicateur)'),
						'name' 			=> 'max',
						'type' 			=> 'text',
						'required'		=> true,
						'size'			=> 30,
						'placeholder' 	=> $this->l('Exemple 0.10'),
					),
					array(
						'type' => 'file',
						'label' => $this->l('Image de la miniature générée(112x80)'),
						'name' => 'image',
						'value' => true,
						'thumb' => $img,
					),
					array(
						'label' 		=> '',
						'name' 			=> 'id_category',
						'type' 			=> 'hidden',
						'required'		=> true,
					),
				),
				'submit' => array(
					'title' => $this->l('Save'),
					'class' => 'button'
				)
			)
		);
		
		$helper 							= new HelperForm();
		$helper->show_toolbar 				= false;
		$helper->table 						= "result_alter";
		$lang 								= new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language 		= $lang->id;
		$helper->module 					= $this;
		$helper->allow_employee_form_lang 	= Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$helper->identifier 				= $this->identifier;
		$helper->currentIndex 				= $this->context->link->getAdminLink('AdminModules', false).'&submit=1&action=submitResultAlter&id_category='. Tools::getValue('id_category'). '&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token 						= Tools::getAdminTokenLite('AdminModules');
		
		
		$helper->tpl_vars = array(
			'fields_value' 	  => array(
				'min' 	  		=> $paramsMinMax["min"],
				'max' 	  		=> $paramsMinMax["max"],
				'link' 	  		=> '',					
				'id_category' 	=> $_GET['id_category']
			),
			'languages' 	=> $this->context->controller->getLanguages(),
			'id_language' 	=> $this->context->language->id
		);
		return $helper->generateForm(array($fields_form_1));
	}
	
	
	private function _loadParamsForResultImcAlter($idCategory = false) {
		$sql = 'SELECT * FROM  `'._DB_PREFIX_. 'diet_category_result_imc_alter` ';
		if ( !empty($idCategory) ) {
			$sql .= 'WHERE id_category='.(int) $idCategory;
		}
		$result = Db::getInstance()->executeS($sql);
		if ( $result ) {
			return array(
				'min' 	=> $result[0]["min"], 
				'max' 	=> $result[0]["max"],
				'image' => $result[0]["image"],
			);
		} else {
			return array(
				'min' => 1,
				'max' => 1,
			);
		}			
	}
	
	/**
	 * 
	 */
	protected function _displayFormCategoryAdd()
	{
		$fields_form_1 = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Ajout d\'une nouvelle entrée'),
					//'icon' => 'icon-cogs',
				),
				'input' => array(
					array(
						'label' 		=> $this->l('Libellé'),
						'name' 			=> 'libelle',
						'type' 			=> 'text',
						'required'		=> true,
						'size'			=> 80,
						'placeholder' 	=> $this->l('Exemple saisir 1 boîte 39€'),

					),
					array(
						'label' 		=> $this->l('Lien vers le produit'),
						'type' 			=> 'text',
						'name' 			=> 'link',
						'size'			=> 80,
						'placeholder' 	=> $this->l('Exemple saisir http://www.schallersupplements.com/PrestaShop/12-garcinia-cambogia')
					),
					array(
						'label' 		=> $this->l('Ordre d\'affichage'),
						'type' 			=> 'text',
						'name' 			=> 'order',
						'size'			=> 80,
						'placeholder' 	=> $this->l('Exemple saisir le chiffre 1 pour un ordre d\'affichage')
					),
					array(
						'label' 		=> '',
						'name' 			=> 'id_category',
						'type' 			=> 'hidden',
						'required'		=> true,
					),
					array(
						'type' => 'switch',
						'is_bool' => true, //retro compat 1.5
						'label' => $this->l('Active'),
						'name' => 'active',
						'values' => array(
							array(
									'id' => 'active_on',
									'value' => 1,
									'label' => $this->l('Enabled')
							),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('Disabled')
							)
						),
					),
					array(
						'type' => 'file',
						'label' => $this->l('Image'),
						'name' => 'image',
						'value' => true,
						//'thumb' => '../modules/productpaymentlogos/img/'.Configuration::get('PRODUCTPAYMENTLOGOS_IMG'),
					)
				),
				'submit' => array(
					'title' => $this->l('Save'),
					'class' => 'button'
				)
			)
		);
	
		$helper 							= new HelperForm();
		$helper->show_toolbar 				= false;
		$helper->table 						= $this->name;
		$lang 								= new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language 		= $lang->id;
		$helper->module 					= $this;
		$helper->allow_employee_form_lang 	= Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$helper->identifier 				= $this->identifier;
		
		//$helper->submit_action 			= 'titi';
		//$helper->submit_action 				= 'submitPrint';
		//$helper->submit_action 				= 'submit'.$this->name;
		
		$helper->currentIndex 				= $this->context->link->getAdminLink('AdminModules', false).'&submit=1&action=submitConfigure&id_category='. Tools::getValue('id_category'). '&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token 						= Tools::getAdminTokenLite('AdminModules');
		
		
		$helper->tpl_vars = array(
			'fields_value' 	  => array(
				'libelle' 	  => '',
				'link' 		  => '',
				'active' 	  => 1,
				'order' 	  => '',					
				'id_category' => $_GET['id_category']
			),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);
	
		return $helper->generateForm(array($fields_form_1));
	}
	
	
	##################################### END CATEGORIES RENDER METHODS ##########################################
	
	
	
	##################################### CATEGORIES CONTENT RENDER METHODS ##########################################
	/**
	 *
	 * @param string $idCategory
	 * @return
	 */
	protected function _getParametersOnCategory($idCategory=false) {
		$sql = 'SELECT * FROM  `'._DB_PREFIX_. 'diet_category_text` ';
		if ( !empty($idCategory) ) {
			$sql .= 'WHERE id_diet_category='.(int) $idCategory;
		}
		$sql .= ' ORDER BY `order` ASC ';
		return Db::getInstance()->executeS($sql);
	}
	
	/**
	 *
	 */
	protected function _displayHomepageContent() {
				
		$listContent = $this->_getParametersOnCategory(Tools::getValue('id_category'));
		
		$helper = new HelperList();
		//$helper->bulk_actions 	= true;
		//$helper->force_show_bulk_actions = true;
		
		$helper->title 			= $this->l('LISTE DES LIENS DE CETTE CATEGORIE');
		$helper->table 			= $this->name;
		$helper->identifier 	= 'id_diet_category_text';
		$helper->no_link 		= true;		
		$helper->simple_header 	= false;
		$helper->show_toolbar 	= false;
		$helper->shopLinkType 	= '';
		$helper->actions 		= array(/*'edit',*/'delete');
	
		$values 			= $listContent;
		$helper->listTotal 	= count($values);
		//$helper->tpl_vars 	= array (
			//'country' => array('title'=> $this->l('Country'),'width'=> 100 )
		//);
		
		/*
		 * 
		 $helper->toolbar_btn['delete'] = array(
 		'href' => $this->context->link->getAdminLink('AdminModules', false)
 		.'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name
 		.'&action=deleteParameter&id_diet_category_text=xxtoken='.Tools::getAdminTokenLite('AdminModules'),
 		'desc' => $this->l('Add new task')
		 );
		 *
		 *
		 *
		 *
		 *
		 */
	
		
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
		.'&action=deleteParameter&id_category=' . Tools::getValue('id_category') . '&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
	
	
		$list = array(
			'text' 			=> array(
				'title' 	=> $this->l('Libellé'),
				'type' 		=> 'text',
				'align'		=> 'center',
				'orderby' 	=> true
			),
			'url' 			=> array(
				'title' 	=> $this->l('Lien vers le produit'),
				'type' 		=> 'link'
			),
			'order' 		=> array(
				'title' 	=> $this->l('Ordre d\'affichage'),
				'type' 		=> 'text',
				'orderby' 	=> true
			),
			'is_active' 	=> array(
				'title' 	=> $this->l('Statut'),
				'type' 		=> 'text',
				'orderby' 	=> true
			),
			'image' 		=> array(
				'title' 	=> $this->l('Image produit'),
				'type' 		=> 'text',
				'orderby' 	=> true
			)
		);	
		return $helper->generateList($values, $list);	
	}
	
	protected function _displaySubmitFormsRecord($id = false) {
		$sql = 'SELECT * FROM  `'._DB_PREFIX_.'diet_category_text_log` ';
		if ( $id ) {
			$sql .= 'WHERE id_category ='. (int) $id;
		}
		$resultList = Db::getInstance()->executeS($sql);
		
		$helper = new HelperList();
		
		$helper->title 			= $this->l('Enregistrements mémorisés sur le formulaire de calcul d\'IMC');
		$helper->table 			= "records";
		$helper->identifier 	= 'id_diet_category_text_log';
		$helper->no_link 		= true;
		$helper->simple_header 	= false;
		$helper->show_toolbar 	= false;
		$helper->shopLinkType 	= '';
		$helper->actions 		= array();				
		$values 				= $resultList;
		$helper->listTotal 		= count($values);
		
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		
		$list = array(
			'sex' => array(
				'title' 	=> $this->l('Sexe'),
				'type' 		=> 'checkbox',
				'orderby' 	=> false						
			),
			'age' => array(
				'title' 	=> $this->l('Âge'),
				'type' 		=> 'checkbox',	
				'orderby' 	=> false
							
			),
			'weight' => array(
				'title' 	=> $this->l('Poids (en Kg)'),
				'type' 		=> 'text',
				'orderby' 	=> false
			),
			'height' => array(
				'title' 	=> $this->l('Taille (en cm)'),
				'type' 		=> 'text',
				'orderby' 	=> false
			),
			'imc' => array(
				'title' 	=> $this->l('Résultat du calcul de l\'imc'),
				'type' 		=> 'text',
				'orderby' 	=> false
			)
			,
			'min' => array(
				'title' 	=> $this->l('Estimation minimale de perte de poids'),
				'type' 		=> 'text',
				'orderby' 	=> false
			)
			,
			'max' => array(
				'title' 	=> $this->l('Estimation maximale de perte de poids'),
				'type' 		=> 'text',
				'orderby' 	=> false
			)
			,
			'id_category' => array(
				'title' 	=> $this->l('Identifiant de la catégorie'),
				'type' 		=> 'text',
				'orderby' 	=> false
			),
			'id_user' => array(
				'title' 	=> $this->l('Info user'),
				'type' 		=> 'text',
				'orderby' 	=> false
			)
			,
			'insert_date' => array(
				'title' 	=> $this->l('Date calcul'),
				'type' 		=> 'text',
				'orderby' 	=> false
			)
		);
		return $helper->generateList($values, $list);		
	}
	
	
	
	
	/**
	 * 
	 * @param unknown $section
	 * @return string
	 */
	protected function _getBreadcrumbs($section) {
		$sContent = '<div id="breadcrumbs">';
		
		$url = $this->context->link->getAdminLink('AdminModules', false) .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules');
		
		
		$sContent .= '<a href="'.$url.'">Accueil</a>';
		switch ($section) {
			case 'homepage':
				$sContent .= '';
				break;
				
			case 'homepagecontent':
				$categInfos = $this->_getAllCategoriesInDB(Tools::getValue('id_category'));
				if ( !empty($categInfos) ) {				
					$sContent .= ' > ' . $categInfos[0]["name"];
				}
				break;
		}
		$sContent .= '</div>';
		return $sContent;
		
	}	
	
}