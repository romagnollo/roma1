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
 * @package   mirasvit/extension_searchautocomplete
 * @version   1.2.14
 * @copyright Copyright (C) 2018 Mirasvit (https://mirasvit.com/)
 */


$_item         = $this->getItem();
$_index     = Mage::helper('searchindex/index')->getIndex('infortis_brands_item');
?>
<li data-url="<?php echo $_index->getUrl($_item); ?>">
    <a class="name highlight" href="<?php echo $_index->getUrl($_item); ?>"><?php echo $_item->getData('label'); ?></a>
</li>