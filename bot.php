<?php

    require_once 'includes/start.inc.php';

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
    use Oni\Feed\Feed as FeedData;

    $discord = new Discord([
        'token' => 'ODI1MTU0MTc3NzcyMTU5MDM2.YF5ytg.c6tGhN9N4XPDJQnxeXU0An3EtFw',
        'loop' => Factory::create(),
        'disabledEvents' => [],
        'loadAllMembers' => true
    ]);

    $discord->on('ready', function(Discord $discord)
    {
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
                            echo "ERROR: {$e->getMessage()} | Line [".__LINE__."]\r\n";
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
                            echo "ERROR: {$e->getMessage()} | Line [".__LINE__."]\r\n";
                        });
                    }

                    $promise->done(function() use ($results) {
                        return new Response(200, ['Content-Type' => 'application/json'], json_encode($results));
                    });
                    break;
                case '!updatenews':
                    $feeds= Feed::all();
                    $channel= $discord->getChannel(Channels::NTBSS);
                    foreach ($feeds as $feed) {
                        $feedData= FeedData::get($feed->feed_url);
                        echo print_r($feedData);
                        foreach ($feedData->channel->item as $item) {
                            $embed= new Embed($discord, [
                                'title' => $item->title,
                                'description' => substr(html_entity_decode($item->description), 0, 2048),
                                'url' => $item->link,
                                'footer' => [
                                    'text' => 'Author: ' . ucwords($item->author) . ' @ ' . date('F j, Y, g:i a', strtotime($item->pubDate))
                                ]
                            ]);
                            $channel->sendEmbed($embed)->done(null, function($e) {
                                echo "ERROR: {$e->getMessage()} | Line [".__LINE__."]\r\n";
                            });
                        }
                    }
            }
        });

        // LISTEN FOR REACTION ADD IN ROLES CHANNEL
        $discord->on(Event::MESSAGE_REACTION_ADD, function(MessageReaction $reaction, Discord $discord)
        {
            if (!$reaction->member->user->bot && $reaction->channel_id == Channels::ROLES) {
                switch ($reaction->emoji->name) {
                    case 'Heal':
                        $reaction->member->addRole(Roles::HEAL)->done(null, function($e) { echo "ERROR: {$e->getMessage()} | Line [".__LINE__."]\r\n"; });
                        break;
                    case 'Tank':
                        $reaction->member->addRole(Roles::TANK)->done(null, function($e) { echo "ERROR: {$e->getMessage()} | Line [".__LINE__."]\r\n"; });
                        break;
                    case 'Range':
                        $reaction->member->addRole(Roles::RANGE)->done(null, function($e) { echo "ERROR: {$e->getMessage()} | Line [".__LINE__."]\r\n"; });
                        break;
                    case 'Attack':
                        $reaction->member->addRole(Roles::ATTACK)->done(null, function($e) { echo "ERROR: {$e->getMessage()} | Line [".__LINE__."]\r\n"; });
                        break;
                    case 'EU':
                        $reaction->member->addRole(Roles::EU)->done(null, function($e) { echo "ERROR: {$e->getMessage()} | Line [".__LINE__."]\r\n"; });
                        break;
                    case 'NA':
                        $reaction->member->addRole(Roles::NA)->done(null, function($e) { echo "ERROR: {$e->getMessage()} | Line [".__LINE__."]\r\n"; });
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
                        $reaction->member->removeRole(Roles::HEAL)->done(null, function($e) { echo "ERROR: {$e->getMessage()} | Line [".__LINE__."]\r\n"; });
                        break;
                    case 'Tank':
                        $reaction->member->removeRole(Roles::TANK)->done(null, function($e) { echo "ERROR: {$e->getMessage()} | Line [".__LINE__."]\r\n"; });
                        break;
                    case 'Range':
                        $reaction->member->removeRole(Roles::RANGE)->done(null, function($e) { echo "ERROR: {$e->getMessage()} | Line [".__LINE__."]\r\n"; });
                        break;
                    case 'Attack':
                        $reaction->member->removeRole(Roles::ATTACK)->done(null, function($e) { echo "ERROR: {$e->getMessage()} | Line [".__LINE__."]\r\n"; });
                        break;
                    case 'EU':
                        $reaction->member->removeRole(Roles::EU)->done(null, function($e) { echo "ERROR: {$e->getMessage()} | Line [".__LINE__."]\r\n"; });
                        break;
                    case 'NA':
                        $reaction->member->removeRole(Roles::NA)->done(null, function($e) { echo "ERROR: {$e->getMessage()} | Line [".__LINE__."]\r\n"; });
                        break;
                }
            }
        });

        $discord->on(Event::GUILD_MEMBER_ADD, function (Member $member, Discord $discord)
        {
            $member->addRole(Roles::YOKAI)->done(function() use ($discord, $member) {
                $embed= new Embed($discord, [
                    'title' => $member->user->username,
                    'description' => 'Another demon joins our army!',
                    'color' => '#00FF00',
                    'thumbnail' => [
                        'url' => $member->user->avatar,
                        'width' => 32,
                        'height' => 32
                    ]
                ]);
                $channel= $discord->getChannel(Channels::WELCOME);
                $channel->sendMessage('', false, $embed)->done(null, function($e) {
                    echo "ERROR: {$e->getMessage()} | Line [".__LINE__."]\r\n";
                });
            });
        }, function($e) {
            echo "ERROR: {$e->getMessage()} | Line [".__LINE__."]\r\n";
        });

        $discord->getLoop()->addPeriodicTimer(3600, function($unknown) use ($discord) {
            $feeds= Feed::all();
            foreach ($feeds as $feed) {
                $feedData= FeedData::get($feed->url);
                foreach ($feedData->channel->item as $item) {
                    $embed= new Embed($discord, [
                        'title' => $item->title,
                        'description' => $item->description,
                        'url' => $item->link,
                        'footer' => [
                            'text' => 'Author: ' . ucwords($item->author) . ' @ ' . date('F j, Y, g:i a', strtotime($item->pubDate))
                        ]
                    ]);
                    $channel= $discord->getChannel(Channels::NTBSS);
                    $channel->sendEmbed($embed)->done(null, function($e) {
                        echo "ERROR: {$e->getMessage()} | Line [".__LINE__."]\r\n";
                    });
                }
            }
        });
    });

    $discord->run();