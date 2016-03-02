<?php
namespace AffilinetAPI;

include_once 'src/ProductLogon.php';
include_once 'src/ProductService.php';

class AffilinetProductServiceTest extends \PHPUnit_Framework_TestCase
{
  protected static $logon;
  protected static $affilinetProductData;

  /**
   * @beforeClass
   */
  public static function setUpBeforeClass() {
    self::$logon = new ProductLogon(\AffilinetCredentials::PUBLISHER_ID,\AffilinetCredentials::PRODUCT_PASSWORD);
    self::$affilinetProductData = new ProductService(self::$logon);
  }


  /**
   * @test
   *
  */
  public function testGetPropertyListByShopId() {
    $propertyList = self::$affilinetProductData->getPropertyListByShopId(1);
    $this->assertTrue(isset($propertyList->GetPropertyListSummary));
  }


  /**
   * @test
   *
  */
  public function testGetShopList() {
    $shopList = self::$affilinetProductData->getShopList();

    $this->assertTrue( isset($shopList->GetShopListSummary) );
    $this->assertTrue( isset($shopList->Shops) );
    $this->assertGreaterThan(0, count($shopList->Shops->Shop));
  }


  /**
   * @test
   *
  */
  public function testGetCategoriesByShopId() {
    $categories = self::$affilinetProductData->getCategoriesByShopId(1);

    $this->assertTrue(isset($categories->Categories));
    $this->assertTrue(isset($categories->GetCategoryListSummary));
  }


  /**
   * @test
  */
  public function tesetSearchProducts() {
    $params = ['Query' => 'Schadslfkjasdlfkjasdölfkajdsfmuck'];
    $searchProducts = self::$affilinetProductData->searchProducts($params);

    $this->assertTrue(isset($searchProducts->Products));
    $this->assertTrue(isset($searchProducts->ProductsSummary));
  }
}
