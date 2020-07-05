<?php

namespace App;

use App\User;
use Carbon\Carbon;
use App\Traits\Favoritable;
use App\Traits\RecordsActivity;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favoritable, RecordsActivity;
    
    // protected $fillable = ['body'];
    protected $guarded = [];

    protected $with = ['owner', 'favorites'];

    protected $appends = ['favoritesCount', 'isFavorited', 'isBest'];

    protected static function boot()
    {
        parent::boot();

        //model event, good, because if you use factory to create reply, it will
        //automatically increate replies_count in Thread table
        static::created(function($reply){
            $reply->thread->increment('replies_count');
        });

        static::deleted(function($reply){

            /**
             * When you delete a best reply, thread should be updated.
             * below logic should do it. But there is another way to
             * implement the same logic on databse level.
             */
            // if($reply->isBest()) {
            //     $reply->thread->update(['best_reply_id' => null]);
            // };
            
            $reply->thread->decrement('replies_count');
        });
    }
    
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function thread()
    {
        return $this->belongsTo(thread::class, 'thread_id');
    }

    public function wasJustPublished()
    {
        return $this->created_at->gt(Carbon::now()->subMinute());
        // return $this->created_at->gt(Carbon::now()->subRealMinute());
    }

    public function path()
    {
        return $this->thread->path() . "#reply-{$this->id}";
    }

    public function mentionedUsers()
    {
        preg_match_all('/@([\w\-]+)/', $this->body, $matches);

        return $matches[1];
    }

    public function setBodyAttribute($body)
    {
        $this->attributes['body'] = preg_replace('/@([\w\-]+)/', '<a href="/profiles/$1">$0</a>', $body);
    }

    public function isBest()
    {
        return $this->thread->best_reply_id == $this->id;
    }

    public function getIsBestAttribute()
    {
        return $this->isBest();
    }
}
