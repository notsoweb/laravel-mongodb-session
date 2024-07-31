<?php namespace Notsoweb\LaravelMongoDB\Session;
/**
 * @copyright 2024 Notsoweb (https://notsoweb.com) - All rights reserved.
 */

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

 /**
 * Proveedor de servicio
 * 
 * Permite registrar el paquete dentro de laravel para usar funciones precargadas.
 * 
 * @author Moisés Cortés C. <moises.cortes@notsoweb.com>
 * 
 * @version 1.0.0
 */
class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Versión de aplicación
     */
    const VERSION = '1.0.0';

    /**
     * Acciones al iniciar servicio
     */
    public function boot() : void
    {
        $this->app->resolving('session',
            fn($session) => $session->extend('mongodb',
                fn($app) => (new SessionManager($app))
                    ->driver('mongodb')
            )
        );
    }

    /**
     * Registrar servicios
     */
    public function register() : void
    {

    }
}