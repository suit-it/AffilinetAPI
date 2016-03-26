<?php
/*
 * This file is part of AffilinetFacade.
 *
 * (c) Michael Golenia <golenia@suit-it.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AffilinetFacade;

/**
 * Class PublisherService
 * @package AffilinetFacade
 */
class PublisherService
{
  const PAYMENT_STATUS_NONE = "None";
  const PAYMENT_STATUS_IN_PROCESS = "InProcess";
  const PAYMENT_STATUS_PAID = "Paid";
  const PAYMENT_STATUS_HOLD = "Hold";
  const PAYMENT_STATUS_SBA = "SBAFormMissing";

  const PAYMENT_TYPE_NONE = "None";
  const PAYMENT_TYPE_TRANSFER = "Transfer";
  const PAYMENT_TYPE_CHEQUE = "Cheque";
  const PAYMENT_TYPE_MONEYBOOKERS = "Moneybookers";
  const PAYMENT_TYPE_PAYPAL = "PayPal";

  const CREATIVE_TYPE_BANNER = "Banner";
  const CREATIVE_TYPE_TEXT = "Text";
  const CREATIVE_TYPE_HTML = "HTML";
  const CREATIVE_TYPE_ROTATION = "Rotation";

  const HTML_LINK_TYPE_HTML_BANNER = "HTMLBanner";
  const HTML_LINK_TYPE_FLASH_BANNER = "FlashBanner";
  const HTML_LINK_TYPE_MICROSITE = "Microsite";
  const HTML_LINK_TYPE_POPUP = "PopUp";
  const HTML_LINK_TYPE_POPUNDER  = "PopUnder";
  const HTML_LINK_TYPE_IFRAME = "IFrame";
  const HTML_LINK_TYPE_HTML_FORM = "HTMLForm";
  const HTML_LINK_TYPE_FLASH_FORM = "FlashForm";
  const HTML_LINK_TYPE_VIDEO_AD = "VideoAd";
  const HTML_LINK_TYPE_PRODUCT_LINK = "ProductLink";
  const HTML_LINK_TYPE_BANNER_ROTATION  = "BannerRotation";
  const HTML_LINK_TYPE_PAGE_PEEL = "PagePeel";
  const HTML_LINK_TYPE_OTHER = "Other";

  const TIME_SPAN_LAST_LOGIN = "LastLogin";
  const TIME_SPAN_LAST_LAST_7_DAYS = "Last7days";
  const TIME_SPAN_LAST_LAST_14_DAYS = "Last14days";
  const TIME_SPAN_LAST_MONTH = "LastMonth";
  const TIME_SPAN_LAST_3_MONTHS = "Last3Months";

  const MESSAGE_STATUS_ALL = "All";
  const MESSAGE_STATUS_UNREAD = "UnreadMessages";
  const MESSAGE_STATUS_READ = "ReadMessages";

  const MESSAGE_PARTNERSHIP_STATUS_ALL = "AllPartnerships";
  const MESSAGE_PARTNERSHIP_STATUS_ALL_ACCEPTED = "AllAcceptedPartnerships";
  const MESSAGE_PARTNERSHIP_STATUS_ALL_TEMPORARILY_ACCEPTED = "AllTemporarilyAcceptedPartnerships";
  const MESSAGE_PARTNERSHIP_STATUS_ALL_DECLINED = "AllDeclinedPartnerships";

  const PARTNERSHIP_ACTION_NOT_FOUND = "NotFound";
  const PARTNERSHIP_ACTION_NULL = "Null";
  const PARTNERSHIP_ACTION_PAUSED = "Paused";
  const PARTNERSHIP_ACTION_WARNING = "Warning";
  const PARTNERSHIP_ACTION_SPECIAL_PERMISSION = "SpecialPermission";
  const PARTNERSHIP_ACTION_SPECIAL_PERMISSION_END = "SpecialPermissionEnd";
  const PARTNERSHIP_ACTION_OK = "Ok";
  const PARTNERSHIP_ACTION_CANCELLED = "Cancelled";
  const PARTNERSHIP_ACTION_REFUSED = "Refused";
  const PARTNERSHIP_ACTION_WAITING = "Waiting";
  const PARTNERSHIP_ACTION_PRELIMANRY_OK = "PreliminaryOk";

