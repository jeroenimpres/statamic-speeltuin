<?php

namespace Impres\Translatee\Actions;

use Statamic\Facades\Config;

class GetDefaultSiteAction
{
    public function execute()
    {
        $defaultLocale = Config::getDefaultLocale();
        $sites = config('statamic.sites.sites');
        return $sites[$defaultLocale];
    }
}