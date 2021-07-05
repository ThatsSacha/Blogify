<?php
namespace App\Model\Class;

abstract class AbstractClass {
    public function __construct(array $data = []) {
        if (count($data) > 0) {
			$this->hydrate($data);
		}
    }

    public function hydrate(array $data) {
		foreach ($data as $key => $value)
		{
			$setterMethod = 'set' . ucfirst($key);
		
			if (method_exists($this, $setterMethod)) {
			    $this->$setterMethod($value);
			}
		}
	}
}