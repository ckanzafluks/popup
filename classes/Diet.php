<?php
class Diet
{
		
	/**
	 * 
	 * @var int
	 */
	public $weight;
	
	/**
	 * 
	 * @var int
	 */
	public $height;
	
	/**
	 *
	 * @var int
	 */
	public $lang;
		
	/**
	 * 
	 * @var unknown
	 */
	public $sex;	
	
	/**
	 * 
	 * @var unknown
	 */
	public $age;
	
	/**
	 * 
	 * @var unknown
	 */
	public $imc;
	
	/**
	 * 
	 * @var unknown
	 */
	public $lostMin;
	
	/**
	 * 
	 * @var unknown
	 */
	public $lostMax;
	
	/**
	 * 
	 * @var array
	 */
	public $intervalInfos;
	
	/**
	 * 
	 * @var unknown
	 */
	private $category;
	
	/**
	 * 
	 * @var unknown
	 */
	public  $image;
		
	/**
	 * 
	 * @var unknown
	 */
	const MIN = 0.10;
	
	/**
	 * 
	 * @var unknown
	 */
	const MAX = 0.15;
	
	
	const CORPULENCE_ACCRUE 		 = 'CORPULENCE_ACCRUE';
	const CORPULENCE_NORMALE 		 = 'CORPULENCE_NORMALE';
	const SURPOIDS 			 		 = 'SURPOIDS';
	const OBESITE_MODEREE 			 = 'OBESITE_MODEREE';
	const OBESITE_CLASSES_2 		 = 'OBESITE_CLASSES_2';
	const OBESITE_MORBIDE_OU_MASSIVE = 'OBESITE_MORBIDE_OU_MASSIVE';
	
	/**
	 * 
	 * @param unknown $weight
	 * @param unknown $height
	 * @param unknown $sex
	 * @param unknown $age
	 * @param string $lang
	 */
	public function __construct($weight, $height, $sex, $age, $lang = 'fr',$id_category)
	{		
		$this->weight 	= (int) $weight;
		$this->height 	= (double) ($height/100);
		$this->sex		= (string) $sex;
		$this->age		= (int) $age;
		$this->lang		= (string) $lang;
		$this->category = (int) $id_category;
		
		// language conversion
		$this->_convertIMC();
		
		// auto calcul IMC
		$this->calculateIMC();

		// Interval info
		$this->calculateIntervalInfos();
		
		// auto calculate weight to lost
		$this->calculateWeightLost();
		
	}
	
