<?php namespace Biffy\Entities\User;

use Biffy\Entities\EloquentAbstractRepository;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class EloquentUserRepository
 * @package Biffy\Entities\User
 */
class EloquentUserRepository extends EloquentAbstractRepository implements UserRepositoryInterface
{
    protected $sorters = [
        'email' => [],
        'given_name' => [],
        'family_name' => [],
    ];

    protected $filters = [
        'email' => ['email LIKE ?', '%:value%'],
        'search' => ['(email LIKE ? OR given_name LIKE ? OR family_name LIKE ?)', '%:value%', '%:value%', '%:value%'],
        'store_id' => ['? IN (select store_id from store_user where store_user.user_id=users.id)', ':value']
    ];

    /**
     * @param User $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * @param int $perPage
     * @param int $curPage
     * @param string $search
     * @return mixed
     */
    public function getPaginated($perPage = 10, $curPage = 1, $search = '')
    {
        $skip = ($curPage - 1) * $perPage;
        return $this->make()->where('email', 'like', "%{$search}%")->skip($skip)->take($perPage)->get();
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function getEmailById($userId)
    {
        $user = $this->find($userId);
        return $user->email;
    }

    /**
     * @param $id
     * @param $setGroups
     */
    public function setGroups($id, $setGroups)
    {
        $user = $this->find($id);
        $user->groups()->sync($setGroups);
    }

    public function getAdminUsers()
    {
        return $this->findAllBy('admin', '1');
    }

    public function getAdminIds()
    {
        return $this->getAdminUsers()->modelKeys();
    }

    public function getUsers(array $userIds)
    {
        return $this->make()->whereIn('id', $userIds)->get()->toArray();
    }

    public function getUserByEmail($email)
    {
        return $this->with(['settings'])->findFirstBy('email', $email);
    }

    public function usernameList($search)
    {
        if ($search === '') {
            return new Collection();
        }

        $this->with = [];
        return $this->make()->where('username', 'like', '%' . $search . '%')->limit(10)->get();
    }

    public function findByUsernames($usernames)
    {
        return $this->make()->whereIn('username', $usernames)->get();
    }

    public function getWithProfile($id)
    {
        return $this->with(['profile'])->find($id);
    }

    public function getFullProfile($id)
    {
        return $this->with(['profile'])->with($this->boardActivityWith())->find($id);
    }

    public function getAllIds()
    {
        return $this->all()->modelKeys();
    }

    public function getWithBoardActivity($id)
    {
        return $this->with($this->boardActivityWith())->find($id);
    }

    private function boardActivityWith()
    {
        // todo pagination or just limit at least
        return [
            'threads' => function ($query) {
                $query->with(['first_post']);
            },
            'posts' => function ($query) {
                $query->with(['thread']);
            }
        ];
    }
}