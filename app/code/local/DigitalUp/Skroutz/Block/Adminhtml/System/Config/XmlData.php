<?php

class DigitalUp_Skroutz_Block_Adminhtml_System_Config_XmlData extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    public function _prepareToRender()
    {
        $this->addColumn('node', array(
            'label' => Mage::helper('adminhtml')->__('XML Node'),
            'style' => 'width:350px',
        ));
        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('adminhtml')->__('Add');
    }
}