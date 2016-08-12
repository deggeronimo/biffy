<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
*/
class ApiTester extends \Codeception\Actor
{
    use _generated\ApiTesterActions;

    public function setUpStore()
    {
        if (!defined('TEST_SET_UP_STORE') || !TEST_SET_UP_STORE) {
            define('TEST_SET_UP_STORE', true);
            $this->sendPOST('stores', ['name' => 'Test Store', 'group_id' => 1]);
        }
    }

    public function setUpLanguage()
    {
        if (!defined('TEST_SET_UP_LANGUAGE') || !TEST_SET_UP_LANGUAGE) {
            define('TEST_SET_UP_LANGUAGE', true);
            $this->sendPOST('languages', ['lang' => 'en']);
        }
    }

    public function setLanguageStrings($languageKey, $value)
    {
        $this->sendGET("languagekeys/{$languageKey}/strings");
        $languageStringIdList = $this->grabDataFromResponseByJsonPath('$.data[0].id');

        foreach ($languageStringIdList as $id)
        {
            $this->sendPUT("languagekeys/{$languageKey}/strings/{$id}", [ 'strings' => [ [ 'id' => $id, 'string' => $value ] ] ]);
            $this->seeResponseCodeIs(205);
        }
    }
}
