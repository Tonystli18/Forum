<?php

namespace Tests\Unit;

use App\Thread;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Support\Facades\Redis;
use App\Notifications\ThreadWasUpdated;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    public function setUp() : void
    {
        parent::setUp();

        $this->thread = factory('App\Thread') ->create();

    }

    /**
     * @test
     */
    public function a_thread_has_a_path()
    {
        $thread = create('App\Thread');
        $this->assertEquals("/threads/{$thread->channel->slug}/{$thread->slug}", $thread->path());
    }

    /**
     * @test
     */
    public function a_thread_has_a_creator()
    {
        $this->assertInstanceOf('App\User', $this->thread->creator);
    }

    /**
     * @test
     *
     * @return void
     */
    public function a_thread_has_replies()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    /**
     * @test
     */
    public function a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]); 

        $this->assertEquals('Foobar', $this->thread->replies[0]->body);
    }
    
    /** @test */
    public function a_thread_notify_all_registered_subscribers_when_a_reply_is_added()
    {
        Notification::fake();
        
        $this->signedIn()
            ->thread
            ->subscribe()
            ->addReply([
                'body' => 'Foobar',
                'user_id' => 999
        ]); 

        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
    }

    /**
     * @test
     */
    public function a_thread_belongs_to_a_channel()
    {
        $thread = create('App\Thread');

        $this->assertInstanceOf('App\Channel', $thread->channel);
    }

    /** @test */
    public function a_thread_can_be_subscribed_to()
    {
        $thread = create('App\Thread');

        $thread->subscribe($userId = 1);

        $this->assertEquals(1, $thread->subscriptions()->where('user_id', $userId)->count());
    }

    /** @test */
    public function a_thread_can_be_unsubscribed_from()
    {
        $thread = create('App\Thread');

        $thread->subscribe($userId = 1);

        $this->assertEquals(1, $thread->subscriptions()->where('user_id', $userId)->count());

        $thread->unsubscribe($userId);

        $this->assertEquals(0, $thread->subscriptions()->where('user_id', $userId)->count());

    }

    /** @test */
    public function a_thread_can_check_if_the_authentificated_user_has_read_all_replies()
    {
        $this->signedIn();

        $user = auth()->user();

        $this->assertTrue($this->thread->hasUpdatesFor($user));

        $user->read($this->thread);
        
        $this->assertFalse($this->thread->hasUpdatesFor($user));

    }

    /** @test */
    // Base on Redis implementation
    // public function a_thread_records_each_visit()
    // {

    //     $thread = make('App\Thread', ['id' => 1]);

    //     $thread->visits()->reset();

    //     $this->assertSame(0, $thread->visits()->count());

    //     $thread->visits()->record();

    //     $this->assertEquals(1, $thread->visits()->count());

    //     $thread->visits()->record();

    //     $this->assertEquals(2, $thread->visits()->count());
    // }

    /** @test */
    public function a_thread_may_be_locked()
    {
        $this->withoutExceptionHandling();
        
        $this->assertFalse($this->thread->locked);

        $this->thread->lock();

        $this->assertTrue($this->thread->locked);
    }

    /** @test */
    public function a_thread_body_is_sanitized_automatically()
    {
        $thread = make('App\Thread', 
        ['body' => '<script>alert("gotcha")</script><h3>Heading Three is allowed</h3><a href="#" onclick="alert(\'gotcha\')">Click me</a>']);
        $this->assertEquals($thread->body, '<h3>Heading Three is allowed</h3><a href="#">Click me</a>');
    }
}
