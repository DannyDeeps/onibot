<?php

use Discord\Discord;
use Discord\Parts\Channel\{ Guild, Channel, Message };
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
                // $channel = $discord->getChannel('825144851267977256');
                // $channel->sendMessage('Select a reaction to designate your role!')->done(function ($new_message) use ($message) {
                //     $react_array= [':Heal:825145748936589312',':Tank:825357985030209576',':Range:825357985030209576',':Attack:825357985030209576'];
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
                // });

                $message_id= $message->id;
                $channel = $discord->getChannel('825144851267977256');
                $react_array= [':Heal:825145748936589312',':Tank:825357985030209576',':Range:825357985030209576',':Attack:825357985030209576'];
                foreach ($react_array as $react) {
                    $channel->getMessage($message_id)->done(function(Message $roleMsg) use ($react) {
                        $roleMsg->react($react);
                    }, function($e) {
                        echo "Error: {$e->getMessage()}";
                    });
                }
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