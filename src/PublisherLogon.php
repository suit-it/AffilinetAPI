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

include('Logon.php');

/**
 * Class PublisherLogon
 * @package AffilinetAPI
 */
class PublisherLogon extends Logon
{
  // Logon Webservice endpoint
  protected $wsdl = "https://api.affili.net/V2.0/Logon.svc?wsdl";

  // Web Service Type
  protected $webserviceType = "Publisher";
}
