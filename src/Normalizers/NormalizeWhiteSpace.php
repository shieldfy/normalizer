<?php

namespace Shieldfy\Normalizer\Normalizers;

use Shieldfy\Normalizer\NormalizeInterface;
use Shieldfy\Normalizer\PreSearchTrait;

class NormalizeWhiteSpace implements NormalizeInterface
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
        $this->preSearch = null;
    }

    /**
     * Run the Normalizer.
     *
     * @return mixed normalized $value
     */
    public function run()
    {
        //check for inline linebreaks
        $search = ['\r', '\n', '\f', '\t', '\v'];
        $this->value = str_replace($search, ';', $this->value);
        // replace replacement characters regular spaces
        $this->value = str_replace('�', ' ', $this->value);
        //convert real linebreaks
        return preg_replace('/(?:\n|\r|\v)/m', '  ', $this->value);
    }
}
