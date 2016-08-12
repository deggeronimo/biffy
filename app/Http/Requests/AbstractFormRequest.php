<?php

namespace Biffy\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

abstract class AbstractFormRequest extends FormRequest
{
    public function input($key = null, $default = null)
    {
        $input = parent::input($key, $default);

        if (is_null($key) && Auth::user())
        {
            return array_merge($input, [ 'user_id' => Auth::user()->userId(), 'store_id' => Auth::user()->storeId() ]);
        }
        else
        {
            return $input;
        }
    }

    public function inputs($keys = [])
    {
        $result = [];

        foreach ($keys as $key)
        {
            $result[$key] = $this->input($key);
        }

        return $result;
    }

    public function response(array $errors)
    {
        $errors = array_reduce(
            $errors,
            function ($carry, $fieldErrors)
            {
                return $carry . array_reduce(
                    $fieldErrors,
                    function ($carry, $val)
                    {
                        return $carry . $val . '<br>';
                    }
                );
            }
        );

        $errors = ['messages' => $errors];
        return parent::response($errors);
    }
} 