<?php

/**
 * BIT Tools Platform | B2W - Companhia Digital
 *
 * Do not edit this file if you want to update this module for future new versions.
 *
 * @category  BitTools
 * @package   BitTools_ModuleName
 *
 * @copyright Copyright (c) 2018 B2W Digital - BIT Tools.
 *
 * Access https://ajuda.skyhub.com.br/hc/pt-br/requests/new for questions and other requests.
 */
class BSeller_SkyHub_Model_Shipment_Plp_Api extends Mage_Api_Model_Resource_Abstract
{
    public function plps($fromDate, $toDate)
    {
        $arrPlps = array();
        $collection = Mage::getModel("bseller_skyhub/shipment_plp")
            ->getCollection();

        $collection->addFieldToFilter('created_at', array('gteq' => $fromDate));
        $collection->addFieldToFilter('created_at', array('lteq' => $toDate . ' 23:59:00'));

        foreach ($collection as $item) {
            $data = $item->toArray(array('id', 'skyhub_code', 'created_at'));


            Mage::register('current_plp', $item);
            $block = Mage::getModel('core/layout')->createBlock('bseller_skyhub/adminhtml_shipment_plp_view_file_json');
            /** add the plp json here */
            $block->toHtml();

            $data['json'] = '';
            $jsonData = $block->getFileContent();
            if (Mage::helper('bseller_skyhub')->isJson($jsonData)) {
                $data['json'] = $jsonData;
            }

            $arrPlps[] = $data;
        }

        return $arrPlps;
    }
}