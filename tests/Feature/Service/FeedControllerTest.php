<?php


namespace Tests\Feature\Service;


use App\Models\Feed;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class FeedControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Config::set('internal_service.token', 'test');
    }

    public function testAccessDenied()
    {
        $response = $this->postJson('api/services/crawler/add-new-items', []);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testAccessWithAuthWithoutCorrectData()
    {
        // authorized but the given data is wrong
        $response = $this->postJson('api/services/crawler/add-new-items', [], [
            'Authorization' => 'bearer test'
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testAccess()
    {
        $feed = Feed::factory()->create();
        $response = $this->postJson('api/services/crawler/add-new-items', [
            'items' => [
                [
                    'title' => 'test',
                    'description' => 'test',
                    'link' => 'test',
                    'pub_date' => now()->toDateTimeString(),
                    'feed_id' => $feed->id
                ]
            ]
        ], [
            'Authorization' => 'bearer test'
        ]);
        $response->assertSuccessful();
    }

    public function testAccessWrongFeedId()
    {
        $response = $this->postJson('api/services/crawler/add-new-items', [
            'items' => [
                [
                    'title' => 'test',
                    'description' => 'test',
                    'link' => 'test',
                    'pub_date' => now()->toDateTimeString(),
                    'feed_id' => -9 // wrong feed id
                ]
            ]
        ], [
            'Authorization' => 'bearer test'
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}