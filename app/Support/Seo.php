<?php

namespace App\Support;

use CoffeeCode\Optimizer\Optimizer;

class Seo
{
    private $optimizer;

    public function __construct()
    {
        $this->optimizer = new Optimizer();

        $this->optimizer->openGraph(
            config('app.name'),
            'pt_BR',
            'article'
        );

        $facebookPage = config('services.social.facebook_page');
        $facebookAuthor = config('services.social.facebook_author');

        if ($facebookPage) {
            $this->optimizer->publisher($facebookPage, $facebookAuthor ?: null);
        }

        $facebookApp = config('services.social.facebook_app');

        if ($facebookApp && ctype_digit((string) $facebookApp)) {
            $this->optimizer->facebook($facebookApp);
        }

        $twitterCreator = config('services.social.twitter_creator');
        $twitterPublisher = config('services.social.twitter_publisher');

        if ($twitterCreator && $twitterPublisher) {
            $this->optimizer->twitterCard(
                $twitterCreator,
                $twitterPublisher,
                parse_url(config('app.url'), PHP_URL_HOST) ?: config('app.url')
            );
        }
    }

    public function render(string $title, string $description, string $url, string $image, bool $follow = true)
    {
        return $this->optimizer->optimize($title, $description, $url, $image, $follow)->render();
    }
}
