<?php

use Discord\Discord;
use Discord\WebSockets\Event;
use Discord\Parts\Channel\{ Guild, Channel, Message };
use Discord\Parts\User\{ Member, User };
use Discord\Parts\Embed\Embed;
use Discord\Parts\WebSockets\MessageReaction;
use React\Http\Message\Response;
use React\EventLoop\Factory;
use Oni\Identify\Reacts;
use Oni\Identify\Roles;
use Oni\Identify\Channels;

require __DIR__ . '/vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$discord = new Discord([
    'token' => 'ODI1MTU0MTc3NzcyMTU5MDM2.YF5ytg.c6tGhN9N4XPDJQnxeXU0An3EtFw',
    'loop' => Factory::create(),
    'disabledEvents' => [],
    'loadAllMembers' => true
]);

$discord->on('ready', function(Discord $discord)
{
    // echo print_r($discord->users);

    // LISTEN FOR COMMANDS
    $discord->on(Event::MESSAGE_CREATE, function (Message $message, Discord $discord)
    {
        switch (strtolower($message->content)) {
            case '!initrole':
                $results= [];
                $reacts= [Reacts::HEAL, Reacts::TANK, Reacts::RANGE, Reacts::ATTACK];
                $embed= new Embed($discord, [
                    'title' => 'Classes',
                    'description' => 'Select the reactions below to be assigned the roles you prefer to play.',
                    'color' => '#00FF00'
                ]);

                $channel = $discord->getChannel('825144851267977256');
                $promise= $channel->sendMessage('', false, $embed);
                foreach ($reacts as $react) {
                    $promise->then(function(Message $message) use ($react) {
                        $results[]= $message->react($react);
                    }, function($e) {
                        echo "Error: " . $e->getMessage();
                    });
                }

                $promise->done(function() use ($results) {
                    return new Response(200, ['Content-Type' => 'application/json'], json_encode($results));
                });
                break;
            case '!initregion':
                $results= [];
                $reacts= [Reacts::EU, Reacts::NA];
                $embed= new Embed($discord, [
                    'title' => 'Region',
                    'description' => 'Select the reactions below to be assigned the region you prefer to play on.',
                    'color' => '#00FF00'
                ]);

                $channel = $discord->getChannel('825144851267977256');
                $promise= $channel->sendMessage('', false, $embed);
                foreach ($reacts as $react) {
                    $promise->then(function(Message $message) use ($react) {
                        $results[]= $message->react($react);
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

    // LISTEN FOR REACTION ADD IN ROLES CHANNEL
    $discord->on(Event::MESSAGE_REACTION_ADD, function(MessageReaction $reaction, Discord $discord)
    {
        if (!$reaction->member->user->bot && $reaction->channel_id == Channels::ROLES) {
            switch ($reaction->emoji->name) {
                case 'Heal':
                    $reaction->member->addRole(Roles::HEAL);
                    break;
                case 'Tank':
                    $reaction->member->addRole(Roles::TANK);
                    break;
                case 'Range':
                    $reaction->member->addRole(Roles::RANGE);
                    break;
                case 'Attack':
                    $reaction->member->addRole(Roles::ATTACK);
                    break;
                case 'EU':
                    $reaction->member->addRole(Roles::EU);
                    break;
                case 'NA':
                    $reaction->member->addRole(Roles::NA);
                    break;
            }
        }
    });

    // LISTEN FOR REACTION REMOVE IN ROLES CHANNEL
    $discord->on(Event::MESSAGE_REACTION_REMOVE, function(MessageReaction $reaction, Discord $discord)
    {
        if (!$reaction->member->user->bot && $reaction->channel_id == Channels::ROLES) {
            switch ($reaction->emoji->name) {
                case 'Heal':
                    $reaction->member->removeRole(Roles::HEAL);
                    break;
                case 'Tank':
                    $reaction->member->removeRole(Roles::TANK);
                    break;
                case 'Range':
                    $reaction->member->removeRole(Roles::RANGE);
                    break;
                case 'Attack':
                    $reaction->member->removeRole(Roles::ATTACK);
                    break;
                case 'EU':
                    $reaction->member->removeRole(Roles::EU);
                    break;
                case 'NA':
                    $reaction->member->removeRole(Roles::NA);
                    break;
            }
        }
    });

    $discord->on(Event::GUILD_MEMBER_ADD, function (Member $member, Discord $discord) {
        echo "USER: " . print_r($member->user->username);
        // $embed= new Embed($discord, [
        //     'title' => $member->user->username,
        //     'description' => 'Another demon joins our army!',
        //     'color' => '#00FF00',
        //     'thumbnail' => $member->user->avatar
        // ]);
        // $channel= $discord->getChannel(Channels::WELCOME);
        // $channel->sendMessage('', false, $embed)->done(null, function($e) {
        //     echo "ERROR: {$e->getMessage()}";
        // });
    }, function($e) {
        echo "ERROR: {$e->getMessage()}";
    });
});

$discord->run();