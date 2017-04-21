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

class DietconfigurationModuleFrontController extends ModuleFrontController


//class DietController extends ModuleFrontController {


	public function init()
	{
		parent::init();
        header("X-Robots-Tag: noindex, nofollow", true);
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
                    

                    // Objet IMC
                    $oCalcul  = new Diet($weight, $height, $sex, $age, 'en', $id_category);
                    $oCalcul->logData(); // We log data
                    
                    
                    $aReturnJson["imc"]             = $oCalcul->imc;
                    $aReturnJson["text"]            = $oCalcul->intervalInfos["text"];
                    $aReturnJson["textLink"]        = $oCalcul->displayPrices();
                    $aReturnJson["color"]           = $oCalcul->intervalInfos["color"];
                    $aReturnJson["min"]             = round($oCalcul->lostMin,2);
                    $aReturnJson["max"]             = round($oCalcul->lostMax,2);
                    $aReturnJson["image"]           = $oCalcul->image;
                    die(Tools::jsonEncode($aReturnJson));
                } 
                catch ( Exception $e ) {
                    $aReturnJson["success"] = 0;
                    $aReturnJson["content"] = $e->getMessage();
                    die(Tools::jsonEncode($aReturnJson));
                }

                break;
            
            default:
                # code...
                break;
        }



    }
}