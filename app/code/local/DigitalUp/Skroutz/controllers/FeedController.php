<?php

/**
 * @author Dionisis Papanikolaou
 */
class DigitalUp_Skroutz_FeedController extends Mage_Core_Controller_Front_Action
{
    // if skroutz is disabled from admin return magento's 404
    public function preDispatch()
    {
        parent::preDispatch();

        if (!Mage::helper('skroutz/data')->feedEnabled()) {
            $this->norouteAction();
        }
    }

    public function indexAction()
    {
        try {

            $feedHelper = Mage::helper('skroutz/feed');
            $dataHelper = Mage::helper('skroutz/data');
            $adminData = $dataHelper->xmlData();

            $date_time = Mage::getModel('core/date')->date('Y-m-d H:i');
            $xml = new SimpleXMLElement('<xml/>');
            $xml->addAttribute('version', '1.0');
            $xml->addAttribute('encoding', 'UTF-8');
            $xml_store = $xml->addChild($dataHelper->getShopName());
            $xml_store->addChild('created_at', $date_time);
            $xml_products = $xml_store->addChild('products');

            $collection = $feedHelper->getCollection();

            foreach ($collection as $product) {
                $xml_product = $xml_products->addChild('product');
                foreach ($adminData as $data) {
                    $node = $data['node'];
                    $xml_product->addChild($node, $feedHelper->createNodeValue($node, $product));
                }
                if ($product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE){
                    $xml_product->addChild('mantest', $feedHelper->createNodeWithChildData($product));
                }
            }
            $this->getResponse()->setHeader('Content-Type', 'text/xml');
            $this->getResponse()->setBody($xml->asXML());

        } catch (Exception $e) {
            $this->getResponse()->setHeader('Content-type', 'text');
            $this->getResponse()->setBody(sprintf("Exception: %s.", $e->getMessage()));
        }
    }
}