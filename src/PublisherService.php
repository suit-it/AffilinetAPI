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
 * Publisher Service
 *
 */
class PublisherService
{
  const SEVEN_DAYS = "Last7days";

  const MSG_ALL = "All";
  const MSG_UNREAD = "UnreadMessages";
  const MSG_READ = "ReadMessages";

  const ALL_ACCEPTED_PARTNERSHIPS = "AllAcceptedPartnerships";

  const ALL_RATES = "AllRates";

  const ALL = "All";

	const DATE_OF_REGISTRATION = "DateOfRegistration";

  private $accountServiceWsdl = "";
  private $wsdls;
  private $logon;
  private $soapClients;

  private $displaySettings;


  public function __construct($logon) {
    // Check Parameters
    if(!($logon instanceOf PublisherLogon)) {
      throw new InvalidArgumentException("Logon is not an instance of PublisherLogon");
    }

    $this->logon = $logon;
    $this->soapClients = array();
    $this->wsdls = array();

    $this->initWsdls();
    $this->initSoapClients();
    $this->initDefaultDisplaySettings();
  }


  public function getLinkedAccounts() {
    return $this->getSoapClientFrom('account_service')->GetLinkedAccounts($this->getCommonParams());
  }


  public function getPayments($params) {
    $paymentParams = $this->getCommonParams();

    $paymentParams = $params + $paymentParams;

    return $this->getSoapClientFrom('account_service')->GetPayments($paymentParams);
  }


  public function getPublisherSummary() {
    return $this->getSoapClientFrom('account_service')->GetPublisherSummary($this->logon->getToken());
  }


  public function getCreativeCategories($params) {
    $creativeCategoriesParams = array(
      'CredentialToken' => $this->logon->getToken()
    );


    $creativeCategoriesParams = $params + $creativeCategoriesParams;

    return $this->getSoapClientFrom('publisher_creative')->
      GetCreativeCategories($creativeCategoriesParams);
  }


  public function searchCreatives($params) {
    $searchCreativesParams = array(
      'CredentialToken' => $this->logon->getToken()
    );

    $searchCreativesParams = $params + $searchCreativesParams;

    return $this->getSoapClientFrom('publisher_creative')->
      SearchCreatives($searchCreativesParams);
  }


  public function getProgramInfoMessages($params) {
    if(isset($params['request']) && !isset($params['GetProgramInfoMessagesRequestMessage'])) {
      $params['GetProgramInfoMessagesRequestMessage'] = $params['request'];
    }

    $programInfoMessagesParams = array(
      'CredentialToken' => $this->logon->getToken()
    );

    $programInfoMessagesParams = $params + $programInfoMessagesParams;

    return $this->getSoapClientFrom('publisher_inbox')->GetProgramInfoMessages($programInfoMessagesParams);
  }


  public function getProgramStatusMessages($params) {
    if(isset($params['request']) && !isset($params['GetProgramStatusMessagesRequestMessage'])) {
      $params['GetProgramStatusMessagesRequestMessage'] = $params['request'];
    }

    $programStatusMessagesParams = array(
      'CredentialToken' => $this->logon->getToken()
    );

    $programStatusMessagesParams = $params + $programStatusMessagesParams;

    return $this->getSoapClientFrom('publisher_inbox')->GetProgramStatusMessages($programStatusMessagesParams);
  }


  public function getRateChanges($params) {
    if(isset($params['request']) && !isset($params['GetRateChangesRequestMessage'])) {
      $params['GetRateChangesRequestMessage'] = $params['request'];
    }

    $rateChangesParams = array(
      'CredentialToken' => $this->logon->getToken()
    );

    $rateChangesParams = $params + $rateChangesParams;

    return $this->getSoapClientFrom('publisher_inbox')->GetRateChanges($rateChangesParams);
  }


  public function setMessageStatus() {
    throw new \Exception("not implemented");
  }


  public function searchVoucherCodes($params) {
    if(isset($params['request']) && !isset($params['SearchVoucherCodesRequestMessage'])) {
      $params['SearchVoucherCodesRequestMessage'] = $params['request'];
    }

    $voucherCodesParams = array(
      'CredentialToken' => $this->logon->getToken()
    );

    $voucherCodesParams = $params + $voucherCodesParams;

    return $this->getSoapClientFrom('publisher_inbox')->SearchVoucherCodes($voucherCodesParams);
  }


  public function setDefaultDisplaySettings($newDisplaySettings) {
    if(is_array($newDisplaySettings) == false) {
      throw New \InvalidArgumentException("exptected type array");
    }

    if(!isset($newDisplaySettings['CurrentPage']) || !isset($newDisplaySettings['PageSize'])) {
      throw new \InvalidArgumentException('Wrong Argument expected array("CurrentPage" => (integer), "PageSize" => (integer))');
    }

    $this->displaySettings = $newDisplaySettings;
  }


