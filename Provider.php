<?php

namespace SocialiteProviders\OneLogin;

use Illuminate\Support\Arr;
use SocialiteProviders\Manager\OAuth2\User;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;

class Provider extends AbstractProvider
{
    /**
     * Unique Provider Identifier.
     */
    const IDENTIFIER = 'ONELOGIN';

    /**
     * {@inheritdoc}
     *
     * @see https://developers.onelogin.com/openid-connect/scopes
     */
    protected $scopes = [
        'openid',
        'profile',
        'email',
    ];

    /**
     * {@inheritdoc}
     */
    protected $scopeSeparator = ' ';

    /**
     * {@inheritdoc}
     */
    protected function getOneLoginUrl()
    {
        return $this->getConfig('base_url');
    }

    /**
     * {@inheritdoc}
     */
    public static function additionalConfigKeys()
    {
        return ['base_url'];
    }

    /**
     * {@inheritdoc}
     *
     * @see https://developers.onelogin.com/openid-connect/api/authorization-code
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase($this->getOneLoginUrl().'/auth', $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return $this->getOneLoginUrl().'/token';
    }

    /**
     * {@inheritdoc}
     *
     * @see https://developers.onelogin.com/openid-connect/api/user-info
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get($this->getOneLoginUrl().'/me', [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id'             => Arr::get($user, 'sub'),
            'email'          => Arr::get($user, 'email'),
            'updated_at'     => Arr::get($user, 'updated_at'),
            'nickname'       => Arr::get($user, 'preferred_username'),
            'name'           => Arr::get($user, 'name'),
            'first_name'     => Arr::get($user, 'given_name'),
            'last_name'      => Arr::get($user, 'family_name'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenFields($code)
    {
        return [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' =>$this->config['redirect'],
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @see https://developers.onelogin.com/openid-connect/api/authorization-code-grant
     */
    public function getAccessTokenResponse($code)
    {
        $AuthorizationCode = base64_encode($this->config['client_id'].':'.$this->config['client_secret']);

        $response = $this->getHttpClient()->post($this->getTokenUrl(), [
            'headers' => [
                'Authorization' => 'Basic '.$AuthorizationCode,
                'Content-Type' => 'application/x-www-form-urlencoded',
                ],
            'form_params' => $this->getTokenFields($code),
        ]);

        return json_decode($response->getBody(), true);
    }
}
