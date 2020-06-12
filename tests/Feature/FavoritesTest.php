<?php

namespace Tests\Feature;

use Exception;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FavoritesTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function guests_can_not_favorite_anything()
    {
        $this->withExceptionHandling();

        $this->post('replies/1/favorites')
            ->assertRedirect('/login');
    }

    /**
     * @test
     *
     * @return void
     */
    public function an_authenticated_user_can_favorite_any_reply()
    {
        $this->withoutExceptionHandling();

        $this->signedIn();

        $reply = create('App\Reply');

        $this->post('replies/'. $reply->id . '/favorites');

        $this->assertCount(1, $reply->favorites);
    }

    /**
     * @test
     *
     * @return void
     */
    public function an_authenticated_user_can_unfavorite_a_reply()
    {
        $this->withoutExceptionHandling();

        $this->signedIn();

        $reply = create('App\Reply');

        $reply->addFavorite();

        $this->delete('replies/'. $reply->id . '/favorites');

        $this->assertCount(0, $reply->favorites);
    }

    /** @test */
    public function an_authenticated_user_may_only_favorite_a_reply_once()
    {
        $this->withoutExceptionHandling();

        $this->signedIn();

        $reply = create('App\Reply');

        try {
            $this->post('replies/'. $reply->id . '/favorites');
            $this->post('replies/'. $reply->id . '/favorites');
        } catch (Exception $e) {
            $this->fail('Add favorite twice.');
        }
        $this->assertCount(1, $reply->favorites);
    }
}