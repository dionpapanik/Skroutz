<?php

class DigitalUp_Skroutz_Block_Success extends Mage_Core_Block_Template
{
    public function getSkroutzOrderData()
    {
        $orderIds = $this->getOrderIds(); // grab the order id
        if (empty($orderIds) || !is_array($orderIds)) {
            return;
        }
        Mage::helper('skroutz')->debugData('success: '.$orderIds); //done

        // load order
        $collection = Mage::getResourceModel('sales/order_collection')
            ->addFieldToFilter('entity_id', array('in' => $orderIds));


        // fetch data
        foreach ($collection as $order) {
            $result[] = sprintf("sa('ecommerce', 'addOrder', JSON.stringify({
order_id: '%s',
revenue: '%s',
shipping: '%s',
tax: '%s'
}));",
                $order->getIncrementId(),
                round($order->getBaseGrandTotal(), 2),
                round($order->getShippingAmount(), 2), // get shipping with tax
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
                    $this->jsQuoteEscape($item->getProduct()->getSku()), // load the product. Works for config product SKUs.
                    $this->jsQuoteEscape($item->getName()),
                    round($item->getProduct()->getFinalPrice(), 2), // load the product. Works for Final Price incl tax.
                    floatval($item->getQtyOrdered())
                );
            }
        }

        Mage::helper('skroutz')->debugData(implode("\n", $result));
        return implode("\n", $result);

    }
}