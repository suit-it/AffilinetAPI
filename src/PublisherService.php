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

  private $accountServiceWsdl = "";
  private $wsdls;
  private $logon;
  private $soapClients;


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
  }


  public function getLinkedAccounts() {
    return $this->getSoapClientFrom('account_service')->GetLinkedAccounts($this->getCommonParams());
  }


  public function getPayments($params) {
    $paymentParams = $this->getCommonParams();
    $paymentParams = array_merge($paymentParams, $params);

    return $this->getSoapClientFrom('account_service')->GetPayments($paymentParams);
  }


  public function getPublisherSummary() {
    return $this->getSoapClientFrom('account_service')->GetPublisherSummary($this->logon->getToken());
  }


  public function getCreativeCategories($params) {
    $creativeCategoriesParams = array(
      'CredentialToken' => $this->logon->getToken()
    );

    $creativeCategoriesParams = array_merge($creativeCategoriesParams, $params);

    return $this->getSoapClientFrom('publisher_creative')->
      GetCreativeCategories($creativeCategoriesParams);
  }


  public function searchCreatives($params) {
    $searchCreativesParams = array(
      'CredentialToken' => $this->logon->getToken()
    );

    $searchCreativesParams = array_merge($searchCreativesParams, $params);

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

    $programInfoMessagesParams = array_merge($programInfoMessagesParams, $params);

    return $this->getSoapClientFrom('publisher_inbox')->GetProgramInfoMessages($programInfoMessagesParams);
  }


  public function getProgramStatusMessages($params) {
    if(isset($params['request']) && !isset($params['GetProgramStatusMessagesRequestMessage'])) {
      $params['GetProgramStatusMessagesRequestMessage'] = $params['request'];
    }

    $programStatusMessagesParams = array(
      'CredentialToken' => $this->logon->getToken()
    );

    $programStatusMessagesParams = array_merge($programStatusMessagesParams, $params);

    return $this->getSoapClientFrom('publisher_inbox')->GetProgramStatusMessages($programStatusMessagesParams);
  }

  private function initWsdls() {
    $this->wsdls['account_service'] = "https://api.affili.net/V2.0/AccountService.svc?wsdl";
    $this->wsdls['publisher_creative'] = "https://api.affili.net/V2.0/PublisherCreative.svc?wsdl";
    $this->wsdls['publisher_inbox'] = "https://api.affili.net/V2.0/PublisherInbox.svc?wsdl";
  }

  private function initSoapClients() {
    $this->soapClients['account_service'] = null;
    $this->soapClients['publisher_creative'] = null;
    $this->soapClients['publisher_inbox'] = null;
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