  const MAIL_MESSAGE_STATUS_NONE = "None";
  const MAIL_MESSAGE_STATUS_READ = "Read";
  const MAIL_MESSAGE_STATUS_UNREAD = "Unread";
  const MAIL_MESSAGE_STATUS_DELETED = "Deleted";

  const SORT_BY_ID = "Id";
  const SORT_BY_PROGRAM_ID = "ProgramId";
  const SORT_BY_TITLE = "Title";
  const SORT_BY_LAST_CHANGE = "LastChangeDate";
  const SORT_BY_START_DATE = "StartDate";
  const SORT_BY_END_DATE = "EndDate";




  const SEVEN_DAYS = "Last7days";

  const MSG_ALL = "All";
  const MSG_UNREAD = "UnreadMessages";
  const MSG_READ = "ReadMessages";

  const ALL_ACCEPTED_PARTNERSHIPS = "AllAcceptedPartnerships";

  const ALL_RATES = "AllRates";

  const ALL = "All";

  const DATE_OF_REGISTRATION = "DateOfRegistration";


  private $wsdls;
  private $logon;
  private $soapClients;

  private $displaySettings;

  /**
   * PublisherService constructor.
   * @param $logon
   */
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

  /**
   * @return mixed
   */
  public function getLinkedAccounts() {
    return $this->getSoapClientFrom('account_service')->GetLinkedAccounts($this->getCommonParams());
  }

  /**
   * @param $params
   * @return mixed
   */
  public function getPayments($params) {
    $paymentParams = $this->getCommonParams();

    $paymentParams = $params + $paymentParams;

    return $this->getSoapClientFrom('account_service')->GetPayments($paymentParams);
  }

  /**
   * @return mixed
   */
  public function getPublisherSummary() {
    return $this->getSoapClientFrom('account_service')->GetPublisherSummary($this->logon->getToken());
  }

  /**
   * @param $params
   * @return mixed
   */
  public function getCreativeCategories($params) {
    $creativeCategoriesParams = array(
      'CredentialToken' => $this->logon->getToken()
    );

    $creativeCategoriesParams = $params + $creativeCategoriesParams;

    return $this->getSoapClientFrom('publisher_creative')->
      GetCreativeCategories($creativeCategoriesParams);
  }

  /**
   * @param $params
   * @return mixed
   */
  public function searchCreatives($params) {
    $searchCreativesParams = array(
      'CredentialToken' => $this->logon->getToken()
    );

    $searchCreativesParams = $params + $searchCreativesParams;

    return $this->getSoapClientFrom('publisher_creative')->
      SearchCreatives($searchCreativesParams);
  }

  /**
   * @param $params
   * @return mixed
   */
  public function getProgramInfoMessages($params) {
    if(isset($params['Request']) && !isset($params['GetProgramInfoMessagesRequestMessage'])) {
      $params['GetProgramInfoMessagesRequestMessage'] = $params['Request'];
    }

    $programInfoMessagesParams = array(
      'CredentialToken' => $this->logon->getToken()
    );

    $programInfoMessagesParams = $params + $programInfoMessagesParams;

    return $this->getSoapClientFrom('publisher_inbox')->GetProgramInfoMessages($programInfoMessagesParams);
  }

  /**
   * @param $params
   * @return mixed
   */
  public function getProgramStatusMessages($params) {
    if(isset($params['Request']) && !isset($params['GetProgramStatusMessagesRequestMessage'])) {
      $params['GetProgramStatusMessagesRequestMessage'] = $params['Request'];
    }

    $programStatusMessagesParams = array(
      'CredentialToken' => $this->logon->getToken()
    );

    $programStatusMessagesParams = $params + $programStatusMessagesParams;

    return $this->getSoapClientFrom('publisher_inbox')->GetProgramStatusMessages($programStatusMessagesParams);
  }

