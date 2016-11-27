<?php
namespace Shieldfy\Normalizer\Normalizers;

use Shieldfy\Normalizer\NormalizeInterface;
use Shieldfy\Normalizer\PreSearchTrait;

class NormalizeQuotes implements NormalizeInterface
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
		$this->preSearch = ['\'', '`', '´', '’', '‘','"'];
	}

	/**
	* Run the Normalizer
	* 
	* @return mixed normalized $value
	* 
	*/
	public function run()
	{
		if( !$this->runPreSearch() ) return $this->value;
		$pattern = array('\'', '`', '´', '’', '‘','"');
        // normalize different quotes to "
        $this->value   = str_replace($pattern, '"', $this->value);
        //make sure harmless quoted strings don't generate false alerts
        $this->value = preg_replace('/^"([^"=\\!><~]+)"$/', '$1', $this->value);
        return $this->value;
	}

}