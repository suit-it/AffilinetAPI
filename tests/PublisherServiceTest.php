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
}

