<?php
/**
 * BSeller Platform | B2W - Companhia Digital
 *
 * Do not edit this file if you want to update this module for future new versions.
 *
 * @category  BSeller
 * @package   BSeller_SkyHub
 *
 * @copyright Copyright (c) 2018 B2W Digital - BSeller Platform. (http://www.bseller.com.br)
 *
 * Access https://ajuda.skyhub.com.br/hc/pt-br/requests/new for questions and other requests.
 */

class BSeller_SkyHub_Block_Adminhtml_Widget_Grid_Container extends BSeller_Core_Block_Adminhtml_Widget_Grid_Container
{

    protected $_blockGroup = 'bseller_skyhub';


    public function __construct()
    {
        parent::__construct();
        $this->removeButton('add');
    }
}
