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
 * @author    Tiago Sampaio <tiago.sampaio@e-smart.com.br>
 */

class BSeller_SkyHub_Block_Adminhtml_Queue_Sales_Order_Status_Grid extends BSeller_SkyHub_Block_Adminhtml_Widget_Grid
{

    /**
     * @param BSeller_SkyHub_Model_Resource_Queue_Collection $collection
     *
     * @return BSeller_SkyHub_Model_Resource_Queue_Collection
     */
    protected function getPreparedCollection(BSeller_SkyHub_Model_Resource_Queue_Collection $collection)
    {
        /** @var Mage_Core_Model_Resource $resource */
        $resource = Mage::getModel('core/resource');

        /** @var BSeller_SkyHub_Model_Resource_Queue_Collection $collection */
        $collection->getSelect()
            ->joinLeft(
                array(
                    'orders' => $resource->getTableName('sales/order')
                ),
                "orders.entity_id = main_table.entity_id",
                array(
                    'increment_id',
                    'customer_email',
                )
            );

        $collection->addFieldToFilter('entity_type', BSeller_SkyHub_Model_Entity::TYPE_SALES_ORDER_STATUS);
        $collection->addFieldToFilter('process_type', BSeller_SkyHub_Model_Queue::PROCESS_TYPE_IMPORT);

        return $collection;
    }


    /**
     * @return $this
     */
    protected function _prepareColumns()
    {
        parent::_prepareColumns();

        $this->addColumnAfter(
            'increment_id',
            array(
                'header'       => $this->__('Order#'),
                'align'        => 'left',
                'width'        => '150px',
                'type'         => 'text',
                'filter_index' => 'orders.increment_id',
            ),
            'entity_id'
        );

        $this->addColumnAfter(
            'customer_email',
            array(
                'header'       => $this->__('Customer E-mail'),
                'align'        => 'left',
                'width'        => '200px',
                'type'         => 'text',
                'filter_index' => 'orders.customer_email',
            ),
            'increment_id'
        );

        $this->sortColumnsByOrder();

        return $this;
    }
    
}
