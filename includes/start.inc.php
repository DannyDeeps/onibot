<?php
    require_once 'vendor/autoload.php';

    error_reporting(E_ALL & ~E_DEPRECATED);

    ActiveRecord\Config::initialize(function($cfg)
    {
        $cfg->set_model_directory('models');
        $cfg->set_connections(
            array(
            'development' => 'mysql://oni:@localhost/oni',
            'production' => 'mysql://oni:@onibot/oni'
            )
        );
        // $cfg->set_default_connection('production');
    });