  /**
   * @param $params
   * @return mixed
   */
  public function getRateChanges($params) {
    if(isset($params['Request']) && !isset($params['GetRateChangesRequestMessage'])) {
      $params['GetRateChangesRequestMessage'] = $params['Request'];
    }

    $rateChangesParams = array(
      'CredentialToken' => $this->logon->getToken()
    );

    $rateChangesParams = $params + $rateChangesParams;

    return $this->getSoapClientFrom('publisher_inbox')->GetRateChanges($rateChangesParams);
  }

  /**
   * @throws \Exception
   */
  public function setMessageStatus() {
    throw new \Exception("not implemented");
  }

  /**
   * @param $params
   * @return mixed
   */
  public function searchVoucherCodes($params) {
    if(isset($params['Request']) && !isset($params['SearchVoucherCodesRequestMessage'])) {
      $params['SearchVoucherCodesRequestMessage'] = $params['Request'];
    }

    $voucherCodesParams = array(
      'CredentialToken' => $this->logon->getToken()
    );

    $voucherCodesParams = $params + $voucherCodesParams;

    return $this->getSoapClientFrom('publisher_inbox')->SearchVoucherCodes($voucherCodesParams);
  }

  /**
   * @param $newDisplaySettings
   */
  public function setDefaultDisplaySettings($newDisplaySettings) {
    if(is_array($newDisplaySettings) == false) {
      throw New \InvalidArgumentException("exptected type array");
    }

    if(!isset($newDisplaySettings['CurrentPage']) || !isset($newDisplaySettings['PageSize'])) {
      throw new \InvalidArgumentException('Wrong Argument expected array("CurrentPage" => (integer), "PageSize" => (integer))');
    }

    $this->displaySettings = $newDisplaySettings;
  }

  /**
   * @return mixed
   */
  public function getDefaultDisplaySettings() {
    return $this->displaySettings;
  }

  /**
   * @param $params
   * @return mixed
   */
  public function getPrograms($params) {
    $programParams = array(
      'CredentialToken' => $this->logon->getToken()
    );

    $programParams = $params + $programParams;

    return $this->getSoapClientFrom('publisher_program')->GetPrograms($programParams);
  }

  /**
   * @return mixed
   */
  public function getProgramCategories() {
    return $this->getSoapClientFrom('publisher_program')->
      GetProgramCategories($this->logon->getToken());
  }

  /**
   * @param $params
   * @return mixed
   */
  public function getProgramRates($params) {
    $programRatesParams = $this->getCommonParams();

    $programRatesParams = $params + $programRatesParams;

    return $this->getSoapClientFrom('publisher_program')->
      GetProgramRates($programRatesParams);
  }

  /**
   * @param $params
   * @return mixed
   */
  public function getTransactions($params) {
    $transactionParams = array(
      'CredentialToken' => $this->logon->getToken()
    );

    $transactionParams = $params + $transactionParams;

    return $this->getSoapClientFrom('publisher_statistics')->
      GetTransactions($transactionParams);
  }

  /**
   * @param $params
   * @return mixed
   */
  public function getBasketItems($params) {
    $basketItemsParams = array(
      'CredentialToken' => $this->logon->getToken()
    );

    $basketItemsParams = $params + $basketItemsParams;

    return $this->getSoapClientFrom('publisher_statistics')->
      GetBasketItems($basketItemsParams);
  }

  /**
   * @param $params
   * @return mixed
   */
	public function getDailyStatistics($params) {
    if(isset($params['Request']) && !isset($params['GetDailyStatisticsRequestMessage'])) {
      $params['GetDailyStatisticsRequestMessage'] = $params['Request'];
    }

		$dailyStatisticsParams = array(
			'CredentialToken' => $this->logon->getToken()
		);

		$dailyStatisticsParams = $params + $dailyStatisticsParams;

		return $this->getSoapClientFrom('publisher_statistics')->
			GetDailyStatistics($dailyStatisticsParams);
	}

