<?php

class NovaPC_Melhorenvio_Block_Adminhtml_Orders extends Mage_Adminhtml_Block_Widget_Container
{

    /**
     * Set template
     */
      public function __construct()
      {
        parent::__construct();
      }

    /**
     * Prepare button and grid
     *
     * @return Mage_Adminhtml_Block_
     */
    protected function _prepareLayout()
    {
       
        $this->setChild('grid', $this->getLayout()->createBlock('melhorenvio/adminhtml_orders_grid', 'orders.grid'));
        return parent::_prepareLayout();
    }

    /**
     * Render grid
     *
     * @return string
     */
    public function getGridHtml()
    {
        return $this->getChildHtml('grid');
    }

    /**
     * Check whether it is single store mode
     *
     * @return bool
     */
    public function isSingleStoreMode()
    {
        if (!Mage::app()->isSingleStoreMode()) {
               return false;
        }

        return true;
    }
}
