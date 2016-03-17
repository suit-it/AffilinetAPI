# Affilinet Facade
simplifies the usage of the WebService by affili.net

## Installation

  Open a command console, enter your project directory and add the following to your composer.json file:
  
  ```sh
"require": {
    "suit-it/affilinet-api": "dev-master"
},
``` 
  
## Examples
```php
<?php
  define('PUBLISHER_ID', '12345');
  define('PUBLISHER_PASSWORD', 'abcde');

  $publisherLogon = new PublisherLogon(PUBLISHER_ID, PUBLISHER_PASSWORD);
  $publisherService = new PublisherService($publisherLogon);

  $programInfoMessagesResponse = $publisherService->getProgramInfoMessages([
  	'Request' => [
  		'TimeSpan' => PublisherService::TIME_SPAN_LAST_LOGIN,
  		'Query' => '',
  		'MessageStatus' => PublisherService::MESSAGE_STATUS_ALL
  	]
  ]);

  print_r(programInfoMessagesResponse);
```

## Why? See the difference!

### getProgramCategories without AffilinetAPI
```php
<?php 

// Send a request to the Logon Service to get an authentication token
$soapLogon = new SoapClient("https://api.affili.net/V2.0/Logon.svc?wsdl");
$token = $soapLogon->Logon(array(
    'Username'  => "AFFILINET_USER_NAME",
    'Password'  => "AFFILINET_PUBLISHER_PASSWORD",
    'WebServiceType' => "Publisher"
));
 
$soapRequest = new SoapClient("https://api.affili.net/V2.0/PublisherProgram.svc?wsdl");
$response = $soapRequest->GetProgramCategories($token);
 
// Show response
print_r($response);
```

### getProgramCategories with AffilinetAPI
```php
  <?php
  use AffilinetAPI;
  
  // Login
  $publisherLogon = new PublisherLogon("AFFILINET_USER_NAME", "AFFILINET_PUBLISHER_PASSWORD");
  $publisherService = new PublisherService($publisherLogon);
  
  // Show response
  print_r($publisherService->getProgramCategories();
```

## License

AffilinetAPI is released under the [MIT License](http://www.opensource.org/licenses/MIT).
