<?php
/*
 * This file is part of AffilinetAPI.
 *
 * (c) Michael Golenia <golenia@suit-it.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AffilinetAPI;

/**
 * Product Service
 *
 */
class ProductService
{
  private $wsdl = "https://product-api.affili.net/V3/WSDLFactory/Product_ProductData.wsdl";
  private $logon;
  private $soapClient;


  public function __construct($logon) {
    // Check Parameters
    if(!($logon instanceOf ProductLogon)) {
      throw new InvalidArgumentException("Logon is not an instance of ProductLogon");
    }

    $this->logon = $logon;
  }


  public function searchProducts($params) {
    $searchProductsParams = $this->getCommonParams();
    $searchProductsParams['ShopIds'] = '0';
    
    $searchProductsParams = array_merge($searchProductsParams, $params);

    return $this->getSoapClient()->SearchProducts($searchProductsParams);
  }


  public function getProducts($params) {
    $productsParams = $this->getCommonParams();
    $productsParams = array_merge($productsParams, $params);

    return $this->getSoapClient()->getProducts($productsParams);
  }


  public function getShopList() {
    return $this->getSoapClient()->GetShopList($this->getCommonParams());
  }


  public function getCategoryList($params) {
    $categoryListParams = $this->getCommonParams();
    $categoryListParams = array_merge($categoryListParams, $params);

    return $this->getSoapClient()->getCategoryList($categoryListParams);  
  }

  
  public function getPropertyList($params) {
    $propertyListParams = $this->getCommonParams();
    $propertyListParams = array_merge($propertyListParams, $params);

    return $this->getSoapClient()->GetPropertyList($propertyListParams);
  }


  public function getCategoriesByShopId($shopId, $params = null) {
    $categoryListParams = $this->getCommonParams();
    $categoryListParams['ShopId'] = $shopId;

    if(!is_null($params)) {
      $categoryListParams = array_merge($categoryListParams, $params);
    }

    return $this->getSoapClient()->GetCategoryList($categoryListParams);
  }


  public function getPropertyListByShopId($shopId, $params = null) {
    // Check Parameters
    if(!is_int($shopId)) {
      throw new InvalidArgumentException("shopId must be from type Integer");
    }

    $propertyListParams = $this->getCommonParams();
    $propertyListParams['ShopId'] = $shopId;

    if(!is_null($params)) {
        $propertyListParams = array_merge($propertyListParams, $params);
    }

    return $this->getSoapClient()->getPropertyList($propertyListParams);
  }


  private function getSoapClient() {
    if($this->soapClient == null) {
      $this->soapClient = new \SoapClient($this->wsdl);
    }

    return $this->soapClient;
  }


  private function getCommonParams() {
    return array(
      'CredentialToken' => $this->logon->getToken(),
      'PublisherId' => $this->logon->getPublisherId()
    );
  }
}
