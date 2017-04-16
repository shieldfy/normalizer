<?php

/*
 * NOTICE OF LICENSE
 *
 * Part of the Shieldfy Normaization Package.
 *
 * This source file is subject to The MIT License (MIT)
 * that is bundled with this package in the LICENSE file.
 *
 * Package: Shieldfy Normaization Package
 * License: The MIT License (MIT)
 * Link:    https://shieldfy.com
 */

namespace Shieldfy\Normalizer\Normalizers;

use Shieldfy\Normalizer\NormalizeInterface;
use Shieldfy\Normalizer\PreSearchTrait;
use Shieldfy\Sniffer\Sniffer;

class NormalizeSerialize implements NormalizeInterface
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
        $this->value     = $value;
        $this->preSearch = [':', ';', '{'];
    }

    /**
     * Run the Normalizer.
     *
     * @return mixed normalized $value
     */
    public function run()
    {
        if (! $this->runPreSearch()) {
            return $this->value;
        }

        $result = (new Sniffer())->is($this->value, 'serialize');
        if (! $result) {
            return $this->value;
        }

        if ($this->value === 'b:0;') {
            return 'false';
        }
        if ($this->value === 'b:1;') {
            return 'true';
        }

        if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
            //options added @ v 7.0 which allow no evaluating for classes
            //object will be instantiated as __PHP_Incomplete_Class
            $decoded = @unserialize($this->value, []);
        } else {
            $decoded = false; //don't serialize it might be danger
        }

        if ($decoded === false) {
            return $this->value;
        }

        if (is_array($decoded)) {
            $arrayValue = '';
            array_walk_recursive($decoded, function ($value, $key) use (&$arrayValue) {
                $arrayValue .= $key.' '.$value;
            });
            $decoded = $arrayValue;
        }

        //Object of class __PHP_Incomplete_Class
        if (gettype($decoded) === 'object') {
            //object serialize must be the same
            return $this->value;
        }

        return $decoded;
    }
}
