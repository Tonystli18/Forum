<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateThreadsTest extends TestCase
{
    // use DatabaseMigrations;
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();

        $this->signedIn();

    }

    /** @test */
    public function a_thread_requires_a_title_and_a_body_when_it_is_updated()
    {
        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->patch($thread->path(), [
            'title' => 'Changed',
        ])->assertSessionHasErrors('body');

        $this->patch($thread->path(), [
            'body' => 'Changed body.',
        ])->assertSessionHasErrors('title');
    }
    
    /** @test */
    public function unauthorized_users_may_not_update_a_threads()
    {
        $thread = create('App\Thread', ['user_id' => create('App\User')->id]);

        $this->patch($thread->path(), [])->assertStatus(403);
    }
    

    /** @test */
    public function a_thread_can_be_updated_by_its_creator()
    {
        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->patch($thread->path(), [
            'title' => 'Changed',
            'body'=> 'Changed body'
        ]);
        tap($thread->fresh(), function($thread) {
            $this->assertEquals('Changed', $thread->title); 
            $this->assertEquals('Changed body', $thread->body); 
        });
    }
    
}
