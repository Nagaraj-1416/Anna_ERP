<?php

namespace App\Repositories\General;

use App\ContactPerson;
use App\Repositories\BaseRepository;
use App\Supplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use function MongoDB\BSON\toJSON;

/**
 * Class ContactPersonRepository
 * @package App\Repositories\Settings
 */
class ContactPersonRepository extends BaseRepository
{
    /**
     * ContactPersonRepository constructor.
     * @param ContactPerson|null $contactPerson
     */
    public function __construct(ContactPerson $contactPerson = null)
    {
        $this->setModel($contactPerson ?? new ContactPerson());
        $this->setCodePrefix('SUP');
    }

    /**
     * Get data to data table
     * @param $model
     * @param Request $request
     * @return array
     */
    public function dataTable($model, Request $request): array
    {
        $this->setModel($model);
        $columns = ['first_name', 'last_name', 'salutation', 'full_name', 'phone', 'mobile', 'email', 'designation', 'department', 'is_active'];
        $searchingColumns = ['first_name', 'last_name', 'salutation', 'full_name', 'phone', 'mobile', 'email', 'designation', 'department', 'is_active'];
        $data = $this->getTableData($request, $columns, $searchingColumns, [], true, 'contactPersons');
        $data['data'] = array_map(function ($item) {
            $item['action'] = "<div class=\"button-group\">";
            if (can('edit', $this->getModel())) {
                $item['action'] .= actionBtn('Edit', null, [], ['onclick' => 'editData(' . $item['id'] . ')', 'data-toggle' => 'modal', 'data-target' => '#edit_modal']);
            }
            if (can('delete', $this->getModel())) {
                $item['action'] .= actionBtn('Delete', null, [], ['onclick' => 'deletePerson(' . $item['id'] . ')', 'class' => 'btn-danger']);
            }
            $item['action'] .= "</div>";
            return $item;
        }, $data['data']);
        return $data;
    }

    public function save($request, $modelName, $newModal)
    {
        foreach ($request->input('first_name') as $key => $data) {
            $contactPerson = new ContactPerson();
            $contactPerson->salutation = array_get($request->input('salutation'), $key);
            $contactPerson->first_name = array_get($request->input('first_name'), $key);
            $contactPerson->last_name = array_get($request->input('last_name'), $key);
            $contactPerson->full_name = array_get($request->input('full_name'), $key);
            $contactPerson->phone = array_get($request->input('phone'), $key);
            $contactPerson->mobile = array_get($request->input('mobile'), $key);
            $contactPerson->email = array_get($request->input('email'), $key);
            $contactPerson->designation = array_get($request->input('designation'), $key);
            $contactPerson->department = array_get($request->input('department'), $key);
            $contactPerson->contact_personable_id = $newModal->id;
            $contactPerson->contact_personable_type = $modelName;
            $contactPerson->save();
        }
        return true;
    }

    public function saveSingle($request, $modelName, $newModal, ContactPerson $contactPerson = null)
    {
        if (!$contactPerson) {
            $contactPerson = new ContactPerson();
        }
        if ($modelName && $newModal) {
            $contactPerson->contact_personable_id = $newModal->id;
            $contactPerson->contact_personable_type = $modelName;
        }
        $contactPerson->salutation = $request->input('salutation');
        $contactPerson->first_name = $request->input('first_name');
        $contactPerson->last_name = $request->input('last_name');
        $contactPerson->full_name = $request->input('full_name');
        $contactPerson->phone = $request->input('phone');
        $contactPerson->mobile = $request->input('mobile');
        $contactPerson->email = $request->input('email');
        $contactPerson->designation = $request->input('designation');
        $contactPerson->department = $request->input('department');
        $contactPerson->save();
        return $contactPerson;
    }

    /**
     * Store new contact person from array
     * @param array $items
     * @param $model
     */
    public function storeFromArray(array $items, $model)
    {
        $now = carbon()->now();
        foreach ($items as $key => $item) {
            if (!isset($items[$key]['contact_personable_type'])) $items[$key]['contact_personable_type'] = null;
            if (!isset($items[$key]['contact_personable_type'])) $items[$key]['contact_personable_type'] = null;
            if (!isset($items[$key]['created_at'])) $items[$key]['created_at'] = null;
            if (!isset($items[$key]['updated_at'])) $items[$key]['updated_at'] = null;
            $items[$key]['contact_personable_type'] = 'APP\\' . class_basename($model);
            $items[$key]['contact_personable_id'] = $model->id;
            $items[$key]['created_at'] = $items[$key]['updated_at'] = $now;
        }
        $this->model->insert($items);
    }

    /**
     * @param $request
     * @param ContactPerson $contactPerson
     * @return bool
     */
    public function update($request, ContactPerson $contactPerson)
    {
        $contactPerson->salutation = $request->input('salutation');
        $contactPerson->first_name = $request->input('first_name');
        $contactPerson->last_name = $request->input('last_name');
        $contactPerson->full_name = $request->input('full_name');
        $contactPerson->phone = $request->input('phone');
        $contactPerson->mobile = $request->input('mobile');
        $contactPerson->email = $request->input('email');
        $contactPerson->designation = $request->input('designation');
        $contactPerson->department = $request->input('department');
        $contactPerson->is_active = $request->input('is_active');
        $contactPerson->save();
        return true;
    }

    public function delete(): array
    {
        try {
            $this->model->delete();
            return ['success' => true, 'message' => 'Contact person deleted successfully!'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Contact person deleted failed!'];
        }

    }
}