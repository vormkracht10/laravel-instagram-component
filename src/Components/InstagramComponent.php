<?php

namespace Vormkracht10\InstagramComponent\Components;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Instagram\Api;
use Instagram\Auth\Checkpoint\ImapClient;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Vormkracht10\PermanentCache\CachedComponent;
use Vormkracht10\PermanentCache\Scheduled;

class InstagramComponent extends CachedComponent implements Scheduled
{
    protected $store = 'file:instagram';

    public function __construct(
        public string $account,
        public Collection $posts,
        public int $limit = 12,
    ) {
    }

    public function render()
    {
        $this->posts = $this->getMediaFromInstagram();

        return view()->first([
            'instagram-component::components.instagram',
        ]);
    }

    public function getApiClient(): Api
    {
        if (! config('instagram-component.account.username') || ! config('instagram-component.account.password')) {
            throw new Exception('Instagram username and/or password is not configured in `config/services.php`');
        }

        $cachePool = new FilesystemAdapter('instagram', 0, storage_path('framework/cache'));

        if (config('instagram-component.proxy.url')) {
            $clientConfig = [
                'proxy' => [
                    'https' => config('instagram-component.proxy.url'),
                ],
            ];
        }

        $client = new Client($clientConfig ?? []);

        $apiClient = new Api($cachePool, $client);

        $apiClient->setLanguage(config('instagram-component.client.language', 'en-US'));
        $apiClient->setUserAgent(config('instagram-component.client.user_agent'));

        $apiClient->login(config('instagram-component.account.username'), config('instagram-component.account.password'), $this->getImapClient());

        return $apiClient;
    }

    public function getImapClient(): ?ImapClient
    {
        if (config('instagram-component.imap.password')) {
            return new ImapClient(config('instagram-component.imap.host'), config('instagram-component.imap.username'), config('instagram-component.imap.password'));
        }

        return null;
    }

    public function getMediaFromInstagram()
    {
        $apiClient = $this->getApiClient();

        $profile = $apiClient->getProfile($this->account);

        $this->posts = collect();
        $tries = 0;

        $this->posts->push(...$profile->getMedias());

        $profile = $apiClient->getMoreMedias($profile);

        $this->posts->push(...$profile->getMedias());

        if ($this->limit > 12) {
            do {
                $profile = $apiClient->getMoreMedias($profile);

                $this->posts = $this->posts->push(...$profile->getMedias());

                $tries++;

                sleep(3);
            } while ($profile->hasMoreMedias() && $this->posts->count() <= $this->limit && $tries <= 6);
        }

        return $this->getPosts();
    }

    public function getPosts(): Collection
    {
        if (
            $this->posts === null ||
            ! $this->posts->count()
        ) {
            return collect();
        }

        return $this->posts
            ->take($this->limit)
            ->map(fn ($post) => $this->getPostData($post));
    }

    public function getPostData($post): object
    {
        return (object) [
            'id' => $post->getId(),
            'type' => $post->getTypeName(),
            'shortCode' => $post->getShortCode(),
            'link' => $post->getLink(),
            'caption' => $post->getCaption(),
            'location' => $post->getLocation(),
            'image' => $this->getImageData($post),
            'image_url' => $post->getThumbnailSrc(),
            'video' => $post->isVideo(),
            'video_url' => $post->getVideoUrl(),
            'date' => $post->getDate(),
            'comments' => $post->getComments(),
            'likes' => $post->getLikes(),
            'views' => $post->getVideoViewCount(),
            'tags' => $post->getHashTags(),
        ];
    }

    public function getImageData($post): ?string
    {
        return 'data:image/jpg;base64,'.base64_encode(Http::get($post->getThumbnailSrc())->getBody());
    }

    public static function schedule($callback)
    {
        $callback->everyMinute();
    }
}
