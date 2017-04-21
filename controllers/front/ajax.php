<?php
/**
 * StorePrestaModules SPM LLC.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 *
 *
 *
 * @author    StorePrestaModules SPM
 * @category content_management
 * @package blockguestbook
 * @copyright Copyright StorePrestaModules SPM
 * @license   StorePrestaModules SPM
 */

class DietconfigurationAjaxModuleFrontController extends ModuleFrontController {


	private $trads;




	public function init()
	{
		parent::init();
        header("X-Robots-Tag: noindex, nofollow", true);

        //var_dump($this->module);die;
        // init traductions
        $this->trad = array(
	        'CORPULENCE_ACCRUE'          => $this->module->l('CORPULENCE_ACCRUE'),
		    'CORPULENCE_NORMALE'         => $this->module->l('CORPULENCE_NORMALE'),
		    'SURPOIDS'                   => $this->module->l('SURPOIDS'),
		    'OBESITE_MODEREE'            => $this->module->l('OBESITE_MODEREE'),
		    'OBESITE_CLASSES_2'          => $this->module->l('OBESITE_CLASSES_2'),
		    'OBESITE_MORBIDE_OU_MASSIVE' => $this->module->l('OBESITE_MORBIDE_OU_MASSIVE'),
        );
	}
	
	public function setMedia()
	{
		parent::setMedia();
    }
	
	/**
	 * @see FrontController::initContent()
	 */
	public function initContent()
	{
		parent::initContent();

        $name_module = 'dietconfiguration';
        //$this->setTemplate('verification_execution.tpl');


        $action = (string) trim(Tools::getValue('action'));

        switch ($action) {
            case 'calculIMC':

                include_once(dirname(__FILE__).'/../../classes/diet.class.php');

                try {
                    
                    //echo Tools::getHttpHost(true).__PS_BASE_URI__;die;
                    
                    $aReturnJson = array();
                    $aReturnJson["success"] = 1;
                    $aReturnJson["content"] = "";
                    
                    $weight         = str_replace(",",".",Tools::getValue('weight'));
                    $height         = str_replace(",",".",Tools::getValue('height'));
                    $sex            = (string) Tools::getValue('sex');
                    $age            = (int)Tools::getValue('age');
                    $id_category    = (int) Tools::getValue('id_category');
                    $language 		=  strtolower($this->context->language->iso_code);                    

                    // Objet IMC

    				//($weight, $height, $sex, $age, $lang = 'fr',$id_category
                    $oCalcul  = new Diet($weight, $height, $sex, $age, $language, $id_category);

                    //$oCalcul->logData(); // We log data
                    $aReturnJson["imc"]             = $oCalcul->imc;
                    $aReturnJson["text"]            = $oCalcul->intervalInfos["text"];
                    $aReturnJson["textLink"]        = $oCalcul->displayPrices();
                    $aReturnJson["color"]           = $oCalcul->intervalInfos["color"];
                    $aReturnJson["min"]             = round($oCalcul->lostMin,2);
                    $aReturnJson["max"]             = round($oCalcul->lostMax,2);
                    $aReturnJson["image"]           = $oCalcul->image;
                    $aReturnJson["plots"]           = Diet::getImcIntervals();
                    die(Tools::jsonEncode($aReturnJson));
                } 
                catch ( Exception $e ) {
                    $aReturnJson["success"] = 0;
                    $aReturnJson["content"] = $e->getMessage();
                    die(Tools::jsonEncode($aReturnJson));
                }

                break;
            
            default:
                break;
        }



    }
}