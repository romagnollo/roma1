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

class BSeller_SkyHub_Adminhtml_Bseller_Skyhub_QueueController extends BSeller_SkyHub_Controller_Admin_Queue
{
    protected $_aclPrefix = 'bseller/bseller_skyhub/queues/';

    /**
     * @param string|null $actionTitle
     *
     * @return $this
     */
    protected function init($actionTitle = null)
    {
        parent::init('Queues Tracking');

        if (!empty($actionTitle)) {
            $this->_title($this->__($actionTitle));
        }

        return $this;
    }
    
    
    /**
     * Catalog Products Queue
     */
    public function productsQueueAction()
    {
        $this->init('Catalog Products Queue');
        $this->_setActiveMenu('bseller/bseller_skyhub/queues/products_queue');
        
        $this->renderLayout();
    }


    /**
     * Catalog Categories Queue
     */
    public function categoriesQueueAction()
    {
        $this->init('Catalog Categories Queue');
        $this->_setActiveMenu('bseller/bseller_skyhub/queues/categories_queue');

        $this->renderLayout();
    }


    /**
     * Catalog Product Attributes Queue
     */
    public function productAttributesQueueAction()
    {
        $this->init('Catalog Product Attributes Queue');
        $this->_setActiveMenu('bseller/bseller_skyhub/queues/product_attributes_queue');

        $this->renderLayout();
    }


    /**
     * Sales Order Status Queue
     */
    public function salesOrderStatusQueueAction()
    {
        $this->init('Sales Order Status Queue');
        $this->_setActiveMenu('bseller/bseller_skyhub/queues/sales_order_status_queue');

        $this->renderLayout();
    }


    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        $action = strtolower($this->getRequest()->getActionName());
        switch ($action) {
            case 'productsqueue':
                $this->_aclSuffix = 'products_queue';
                break;
            case 'categoriesqueue':
                $this->_aclSuffix = 'categories_queue';
                break;
            case 'productattributesqueue':
                $this->_aclSuffix = 'product_attributes_queue';
                break;
            case 'salesorderstatusqueue':
                $this->_aclSuffix = 'sales_order_status_queue';
                break;
            default:
                break;
        }

        return parent::_isAllowed();
    }

    /**
     * Delete Unique Queue ID.
     */
    public function deleteAction()
    {
        $queueId = (int)$this->getRequest()->getParam('id');

        $this->deleteQueueIds($queueId);
        $this->redirectBack();
    }

    /**
     * Delete a bunch of queue IDs.
     */
    public function massDeleteAction()
    {
        $queueIds = (array)$this->getRequest()->getPost('queue_ids');

        $this->deleteQueueIds($queueIds);
        $this->redirectBack();
    }
}
