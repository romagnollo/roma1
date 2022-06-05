<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/extension_searchsphinx
 * @version   2.3.48
 * @copyright Copyright (C) 2020 Mirasvit (https://mirasvit.com/)
 */



class Mirasvit_SearchIndex_Block_Adminhtml_Validation_Tab_Result extends Mage_Adminhtml_Block_Template
{
    
    /**
     * {@inheritdoc}
     */
    public function _prepareLayout()
    {
        $this->setTemplate('searchindex/validation/tab/result.phtml');

        return parent::_prepareLayout();
    }

    /**
     * {@inheritdoc}
     */
    public function getQ()
    {
        return Mage::app()->getRequest()->getParam('q');
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return intval(Mage::app()->getRequest()->getParam('id'));
    }

    /**
     * {@inheritdoc}
     */
    public function getValidationResult()
    {
        $result = array();

        if (!$this->getQ() || !$this->getId()) {
            return array();
        }

        $catalogIndex = Mage::helper('searchindex/index')->getIndex('mage_catalog_product');
        $matchedIds = $catalogIndex->getMatchedIds($this->getQ());

        if (isset($matchedIds[$this->getId()])) {
            $result[] = array(true, 'Search return this product by this search phase', '');
        } else {
            $description = array();
            $validateUrl = Mage::helper('adminhtml')->getUrl('adminhtml/mstcore_validator/index/', array('modules' => ''));
            $queryLenChangeUrl = Mage::helper('adminhtml')->getUrl('adminhtml/system_config/edit/section/catalog/', array());

            $description[] = 'Please, make sure this product is enabled, in stock and visible in search.';
            $description[] = 'Be sure, that this product contains these search phrase.';
            $description[] = "Your Minimal Query Length is " .Mage::getStoreConfig(Mage_CatalogSearch_Model_Query::XML_PATH_MIN_QUERY_LENGTH) ." , you can change it in <a href='$queryLenChangeUrl' target='_blank'>Catalog Search section</a>";
            $description[] = "Also please <a href='$validateUrl' target='_blank'>validate extension installation</a>.";

            $result[] = array(false, 'Search did NOT return this product by this search phase',
                implode('<br>', $description));
        }

        $res = Mage::getSingleton('core/resource');
        $conn = $res->getConnection('core_read');

        $tables = array(
            'catalog/product' => array('entity_id', ' This product does not exist!'),
            'catalogsearch/fulltext' => array('product_id', 'Run full reindex of "Catalog Search" index".'),
            'catalog/category_product' => array('product_id', 'Please, assign the product to some category.'),
            'catalog/category_product_index' => array('product_id', 'Run full reindex of "Category Products".'),
            'catalog/product_index_price' => array('entity_id', 'Run full reindex of "Product Prices".'),
            'cataloginventory/stock_status' => array('product_id', 'Run full reindex of "Stock Status".'),
        );

        foreach ($tables as $table => $data) {
            $tabl = $res->getTableName($table);

            $rows = $conn->fetchAll("SELECT * FROM $tabl WHERE $data[0]=".$this->getId());

            if (count($rows) > 0) {
                $result[] = array(true, "Table $tabl contains this product", '');
            } else {
                $result[] = array(false, "Table $tabl DOES NOT contain this product.", $data[1]);
            }

            if (isset($_GET['table'])) {
                echo $this->printTable($rows);
            }
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function printTable($result)
    {
        $html = '';
        $html .= '<table border="1">';
        foreach ($result as $row) {
            $html .= '<tr>';
            foreach ($row as $column => $value) {
                $html .= '<th>'.$column.'</th>';
            }
            $html .= '</tr>';
            break;
        }

        foreach ($result as $row) {
            $html .= '<tr>';
            foreach ($row as $value) {
                $html .= '<td>'.$value.'</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</table>';

        return $html;
    }
}
