<?php
namespace Shieldfy\Normalizer;
trait PreSearchTrait
{
	protected $preSearch; //characters to search before normalize to speed up the process
	public function runPreSearch()
	{
		$needles = $this->preSearch;
		foreach($needles as $needle){
            if(strpos($this->value,$needle) !== false){
                return true;
            }
        }
        return false;
	}
}