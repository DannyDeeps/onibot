<?php
    require_once 'includes/start.inc.php';

    use Oni\Feed\Feed as FeedData;
    use Kint\Kint;

    $feedItems= [];
    $feedData= FeedData::get('https://store.steampowered.com/feeds/news/app/633230/?cc=GB&l=english&snr=1_2108_9__2107');
    foreach ($feedData->channel->item as $item) {
        echo $item->title;
    }

    Kint::dump($feedData);