<?php

namespace Impres\Translatee\Domain\Export\Collectors;

use Statamic\Facades\GlobalSet;
use Impres\Translatee\Domain\Export\Collectors\Contracts\Collector;

class GlobalCollector implements Collector
{
    /**
     * Returns all global sets.
     *
     * @return \Statamic\Globals\GlobalCollection
     */
    public function all($config)
    {
        $allGlobalSetVariables = collect();
        foreach(GlobalSet::all() as $globalSet) {
            $allGlobalSetVariables = $allGlobalSetVariables->push($globalSet->inDefaultSite());
        }
        return $allGlobalSetVariables;
    }

    /**
     * Returns a single global set.
     *
     * @param string|int $handle
     * @return \Statamic\Globals\GlobalCollection
     */
    public function find($handle)
    {
        return GlobalSet::findByHandle($handle)->inDefaultSite();
    }
}
