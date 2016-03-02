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


  public function getShopList() {
    return $this->getSoapClient()->GetShopList(
      ['CredentialToken' => $this->logon->getToken(),
       'PublisherId' => $this->logon->getPublisherId(),
       'PageSettings' => ['PageSize' => 100]
      ]
    );
  }


  public function getCategoriesByShopId($shopId) {
    return $this->getSoapClient()->GetCategoryList(
      ['CredentialToken' => $this->logon->getToken(),
       'PublisherId' => $this->logon->getPublisherId(),
       'ShopId' => $shopId
      ]
    );
  }


  public function searchProducts($params) {
    $searchProductsParams = [
      'CredentialToken' => $this->logon->getToken(),
      'PublisherId' => $this->logon->getPublisherId(),
      'ShopIds' => isset($params['ShopIds']) ? $params['ShopIds'] : $this->getShopList(),
      'ShopIdMode' => isset($params['ShopIdMode']) ? $params['ShopIdMode'] : 'Include',
      'Query' => $params['Query'],
      'ExcludeSubCategories' => false,
      'PageSettings' => ["PageSize" => 100],
      'UseAffilinetCategories' => 'true'
    ];

    $searchProductsParams = array_merge($searchProductsParams, $params);

    return $this->getSoapClient()->SearchProducts($searchProductsParams);
  }


  private function getSoapClient() {
    if($this->soapClient == null) {
      $this->soapClient = new \SoapClient($this->wsdl);
    }

    return $this->soapClient;
  }

}
