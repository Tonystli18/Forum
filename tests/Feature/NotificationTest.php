<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp() : void
    {
        parent::setUp();

        $this->signedIn();
    }

    /** @test */
    public function a_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_not_by_current_user()
    {
        $thread = create('App\Thread')->subscribe();

        $user = auth()->user();

        $this->assertCount(0, $user->notifications);

        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'Some reply here',
        ]);

        $this->assertCount(0, $user->fresh()->notifications);

        $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'Some reply here',
        ]);

        $this->assertCount(1, $user->fresh()->notifications);

    }

    /** @test */
    public function a_user_can_fetch_their_unread_notifications()
    {

        create(DatabaseNotification::class);

        $response = $this->getJson("/profiles/" . auth()->user()->name . "/notifications")->json();

        $this->assertCount(1, $response);

    }

    /** @test */
    public function a_user_can_mark_a_notification_as_read()
    {
        $this->withoutExceptionHandling();

        create(DatabaseNotification::class);

        $user = auth()->user();

        $this->assertCount(1, $user->unreadNotifications);

        $notificationId = $user->unreadNotifications->first()->id;

        // $this->delete('/profiles/'.$user->id.'/notifications/'.$notificationId);
        //same as above code
        $this->delete("/profiles/{$user->name}/notifications/{$notificationId}");

        $this->assertCount(0, $user->fresh()->unreadNotifications);

    }

}
