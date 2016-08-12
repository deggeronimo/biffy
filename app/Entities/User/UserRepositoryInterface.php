<?php namespace Biffy\Entities\User;

use Biffy\Entities\AbstractRepositoryInterface;

interface UserRepositoryInterface extends AbstractRepositoryInterface
{

    /**
     * @param int $perPage
     * @param int $curPage
     * @param string $search
     * @return mixed
     */
    public function getPaginated($perPage = 10, $curPage = 1, $search = '');

    public function getEmailById($userId);

    public function setGroups($id, $groups);

    public function getAdminUsers();
    public function getAdminIds();
    public function getUsers(array $userIds);
    public function getUserByEmail($email);

    public function usernameList($search);
    public function findByUsernames($usernames);

    public function getWithProfile($id);
    public function getFullProfile($id);
    public function getWithBoardActivity($id);
    public function getAllIds();
} 