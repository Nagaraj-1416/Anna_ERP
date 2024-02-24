<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;

use App\ContactPerson;
use App\Http\Requests\General\ContactPersonRequest;
use App\Repositories\General\ContactPersonRepository;
use Illuminate\Http\RedirectResponse;

/**
 * Class ContactPersonController
 * @package App\Http\Controllers\
 */
class ContactPersonController extends Controller
{
    /**
     * @var ContactPersonRepository
     */
    protected $contactPerson;

    /**
     * ContactPersonController constructor.
     * @param ContactPersonRepository $contactPerson
     */
    public function __construct(ContactPersonRepository $contactPerson = null)
    {
        $this->contactPerson = $contactPerson;
    }

    /**
     * Store the new Contact person
     * @param ContactPersonRequest $request
     * @param $model
     * @param $modelId
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */

    public function store(ContactPersonRequest $request, $model, $modelId)
    {
        $this->authorize('store', $this->contactPerson->getModel());
        $modelName = 'App\\' . $model;
        $newModal = app($modelName)->find($modelId);
        $this->contactPerson->save($request, $modelName, $newModal);
        return redirect()->back();
    }

    /**
     * Handle the index page data table data
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function getData()
    {
        $this->authorize('index', $this->contactPerson->getModel());
        $request = request();
        $model = $request->model;
        $modelId = $request->modelId;
        $model = app('App\\' . $model)->find($modelId);
        $data = $this->contactPerson->dataTable($model, $request);
        if (\request()->ajax()) {
            return $data;
        }
    }

    /**
     * Handle the edit Contact person
     * @param ContactPerson $contactPerson
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(ContactPerson $contactPerson)
    {
        $this->authorize('edit', $this->contactPerson->getModel());
        return response()->json($contactPerson);
    }

    /**
     * Handle update contact person
     * @param ContactPersonRequest $request
     * @param ContactPerson $contactPerson
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */

    public function update(ContactPersonRequest $request, ContactPerson $contactPerson)
    {
        $this->authorize('update', $this->contactPerson->getModel());
        $this->contactPerson->update($request, $contactPerson);
        return redirect()->back();
    }

    /**
     * Handle the delete contact person
     * @param ContactPerson $contactPerson
     * @param $model
     * @param $modelId
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */

    public function delete(ContactPerson $contactPerson, $model, $modelId)
    {
        $this->authorize('delete', $this->contactPerson->getModel());
        $this->contactPerson->setModel($contactPerson);
        $response = $this->contactPerson->delete();
        return response()->json($response);
    }
}
