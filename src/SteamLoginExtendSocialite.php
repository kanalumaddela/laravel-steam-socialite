<?php

namespace kanalumaddela\LaravelSteamLogin;

use SocialiteProviders\Manager\SocialiteWasCalled;

class SteamLoginExtendSocialite
{
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('steam', SteamLoginSocialiteProvider::class);
    }
}
