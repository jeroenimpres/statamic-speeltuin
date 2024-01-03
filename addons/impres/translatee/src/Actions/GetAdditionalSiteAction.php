<?php

namespace Impres\Translatee\Actions;

use Statamic\Facades\Config;

class GetAdditionalSiteAction
{
    /* Return available sites, but not the default site as this will always be used as base for translations */
    public function execute()
    {
        $availableSites = config('statamic.sites.sites');
        $defaultSite = [Config::getDefaultLocale() => app(GetDefaultSiteAction::class)->execute()];
        return array_diff_key($availableSites, $defaultSite);
    }
}