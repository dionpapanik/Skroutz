<?php

/**
 * @author Dionisis Papanikolaou
 */
class DigitalUp_Skroutz_Helper_Data extends Mage_Core_Helper_Abstract
{

    const FEED_ENABLED = 'skroutz/feed/enabled';
    const XML_DATA = 'skroutz/feed/xml_data';
    const ANALYTICS_ENABLED = 'skroutz/analytics/enabled';
    const ANALYTICS_ID = 'skroutz/analytics/account_id';
    const DEBUG = 'skroutz/developer/debug';


    protected $_feedEnabled = null;
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
     * get data from admin table
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
            // $this->debugData($this->_xmlData);
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
     * log data in skroutz.log if enabled
     * dump data on screen if enabled && isDevAllowed
     *
     * @param string $data
     * @return string
     */
    public function debugData($data)
    {
        if ((bool)Mage::getStoreConfig(self::DEBUG)) {
            Mage::log($data, null, 'skroutz.log', true);
            if (Mage::helper('core')->isDevAllowed()) {
                Zend_Debug::dump($data);
            }
        }
    }
}