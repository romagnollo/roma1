<?php
/**
 * BSeller Platform | B2W - Companhia Digital
 *
 * Do not edit this file if you want to update this module for future new versions.
 *
 * @category  BSeller
 * @package   BSeller_Core
 *
 * @copyright Copyright (c) 2018 B2W Digital - BSeller Platform. (http://www.bseller.com.br)
 *
 * @author    Tiago Sampaio <tiago.sampaio@e-smart.com.br>
 */

abstract class BSeller_Core_Model_System_Config_Source_Abstract
{
    
    use BSeller_Core_Trait_Data;
    
    
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray($multiselect = null)
    {
        $options = array();
    
        foreach ((array) $this->optionsKeyValue($multiselect) as $value => $label) {
            $options[] = array(
                'label' => $label,
                'value' => $value
            );
        }
    
        return $options;
    }
    
    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray($multiselect = null)
    {
        $options = array();
        
        foreach ((array) $this->optionsKeyValue($multiselect) as $value => $label) {
            $options[$value] = $label;
        }
        
        return $options;
    }
    
    
    /**
     * @return array
     */
    abstract protected function optionsKeyValue($appendFirst = false);
}
