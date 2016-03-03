<?php
namespace AffilinetAPI;

include_once 'src/ProductLogon.php';
include_once 'src/ProductService.php';

class AffilinetProductServiceTest extends \PHPUnit_Framework_TestCase
{
  protected static $logon;
  protected static $productService;

  /**
   * @beforeClass
   */
  public static function setUpBeforeClass() {
    self::$logon = new ProductLogon(\AffilinetCredentials::PUBLISHER_ID,\AffilinetCredentials::PRODUCT_PASSWORD);
    self::$productService = new ProductService(self::$logon);
  }


  /**
   * @test
   *
  */
  public function testGetPropertyListByShopId() {
    $propertyList = self::$productService->getPropertyListByShopId(1);
    $this->assertTrue(isset($propertyList->GetPropertyListSummary));
  }

  /**
   * @test
   *
  */
  public function testGetShopList() {
    $shopList = self::$productService->getShopList();

    $this->assertTrue( isset($shopList->GetShopListSummary) );
    $this->assertTrue( isset($shopList->Shops) );
    $this->assertGreaterThan(0, count($shopList->Shops->Shop));
  }


  /**
   * @test
   *
  */
  public function testGetCategoriesByShopId() {
    $categories = self::$productService->getCategoriesByShopId(1);

    $this->assertTrue(isset($categories->Categories));
    $this->assertTrue(isset($categories->GetCategoryListSummary));
  }


  /**
   * @test
  */
  public function testSearchProducts() {
    $params = ['Query' => 'Schadslfkjasdlfkjasdölfkajdsfmuck'];
    $searchProducts = self::$productService->searchProducts($params);

    $this->assertTrue(isset($searchProducts->Products));
    $this->assertTrue(isset($searchProducts->ProductsSummary));
  }

  /**
   * @test
  */
  public function testGetProducts() {
    $products = self::$productService->getProducts(array('ProductIds' => array('0')));

    $this->assertTrue(isset($products->ProductsSummary));
    $this->assertTrue(isset($products->Products));
  }

}
