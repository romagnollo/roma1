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



//2.3.12 - 2.3.13
$installer = $this;
$installer->startSetup();
$query = "ALTER TABLE `{$installer->getTable('searchindex/index')}` MODIFY COLUMN `title` text NOT NULL;";

$installer->run("ALTER TABLE `{$installer->getTable('searchindex/index')}` MODIFY COLUMN `title` text NOT NULL;");

$installer->endSetup();
