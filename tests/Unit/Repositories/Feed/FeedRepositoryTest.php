<?php


namespace Tests\Unit\Repositories\Feed;


use App\Models\Feed;
use App\Repositories\Feed\FeedRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
 use Tests\TestCase;

class FeedRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    private FeedRepository $feedRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->feedRepository = resolve(FeedRepository::class);
    }

    public function testCreateFeed()
    {
        $name = 'testName';
        $url = 'TEST.com';
        $rssPath = 'RSs';
        $image = 'Test.com/image.jpg';

        $feed = $this->feedRepository->addFeed($url, $name, $rssPath, $image);
        $this->assertEquals($name, $feed->name);
        $this->assertEquals(strtolower($url), $feed->url);
        $this->assertEquals(strtolower($rssPath), $feed->rss_path);
        $this->assertEquals(strtolower($image), $feed->image);
        $this->assertTrue($feed instanceof Feed);
    }
}