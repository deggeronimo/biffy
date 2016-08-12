<?php namespace Biffy\Entities\DeviceType;

use Biffy\Entities\DeviceChecklist\DeviceChecklist;
use Biffy\Entities\EloquentAbstractRepository;
use Biffy\Facades\LanguageTranslator;

/**
 * Class EloquentDeviceTypeRepository
 * @package Biffy\Entities\DeviceType
 */
class EloquentDeviceTypeRepository extends EloquentAbstractRepository implements DeviceTypeRepositoryInterface
{
    /**
     * @var array
     */
    protected $filters = [
        'display_id' => ['device_types.device_display_size_id = ?', ':value'],
        'os_id' => ['device_types.device_operating_system_id = ?', ':value'],
        'parent_id' => ['device_types.parent_device_type_id = ?', ':value'],
        'top' => ['device_types.top = 1'],

        'name' => [ 'ls_name.string like ?', '%:value%' ],
        'parent_name' => [ 'pnls.string like ?', '%:value%' ],
        'family_id' => [ 'device_types.device_family_id = ?', ':value' ],
        'manufacturer_id' => [ 'device_types.device_manufacturer_id = ?', ':value' ]
    ];

    protected $sorters = [
        'created_at' => [],
        'name' => [],
        'release_date' => [],
        'selectable' => [],
        'sort_order' => [],
        'status' => [],
        'top' => [],
        'views' => [],
    ];

    /**
     * @param DeviceType $model
     * @param DeviceChecklist $deviceChecklistModel
     */
    public function __construct(DeviceType $model, DeviceChecklist $deviceChecklistModel)
    {
        $this->model = $model;
        $this->deviceChecklistModel = $deviceChecklistModel;

        $this->with([ 'parentDeviceType', 'deviceManufacturer', 'deviceFamily', 'deviceChecklist', 'deviceChecklist.checklistFunction',
            'deviceItemChecklist', 'deviceItemChecklist.checklistItem' ]);
    }

    public function all()
    {
        return $this->get();
    }

    protected function preGet()
    {
        $languageId = LanguageTranslator::languageId();

        $this->query->leftJoin('device_types as pdt', 'device_types.parent_device_type_id', '=', 'pdt.id')
            ->leftJoin('language_strings as pnls', 'pdt.name_language_key_id', '=', 'pnls.language_key_id')
            ->addSelect('pnls.string as parent_name')
            ->whereRaw("(pnls.language_id = {$languageId} or pnls.language_id IS NULL)");
    }

    /**
     * @param $deviceTypeId
     * @return array
     */
    private function getDeviceTree($deviceTypeId)
    {
        $deviceTypeArray = [ $deviceTypeId ];
        $deviceType = $this->find($deviceTypeId);
        while ($deviceType->device_type_id != 0)
        {
            $deviceTypeArray[] = $deviceType->device_type_id;
            $deviceType = $this->find($deviceType->device_type_id);
        }
        return $deviceTypeArray;
    }

    /**
     * @param $deviceTypeId
     * @return array
     */
    public function getDeviceChecklist($deviceTypeId)
    {
        $deviceTypeArray = $this->getDeviceTree($deviceTypeId);
        $deviceChecklistArray = [];
        for ($i = count($deviceTypeArray) - 1; $i >= 0; $i --)
        {
            $deviceChecklist = $this->deviceChecklistModel->with('checklistFunction')
                ->where('device_type_id', '=', $deviceTypeArray[$i])->get()->toArray();
            for ($j = 0; $j < count($deviceChecklist); $j ++)
            {
                $deviceChecklistItem = & $this->getValueById($deviceChecklistArray, $deviceChecklist[$j]['checklist_function']['id']);
                if ($deviceChecklistItem == null)
                {
                    $deviceChecklistArray[] = [
                        'id' => $deviceChecklist[$j]['checklist_function']['id'],
                        'name' => $deviceChecklist[$j]['checklist_function']['name'],
                        'item_id' => [ $deviceChecklist[$j]['item_id'] ]
                    ];
                }
                else
                {
                    $deviceChecklistItem['item_id'][] = $deviceChecklist[$j]['item_id'];
                }
            }
        }
        return $deviceChecklistArray;
    }

    private function & getValueById( & $array, $id)
    {
        for ($i = 0; $i < count($array); $i ++)
        {
            if (isset($array[$i]['id']) && $array[$i]['id'] == $id)
            {
                return $array[$i];
            }
        }

        $null = null;
        return $null;
    }
}