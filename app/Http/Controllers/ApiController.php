<?php namespace Biffy\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;

/**
 * Class ApiController
 * @package Biffy\Http\Controllers
 */
class ApiController extends Controller
{
    use Helpers\ApiResponseHelper;

    /**
     * @param string $key
     * @param string default
     * @return mixed
     */
    protected function input($key = null, $default = null)
    {
        return Input::get($key, $default);
    }

    /**
     * @return mixed
     */
    protected function inputAll ()
    {
        return Input::all ();
    }

    /**
     * @param $data
     * @param $keys
     * @return mixed
     */
    protected function keysOnly($data, $keys)
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $data)) {
                $tmp = array_map(function ($val) {
                        return $val['id'];
                    }, $data[$key]);
                $data[$key] = $tmp;
            }
        }

        return $data;
    }

    protected $morphTo = [];

    protected function morph($data, $morph)
    {
        $return = [];
        foreach ($morph as $k => $v) {
            if (is_array($v)) {
                if (count($v) === 1 && array_keys($v)[0] === '[]') {
                    // handle set
                    if (count($v['[]']) === 0) {
                        $return[$k] = $data[$k];
                        continue;
                    }

                    foreach ($data[$k] as $d) {
                        $return[$k][] = $this->morph($d, $v['[]']);
                    }
                } else {
                    // handle nested
                    $return[$k] = $this->morph($data[$k], $v);
                }
            } else {
                // handle value
                $return[$v] = $data[$v];
            }
        }
        return $return;
    }

    protected function getMorph($index)
    {
        return array_key_exists($index, $this->morphTo) ? $this->morphTo[$index] : null;
    }

    /**
     * @param string $method
     * @param array $parameters
     * @return mixed|void
     */
    public function __call($method, $parameters)
    {
        if (strpos($method, 'before') !== false || strpos($method, 'after') !== false) {
            if (method_exists($this, $method)) {
                call_user_func_array([$this, $method], $parameters);
            } else {
                return;
            }
        }

        parent::__call($method, $parameters);
    }
} 