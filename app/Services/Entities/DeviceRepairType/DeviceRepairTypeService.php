<?php namespace Biffy\Services\Entities\DeviceRepairType;

use Biffy\Entities\DeviceRepairType\DeviceRepairTypeRepositoryInterface;
use Biffy\Facades\LanguageTranslator;
use Biffy\Services\Entities\LanguageKey\LanguageKeyService;
use Biffy\Services\Entities\Service;
use DB;
use Exception;

class DeviceRepairTypeService extends Service
{
    protected $languageKeyService;

    public function __construct(DeviceRepairTypeRepositoryInterface $repo, LanguageKeyService $languageKeyService)
    {
        $this->repo = $repo;

        $this->languageKeyService = $languageKeyService;
    }

    public function create($attributes)
    {
        DB::beginTransaction();

        try
        {
            $result = parent::create($attributes);

            $languageKeyList = [
                'EstimatedCost',
                'MetaDescription',
                'MetaKeywords',
                'Name',
                'WebDescription'
            ];

            foreach ($languageKeyList as $languageKey)
            {
                $this->languageKeyService->create([
                    'key' => LanguageTranslator::generateKey($result, $result->id, $languageKey)
                ]);
            }

            DB::commit();

            return $this->find($result->id);
        }
        catch (Exception $e)
        {
            dd($e->getMessage());

            DB::rollback();

            return null;
        }
    }
}