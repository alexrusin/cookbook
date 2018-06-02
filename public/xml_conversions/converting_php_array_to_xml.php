<?php

require '../bootstrap.php';
use App\Utilities\ConvertXml;

$testArray = [
  'bla' => 'blub',
  'foo' => 'bar',
  'Orders' => [
  		['OrderId' => 123, 'OrderName' => 'My Order 1'],
  		['OrderId' => 345, 'OrderName' => 'My Order 2'],

  ],
  'another_array' => [
    'stack' => 'overflow',
  ],
];

$convert = new ConvertXml();
var_dump($convert->arrayToXml($testArray, 'OrderResponse'));

echo '<br><hr><br>';

$myXml = '<OrderResponse>
    <bla>blub</bla>
    <foo>bar</foo>
    <Orders>
        <Order>
            <OrderId>123</OrderId>
            <OrderName>My Order 1</OrderName>
        </Order>
        <Order>
            <OrderId>345</OrderId>
            <OrderName>My Order 2</OrderName>
        </Order>
    </Orders>
    <another_array>
        <stack>overflow</stack>
    </another_array>
</OrderResponse>';

$xmlObj = simplexml_load_string($myXml);

var_dump(json_decode(json_encode($xmlObj)));