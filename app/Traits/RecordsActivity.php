<?php

namespace App\Traits;

trait RecordsActivity
{
    protected static function bootRecordsActivity() {

        if(auth()->guest()) return;

        foreach (static::getActivitiesToRecord() as $event) {
            static::$event(function($activitySubjectModel) use ($event) {
                $activitySubjectModel->recordActivity($event);
            });
        }

        static::deleting(function($model){
            $model->activity()->delete();
        });
    }

    protected static function getActivitiesToRecord() {
        // return ['created', 'deleted'];
        return ['created'];
    }

    protected function recordActivity($event){

        $this->activity()->create([
            'type' => $this->getActivityType($event),
            'user_id' => auth()->id()
        ]);
        // create('App\Activity', [
        //     'type' => $this->getActivityType($event),
        //     'user_id' => auth()->id(),
        //     'subject_id' => $this->id,
        //     'subject_type' => get_class($this)
        // ]);
    }

    protected function activity()
    {
        return $this->morphMany('App\Activity', 'subject');
    }

    protected function getActivityType($event) {

        $type = strtolower((new \ReflectionClass($this))->getShortName());
        return "{$event}_{$type}";
    }
}