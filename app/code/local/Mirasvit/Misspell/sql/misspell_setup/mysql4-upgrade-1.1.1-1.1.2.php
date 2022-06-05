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
 * @package   mirasvit/extension_misspell
 * @version   1.2.11
 * @copyright Copyright (C) 2018 Mirasvit (https://mirasvit.com/)
 */




$installer = $this;

$installer->startSetup();
$installer->run("DROP TABLE IF EXISTS `{$installer->getTable('misspell/misspell_suggest')}`;");
$installer->run("
CREATE TABLE `{$installer->getTable('misspell/misspell_suggest')}` (
   `suggest_id` int(11) unsigned NOT NULL auto_increment,
   `query` varchar(255) NOT NULL default '',
   `suggest` varchar(255) NOT NULL default '',
    PRIMARY KEY  (`suggest_id`)
) ENGINE=InnoDb DEFAULT CHARSET=utf8;
");
$installer->endSetup();
