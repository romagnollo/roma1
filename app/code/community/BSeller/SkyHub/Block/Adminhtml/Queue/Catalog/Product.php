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

class BSeller_SkyHub_Block_Adminhtml_Queue_Catalog_Product extends BSeller_SkyHub_Block_Adminhtml_Widget_Grid_Container
{

    protected $_controller = 'adminhtml_queue_catalog_product';


    public function __construct()
    {
        $this->_headerText = $this->__('Catalog Products Queue');
        parent::__construct();
    }
}
