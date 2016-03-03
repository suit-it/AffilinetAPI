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


  private function initWsdls() {
    $this->wsdls['account_service'] = "https://api.affili.net/V2.0/AccountService.svc?wsdl";
  }

  private function initSoapClients() {
    $this->soapClients['account_service'] = null;
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
