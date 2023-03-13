<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SanitizationTest extends TestCase
{
    use WithFaker;
    use DatabaseTransactions;

    public function testGlobalModelSanitization()
    {
        $post = factory(\App\Models\Post::class)->create();

        // Test that it strips out javascript and retains ampersands without encoding other potential characters
        $post->body = 'TEST<script>alert("Evil");</script> & ©';

        $post->save();

        $this->assertEquals($post->body, 'TEST & ©');
    }

}
