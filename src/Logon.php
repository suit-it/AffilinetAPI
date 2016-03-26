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
 * Class Logon
 * @package AffilinetFacade
 */
abstract class Logon
{
  // Logon Webservice endpoint
	protected $wsdl;
  protected $webserviceType;

	// Credentials
	private $username;
	private $password;

	// Soap Client instance
	private $soapClient;


	/**
	 * Class constructor. Expects username and password
	 *
	 * @param int $username your publisher Id
	 * @param string $password your publisher/product web services password
	 */
	public function __construct($username, $password) {
		$this->username = $username;
		$this->password = $password;

    $this->soapClient = new \SoapClient($this->wsdl);
	}

	/**
	* Get authentication token
	*
	* @return string
	*/
	public function getToken() {
		// If there is no token stored or the token has already expired a new token is requested
		if(!isset($_SESSION['token']) or $this->tokenHasExpired()) {

			// Get new token and get store token expiration date
			$_SESSION['token'] = $this->createToken();
			$_SESSION['expiration_date'] = $this->getTokenExpirationDate();
		}

		// Return token
		return $_SESSION['token'];
  }

  public function getPublisherId() {
    return $this->username;
  }

	/**
	* Checks if token is expired
	*
	* @return boolean
	*/
	private function tokenHasExpired() {
		// If expiration date is not available, return true
		if (!isset($_SESSION['expiration_date'])) {
			return true;
		}

		// Check if the token has already expired
		return date(DATE_ATOM) > $_SESSION['expiration_date'];
	}

	/**
	* Create a new authentication token
	*
	* @return string
	*/
	private function createToken() {
		// Send a request to the Affilinet Logon Service to get an authentication token
		return $this->soapClient->Logon(array(
			'Username'  => $this->username,
			'Password'  => $this->password,
			'WebServiceType' => $this->webserviceType
		));
	}

	/**
	* Get token expiration date
	*
	* @return string
	*/
	private function getTokenExpirationDate() {
		// Send a request to the Affilinet Logon Service to get the token expiration date
		return $this->soapClient->GetIdentifierExpiration($_SESSION['token']);
	}
}
