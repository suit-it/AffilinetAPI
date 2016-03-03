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

include_once 'src/PublisherLogon.php';
include_once 'src/PublisherService.php';

class PublisherServiceTest extends \PHPUnit_Framework_TestCase
{
  protected static $logon;
  protected static $publisherService;

  /**
   * @beforeClass
   */
  public static function setUpBeforeClass() {
    self::$logon = new PublisherLogon(\AffilinetCredentials::PUBLISHER_ID,\AffilinetCredentials::PUBLISHER_PASSWORD);
    self::$publisherService = new PublisherService(self::$logon);
  }


  /**
   * @test
   */
  public function testGetLinkedAccounts() {
    $linkedAccounts = self::$publisherService->getLinkedAccounts();

    $this->assertInstanceOf('stdClass', $linkedAccounts);
    $this->assertTrue(isset($linkedAccounts->LinkedAccountCollection));
  }

  /**
   * @test
  */
  public function testGetPayments() {
    $startDate = strtotime("-5 years");
    $endDate = strtotime("today");

    $payments = self::$publisherService->getPayments(array(
      'StartDate' => $startDate,
      'EndDate' => $endDate
    ));

    $this->assertInstanceOf('stdClass', $payments);
  }

  /**
   * @test
  */
  public function testGetPublisherSummary() {
    $publisherSummary = self::$publisherService->getPublisherSummary();

    $this->assertInstanceOf('stdClass', $publisherSummary);
    $this->assertTrue(isset($publisherSummary->Partnerships));
  }

  /**
   * @test
 */
 public function testGetCreativeCategories() {
   $creativeCategories = self::$publisherService->getCreativeCategories(array(
     'ProgramId' => 10
   ));

   $this->assertInstanceOf('stdClass', $creativeCategories);
   $this->assertTrue(isset($creativeCategories->CreativeCategoryCollection));
 }

  /**
   * @test
  */
  public function testGetSearchCreatives() {
    $displaySettings = array(
      'PageSize' => 10,
      'CurrentPage' => 1
    );

    $searchCreativeQuery = array('ProgramIds' => array('3432'));

    $searchCreatives = self::$publisherService->searchCreatives(array(
      'DisplaySettings' => $displaySettings,
      'SearchCreativesQuery' => $searchCreativeQuery
    ));

    $this->assertInstanceOf('stdClass', $searchCreatives);
    $this->assertTrue(isset($searchCreatives->CreativeCollection));
  }


  /**
   * @test
  */
  public function testGetprogramInfoMessages() {
    $params = array(
      'TimeSpan' => PublisherService::SEVEN_DAYS,
      'Query' => '',
      'MessageStatus' => PublisherService::MSG_READ
    );

    $programInfoMessages = self::$publisherService->getProgramInfoMessages(array(
      'request' => $params
    ));

    $this->assertInstanceOf('stdClass', $programInfoMessages);
    $this->assertTrue(isset($programInfoMessages->ArrayOfMessage));
  }

  /**
   * @test
  */
  public function testGetProgramStatusMessages() {
    $params = array(
      'TimeSpan' => PublisherService::SEVEN_DAYS,
      'Query' => '',
      'MessagePartnershipStatus' => PublisherService::ALL_ACCEPTED_PARTNERSHIPS
    );

    $programStatusMessages = self::$publisherService->getProgramStatusMessages(array(
      'request' => $params
    ));

    $this->assertInstanceOf('stdClass', $programStatusMessages);
    $this->assertTrue(isset($programStatusMessages->ArrayOfPartnershipStatus));
  }
}

