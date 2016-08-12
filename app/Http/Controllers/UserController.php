<?php namespace Biffy\Http\Controllers;

use Biffy\Http\Controllers\Helpers\ServiceCRUDControllerHelper;
use Biffy\Services\Entities\Store\StoreService;
use Biffy\Services\Entities\User\UserService;

/**
 * Class UserController
 * @package Biffy\Http\Controllers
 */
class UserController extends ApiController
{
    use ServiceCRUDControllerHelper;

    /**
     * @var UserService
     */
    protected $service;

    /**
     * @param UserService $service
     */
    function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        if (!is_null($this->input('username-list'))) {
            return $this->data($this->service->usernameList($this->input('search'))->toArray())->respond();
        } else if (!is_null($this->input('admin'))) {
            return $this->data($this->service->getAdminUsers())->statusOk()->respond();
        } else {
            $perPage = \StoreConfig::get('results-per-page');
            $perPage = (is_null($perPage)?10:$perPage);

            $count = $this->input('count', $perPage);
            $page = $this->input('page', 1);

            $result = $this->service->paginate($count, $page)->filterBy($this->input('filter'))->sortBy($this->input('sorting'))->get();

            return $this->data($result->toArray()['data'])->paginator($result)->respond();
        }
    }

    public function show($id)
    {
        if ($this->input('profile')) {
            $user = $this->service->getWithProfile($id, $this->input('boards'));
        } else if ($this->input('groups')) {
            $user = $this->service->getWithGroups($id);

        } else {
            $user = $this->service->find($id);
        }

        return $this->data($user->toArray())->respond();
    }

    public function employees(StoreService $storeService)
    {
        return $this->data($storeService->getUsers(\Auth::user()->storeId())->toArray())->respond();
    }

    /**
     * @param $id
     */
    public function update($id)
    {
        if (is_array($this->input('groups')))
        {
            $this->service->setGroups($id, $this->input('groups'));
        }

        $this->service->update($id, $this->inputAll());

        if ($this->input('profile')) {
            $this->service->updateProfile($id, $this->input('profile'));
        }

        if ($this->input('settings')) {
            $this->service->updateSettings($id, $this->input('settings'));
        }
    }

    /**
     * @param mixed $data
     * @return $this
     */
    protected function data($data)
    {
        $data = $this->keysOnly($data, ['groups', 'permissions']);

        return parent::data($data);
    }
} 