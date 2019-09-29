<?php

function share_tweeter($text, $url = "", $hashtags = "")
{
    if (empty($url))
        $url = url()->current();
    return "https://twitter.com/intent/tweet?text=" . $text . '&url=' . $url . '&hashtags=' . $hashtags;
}

function share_linkedIn($title, $url = "", $description = "", $source = '')
{
    if (empty($url))
        $url = url()->current();
    $params = ['mini' => true, 'url' => $url, 'title' => $title];
    if (!empty($description))
        $params['summary'] = mb_substr(urlencode($description), 0, 200);
    if (!empty($source))
        $params['source'] = $source;
    return 'https://www.linkedin.com/shareArticle?' . http_build_query($params);
}

function share_telegram($url = "", $text = "")
{
    if (empty($url))
        $url = url()->current();
    return 'https://telegram.me/share/url?url=' . $url . '&text=' . $text;
}

function shareFacebook($url = "")
{
    if (empty($url))
        $url = url()->current();
    return 'https://www.facebook.com/sharer/sharer.php?u=' . $url;
}