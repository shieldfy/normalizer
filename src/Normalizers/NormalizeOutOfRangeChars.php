<?php
namespace Shieldfy\Normalizer\Normalizers;

use Shieldfy\Normalizer\NormalizeInterface;
use Shieldfy\Normalizer\PreSearchTrait;

class NormalizeOutOfRangeChars implements NormalizeInterface
{
	use PreSearchTrait;

	protected $value;

	/**
	* Constructor
	* 
	* @param mixed $value
	* 
	*/
	public function __construct($value)
	{
		$this->value = $value;
		$this->preSearch = null;
	}

	/**
	* Run the Normalizer
	* 
	* @return mixed normalized $value
	* 
	*/
	public function run()
	{
		$values = str_split($this->value);
        foreach ($values as $item) {
            if (ord($item) >= 127) {
                $this->value = str_replace($item, ' ', $this->value);
            }
        }
        return $this->value;
	}

}