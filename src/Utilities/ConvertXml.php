<?php 

namespace App\Utilities;

use SimpleXMLIterator;
use SimpleXMLElement;
use Inflector\Inflector;

class ConvertXml
{
	const UNKNOWN_KEY = 'undefined';

	public function xmlToArray(SimpleXMLIterator $xml) : array 
	{
		$a = array();
		for ($xml->rewind(); $xml->valid(); $xml->next()) {
			if (!array_key_exists($xml->key(), $a)) {
				$a[$xml->key()] = array();
			}

			if($xml->hasChildren()) {
				$a[$xml->key()][] = $this->xmlToArray($xml->current());
			} else {
				$a[$xml->key()] = (array) $xml->current()->attributes();
				$a[$xml->key()]['value'] = strval($xml->current());
			}
		}

		return $a;
	}

	public function arrayToXml(array $a, $rootElement = null) 
	{
		$starterString = '<?xml version="1.0" standalone="yes"?>';
		if (!$rootElement) {
			$starterString .= '<root></root>';
		} else {
			$starterString .='<' . trim($rootElement) . '></' . trim($rootElement) . '>';
		}
		
		$xml = new SimpleXMLElement($starterString);

		$this->phpToXml($a, $xml);

		return $xml->asXML();
	}

	protected function phpToXml($value, &$xml, $parent = null) 
	{
		$node = $value;
		if(is_object($node)) {
			$node = get_object_vars($node);
		}

		if (is_array($node)) {
			foreach ($node as $k => $v) {
				if (is_numeric($k)) {
					
					if($parent) {
						$k = Inflector::singular($parent);
					} else {
						$k = 'number' . $k;
					}
					
				}

				if (is_array($v)) {
					$newNode = $xml->addChild($k);
					$this->phpToXml($v, $newNode, $k);
				} elseif (is_object($v)) {
					$newNode = $xml->addChild($k);
					$this->phpToXml($v, $newNode, $k);
				} else {
					$xml->addChild($k, $v);
				}
			}
		} else {
			$xml->addChild(self::UNKNOWN_KEY, $node);
		}
	}
}