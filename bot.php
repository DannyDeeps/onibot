<?php

    require_once 'includes/start.inc.php';

    use Discord\Discord;
    use Discord\WebSockets\Event;
    use Discord\Parts\Channel\Message;
    use Discord\Parts\User\Member;
    use Discord\Parts\Embed\Embed;
    use Discord\Parts\WebSockets\MessageReaction;
    use React\Http\Message\Response;
    use React\EventLoop\Factory;
    use Oni\Identify\Reacts;
    use Oni\Identify\Roles;
    use Oni\Identify\Channels;
    use Oni\Feed\Feed as FeedData;
    use Discord\Parts\Embed\Field;

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
                    $reacts= [Reacts::EU, Reacts::NA];
                    $fieldEU= new Field($discord, [
                        'name' => 'EU',
                        'value' => 'Rinnegan',
                        'inline' => true
                    ]);
                    $fieldNA= new Field($discord, [
                        'name' => 'NA',
                        'value' => 'Sharingan',
                        'inline' => true
                    ]);
                    $embed= new Embed($discord, [
                        'title' => 'Region',
                        'description' => 'Select the reaction below for your local region.',
                        'color' => '#00FF00',
                        'fields' => [$fieldEU, $fieldNA]
                    ]);

                    $results= [];
                    $channel = $discord->getChannel(Channels::ROLES);
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
                    foreach ($feeds as $feed) {
                        $feedData= FeedData::get($feed->feed_url);
                        foreach ($feedData->channel->item as $item) {
                            $newsDate= date('YmdHis', strtotime($item->pubDate));
                            if ($newsDate > $feed->updated) {
                                $embed= new Embed($discord, [
                                    'title' => (String) $item->title,
                                    // 'description' => $item->description,
                                    'url' => (String) $item->link,
                                    'footer' => [
                                        'text' => 'Author: ' . ucwords($item->author) . ' @ ' . date('F j, Y, g:i a', strtotime($item->pubDate))
                                    ]
                                ]);
                                $channel= $discord->getChannel(Channels::NTBSS);
                                $channel->sendEmbed($embed)->done(
                                    function() use ($item) {
                                        echo "New News: {$item->title} | Line [".__LINE__."]\r\n";
                                    }, function($e) {
                                        echo "New News: {$e->getMessage()} | Line [".__LINE__."]\r\n";
                                    }
                                );
                            } else {
                                echo "Article Already Posted: " . $item->title;
                            }
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
                        $reaction->member->addRole(Roles::HEAL)->done(
                            function() use ($reaction) {
                                echo "Add Heal: {$reaction->member->username} | Line [".__LINE__."]\r\n";
                            }, function($e) {
                                echo "Add Heal: {$e->getMessage()} | Line [".__LINE__."]\r\n";
                            }
                        );
                        break;
                    case 'Tank':
                        $reaction->member->addRole(Roles::TANK)->done(
                            function() use ($reaction) {
                                echo "Add Tank: {$reaction->member->username} | Line [".__LINE__."]\r\n";
                            }, function($e) {
                                echo "Add Tank: {$e->getMessage()} | Line [".__LINE__."]\r\n";
                            }
                        );
                        break;
                    case 'Range':
                        $reaction->member->addRole(Roles::RANGE)->done(
                            function() use ($reaction) {
                                echo "Add Range: {$reaction->member->username} | Line [".__LINE__."]\r\n";
                            }, function($e) {
                                echo "Add Range: {$e->getMessage()} | Line [".__LINE__."]\r\n";
                            }
                        );;
                        break;
                    case 'Attack':
                        $reaction->member->addRole(Roles::ATTACK)->done(
                            function() use ($reaction) {
                                echo "Add Attack: {$reaction->member->username} | Line [".__LINE__."]\r\n";
                            }, function($e) {
                                echo "Add Attack: {$e->getMessage()} | Line [".__LINE__."]\r\n";
                            }
                        );
                        break;
                    case 'EU':
                        $reaction->member->setNickname("\u{1F1EA}\u{1F1FA}| " . $reaction->member->username)->done(
                            function() use ($reaction) {
                                echo "Add EU: {$reaction->member->username} | Line [".__LINE__."]\r\n";
                            }, function($e) {
                                echo "Add EU: {$e->getMessage()} | Line [".__LINE__."]\r\n";
                            }
                        );
                        break;
                    case 'NA':
                        $reaction->member->setNickname("\u{1F1F3}\u{1F1E6}| " . $reaction->member->username)->done(
                            function() use ($reaction) {
                                echo "Add NA: {$reaction->member->username} | Line [".__LINE__."]\r\n";
                            }, function($e) {
                                echo "Add NA: {$e->getMessage()} | Line [".__LINE__."]\r\n";
                            }
                        );
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
                        $reaction->member->removeRole(Roles::HEAL)->done(
                            function() use ($reaction) {
                                echo "Remove Heal: {$reaction->member->username} | Line [".__LINE__."]\r\n";
                            }, function($e) {
                                echo "Remove Heal: {$e->getMessage()} | Line [".__LINE__."]\r\n";
                            }
                        );
                        break;
                    case 'Tank':
                        $reaction->member->removeRole(Roles::TANK)->done(
                            function() use ($reaction) {
                                echo "Remove Tank: {$reaction->member->username} | Line [".__LINE__."]\r\n";
                            }, function($e) {
                                echo "Remove Tank: {$e->getMessage()} | Line [".__LINE__."]\r\n";
                            }
                        );
                        break;
                    case 'Range':
                        $reaction->member->removeRole(Roles::RANGE)->done(
                            function() use ($reaction) {
                                echo "Remove Range: {$reaction->member->username} | Line [".__LINE__."]\r\n";
                            }, function($e) {
                                echo "Remove Range: {$e->getMessage()} | Line [".__LINE__."]\r\n";
                            }
                        );
                        break;
                    case 'Attack':
                        $reaction->member->removeRole(Roles::ATTACK)->done(
                            function() use ($reaction) {
                                echo "Remove Attack: {$reaction->member->username} | Line [".__LINE__."]\r\n";
                            }, function($e) {
                                echo "Remove Attack: {$e->getMessage()} | Line [".__LINE__."]\r\n";
                            }
                        );
                        break;
                    case 'EU':
                        $reaction->member->setNickname('')->done(
                            function() use ($reaction) {
                                echo "Remove EU: {$reaction->member->username} | Line [".__LINE__."]\r\n";
                            }, function($e) {
                                echo "Remove EU: {$e->getMessage()} | Line [".__LINE__."]\r\n";
                            }
                        );
                        break;
                    case 'NA':
                        $reaction->member->setNickname('')->done(
                            function() use ($reaction) {
                                echo "Remove NA: {$reaction->member->username} | Line [".__LINE__."]\r\n";
                            }, function($e) {
                                echo "Remove NA: {$e->getMessage()} | Line [".__LINE__."]\r\n";
                            }
                        );
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
                $channel->sendEmbed($embed)->done(
                    function() use ($member) {
                        echo "New Member: {$member->username} | Line [".__LINE__."]\r\n";
                    }, function($e) {
                        echo "New Member: {$e->getMessage()} | Line [".__LINE__."]\r\n";
                    }
                );
            });
        });

        $discord->getLoop()->addPeriodicTimer(43200, function($unknown) use ($discord) {
            $feeds= Feed::all();
            foreach ($feeds as $feed) {
                $feedData= FeedData::get($feed->url);
                foreach ($feedData->channel->item as $item) {
                    $newsDate= date('YmdHis', strtotime($item->pubDate));
                    if ($newsDate > $feed->updated) {
                        $embed= new Embed($discord, [
                            'title' => $item->title,
                            'description' => $item->description,
                            'url' => $item->link,
                            'footer' => [
                                'text' => 'Author: ' . ucwords($item->author) . ' @ ' . date('F j, Y, g:i a', strtotime($item->pubDate))
                            ]
                        ]);
                        $channel= $discord->getChannel(Channels::NTBSS);
                        $channel->sendEmbed($embed)->done(
                            function() use ($item) {
                                echo "New News: {$item->title} | Line [".__LINE__."]\r\n";
                            }, function($e) {
                                echo "New News: {$e->getMessage()} | Line [".__LINE__."]\r\n";
                            }
                        );
                    }
                }
            }
        });
    });

    $discord->run();