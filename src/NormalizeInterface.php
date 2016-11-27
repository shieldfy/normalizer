<?php

namespace Shieldfy\Normalizer;

/**
 * String Type Interface.
 */
interface NormalizeInterface
{
    public function __construct($value);

    public function run();
}
