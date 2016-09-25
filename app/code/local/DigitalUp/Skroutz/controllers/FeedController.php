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

        if (!Mage::helper('skroutz')->feedEnabled()) {
            $this->norouteAction();
        }
    }

    public function indexAction()
    {
        $this->getResponse()->setHeader('Content-type', 'text/xml');

        $feedHelper = Mage::helper('skroutz/feed');
        // $dataHelper = Mage::helper('skroutz/data');
        // $adminData = $dataHelper->xmlData();


        $date_time = Mage::getModel('core/date')->date('Y-m-d H:i');
        $xml = new SimpleXMLElement('<xml/>');
        $xml->addAttribute('version', '1.0');
        $xml->addAttribute('encoding', 'UTF-8');
        $xml_store = $xml->addChild("shop_name");
        $xml_store->addChild('created_at', $date_time);
        $xml_products = $xml_store->addChild('products');

        $collection = $feedHelper->getCollection();

        foreach ($collection as $product) {

            /*
             * gamiesai!! :)
             *
             * foreach grabs data from admin and proccess them to 'skroutz xml' type data.
             * works fine for 'simple' elements (sku, images, name, descr etc.)
             * doesn't work for 'comlicated' elemetns like img, url path etc.
             *
             * @todo FIND TIME & MAKE IT HAPPEN!
             */

            /* foreach ($adminData as $data) {
                 $node = $data['node'];
                 $prod_data = $data['product_attribute'];
                 $xml_product->addChild("$node", $product->getData("$prod_data"));
             }*/

            $xml_product = $xml_products->addChild('product');
            $xml_product->addAttribute('id', $product->getId());
            $xml_product->addChild('name', $product->getName());
            $xml_product->addChild('link', strpos($product->getProductUrl(), '?') > 0 ? substr($product->getProductUrl(), 0, strpos($product->getProductUrl(), '?')) : $product->getProductUrl());
            $xml_product->addChild('price_with_vat', number_format($product->getFinalPrice(), 2));
            $xml_product->addChild('category', $feedHelper->getTheCrumb($product));
            $xml_product->addChild('image', Mage::getModel('catalog/product_media_config')->getMediaUrl($product->getImage()));
            $xml_product->addChild('manufacturer', $product->getAttributeText('manufacturer'));
            $xml_product->addChild('sku', $product->getSku());
            $xml_product->addChild('stock', $product->isSaleable() ? 'Y' : 'N');
            $xml_product->addChild('description', strip_tags(html_entity_decode($product->getDescription())));

        }
        print($xml->asXML());
    }

}

