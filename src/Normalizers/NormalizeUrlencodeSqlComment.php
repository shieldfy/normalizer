<?php

namespace Shieldfy\Normalizer\Normalizers;

use Shieldfy\Normalizer\NormalizeInterface;
use Shieldfy\Normalizer\PreSearchTrait;

class NormalizeUrlencodeSqlComment implements NormalizeInterface
{
    use PreSearchTrait;

    protected $value;

    /**
     * Constructor.
     *
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
        $this->preSearch = ['%'];
    }

    /**
     * Run the Normalizer.
     *
     * @return mixed normalized $value
     */
    public function run()
    {
        if (!$this->runPreSearch()) {
            return $this->value;
        }

        if (preg_match_all('/(?:\%23.*?\%0a)/im', $this->value, $matches)) {
            $converted = $this->value;
            foreach ($matches[0] as $match) {
                $converted = str_replace($match, ' ', $converted);
            }
            $this->value .= "\n".$converted;
        }

        return $this->value;
    }
}
