<?php
namespace Shieldfy\Normalizer\Normalizers;

use Shieldfy\Normalizer\NormalizeInterface;
use Shieldfy\Normalizer\PreSearchTrait;

class NormalizeJson implements NormalizeInterface
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
		$this->preSearch = [':','{','['];
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

		$decoded = json_decode($this->value,1);
	 	$result = (json_last_error() == JSON_ERROR_NONE) && is_array($decoded);
        if($result){
            /* decoded is array */
            if(is_array($decoded)){
                $array_value = '';
                array_walk_recursive($decoded, function($v,$k) use(&$array_value){
                    $array_value .= $k.' '.$v;
                });
                $this->value = $array_value;
            }
        }
        return $this->value;
	}

}