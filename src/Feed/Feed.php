<?php namespace Oni\Feed;

    class Feed
    {
        public static function get(String $feedUrl)
        {
            return \simplexml_load_file($feedUrl);
        }
    }