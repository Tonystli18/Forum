<?php

namespace App\Rules;

use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Validation\Rule;

class Recaptcha implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $response = Http::bodyFormat('form_params')
                    ->contentType('application/x-www-form-urlencoded')
                    ->post('https://www.google.com/recaptcha/api/siteverify',[
                            'secret' => config('services.recaptcha.secret'),
                            'response' => $value,
                            'remoteip' => request()->ip()
                    ]);

        // if(! $response->json()['success']) {
        // throw new \Exception('Recaptcha failed');
        // };
        return $response->json()['success'];
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
