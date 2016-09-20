<?php

class DigitalUp_Skroutz_Model_Observer
{
    public function setSkroutzAnalyticsOnSuccessPageView(Varien_Event_Observer $observer)
    {
        $orderIds = $observer->getEvent()->getOrderIds();
        if (empty($orderIds) || !is_array($orderIds)) {
            return;
        }
        $block = Mage::app()->getFrontController()->getAction()->getLayout()->getBlock('skroutz_success_page');
        if ($block) {
            $block->setOrderIds($orderIds);
        }
        Mage::helper('skroutz')->debugData('observer id: '.$orderIds);
    }

}