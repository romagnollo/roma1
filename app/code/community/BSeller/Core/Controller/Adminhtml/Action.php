<?php
/**
 * BSeller Platform | B2W - Companhia Digital
 *
 * Do not edit this file if you want to update this module for future new versions.
 *
 * @category  BSeller
 * @package   BSeller_Core
 *
 * @copyright Copyright (c) 2016 B2W Digital - BSeller Platform. (http://www.bseller.com.br)
 *
 * @author    BSeller Core Team <dev@e-smart.com.br>
 */

class BSeller_Core_Controller_Adminhtml_Action extends Mage_Adminhtml_Controller_Action
{

    use BSeller_Core_Trait_Data,
        BSeller_Core_Trait_Controller;

    protected function _isAllowed()
    {
        return parent::_isAllowed();
    }
}
