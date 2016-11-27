<?php

namespace Shieldfy\Normalizer\Normalizers;

use Shieldfy\Normalizer\NormalizeInterface;
use Shieldfy\Normalizer\PreSearchTrait;

class NormalizeUTFHexEncode implements NormalizeInterface
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
        $this->preSearch = ['\u', '%u'];
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

        if (!preg_match('/[%\\\]u([0-9a-fA-F]{4})/U', $this->value)) {
            return $this->value;
        }

        preg_match_all('/[%\\\]u[0-9a-f]{4}/ims', $this->value, $matches);
        if (!empty($matches[0])) {
            foreach ($matches[0] as $match) {
                $unicode = str_replace('%u', '\\u', $match);
                //echo $unicode;exit;
                $unicode = json_decode('["'.$unicode.'"]');
                $this->value = str_replace($match, $unicode[0], $this->value);
            }
            $this->value .= "\n\u0001";
        }

        return $this->value;
    }
}
