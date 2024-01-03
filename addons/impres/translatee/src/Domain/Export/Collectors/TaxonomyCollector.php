<?php

namespace Impres\Translatee\Domain\Export\Collectors;

use Statamic\Facades\Term;
use Statamic\Facades\Taxonomy;
use Impres\Translatee\Domain\Export\Collectors\Contracts\Collector;

class TaxonomyCollector implements Collector
{
    /**
     * Retrieves all terms in all taxonomies.
     *
     * @return Collection
     */
    public function all($config)
    {
        $taxonomies = Taxonomy::handles();
        $terms = collect();

        foreach ($taxonomies as $handle) {
            $items = Term::whereTaxonomy($handle);

            foreach ($items as $entry) {
                $terms = $terms->push($entry);
            }
        }

        return $terms;
    }

    /**
     * Returns all terms in the selected taxonomy.
     *
     * @param string $handle
     * @return \Statamic\Taxonomies\TermCollection
     */
    public function find($handle)
    {
        return Term::whereTaxonomy($handle);
    }
}
