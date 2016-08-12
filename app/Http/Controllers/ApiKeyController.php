<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\ApiKey\ApiKeyService;

class ApiKeyController extends ApiController
{
    /**
     * @var ApiKeyService
     */
    private $service;

    public function __construct(ApiKeyService $service)
    {
        $this->service = $service;
    }

    public function getKeys()
    {
        return $this->data($this->service->all()->toArray())->respond();
    }

    public function postGenerate()
    {
        $name = $this->input('name');
        $key = $this->createKey($name);
        return $this->data($this->service->create(['name' => $name, 'key' => $key])->toArray())->respond();
    }

    public function deleteKey($id)
    {
        $this->service->deleteModelByID($id);
    }

    private function createKey($name)
    {
        do {
            $salt = sha1($name . time() . mt_rand());
            $key = substr($salt, 0, 40);
        } while ($this->service->keyExists($key));

        return $key;
    }
} 