<?php

use Discord\Discord;
use Discord\Parts\Channel\{ Guild, Channel, Message };
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
                $channel = $discord->getChannel('825144851267977256');
                $promise= $channel->sendMessage('>>> Select a reaction to designate your role!');
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
                    });
                }

                $promise->done(function() use ($results) {
                    return new Response(200, ['Content-Type' => 'application/json'], json_encode($results));
                });


                // $channel = $discord->getChannel('825144851267977256');
                // $channel->sendMessage('Select a reaction to designate your role!')->done(function ($new_message) use ($message, $react_array) {
                //     echo var_dump($new_message);
                //     return;

                //     $promise = null;
                //     $string = '';
                //     $string1 = '$promise = $new_message->react(array_shift($react_array))->done(function () use ($react_array, $i, $new_message) {';
                //     $string2 = '});';
                //     for ($i = 0; $i < count($react_array); $i++) {
                //       $string .= $string1;
                //     }
                //     for ($i = 0; $i < count($react_array); $i++) {
                //       $string .= $string2;
                //     }
                //     eval($string); //I really hate this language sometimes
                // }, function($e) {
                //     ob_flush();
                //     ob_start();
                //     var_dump($e);
                //     file_put_contents("error.txt", ob_get_flush());
                // });
                break;
            case '!initregion':
                $channel = $discord->getChannel('825144851267977256');
                $channel->sendMessage('Select a reaction to designate your region!')->done(function(Message $msg) {
                    $msg->react(':EU:825357985030209576');
                    $msg->react(':NA:825357985030209576');
                }, function($e) {
                    echo "Error: {$e->getMessage()}";
                });
                break;
        }
    });

    
});

$discord->run();