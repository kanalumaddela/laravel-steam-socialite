<?php

namespace kanalumaddela\LaravelSteamLogin;

use Illuminate\Http\Request;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class SteamLoginSocialiteProvider extends AbstractProvider
{
    public const IDENTIFIER = 'STEAM';

    /**
     * {@inheritdoc}
     */
    protected $stateless = true;

    /**
     * Steam API GetPlayerSummaries URL.
     *
     * @var string
     */
    protected const STEAM_PLAYER_API = 'https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?steamids=%s';

    /**
     * @var \kanalumaddela\LaravelSteamLogin\SteamLogin
     */
    protected $steamLogin;

    /**
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    public function __construct(Request $request, $clientId, $clientSecret, $redirectUrl, $guzzle = [])
    {
        parent::__construct($request, $clientId, $clientSecret, $redirectUrl, $guzzle);

        $this->app = app();
        $this->steamLogin = $this->app->get(SteamLogin::class);
        $this->steamLogin->setGuzzle($this->getHttpClient());
    }

    public function user()
    {
        if ($this->user) {
            return $this->user;
        }

        if ($this->steamLogin->validated()) {
        }
    }

    /**
     * @inheritDoc
     */
    protected function getAuthUrl($state)
    {
        if (!filter_var($this->redirectUrl, FILTER_VALIDATE_URL)) {
            $this->redirectUrl = $this->parseUrl($this->redirectUrl);
        }

        return $this->steamLogin->buildLoginUrl($this->redirectUrl, null, false, false);
    }

    /**
     * @inheritDoc
     */
    protected function getTokenUrl(): string
    {
        return static::STEAM_PLAYER_API;
    }

    /**
     * @inheritDoc
     */
    protected function getUserByToken($token)
    {
        // TODO: Implement getUserByToken() method.
    }

    /**
     * @inheritDoc
     */
    protected function mapUserToObject(array $user)
    {
        // TODO: Implement mapUserToObject() method.
    }

    protected function parseUrl(string $redirectUrl): string
    {
        $url = $this->app->get('url');

        try {
            $redirectUrl = $url->route($redirectUrl);
        } catch (RouteNotFoundException $e) {
            $redirectUrl = $url->to($redirectUrl);
        }

        return $redirectUrl;
    }
}
