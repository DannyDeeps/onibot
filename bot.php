<?php

use Discord\Discord;
use Discord\Parts\Channel\{ Guild, Channel, Message };
use Discord\Parts\Embed\Embed;
use React\Http\Message\Response;
use React\EventLoop\Factory;

require __DIR__ . '/vendor/autoload.php';

$loop = Factory::create();
$discord = new Discord([
    'token' => 'ODI1MTU0MTc3NzcyMTU5MDM2.YF5ytg.c6tGhN9N4XPDJQnxeXU0An3EtFw',
    'loop' => $loop,
    'disabledEvents' => []
]);

$discord->on('ready', function(Discord $discord)
{
    $discord->on('message', function (Message $message, Discord $discord)
    {
        switch (strtolower($message->content)) {
            case '!initrole':
                $embed= new Embed($discord, [
                    'title' => 'Classes',
                    'description' => 'Select the reactions below to be assigned the roles you prefer to play.',
                    'color' => '#00FF00'
                ]);

                $channel = $discord->getChannel('825144851267977256');
                $promise= $channel->sendMessage('', false, $embed);
                $react_array= [
                    'Heal' => ':Heal:825145748936589312',
                    'Tank' => ':Tank:825152268340953109',
                    'Range' => ':Range:825150110571954197',
                    'Attack' => ':Attack:825152209950867467'
                ];
                $results= [];
                foreach ($react_array as $name => $code) {
                    $promise->then(function(Message $message) use ($code) {
                        $results[]= $message->react($code);
                    }, function($e) {
                        echo "Error: " . $e->getMessage();
                    });
                }

                $promise->done(function() use ($results) {
                    return new Response(200, ['Content-Type' => 'application/json'], json_encode($results));
                });
                break;
            case '!initregion':
                $embed= new Embed($discord, [
                    'title' => 'Region',
                    'description' => 'Select the reactions below to be assigned the region you prefer to play on.',
                    'color' => '#00FF00'
                ]);

                $channel = $discord->getChannel('825144851267977256');
                $promise= $channel->sendMessage('', false, $embed);
                $react_array= [
                    'EU' => ':EU:825357985030209576',
                    'NA' => ':NA:825357985030209576',
                    'Range' => ':Range:825150110571954197',
                    'Attack' => ':Attack:825152209950867467'
                ];
                $results= [];
                foreach ($react_array as $name => $code) {
                    $promise->then(function(Message $message) use ($code) {
                        $results[]= $message->react($code);
                    }, function($e) {
                        echo "Error: " . $e->getMessage();
                    });
                }

                $promise->done(function() use ($results) {
                    return new Response(200, ['Content-Type' => 'application/json'], json_encode($results));
                });
                break;
        }
    });
});

$discord->run();