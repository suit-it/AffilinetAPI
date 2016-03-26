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

include_once 'Logon.php';

/**
 * Class PublisherLogon
 * @package AffilinetFacade
 */
class PublisherLogon extends Logon
{
  // Logon Webservice endpoint
  protected $wsdl = "https://api.affili.net/V2.0/Logon.svc?wsdl";

  // Web Service Type
  protected $webserviceType = "Publisher";
}
