<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Post;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PublishDateTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Tests posts with published date in the past are retrieved
     *
     * @return void
     */
    public function testPublishDatePast()
    {
        $currentPostsCount = count(Post::all());

        factory(Post::class, 5)->states('past published')->create();

        $updatedPostsCount = count(Post::all());

        $this->assertEquals($currentPostsCount +5, $updatedPostsCount);
    }


    /**
     * Tests posts with published date in the future are not retrieved
     *
     * @return void
     */
    public function testPublishDateFuture()
    {
        $currentPostsCount = count(Post::all());

        factory(Post::class, 5)->states('future published')->create();

        $updatedPostsCount = count(Post::all());

        $this->assertEquals($currentPostsCount, $updatedPostsCount);
    }
}
