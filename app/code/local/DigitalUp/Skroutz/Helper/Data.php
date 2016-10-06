<?php

/**
 * @author Dionisis Papanikolaou
 */
class DigitalUp_Skroutz_Helper_Data extends Mage_Core_Helper_Abstract
{

    const FEED_ENABLED = 'skroutz/feed/enabled';
    const SHOP_NAME = 'skroutz/feed/shop_name';
    const PRODUCT_TYPE = 'skroutz/feed/product_type';
    const SUPER_ATTRS = 'skroutz/feed/super_attributes';
    const SHIPPING_COST = 'skroutz/feed/shipping_cost';
    const XML_DATA = 'skroutz/feed/xml_data';
    const ANALYTICS_ENABLED = 'skroutz/analytics/enabled';
    const ANALYTICS_ID = 'skroutz/analytics/account_id';
    const DEBUG = 'skroutz/developer/debug';


    protected $_feedEnabled = null;
    protected $_shopName = null;
    protected $_productType = null;
    protected $_supperAttrs = null;
    protected $_shippingCost = null;
    protected $_xmlData = null;
    protected $_analyticsEnabled = null;
    protected $_analyticsId = null;


    /**
     * Checks if skroutz feed module is enabled
     *
     * @return  bool
     */
    public function feedEnabled()
    {
        if (is_null($this->_feedEnabled)) {
            $this->_feedEnabled = (bool)Mage::getStoreConfig(self::FEED_ENABLED);
        }
        return $this->_feedEnabled;
    }

    /**
     * Returns Name of the shop
     *
     * @return  string
     */
    public function getShopName()
    {
        if (is_null($this->_shopName)) {
            $this->_shopName = (string)Mage::getStoreConfig(self::SHOP_NAME);
        }
        return $this->_shopName;
    }

    /**
     * Returns selected product types from admin
     *
     * @return  array
     */
    public function getProductType()
    {
        if (is_null($this->_productType)) {
            $this->_productType = (string)Mage::getStoreConfig(self::PRODUCT_TYPE);
        }
        return explode(',', $this->_productType);
    }

    /**
     * Returns attributes responsible for config products
     *
     * @return  array || null
     */
    public function getSupperAttributes()
    {
        $this->_supperAttrs = (string)Mage::getStoreConfig(self::SUPER_ATTRS);
        if ($this->_supperAttrs) {
            return explode(',', $this->_supperAttrs);            
        }
        return null;
    }

    /**
     * Return fixed Shipping Cost
     *
     * @return  string
     */
    public function getShippingCost()
    {
        if (is_null($this->shippingCost)) {
            $this->shippingCost = (string)Mage::getStoreConfig(self::SHIPPING_COST);
        }
        return $this->shippingCost;
    }

    /**
     * get xml nodes from admin table
     * if data exists return unserialized array
     * else return null
     *
     * @return  array || null
     */
    public function xmlData()
    {
        $this->_xmlData = Mage::getStoreConfig(self::XML_DATA);
        if ($this->_xmlData) {
            $this->_xmlData = unserialize($this->_xmlData);
            return $this->_xmlData;
        }
        return null;
    }

    /**
     * Checks if skroutz analytics module is enabled
     *
     * @return  bool
     */
    public function analyticsEnabled()
    {
        if (is_null($this->_analyticsEnabled)) {
            $this->_analyticsEnabled = (bool)Mage::getStoreConfig(self::ANALYTICS_ENABLED);
        }
        return $this->_analyticsEnabled;
    }

    /**
     * Skroutz analytics ID
     *
     * @return  string
     */
    public function analyticsId()
    {
        if (is_null($this->_analyticsId)) {
            $this->_analyticsId = (string)Mage::getStoreConfig(self::ANALYTICS_ID);
        }
        return $this->_analyticsId;
    }


    /**
     * function to debug data
     * log data in digitalup_skroutz.log if enabled
     * dump data on screen if enabled && isDevAllowed
     *
     * @param string $data
     * @return dump
     */
    public function debugData($data)
    {
        if ((bool)Mage::getStoreConfig(self::DEBUG)) {
            Mage::log($data, null, 'digitalup_skroutz.log', true);
            if (Mage::helper('core')->isDevAllowed()) {
                Zend_Debug::dump($data);
            }
        }
    }
}