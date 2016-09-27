<?php

/**
 * @author Dionisis Papanikolaou
 */
class DigitalUp_Skroutz_Helper_Feed extends Mage_Core_Helper_Abstract
{
    /**
     * get collection of items for xml feed
     * filter only simple, enabled and catalog/searchable products
     *
     * @return Mage_Catalog_Model_Product $collection
     */
    public function getCollection()
    {
        $collection = Mage::getResourceModel('catalog/product_collection')
            ->addAttributeToFilter('type_id', Mage_Catalog_Model_Product_Type::TYPE_SIMPLE)
            ->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
            ->addAttributeToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
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
    public function getTheCrumb($product)
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
}
