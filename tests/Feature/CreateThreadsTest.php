<?php

namespace Tests\Feature;

use App\Activity;
use App\Rules\Recaptcha;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Mockery;
class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp() : void
    {
        parent::setUp();

        //mock recaptcha class
        // app()->singleton(Recaptcha::class, function(){
        //     $m = \Mockery::mock(Recaptcha::class);
        //     $m->shouldReceive('passes')->andReturn(true);
        //     return $m;
        // });

        $this->instance(Recaptcha::class, Mockery::mock(Recaptcha::class, function ($mock) {
            $mock->shouldReceive('passes')->andReturn(true);
        }));
    }
    
    /**
     * @test
     */
    public function guests_may_not_create_threads()
    {

        $this->get('/threads/create')
            ->assertRedirect('/login');

        $this->post('/threads')
            ->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function an_authenticated_user_can_create_new_forum_threads()
    {
        $response = $this->publishThread(['title' => 'Some Title', 'body' => 'Some body.']);

        $this->get($response->headers->get('location'))
            ->assertSeeText('Some Title')
            ->assertSeeText('Some body.');
    }

    /**
     * @test
     */
    public function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /**
     * @test
     */
    public function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_thread_requires_recaptcha_verification()
    {
        unset(app()[Recaptcha::class]);
        
        $this->publishThread(['g-recaptcha-response' => 'test'])
            ->assertSessionHasErrors('g-recaptcha-response');
    }
    

    /**
     * @test
     */
    public function a_thread_requires_a_valid_channel()
    {
        $channels = factory('App\Channel', 2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    /** @test */
    public function a_thread_requires_a_unique_slug()
    {
        $this->withoutExceptionHandling();

        $this->signedIn();

        $thread = create('App\Thread', ['title' => 'Foo Title']);

        $this->assertEquals($thread->fresh()->slug, 'foo-title');

        $thread = $this->postJson(route('threads'), $thread->toArray()+['g-recaptcha-response' => 'token'])->json();

        $this->assertEquals("foo-title-{$thread['id']}", $thread['slug']);
    }

    /** @test */
    public function a_thread_with_a_title_that_ends_in_a_number_should_generate_the_proper_slug()
    {
        $this->withoutExceptionHandling();

        $this->signedIn();

        $thread = create('App\Thread', ['title' => 'Some Title 24']);

        $thread = $this->postJson(route('threads'), $thread->toArray()+ ['g-recaptcha-response' => 'token'])->json();

        $this->assertEquals("some-title-24-{$thread['id']}", $thread['slug']);

    }
    

    /** @test */
    public function unauthorized_users_may_not_delete_threads()
    {
        // $this->withoutExceptionHandling();

        $thread = create('App\Thread');

        $this->delete($thread->path())->assertRedirect('/login');

        $this->signedIn();

        $this->delete($thread->path())->assertStatus(403);

    }

    /** @test */
    public function authorized_users_can_delete_threads()
    {
        $this->withoutExceptionHandling();

        $this->signedIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        $this->assertEquals(0, Activity::count());
    }

    public function publishThread($overrides = [])
    {
        $this->signedIn();

        $thread = make('App\Thread', $overrides);

        return $this->post('/threads', $thread->toArray()+ ['g-recaptcha-response' => 'token']);
    }
}
