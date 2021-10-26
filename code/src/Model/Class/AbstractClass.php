<?php
namespace App\Model\Class;

use Exception;

abstract class AbstractClass {
    public function __construct(array $data = []) {
        if (count($data) > 0) {
			$this->hydrate($data);
		}
    }

    public function hydrate(array $data) {
		foreach ($data as $key => $value) {
			$keyUnderscore = strpos($key, '_');

			if ($keyUnderscore > 0) {
				$key = str_replace('_', '', $key);
				$key = str_replace($key[$keyUnderscore], ucfirst($key[$keyUnderscore]), $key);
			}
			
			$setterMethod = 'set' . ucfirst($key);
			
			if (method_exists($this, $setterMethod)) {
				if (is_string($value)) {
					$value = htmlspecialchars($value);
				}

				$this->$setterMethod($value);
			}
		}
	}
}