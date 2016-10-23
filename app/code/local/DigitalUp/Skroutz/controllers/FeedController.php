<?php

/**
 * @author Dionisis Papanikolaou
 */
class DigitalUp_Skroutz_FeedController extends Mage_Core_Controller_Front_Action
{
    // if skroutz is disabled from admin return magento's 404 with notice
    public function preDispatch()
    {
        parent::preDispatch();

        if (!Mage::helper('skroutz/data')->feedEnabled()) {
        	Mage::getSingleton('core/session')->addError('Skroutz disabled from admin panel');
            $this->norouteAction();
        }
    }

    public function indexAction()
    {
        try {
            Varien_Profiler::start('skroutz');

            $feedHelper = Mage::helper('skroutz/feed');
            $dataHelper = Mage::helper('skroutz/data');
            $adminData = $dataHelper->xmlData();
            $xml = new SimpleXMLElement('<xml/>');
            $xml->addAttribute('version', '1.0');
            $xml->addAttribute('encoding', 'UTF-8');
            $xml_store = $xml->addChild($dataHelper->getShopName());
            $xml_store->addChild('created_at', Mage::getModel('core/date')->date('Y-m-d H:i'));
            $xml_products = $xml_store->addChild('products');

            $collection = $feedHelper->getCollection();

            foreach ($collection as $product) {
                $xml_product = $xml_products->addChild('product');
                foreach ($adminData as $data) {
                    $node = $data['node'];
                    $xml_product->addChild($node, $feedHelper->createNodeValue($node, $product));
                }
                if ($product->isConfigurable()) {
                    if ($dataHelper->getSupperAttributes()) {
                        foreach ($dataHelper->getSupperAttributes() as $attribute) {
                            $xml_product->addChild($attribute, $feedHelper->createNodeWithChildData($product, $attribute));
                        }
                    }
                }

                if ($dataHelper->getShippingCost()) {
                    $xml_product->addChild('shipping', $dataHelper->getShippingCost());
                }
            }
            $this->getResponse()->setHeader('Content-Type', 'text/xml');
            $this->getResponse()->setBody($xml->asXML());

            Varien_Profiler::stop('skroutz');

        } catch (Exception $e) {
            $this->getResponse()->setHeader('Content-type', 'text');
            $this->getResponse()->setBody(sprintf("Exception: %s.", $e->getMessage()));
        }
    }
}