<?php
    switch ($type) {
        case 'facebook':
            $url = 'https://www.facebook.com/sharer/sharer.php?u=' . $url;
            break;

        case 'linkedin':
            $url = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $url . '&title=' . $title;
            break;

        case 'twitter':
            $url = 'https://twitter.com/share?url=' . $url . '&text=' . $title;
            break;

        case 'email':
            $url = 'mailto:?subject='. $title . '&body=' . $title . '[' . $url . ']';
            break;
    }
?>
<a href="{{ $url }}" target="_blank" class="nav-link p-0">
    @switch($type)
        @case('facebook')
            @svg('assets/svg/icon_share_facebook.svg', 'icon icon-share')
        @break

        @case('linkedin')
            @svg('assets/svg/icon_share_linkedin.svg', 'icon icon-share')
        @break

        @case('twitter')
            @svg('assets/svg/icon_share_twitter.svg', 'icon icon-share')
        @break

        @case('email')
            @svg('assets/svg/icon_share_email.svg', 'icon icon-share')
        @break
    @endswitch
</a>

