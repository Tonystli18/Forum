<?php

namespace App\Listeners;

use App\User;
use App\Events\ThreadReceivedNewReply;
use App\Notifications\YouWereMentioned;


class NotifyMentionedUsers
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ThreadReceivedNewReply  $event
     * @return void
     */
    public function handle(ThreadReceivedNewReply $event)
    {
        // $mentionedUsers = $event->reply->mentionedUsers();

        // foreach($mentionedUsers as $name) {
        //     $user = User::where('name', $name)->first();
        //     if($user) {
        //         $user->notify(new YouWereMentioned($event->reply));
        //     }
        // }

        $names = User::whereIn('name', $event->reply->mentionedUsers())
            ->get()
            ->each(function($user) use ($event) {
                $user->notify(new YouWereMentioned($event->reply));
            });
    }
}
