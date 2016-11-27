<?php

namespace Shieldfy\Normalizer\Normalizers;

use Shieldfy\Normalizer\NormalizeInterface;
use Shieldfy\Normalizer\PreSearchTrait;

class NormalizeSQLHex implements NormalizeInterface
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
        $this->preSearch = ['0x'];
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

        $matches = [];
        if (preg_match_all('/(?:(?:\A|[^\d])0x[a-f\d]{3,}[a-f\d]*)+/im', $this->value, $matches)) {
            foreach ($matches[0] as $match) {
                $converted = '';
                foreach (str_split($match, 2) as $hexIndex) {
                    if (preg_match('/[a-f\d]{2,3}/i', $hexIndex)) {
                        $converted .= chr(hexdec($hexIndex));
                    }
                }
                $this->value = str_replace($match, $converted, $this->value);
            }
        }
        // take care of hex encoded ctrl chars
        $this->value = preg_replace('/0x\d+/m', ' 1 ', $this->value);

        return $this->value;
    }
}
