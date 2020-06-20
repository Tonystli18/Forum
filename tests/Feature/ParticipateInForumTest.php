<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;


    /** @test */
    function unauthenticated_users_may_not_add_replies()
    {
        $this->withExceptionHandling()
            ->post('threads/some-channel/1/replies', [])
            ->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
        // $this->be($user = create('App\User'));
        $this->signedIn();

        $thread = create('App\Thread');

        $reply = make('App\Reply');

        $this->post($thread->path().'/replies', $reply->toArray());
        // $this->actingAs($user)->post($thread->path().'/replies', $reply->toArray())
                        // ->assertSuccessful();

        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
        $this->assertEquals(1, $thread->fresh()->replies_count);
        // $this->get($thread->path())
        //     ->assertSeeText($reply->body);
    }

    /**
     * @test
     */
    public function a_reply_requires_a_body()
    {
        $this->withoutExceptionHandling()->signedIn();

        $thread = create('App\Thread');

        $reply = make('App\Reply', ['body' => null]);
        
        $this->post($thread->path().'/replies', $reply->toArray())
            ->assertStatus(422);
    }

    /** @test */
    public function unauthorized_users_can_not_delete_replies()
    {
        $reply = create('App\Reply');

        $this->delete("/replies/{$reply->id}")
                        ->assertRedirect('login');

        $this->signedIn()
            ->delete("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_delete_replies()
    {
        $this->signedIn();

        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $this->delete("/replies/{$reply->id}")->assertStatus(302);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertEquals(0, $reply->thread->fresh()->replies_count);

    }

    /** @test */
    public function authorized_users_can_upldate_replies()
    {
        $this->signedIn();

        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $updatedReply = 'You been changed, fool.';

        $this->patch("/replies/{$reply->id}", ['body' => $updatedReply]);

        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => $updatedReply]);

    }

    /** @test */
    public function unauthorized_users_can_not_update_replies()
    {
        $reply = create('App\Reply');

        $this->patch("/replies/{$reply->id}")
                        ->assertRedirect('login');

        $this->signedIn()
            ->patch("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    /** @test */
    public function replies_that_contain_spam_may_not_be_created()
    {
        $this->withoutExceptionHandling();
        
        $this->signedIn();

        $thread = create('App\Thread');

        $reply = make('App\Reply', [
            'body'=> 'Yahoo Customer Support.'
        ]);

        $this->post($thread->path().'/replies', $reply->toArray())
                ->assertStatus(422);

    }

    /** @test */
    public function users_may_only_reply_once_per_minute()
    {

        $this->withoutExceptionHandling();

        $this->signedIn();

        $thread = create('App\Thread');

        $reply = make('App\Reply', [
            'body'=> 'My simple reply.'
        ]);
        
        $this->post($thread->path().'/replies', $reply->toArray())
            ->assertStatus(201);

        $this->post($thread->path().'/replies', $reply->toArray())
        ->assertStatus(429);
    }
    
    
    
}
