<?php

namespace App\Repositories\Feed;

use App\Models\Feed;
use App\Models\Item;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class FeedRepository extends BaseRepository implements FeedRepositoryInterface
{
    public function __construct(Feed $model)
    {
        parent::__construct($model);
    }

    /**
     * @param string $name
     * @param string $url
     * @param string $rssPath
     * @param string $image
     * @return Feed
     */
    public function addFeed(
        string $name,
        string $url,
        string $rssPath,
        string $image
    ): Feed {
        return $this->create([
            "name" => $name,
            "url" => $url,
            "rss_path" => $rssPath,
            "image" => $image,
        ]);
    }

    public function addItem(
        int $feedId,
        string $title,
        string $link,
        string $image,
        string $description,
        $pubDate
    ): Item {
        return Item::create([
            "feed_id" => $feedId,
            "title" => $title,
            "link" => $link,
            "image" => $image,
            "description" => $description,
            "pub_date" => $pubDate,
        ]);
    }
}
