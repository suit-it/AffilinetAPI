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
 * Class ProductLogon
 * @package AffilinetFacade
 */
class ProductLogon extends Logon
{
  // Logon Webservice endpoint
  protected $wsdl = "https://product-api.affili.net/Authentication/Logon.svc?wsdl";

  // Web Service Type
  protected $webserviceType = "Product";
}
