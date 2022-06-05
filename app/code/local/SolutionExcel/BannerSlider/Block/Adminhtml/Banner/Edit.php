<?php
/**
 * SolutionExcel BannerSlider Module.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the SolutionExcel Team
 * that is bundled with this package of SolutionExcel Inc.
 * This package developed for Magento COMMUNITY edition.
 * =================================================================
 *
 * @category    SolutionExcel
 * @package     SolutionExcel_BannerSlider
 * @copyright   Copyright (c) 2016 SolutionExcel (http://www.solutionexcel.com/)
 *
 */
 
class SolutionExcel_BannerSlider_Block_Adminhtml_Banner_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
		public function __construct()
		{

				parent::__construct();
				$this->_objectId = "id";
				$this->_blockGroup = "bannerslider";
				$this->_controller = "adminhtml_banner";
				$this->_updateButton("save", "label", Mage::helper("bannerslider")->__("Save Item"));
				$this->_updateButton("delete", "label", Mage::helper("bannerslider")->__("Delete Item"));

				$this->_addButton("saveandcontinue", array(
					"label"     => Mage::helper("bannerslider")->__("Save And Continue Edit"),
					"onclick"   => "saveAndContinueEdit()",
					"class"     => "save",
				), -100);



				$this->_formScripts[] = "

							function saveAndContinueEdit(){
								editForm.submit($('edit_form').action+'back/edit/');
							}
						";
		}

		public function getHeaderText()
		{
				if( Mage::registry("banner_data") && Mage::registry("banner_data")->getId() ){

				    return Mage::helper("bannerslider")->__("Edit Item '%s'", $this->htmlEscape(Mage::registry("banner_data")->getTitle()));

				} 
				else{

				     return Mage::helper("bannerslider")->__("Add Item");

				}
		}
}