  public function getDefaultDisplaySettings() {
    return $this->displaySettings;
  }


  public function getPrograms($params) {
    $programParams = array(
      'CredentialToken' => $this->logon->getToken()
    );

    $programParams = $params + $programParams;

    return $this->getSoapClientFrom('publisher_program')->GetPrograms($programParams);
  }


  public function getProgramCategories() {
    return $this->getSoapClientFrom('publisher_program')->
      GetProgramCategories($this->logon->getToken());
  }


  public function getProgramRates($params) {
    $programRatesParams = $this->getCommonParams();

    $programRatesParams = $params + $programRatesParams;

    return $this->getSoapClientFrom('publisher_program')->
      GetProgramRates($programRatesParams);
  }


  public function getTransactions($params) {
    $transactionParams = array(
      'CredentialToken' => $this->logon->getToken()
    );

    $transactionParams = $params + $transactionParams;

    return $this->getSoapClientFrom('publisher_statistics')->
      GetTransactions($transactionParams);
  }


  public function getBasketItems($params) {
    $basketItemsParams = array(
      'CredentialToken' => $this->logon->getToken()
    );

    $basketItemsParams = $params + $basketItemsParams;

    return $this->getSoapClientFrom('publisher_statistics')->
      GetBasketItems($basketItemsParams);
  }


	public function getDailyStatistics($params) {
    if(isset($params['request']) && !isset($params['GetDailyStatisticsRequestMessage'])) {
      $params['GetDailyStatisticsRequestMessage'] = $params['request'];
    }

		$dailyStatisticsParams = array(
			'CredentialToken' => $this->logon->getToken()
		);

		$dailyStatisticsParams = $params + $dailyStatisticsParams;

		return $this->getSoapClientFrom('publisher_statistics')->
			GetDailyStatistics($dailyStatisticsParams);
	}


	public function getProgramStatistics($params) {
    if(isset($params['request']) && !isset($params['GetProgramStatisticsRequestMessage'])) {
      $params['GetProgramStatisticsRequestMessage'] = $params['request'];
    }

		$programStatisticsParams = array(
			'CredentialToken' => $this->logon->getToken()
		);

		$programStatisticsParams = $params + $programStatisticsParams;

		return $this->getSoapClientFrom('publisher_statistics')->
			GetProgramStatistics($programStatisticsParams);
	}


	public function getSalesLeadsStatistics($params) {
    if(isset($params['request']) && !isset($params['GetSalesLeadsStatisticsRequestMessage'])) {
      $params['GetSalesLeadsStatisticsRequestMessage'] = $params['request'];
    }

		$salesLeadStatisticsParams = array(
			'CredentialToken' => $this->logon->getToken()
		);

		$salesLeadStatisticsParams = $params + $salesLeadStatisticsParams;

		return $this->getSoapClientFrom('publisher_statistics')->
			GetSalesLeadsStatistics($salesLeadStatisticsParams);
	}


  private function initDefaultDisplaySettings() {
    $this->displaySettings = array(
      'CurrentPage' => 1,
      'PageSize' => 10,
      'SortByEnum' => 'ProgramId',
      'SortOrderEnum' => 'Ascending'
    );
  }

  private function initWsdls() {
    $this->wsdls['account_service'] = "https://api.affili.net/V2.0/AccountService.svc?wsdl";
    $this->wsdls['publisher_creative'] = "https://api.affili.net/V2.0/PublisherCreative.svc?wsdl";
    $this->wsdls['publisher_inbox'] = "https://api.affili.net/V2.0/PublisherInbox.svc?wsdl";
    $this->wsdls['publisher_program'] = "https://api.affili.net/V2.0/PublisherProgram.svc?wsdl";
    $this->wsdls['publisher_statistics'] = "https://api.affili.net/V2.0/PublisherStatistics.svc?wsdl";
  }

  private function initSoapClients() {
    $this->soapClients['account_service'] = null;
    $this->soapClients['publisher_creative'] = null;
    $this->soapClients['publisher_inbox'] = null;
    $this->soapClients['publisher_program'] = null;
    $this->soapClients['publisher_statistics'] = null;
  }

  private function getSoapClientFrom($service) {
    if($this->soapClients[$service] == null) {
      $this->soapClients[$service] = new \SoapClient($this->wsdls[$service]);
    }

    return $this->soapClients[$service];
  }

  private function getCommonParams() {
    return array(
      'CredentialToken' => $this->logon->getToken(),
      'PublisherId' => $this->logon->getPublisherId()
    );
  }
}
