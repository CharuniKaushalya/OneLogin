<?php

namespace SocialiteProviders\OneLogin;

use SocialiteProviders\Manager\SocialiteWasCalled;

class OneLoginExtendSocialite
{
    /**
     * Register the provider.
     *
     * @param \SocialiteProviders\Manager\SocialiteWasCalled $socialiteWasCalled
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('onelogin', Provider::class);
    }
}
