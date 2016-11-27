<?php
namespace Shieldfy\Normalizer\Normalizers;

use Shieldfy\Normalizer\NormalizeInterface;
use Shieldfy\Normalizer\PreSearchTrait;
use Shieldfy\Sniffer\Sniffer;

class NormalizeSerialize implements NormalizeInterface
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
		$this->preSearch = [':',';','{'];
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

		$result = (new Sniffer)->sniff($this->value,'serialize'); 
		if(!$result) return $this->value;

		if($this->value === 'b:0;') return 'false';
        if($this->value === 'b:1;') return 'true';
        
        $decoded = @unserialize($this->value);
        if($decoded === false) return $this->value;

        if(is_array($decoded)){
            $array_value = '';
            array_walk_recursive($decoded, function($v,$k) use(&$array_value){
                $array_value .= $k.' '.$v;
            });
            $decoded = $array_value;
        }
		return $decoded;
	}

}