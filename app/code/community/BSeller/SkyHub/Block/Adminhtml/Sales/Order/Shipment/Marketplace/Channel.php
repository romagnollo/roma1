<?php

/**
 * BSeller Platform | B2W - Companhia Digital
 *
 * Do not edit this file if you want to update this module for future new versions.
 *
 * @category  BSeller
 * @package   BSeller_SkyHub
 *
 * @copyright Copyright (c) 2020 B2W Digital - BSeller Platform. (http://www.bseller.com.br)
 *
 * @author    Tiago Tescaro <tiago.tescaro@b2wdigital.com>
 */

class BSeller_SkyHub_Block_Adminhtml_Sales_Order_Shipment_Marketplace_Channel
    extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    /** @var string */
    protected $_render;

    /**
     * Prepare to render
     *
     * @return void
     */
    protected function _prepareToRender()
    {
        $this->addColumn(
            'channel',
            [
                'label'    => Mage::helper('sales')->__('Channel'),
                'class'    => 'input-text required-entry',
                'renderer' => $this->_getRendererChannels()
            ]
        );

        $this->addColumn(
            'method_shipping_default',
            [
                'label'    => Mage::helper('sales')->__('Method Shipping Default'),
                'class'    => 'input-text required-entry'
            ]
        );

        $this->addColumn(
            'carrier_shipping_default',
            [
                'label'    => Mage::helper('sales')->__('Carrier Shipping Default'),
                'class'    => 'input-text required-entry'
            ]
        );
    }

    /**
     * Return renderer by type
     *
     * @return array
     */
    protected function _getRendererChannels()
    {
        if (!$this->_render) {
            $options = $this->_getChannels();
            $this->_render = $this->getLayout()
                ->createBlock('bseller_skyhub/adminhtml_sales_order_shipment_marketplace_field_channels')
                ->setIsRenderToJsTemplate(true)
                ->setOptions($options);
        }

        return $this->_render;
    }

    /**
     * @return array
     */
    protected function _getChannels()
    {
        $model = Mage::getModel('bseller_skyhub/source_order_marketplaces');
        return $model->toOptionArray();
    }

    /**
     * Prepare row and set option selected
     *
     * @param Varien_Object $row
     * @return void
     */
    protected function _prepareArrayRow(Varien_Object $row)
    {
        $row->setData(
            'option_extra_attr_' . $this->_getRendererChannels()
                ->calcOptionHash($row->getData('channel')),
            'selected="selected"'
        );
    }
}