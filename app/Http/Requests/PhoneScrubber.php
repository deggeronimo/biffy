<?php namespace Biffy\Http\Requests;

trait PhoneScrubber
{
    public function input($key = null, $default = null)
    {
        $input = parent::input($key, $default);

        if (is_null($key))
        {
            if (isset($input['phone']))
            {
                $input['phone'] = preg_replace('/[^0-9]/', '', $input['phone']);
            }

            return array_merge($input, [ 'user_id' => \Auth::user()->userId(), 'store_id' => \Auth::user()->storeId() ]);
        }
        else
        {
            if ($key == 'phone')
            {
                $input = preg_replace('/[^0-9]/', '', $input);
            }

            return $input;
        }
    }
}