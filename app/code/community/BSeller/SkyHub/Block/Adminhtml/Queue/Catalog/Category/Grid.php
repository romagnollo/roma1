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

class BSeller_SkyHub_Block_Adminhtml_Queue_Catalog_Category_Grid extends BSeller_SkyHub_Block_Adminhtml_Widget_Grid
{
    /**
     * @var string
     */
    protected $_entityType = BSeller_SkyHub_Model_Entity::TYPE_CATALOG_CATEGORY;

    /**
     * @param BSeller_SkyHub_Model_Resource_Queue_Collection $collection
     *
     * @return BSeller_SkyHub_Model_Resource_Queue_Collection
     *
     * @throws Mage_Core_Exception
     */
    protected function getPreparedCollection(BSeller_SkyHub_Model_Resource_Queue_Collection $collection)
    {
        /** @var Mage_Eav_Model_Entity_Attribute $name */
        $name = Mage::getModel('eav/entity_attribute');
        $name->loadByCode(Mage_Catalog_Model_Category::ENTITY, 'name');

        $condition  = "eav.entity_id = main_table.entity_id ";
        $condition .= "AND eav.entity_type_id = '{$name->getEntityTypeId()}' ";
        $condition .= "AND eav.attribute_id = '{$name->getId()}' ";
        $condition .= "AND eav.store_id = '{$this->getStoreId()}' ";

        /** @var BSeller_SkyHub_Model_Resource_Queue_Collection $collection */
        $collection->getSelect()
            ->joinLeft(
                array('eav' => $name->getBackendTable()),
                $condition,
                array('category_name' => 'value')
            );

        $collection->addFieldToFilter('entity_type', BSeller_SkyHub_Model_Entity::TYPE_CATALOG_CATEGORY);

        return $collection;
    }


    /**
     * @return $this
     */
    protected function _prepareColumns()
    {
        parent::_prepareColumns();

        $this->addColumnAfter(
            'category_name',
            array(
                'header'       => $this->__('Category Name'),
                'align'        => 'left',
                'type'         => 'text',
                'filter_index' => 'eav.value',
            ),
            'entity_id'
        );

        $this->sortColumnsByOrder();

        return $this;
    }
    
}
