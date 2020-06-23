<?php

namespace App;

use App\Traits\Favoritable;
use App\Filters\ThreadFilters;
use App\Traits\RecordsActivity;
use App\Events\ThreadHasNewReply;
use App\Events\ThreadReceivedNewReply;
use App\Notifications\ThreadWasUpdated;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity;

    // protected $fillable = ['title', 'body'];

    protected $guarded =[];

    protected $with = ['creator', 'channel'];

    protected $appends = ['isSubscribedTo'];

    protected static function boot() 
    {
        parent::boot();

        // store replies count into Thread table, so below logics are not needed anymore
        // static::addGlobalScope('replyCount', function($builder){
        //     $builder->withCount('replies');
        // });

        // another way to keey database concistancy when delete thread
        // cascading delete replies to the specific thread
        static::deleting(function ($thread) {
            // $thread->replies->each(function($reply){
            //     $reply->delete();
            // });
            $thread->replies->each->delete();
        });

        // if sometimes you'd like to get thread without eager loading
        // use blow code, remove $with property.
        // 
        // static::addGlobalScope('creator', function($builder){
        //     $builder->with('creator');
        // });
    }

    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
        // return '/threads/'. $this->channel->slug . '/'. $this->id;
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function getRepliesCount()
    {
        return $this->replies()->count();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

        event(new ThreadReceivedNewReply($reply));

        return $reply;
    }

    // public function notifySubscribers($reply)
    // {
    //     $this->subscriptions
    //         ->where('user_id', '!=', $reply->user_id)
    //         ->each
    //         ->notify($reply);
    // }

    public function channel()
    {
        return $this->belongsTo(Channel::class, 'channel_id');
    }

    public function scopeFilter($query, ThreadFilters $filters)
    {
        return $filters->apply($query);
    }

    public function subscribe($userId= null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);

        return $this;
    }

    public function unsubscribe($userId= null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
                    ->where('user_id', auth()->id())
                    ->exists();
    }

    public function hasUpdatesFor($user)
    {
        $key = $user->visitedThreadCacheKey($this);
        return $this->updated_at > cache($key);
    }
}
