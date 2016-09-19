<?php

class DigitalUp_Skroutz_Helper_Data extends Mage_Core_Helper_Abstract
{

    const FEED_ENABLED = 'skroutz/feed/enabled';
    const ANALYTICS_ENABLED = 'skroutz/analytics/enabled';
    const ANALYTICS_ID = 'skroutz/analytics/account_id';


    protected $_feedEnabled = null;
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
}
	 