  /**
   * @param $params
   * @return mixed
   */
	public function getProgramStatistics($params) {
    if(isset($params['Request']) && !isset($params['GetProgramStatisticsRequestMessage'])) {
      $params['GetProgramStatisticsRequestMessage'] = $params['Request'];
    }

		$programStatisticsParams = array(
			'CredentialToken' => $this->logon->getToken()
		);

		$programStatisticsParams = $params + $programStatisticsParams;

		return $this->getSoapClientFrom('publisher_statistics')->
			GetProgramStatistics($programStatisticsParams);
	}

  /**
   * @param $params
   * @return mixed
   */
	public function getSalesLeadsStatistics($params) {
    if(isset($params['Request']) && !isset($params['GetSalesLeadsStatisticsRequestMessage'])) {
      $params['GetSalesLeadsStatisticsRequestMessage'] = $params['Request'];
    }

		$salesLeadStatisticsParams = array(
			'CredentialToken' => $this->logon->getToken()
		);

		$salesLeadStatisticsParams = $params + $salesLeadStatisticsParams;

		return $this->getSoapClientFrom('publisher_statistics')->
			GetSalesLeadsStatistics($salesLeadStatisticsParams);
	}

  /**
   * @param $params
   * @return mixed
   */
	public function getSubIdStatistics($params) {
    if(isset($params['Request']) && !isset($params['GetSubIdStatisticsRequestMessage'])) {
      $params['GetSubIdStatisticsRequestMessage'] = $params['Request'];
    }

		$subIdStatisticsParams = array(
			'CredentialToken' => $this->logon->getToken()
		);

		$subIdStatisticsParams = $params + $subIdStatisticsParams;

		return $this->getSoapClientFrom('publisher_statistics')->
			GetSubIdStatistics($subIdStatisticsParams);
	}

  /**
   * @param $params
   * @return mixed
   */
	public function getClicksBySubIdPerDay($params) {
    if(isset($params['Request']) && !isset($params['GetClicksBySubIdPerDayRequestMessage'])) {
      $params['GetClicksBySubIdPerDayRequestMessage'] = $params['Request'];
    }

		$clicksBySubIdPerDayParams = array(
			'CredentialToken' => $this->logon->getToken()
		);

		$clicksBySubIdPerDayParams = $params + $clicksBySubIdPerDayParams;

		return $this->getSoapClientFrom('publisher_statistics')->
			GetClicksBySubIdPerDay($clicksBySubIdPerDayParams);
	}

  /**
   * @param $params
   * @return mixed
   */
	public function getPublisherClicksSummary($params) {
    if(isset($params['Request']) && !isset($params['GetPublisherClicksSummaryRequestMessage'])) {
      $params['GetPublisherClicksSummaryRequestMessage'] = $params['Request'];
    }

		$publisherClicksSummaryParams = array(
			'CredentialToken' => $this->logon->getToken()
		);

		$publisherClicksSummaryParams = $params + $publisherClicksSummaryParams;

		return $this->getSoapClientFrom('publisher_statistics')->
			GetPublisherClicksSummary($publisherClicksSummaryParams);
	}

  /**
   * @param $params
   * @return mixed
   */
	public function getPublisherStatisticsPerClick($params) {
    if(isset($params['Request']) && !isset($params['GetPublisherStatisticsPerClickRequestMessage'])) {
      $params['GetPublisherStatisticsPerClickRequestMessage'] = $params['Request'];
    }

		$publisherStatisticsPerClickParams = array(
			'CredentialToken' => $this->logon->getToken()
		);

		$publisherStatisticsPerClickParams = $params + $publisherStatisticsPerClickParams;

		return $this->getSoapClientFrom('publisher_statistics')->
			GetPublisherStatisticsPerClick($publisherStatisticsPerClickParams);
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
