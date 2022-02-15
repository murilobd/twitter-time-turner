<?php

namespace App\Http\Requests;

use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class TweetRequest extends FormRequest
{
    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            // if user don't send publish_now, automatically set as false
            'publish_now' => $this->publish_now ?? false
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tweet' => 'required',
            'timezone' => 'bail|required|timezone',
            'publish_now' => 'boolean',
            'publish_datetime' => [
                'required_if:publish_now,false',
                Rule::when(function ($input) {
                    return !($input->publish_now ?? true);
                }, ['after_or_equal:' . $this->nowWithTimestamp()])
            ],
        ];
    }

    public function nowWithTimestamp()
    {
        $now = Carbon::now()->second(0);
        try {
            $now->timezone($this->timezone ?? 'utc');
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $now->addMinutes(1)->toDateTimeString();
    }
}
