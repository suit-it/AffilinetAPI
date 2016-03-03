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

  /**
   *
   *
  */
  public function __construct($logon) {
    // Check Parameters
    if(!($logon instanceOf ProductLogon)) {
      throw new InvalidArgumentException("Logon is not from class ProductLogon");
    }

    $this->logon = $logon;
  }


  public function searchProducts($params) {
    $searchProductsParams = [
      'CredentialToken' => $this->logon->getToken(),
      'PublisherId' => $this->logon->getPublisherId(),
      'ShopIds' => isset($params['ShopIds']) ? $params['ShopIds'] : '0',
      'ExcludeSubCategories' => false
    ];

    $searchProductsParams = array_merge($searchProductsParams, $params);

    return $this->getSoapClient()->SearchProducts($searchProductsParams);
  }

  public function getProducts($params) {
    $productsParams = array(
      'CredentialToken' => $this->logon->getToken(),
      'PublisherId' => $this->logon->getPublisherId()
    );

    $productsParams = array_merge($productsParams, $params);

    return $this->getSoapClient()->getProducts($productsParams);
  }


  public function getShopList() {
    return $this->getSoapClient()->GetShopList(
      ['CredentialToken' => $this->logon->getToken(),
       'PublisherId' => $this->logon->getPublisherId(),
      ]
    );
  }

  // TODO WRITE TEST
  public function getCategoryList() {

  }

  // TODO WRITE TEST
  public function getPropertyList() {

  }


  public function getCategoriesByShopId($shopId, $params = null) {
    $categoryListParams = array('CredentialToken' => $this->logon->getToken(),
                                'PublisherId' => $this->logon->getPublisherId(),
                                'ShopId' => $shopId);

    if(!is_null($params)) {
      $categoryListParams = array_merge($categoryListParams, $params);
    }

    return $this->getSoapClient()->GetCategoryList($categoryListParams);
  }


  /**
   *
   *
   * @return PropertyList
  */
  public function getPropertyListByShopId($shopId) {
    // Check Parameters
    if(!is_int($shopId)) {
      throw new InvalidArgumentException("shopId must be from type Integer");
    }

    return $this->getSoapClient()->getPropertyList(
      ['CredentialToken' => $this->logon->getToken(),
       'PublisherId' => $this->logon->getPublisherId(),
       'ShopId' => $shopId]
    );
  }


  private function getSoapClient() {
    if($this->soapClient == null) {
      $this->soapClient = new \SoapClient($this->wsdl);
    }

    return $this->soapClient;
  }

}
