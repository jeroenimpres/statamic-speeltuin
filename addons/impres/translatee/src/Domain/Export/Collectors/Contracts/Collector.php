<?php

namespace Impres\Translatee\Domain\Export\Collectors\Contracts;

interface Collector
{
    public function all($config);

    public function find($handle);
}
