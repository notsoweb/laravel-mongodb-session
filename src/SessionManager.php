<?php namespace Notsoweb\LaravelMongoDB\Session;
/**
 * @copyright 2024 Notsoweb (https://notsoweb.com) - All rights reserved.
 */

use Illuminate\Foundation\Application;
use Illuminate\Support\Manager;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\MongoDbSessionHandler;

/**
 * Administrador de sesiones
 * 
 * Gestiona la sesión del usuario Laravel para bases de datos mongoDB.
 * 
 * @author Moisés Cortés C. <moises.cortes@notsoweb.com>
 * 
 * @version 1.0.0
 */
class SessionManager extends Manager
{
    /**
     * Constructor
     */
    public function __construct(
        protected Application $app
    ) {}

    /**
     * Crear instancia del controlador de base de datos
     */
    protected function createMongoDBDriver() : MongoDbSessionHandler
    {
        $connection = $this->getMongoDBConnection();

        $handler = new MongoDbSessionHandler($connection->getMongoClient(), $this->getMongoDBOptions(
            database: (string) $connection->getMongoDB(),
            collection: $this->app['config']['session.table'])
        );

        $handler->open('/', 'mongodb');

        return $handler;
    }

    /**
     * Obtener conexión del controlador MongoDB
     *
     * @return Connection
     */
    protected function getMongoDBConnection()
    {
        $connection = $this->app['config']['session.connection'];

        if (is_null($connection)) {
            $default = $this->app['db']->getDefaultConnection();

            $connections = $this->app['config']['database.connections'];

            if ($connections[$default]['driver'] != 'mongodb') {
                foreach ($connections as $name => $candidate) {
                    if ($candidate['driver'] == 'mongodb') {
                        $connection = $name;
                        break;
                    }
                }
            }
        }

        return $this->app['db']->connection($connection);
    }

    /**
     * Opciones de la sesión
     */
    protected function getMongoDBOptions($database, $collection) : array
    {
        return [
            'database' => $database,
            'collection' => $collection,
            'id_field' => '_id',
            'data_field' => 'payload',
            'time_field' => 'last_activity'
        ];
    }

    /**
     * Controlador por default
     */
    public function getDefaultDriver() : string
    {
        return 'mongodb';
    }
}