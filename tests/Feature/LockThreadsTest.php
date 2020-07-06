<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LockThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** 
     * @test
     * 
     */
    public function a_locked_thread_may_not_receive_reply()
    {
        $this->withoutExceptionHandling();

        $this->signedIn();

        $thread = create('App\Thread', ['locked' => true]);
        
        $this->post($thread->path() . '/replies', [
            'body' => 'Foobar',
            'user_id' => auth()->id()
        ])->assertStatus(422);
    }

    /** @test */
    public function non_administrator_may_not_lock_threads()
    {
        // $this->withoutExceptionHandling();

        $this->signedIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->post(route('locked-threads.store', $thread))->assertStatus(403);

        $this->assertFalse($thread->fresh()->locked);
    }
    
    /** @test */
    public function administrators_can_lock_threads()
    {
        $this->withoutExceptionHandling();

        $this->signedIn(factory('App\User')->states('administrator')->create());

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->post(route('locked-threads.store', $thread));

        $this->assertTrue($thread->fresh()->locked);
    }
    
    /** @test */
    public function administrators_can_unlock_threads()
    {
        $this->withoutExceptionHandling();

        $this->signedIn(factory('App\User')->states('administrator')->create());

        $thread = create('App\Thread', ['user_id' => auth()->id(), 'locked' => true]);

        $this->delete(route('locked-threads.destroy', $thread));

        $this->assertFalse($thread->fresh()->locked);
    }
    
}
