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

  /**
   * @test
  */
  public function testGetRateChanges() {
    $params = array(
      'TimeSpan' => PublisherService::SEVEN_DAYS,
      'Query' => '',
      'MessageStatus' => PublisherService::ALL_RATES
    );

    $rateChanges = self::$publisherService->getRateChanges(array(
      'request' => $params
    ));

    $this->assertInstanceOf('stdClass', $rateChanges);
    $this->assertTrue(isset($rateChanges->ArrayOfChangedCommissionRateProgram));
  }

  /**
   * @test
   * @expectedException Exception
  */
  public function testSetMessageStatus() {
    self::$publisherService->setMessageStatus();
  }

  /**
   * @test
  */
  public function testSearchVoucherCodes() {
    $params = array(
			'StartDate' => strtotime("now"),
			'EndDate' => strtotime("now"),
			'VoucherCodeContent' => 'Empty',
			'VoucherType' => 'AllProducts'
    );

    $voucherCodes = self::$publisherService->searchVoucherCodes(
      array(
      'request' => $params,
      'DisplaySettings' => array(
        'CurrentPage' => 1,
        'PageSize' => 10
      )
    ));

    $this->assertInstanceOf('stdClass', $voucherCodes);
		$this->assertTrue(isset($voucherCodes->VoucherCodeCollection));
  }

  /**
   * @test
  */
  public function testSetDefaultDisplaySettings() {
    $myDisplaySettings = array(
      'CurrentPage' => 1,
      'PageSize' => 10
    );

    $displaySettings = self::$publisherService->setDefaultDisplaySettings($myDisplaySettings);
    $expected = self::$publisherService->getDefaultDisplaySettings();

    $this->assertEquals($expected, $myDisplaySettings);
  }

  /**
   * @test
   * @expectedException InvalidArgumentException
   * @expectedExceptionMessage Wrong Argument expected array("CurrentPage" => (integer), "PageSize" => (integer))
  */
  public function testSetDefaultDisplaySettingsWithWrongArguments() {
    $myWrongDisplaySettings = array(
      'CrrntPa' => 1,
      'PageSize' => 10
    );

    $displaySettings = self::$publisherService->setDefaultDisplaySettings($myWrongDisplaySettings);
  }

  /**
   * @test
   * @expectedException InvalidArgumentException
   * @expectedExceptionMessage exptected type array
  */
  public function testSetDefaultDisplaySettingsWithWrongType() {
    $displaySettings = self::$publisherService->setDefaultDisplaySettings(0);
  }


  /**
   * @test
   */
  public function testGetPrograms() {
    $programsQuery = array(
      'PartnershipStatus' => array('Active')
    );

    $programs = self::$publisherService->getPrograms(array(
      'DisplaySettings' => self::$publisherService->getDefaultDisplaySettings(),
      'GetProgramsQuery' => $programsQuery
    ));

    $this->assertInstanceOf('stdClass', $programs);
    $this->assertTrue(isset($programs->ProgramCollection));
  }


  /**
   * @test
   */
  public function testGetProgramCategories() {
    $programCategories = self::$publisherService->getProgramCategories();

    $this->assertInstanceOf('stdClass', $programCategories);
    $this->assertTrue(isset($programCategories->RootCategories));
  }


  /**
   * @test
  */
  public function testGetProgramRates() {
    $programRates = self::$publisherService->getProgramRates(array(
      'ProgramId' => \AffilinetCredentials::PROGRAM_ID
    ));

    $this->assertInstanceOf('stdClass', $programRates);
    $this->assertTrue(isset($programRates->RateCollection));
  }


  /**
   * @test
  */
  public function testGetTransactions() {
    $startDate = strtotime("-2 weeks");
    $endDate = strtotime("today");

    $transactionQuery = array(
      'TransactionStatus' => PublisherService::ALL,
      'StartDate' => $startDate,
      'EndDate' => $endDate
    );

    $transactions = self::$publisherService->getTransactions(array(
      'PageSettings' => self::$publisherService->getDefaultDisplaySettings(),
      'TransactionQuery' => $transactionQuery
    ));

    $this->assertInstanceOf('stdClass', $transactions);
    $this->assertTrue(isset($transactions->TransactionCollection));
  }


  /**
   * @test
   */
  public function testGetBasketItems() {
    $basketItems = self::$publisherService->getBasketItems(array(
      'BasketId' => 10
    ));

    $this->assertInstanceOf('stdClass', $basketItems);
    $this->assertTrue(isset($basketItems->BasketItemCollection));
  }


  /**
   * @test
   */
  public function testGetDailyStatistics() {
		$startDate = strtotime("-1 week");
		$endDate = strtotime("today");

		$params = array(
				'StartDate' => $startDate,
				'EndDate' => $endDate,
				'ProgramId' => '0',
				'SubId' => '',
				'ProgramTypes' => PublisherService::ALL,
				'ValuationType' => PublisherService::DATE_OF_REGISTRATION
		);

    $dailyStatistics = self::$publisherService->getDailyStatistics(array(
      'request' => $params
    ));

		$this->assertInstanceOf('stdClass', $dailyStatistics);
		$this->assertTrue(isset($dailyStatistics->DailyStatisticsRecords));
  }
}
