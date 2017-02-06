<?php

/**
 * @author Dionisis Papanikolaou
 * @date 6/2/2017
 */
class DigitalUp_Skroutz_Block_Config_Info extends Mage_Adminhtml_Block_Abstract implements Varien_Data_Form_Element_Renderer_Interface
{
    protected $_template = 'digitalup/skroutz/info.phtml';

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        return $this->toHtml();
    }
}