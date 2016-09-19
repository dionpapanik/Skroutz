<?php

class DigitalUp_Skroutz_Block_Success extends Mage_Core_Block_Template
{
    public function getSkroutzOrderData()
    {
        $orderIds = $this->getOrderIds(); // grab the order id       
        if (empty($orderIds) || !is_array($orderIds)) {
            return;
        }
        // Zend_Debug::dump($orderIds); // passed correctly!

        // load order
        $collection = Mage::getResourceModel('sales/order_collection')
            ->addFieldToFilter('entity_id', array('in' => $orderIds));


        // fetch data
        foreach ($collection as $order) {
            $result[] = sprintf("sa('ecommerce', 'addOrder', JSON.stringify({
order_id: '%s',
revenue: '%s',
shipping: '%s'
tax: '%s'
}));",
                $order->getIncrementId(),
                round($order->getBaseGrandTotal(), 2),
                round($order->getBaseShippingAmount(), 2),
                round($order->getBaseTaxAmount(), 2)
            );

            foreach ($order->getAllVisibleItems() as $item) {
                $result[] = sprintf("sa('ecommerce', 'addItem', JSON.stringify({
order_id: '%s',
product_id: '%s',
name: '%s',
price: '%s',
quantity: '%s'
}));",
                    $order->getIncrementId(),
                    $this->jsQuoteEscape($item->getSku()),
                    $this->jsQuoteEscape($item->getName()),
                    round($item->getBasePrice(), 2),
                    floatval($item->getQtyOrdered())
                );
            }
        }
        return implode("\n", $result);
    }
}