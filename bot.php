<?php

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Psr\Http\Message\ResponseInterface;
use React\EventLoop\Factory;
use React\Http\Browser;

require __DIR__ . '/vendor/autoload.php';

$loop = Factory::create();
$browser = new Browser($loop);
$discord = new Discord([
    'token' => 'ODI1MTU0MTc3NzcyMTU5MDM2.YF5ytg.c6tGhN9N4XPDJQnxeXU0An3EtFw',
    'loop' => $loop,
    'disabledEvents' => []
]);

$discord->on('ready', function(Discord $discord) {
    $discord->on(Event::MESSAGE_CREATE, function (Message $message, Discord $discord) use ($browser) {
        switch (strtolower($message->content)) {
            case '!joke':
                $browser->get('https://api.chucknorris.io/jokes/random')->then(function (ResponseInterface $response) use ($message) {
                    $joke = json_decode($response->getBody())->value;
                    $message->reply($joke);
                });
                break;
            case '!initrole':
                new Message();
                break;
        }
    });
});


$discord->run();