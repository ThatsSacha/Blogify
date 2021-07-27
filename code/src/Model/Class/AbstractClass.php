<?php
namespace App\Model\Class;

abstract class AbstractClass {
    public function __construct(array $data = [], array $mandatoryFields = []) {
        if (count($data) > 0) {
			$this->hydrate($data, $mandatoryFields);
		}
    }

    public function hydrate(array $data, array $mandatoryFields = []) {
		foreach ($data as $key => $value) {
			var_dump($value, $data);
			/*if (in_array($key, $mandatoryFields)) {
				
			}*/
			$keyUnderscore = strpos($key, '_');
			if ($keyUnderscore > 0) {
				$key = str_replace('_', '', $keyUnderscore);
				$key = str_replace($key[$keyUnderscore], ucfirst($key[$keyUnderscore]), $key);
			}

			$setterMethod = 'set' . ucfirst($key);
		
			if (method_exists($this, $setterMethod)) {
			    $this->$setterMethod($value);
			}
		}
	}
}