<?php

/**
 * @author Dionisis Papanikolaou
 */
class DigitalUp_Skroutz_Helper_Feed extends Mage_Core_Helper_Abstract
{
    /**
     * get collection of items for xml feed
     * filter only simple, enabled and catalog/searchable products
     * @todo return all types of products
     *
     * @return Mage_Catalog_Model_Resource_Product_Collection $collection
     */
    public function getCollection()
    {
        $collection = Mage::getResourceModel('catalog/product_collection')
            ->addAttributeToFilter('type_id', Mage_Catalog_Model_Product_Type::TYPE_SIMPLE)
            ->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
            ->addAttributeToFilter('visibility', array('neq' => Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE))
            ->addAttributeToSelect('*');
        $collection->load();
        return $collection;
    }

    /**
     * get path of first category product is assigned for
     *
     * @param Mage_Catalog_Model_Product $product
     * @return string
     */
    private function _getTheCrumb($product)
    {
        unset($crumb);
        $crumb = array();
        $category = Mage::getModel('catalog/category')->load($product->getCategoryIds()[0]); //load the first category of the prodduct
        $pathInStore = $category->getPathInStore(); // get the path ids - type string
        $pathIds = array_reverse(explode(',', $pathInStore)); // reverse it. convert to array
        $cat = Mage::getModel('catalog/category');
        foreach ($pathIds as $id) { //iterate ids and get name
            $cat->load($id);
            $crumb[] = $cat->getData("name");
        }
        return htmlspecialchars(implode(' > ', $crumb));
    }

    /**
     * return availability of a product
     * checks also extra_availability attribute
     *
     * @param Mage_Catalog_Model_Product $product
     * @return string
     */
    private function _getAvailability($product)
    {
        $stock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product);
        if ($stock == 1) {
            if ($product->getExtra_availability()) {
                return $product->getAttributeText('extra_availability');
            } else {
                return 'Άμεσα Διαθέσιμο';
            }
        } else {
            return 'Εκτός Αποθέματος';
        }
    }

    /**
     * return the value of the XML node
     * checks also extra_availability attribute
     *
     * @params Mage_Catalog_Model_Product $product , string $node
     * @return string || float
     */
    public function createNodeValue($node, $product)
    {
        switch ($node) {
            case 'id':
                return $product->getId();
                break;
            case 'sku':
                return $product->getSku();
                break;
            case 'name':
                return $product->getName();
                break;
            case 'price_with_vat':
                Mage::app()->loadAreaPart(Mage_Core_Model_App_Area::AREA_FRONTEND, Mage_Core_Model_App_Area::PART_EVENTS); // use frontend events to get the FinalPrice after rules.
                $price = Mage::helper('tax')->getPrice($product, $product->getFinalPrice());
                return $price;
                break;
            case 'url':
                return strpos($product->getProductUrl(), '?') > 0 ? substr($product->getProductUrl(), 0, strpos($product->getProductUrl(), '?')) : $product->getProductUrl();
                break;
            case 'image':
                return Mage::getModel('catalog/product_media_config')->getMediaUrl($product->getImage());
                break;
            case 'category':
                return $this->_getTheCrumb($product);
                break;
            case 'manufacturer':
                return $product->getAttributeText('manufacturer');
                $product->isSaleable();
                break;
            case 'availability':
                return $this->_getAvailability($product);
                break;
            case 'stock':
                return $product->isSaleable() ? 'Y' : 'N';
                break;
            case 'description':
                return strip_tags(html_entity_decode($product->getDescription()));
                break;
        }
    }
}