	/**
	 * 
	 * @param string $idCategory
	 * @return array 
	 */
	private function _loadParamsForResultImcAlter($idCategory = false) {
				
		try {
			$sql = 'SELECT * FROM  `'._DB_PREFIX_. 'diet_category_result_imc_alter`';
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
					'min' 	=> 1,
					'max' 	=> 1,
					'image' => '',
				);
			}
		}
		catch ( Exception $e ) {
			return array(
				'min' 	=> 1,
				'max' 	=> 1,
				'image' => '',
			);			
		}
	}
	
	/**
	 * 
	 */
	private function calculateIntervalInfos() {
		
		if ( $this->imc ) {

			$aIntervals 		 = self::getImcIntervals();
			$this->intervalInfos = end($aIntervals);
			foreach ( $aIntervals as $aRow ) {
				$min = $aRow["from"];
				$max = $aRow["to"];
				if ( $this->imc >= $min AND $this->imc <= $max ) {
					$this->intervalInfos = $aRow; 
					break;
				}
			}			
		}		
	}
	
	
	
	/*
	 * 
	 * IMC = poids/taille²
	 * 
	 * 	Maigreur	
		< 18,5
		 	Normal	
		18,5 à 24,9
		 	Surpoids	
		25 à 29,9
		 	Obésité	
		> 30
		 	Obésité massive	
		> 40
	 */
	private function calculateIMC() {		
		$imc = $this->weight / ($this->height * $this->height);
		$this->imc = ceil($imc);
	}
	
	/**
	 * @todo: calculer le poids à perde en fonction de l'IMC
	 * @return void
	 */
	private function calculateWeightLost() {
		if ( !empty($this->imc) ) {
			$this->lostMin = ceil( $this->imc * self::MIN );
			$this->lostMax = ceil( $this->imc * self::MAX );		
		}
		
		$aParamsAjustement = $this->_loadParamsForResultImcAlter($this->category);
		if ( !empty($aParamsAjustement) ) {
			$this->lostMin 		= ceil($this->lostMin * $aParamsAjustement["min"]);
			$this->lostMax 		= ceil($this->lostMax * $aParamsAjustement["max"]);
			if ( !empty($aParamsAjustement["image"]) ) {
				$baseUrl 	    = Tools::getHttpHost(true).__PS_BASE_URI__;
				$this->image	= "<img src='$baseUrl/module/dietconfig/img/front/". $this->category . "/". $aParamsAjustement["image"] . "' />";
			}
		}
	}
	
	/**
	 * 
	 */
	private function _convertIMC() {
		if ( $this->lang == 'en' ) {
			// Weight conversion => 1kg => 2.2046226 livres
			$tmpWeight = ($this->weight / 2.2046226);
			$this->weight = $tmpWeight;
			 
			// Height conversion => 100 cm => 3.28084 ft
			$tmpHeight = ($this->height*100) / 3.28084;
			$this->height = $tmpHeight;
		}
	}
	
	/**
	 * 
	 * @return multitype:number string multitype:number string
	 */
	public static function getImcIntervals() {

		$aInterval[] = array(
			'from'	=> 0,
			'to'	=> 18.4, 	
			'color'	=> '#55BF3B',
			'text'	=> self::CORPULENCE_ACCRUE,		
		);
		$aInterval[] = array(
			'from'	=> 18.5,
			'to'	=> 24.9,
			'color'	=> '#C17E43',
			'text'	=> self::CORPULENCE_NORMALE,
		);
		$aInterval[] = array(
			'from'	=> 25,
			'to'	=> 29.9,
			'color'	=> '#F99999',
			'text'	=> self::SURPOIDS,
		);
		$aInterval[] = array(
			'from'	=> 30,
			'to'	=> 34.9,
			'color'	=> '#F99999',
			'text'	=> self::OBESITE_MODEREE,
		);
		$aInterval[] = array(
			'from'	=> 35,
			'to'	=> 39.9,
			'color'	=> '#FD5B5B',
			'text'	=> self::OBESITE_CLASSES_2,
		);			
		$aInterval[] = array(
			'from'	=> 40,
			'to'	=> 55,
			'color'	=> '#FD0303',
			'text'	=> self::OBESITE_MORBIDE_OU_MASSIVE,
		);
		return $aInterval;
	}
		
	
	/**
	 * 
	 * @param unknown $idCategory
	 * @return boolean
	 */
	public static function isActive($idCategory) {
		$sql = 'SELECT * FROM  `'._DB_PREFIX_. 'diet_category_text`
					WHERE id_diet_category='.(int) $idCategory . ' AND is_active = 1';
		$resultDB = Db::getInstance()->executeS($sql);
		if ( !empty($resultDB) ) {
			return true;
		}
		return false;
	}
	
	
	
	/**
	 * 
	 * @return Ambigous <NULL, string>
	 */
	public function displayPrices() {
		
		$aTxtReturn = NULL;
		if ( !empty($this->category) ) {

			$sql = 'SELECT * FROM  `'._DB_PREFIX_. 'diet_category_text` 
					WHERE id_diet_category='.(int) $this->category . ' ORDER BY `order` ASC';
			$resultDB = Db::getInstance()->executeS($sql);
		
			if ( !empty($resultDB) ) {				
				$aTxtReturn .= '<div class="linkProducts">';
					$i = 0;
					foreach ( $resultDB as $lineDB ) {
						$i++;
						//$aTxtReturn .= '<div class="lineLinks">';
							
						$aTxtReturn .= '<div class="lineLinks">';
							$aTxtReturn .= '<a href="'. $lineDB["url"] . '?b=0" >';
								$aTxtReturn .= 	$lineDB["text"];
							$aTxtReturn .= '</a>';
						$aTxtReturn .= '</div>';
						
						$baseUrl = Tools::getHttpHost(true).__PS_BASE_URI__;
						$aTxtReturn .= '<div class="lineLinks hover">';
							$aTxtReturn .= "<img src='$baseUrl/themes/theme686/img/diet/". $lineDB["id_diet_category"] . "/". $lineDB["image"] . "?v=1' /><br />";
							
							$aTxtReturn .= '<span class="buttons_bottom_block boxy">';
								$aTxtReturn .= '<a class="exclusive button" href="' . $lineDB["url"] . '?b=0">';
									$aTxtReturn .= '{0}';
								$aTxtReturn .= '</a>';
							$aTxtReturn .= '</span>';
							
						$aTxtReturn .= '</div>';
							
							// Image product							
						//$aTxtReturn .= '</div>';
					}
				$aTxtReturn .= '</div>';
			}
		}
		return $aTxtReturn;
	}
	

	public function logData()
	{
			
		$context = Context::getContext();
		$idUser  = 'Inconnu';
		if ( !empty( $context->customer->id ) ) {
			$idUser = $context->customer->id . '****' . $context->customer->firstname . '****' . $context->customer->lastname;
		}
				
		$sex 		= $this->sex;
		$age 		= $this->age;
		$weight 	= $this->weight;
		$height 	= $this->height;
		$imc 		= $this->imc;
		$max 		= $this->lostMax;
		$min 		= $this->lostMin;
		$idCategory = $this->category;		
		
		
		$sql = "INSERT INTO `" . _DB_PREFIX_ . "diet_category_text_log` (
					`id_diet_category_text_log`, 
					`sex`, 
					`age`, 
					`weight`,
					`height`, 
					`imc`, 
					`max`, 
					`min`, 
					`id_category`, 
					`insert_date`,	
					`id_user`
				)
				VALUES (
					NULL , 
					'$sex',  
					'$age', 
					'$weight',  
					'$height',  
					'$imc',  
					'$max',  
					'$min',
					'$idCategory', 
					CURRENT_TIMESTAMP ,  
					'$idUser'
				)";		
		
		return Db::getInstance()->executeS($sql);		
		
	}
	
	
	
	
	
	// 1kg => 2.2046226 livres	
	// 100 cm => 3.28084 ft

	
	
}

