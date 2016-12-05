<?php

class DigitalUp_Skroutz_Block_Success extends Mage_Core_Block_Template
{
    public function getSkroutzOrderData()
    {
        $orderIds = $this->getOrderIds(); // grab the order id
        if (empty($orderIds) || !is_array($orderIds)) {
            return;
        }
        Mage::helper('skroutz')->debugData('success: ' . $orderIds); //done

        // load order
        $collection = Mage::getResourceModel('sales/order_collection')
            ->addFieldToFilter('entity_id', array('in' => $orderIds));


        $core_helper = Mage::helper('core'); // init core helper
        // fetch data
        foreach ($collection as $order) {
            $surcharge = 0; //init surcharge
            if ($core_helper->isModuleEnabled('Magebright_Surcharge') && $core_helper->isModuleOutputEnabled('Magebright_Surcharge')) {
                $surcharge = round($order->getFeeAmount(), 2); // remove magebright surcharge from grand total if module exists
            }

            $result[] = sprintf("sa('ecommerce', 'addOrder', JSON.stringify({
order_id: '%s',
revenue: '%s',
shipping: '%s',
tax: '%s'
}));",
                $order->getIncrementId(),
                round($order->getBaseGrandTotal(), 2) - $surcharge, //get grand total without surcharge if exists
                round($order->getShippingAmount(), 2), // get shipping with tax
                round($order->getBaseTaxAmount(), 2) // get clean tax
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