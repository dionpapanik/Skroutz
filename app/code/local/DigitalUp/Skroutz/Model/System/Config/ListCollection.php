<?php

/**
 * @author Dionisis Papanikolaou
 */
class DigitalUp_Skroutz_Model_System_Config_ListCollection
{
    public function toOptionArray()
    {
        // @todo original magento way
        /*return array(
            array('value' => 'simple', 'label' => 'Simple Products'),
            array('value' => 'configurable', 'label' => 'Configurable Products'),
        );*/
        return Mage::getModel('catalog/product_type')->getOptions(); //@done
    }
}