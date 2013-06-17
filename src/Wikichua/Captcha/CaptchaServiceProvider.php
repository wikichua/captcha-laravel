<?php namespace Wikichua\Captcha;

use Illuminate\Support\ServiceProvider;
use Route;


class CaptchaServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('wikichua/captcha');

		require __DIR__ . '/../../routes.php';
		require __DIR__ . '/../../validates.php';
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['captcha'] = $this->app->share(function($app){
			return new Captcha;
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('captcha');
	}

}