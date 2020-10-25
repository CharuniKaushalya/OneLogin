# OneLogin

## Installation & Basic Usage

Please see the [Base Installation Guide](https://socialiteproviders.com/usage/) then follow the provider specific instructions below.

### Add OneLogin provider for your oAuth provider
Clone the OneLoginExtendSocialite.php and Provider.php to your laravel project.

### Add configuration to `config/services.php`

```php
'onelogin' => [    
  'client_id' => env('ONELOGIN_CLIENT_ID'),  
  'client_secret' => env('ONELOGIN_CLIENT_SECRET'),  
  'redirect' => env('ONELOGIN_REDIRECT_URI'),
  'base_url' => env('ONELOGIN_BASE_URI') 
],
```

### Add provider event listener

Configure the package's listener to listen for `SocialiteWasCalled` events.

Add the event to your `listen[]` array in `app/Providers/EventServiceProvider`. See the [Base Installation Guide](https://socialiteproviders.com/usage/) for detailed instructions.

```php
protected $listen = [
    \SocialiteProviders\Manager\SocialiteWasCalled::class => [
        // ... other providers
        'App\\OneLogin\\OneLoginExtendSocialite@handle',
    ],
];
```

### Usage

You should now be able to use the provider like you would regularly use Socialite (assuming you have the facade installed):

```php
return Socialite::driver('onelogin')->redirect();
```

### Returned User fields

- ``id``
- ``name``
- ``email``
- ``nickname``
- ``first_name``
- ``last_name``
- ``family_name``
