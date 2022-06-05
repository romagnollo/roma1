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



/**
 * Виджет формы поиска (т.е. его можно добавить на страницу через редактор).
 *
 * @category Mirasvit
 */
class Mirasvit_SearchAutocomplete_Block_Widget_Form extends Mirasvit_SearchAutocomplete_Block_Form implements Mage_Widget_Block_Interface
{
    public function _prepareLayout()
    {
        $this->setTemplate('searchautocomplete/widget/form.phtml');

        return parent::_prepareLayout();
    }
}
