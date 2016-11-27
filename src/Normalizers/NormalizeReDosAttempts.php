<?php
namespace Shieldfy\Normalizer\Normalizers;

use Shieldfy\Normalizer\NormalizeInterface;
use Shieldfy\Normalizer\PreSearchTrait;

class NormalizeReDosAttempts implements NormalizeInterface
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
		if(strlen($this->value) < 10) return $this->value;
        return preg_replace('/([a-z])\1{10,}/i', '$1', $this->value);
	}

}