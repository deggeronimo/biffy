<?php

namespace Biffy\Services\Directory;

class DirectoryService
{
    /**
     * @var \Google_Client
     */
    private $client;

    /**
     * @var \Google_Service_Directory
     */
    private $directory;

    public function __construct()
    {
        $scopes = [
            \Google_Service_Directory::ADMIN_DIRECTORY_GROUP,
            \Google_Service_Directory::ADMIN_DIRECTORY_GROUP_MEMBER,
            \Google_Service_Directory::ADMIN_DIRECTORY_USER,
            \Google_Service_Directory::ADMIN_DIRECTORY_ORGUNIT,
        ];

        $client = new \Google_Client();
        $client->setApplicationName('uBreakiFix Portal');

        if (\Session::get('gdir.service_token')) {
            $client->setAccessToken(\Session::get('gdir.service_token'));
        }

        $privateKeyPath = base_path('directory_private_key.p12');

        if (!file_exists($privateKeyPath)) {
            return;
        }

        $credentials = new \Google_Auth_AssertionCredentials(
            env('gdirectory_service_account_name'), $scopes, file_get_contents(
                $privateKeyPath
            ), 'notasecret', 'http://oauth.net/grant_type/jwt/1.0/bearer',
            env('gdirectory_user')
        );

        $client->setAssertionCredentials($credentials);

        if ($client->getAuth()->isAccessTokenExpired()) {
            // todo uncomment
//            $client->getAuth()->refreshTokenWithAssertion($credentials);
        }

        \Session::put('gdir.service_token', $client->getAccessToken());

        $directory = new \Google_Service_Directory($client);

        $this->client = $client;
        $this->directory = $directory;
    }

    /**
     * @param $givenName
     * @param $familyName
     * @param $password
     * @param $email
     * @return \Google_Service_Directory_User
     */
    public function createUser($givenName, $familyName, $password, $email)
    {
        $user = new \Google_Service_Directory_User();

        $name = new \Google_Service_Directory_UserName();
        $name->setGivenName($givenName);
        $name->setFamilyName($familyName);
        $user->setName($name);

        $user->setPassword($password);
        $user->setChangePasswordAtNextLogin(true);

        $user->setPrimaryEmail($email);

        $this->fire('user.created', ['givenName' => $givenName, 'familyName' => $familyName, 'email' => $email]);
        return $this->directory->users->insert($user);
    }

    /**
     * @param $email
     */
    public function deleteUser($email)
    {
        $this->fire('user.deleted', ['email' => $email]);
        $this->directory->users->delete($email);
    }

    /**
     * @param $email
     * @return \Google_Service_Directory_User
     */
    public function getUser($email)
    {
        return $this->directory->users->get($email);
    }

    /**
     * @param $email
     * @param $pageToken
     * @return array
     */
    public function listUsers($email, $pageToken = null)
    {
        $result = $this->directory->users->listUsers(['domain' => $email, 'pageToken' => $pageToken]);
        $users = $result->getUsers();
        $nextPageToken = $result->getNextPageToken();
        if (!is_null($nextPageToken)) {
            return array_merge($users, $this->listUsers($email, $nextPageToken));
        }
        return $users;
    }

    /**
     * @param $groupEmail
     * @param $groupName
     * @return \Google_Service_Directory_Group
     */
    public function createGroup($groupEmail, $groupName)
    {
        $group = new \Google_Service_Directory_Group();
        $group->setEmail($groupEmail);
        $group->setName($groupName);

        $this->fire('group.created', ['email' => $groupEmail, 'name' => $groupName]);
        return $this->directory->groups->insert($group);
    }

    /**
     * @param $groupEmail
     * @return \Google_Service_Directory_Group
     */
    public function getGroup($groupEmail)
    {
        return $this->directory->groups->get($groupEmail);
    }

    /**
     * @param $domain
     * @param $pageToken
     * @return array
     */
    public function listGroups($domain, $pageToken = null)
    {
        $result = $this->directory->groups->listGroups(['domain' => $domain, 'pageToken' => $pageToken]);
        $groups = $result->getGroups();
        $nextPageToken = $result->getNextPageToken();
        if (!is_null($nextPageToken)) {
            return array_merge($groups, $this->listGroups($domain, $nextPageToken));
        }
        return $groups;
    }

    /**
     * @param $groupEmail
     */
    public function deleteGroup($groupEmail)
    {
        $this->fire('group.deleted', ['email' => $groupEmail]);
        $this->directory->groups->delete($groupEmail);
    }

    /**
     * @param $email
     * @param $pageToken
     * @return array
     */
    public function listGroupMembers($email, $pageToken = null)
    {
        $result = $this->directory->members->listMembers($email, ['pageToken' => $pageToken]);
        $members = $result->getMembers();
        $nextPageToken = $result->getNextPageToken();
        if (!is_null($nextPageToken)) {
            return array_merge($members, $this->listGroupMembers($email, $nextPageToken));
        }
        return $members;
    }

    /**
     * @param $groupEmail
     * @param $userEmail
     * @return \Google_Service_Directory_Member
     */
    public function addUserToGroup($groupEmail, $userEmail)
    {
        $member = new \Google_Service_Directory_Member();
        $member->setEmail($userEmail);

        $this->fire('group.userAdded', ['group' => $groupEmail, 'user' => $userEmail]);
        return $this->directory->members->insert($groupEmail, $member);
    }

    /**
     * @param $groupEmail
     * @param $userEmail
     */
    public function removeUserFromGroup($groupEmail, $userEmail)
    {
        $this->fire('group.userRemoved', ['group' => $groupEmail, 'user' => $userEmail]);
        $this->directory->members->delete($groupEmail, $userEmail);
    }

    /**
     * @param $userEmail
     * @return \Google_Service_Directory_Groups
     */
    public function getUsersGroups($userEmail)
    {
        return $this->directory->groups->listGroups(['userKey' => $userEmail]);
    }

    /**
     * @param string $event
     * @param array $context
     */
    private function fire($event, $context)
    {
        \Event::fire('google.' . $event, ['context' => $context]);
    }
}