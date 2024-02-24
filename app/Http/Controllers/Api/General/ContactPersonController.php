<?php

namespace App\Http\Controllers\Api\General;

use App\ContactPerson;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\General\ContactPersonStoreRequest;
use App\Http\Resources\ContactPersonResource;
use App\Repositories\General\ContactPersonRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContactPersonController extends ApiController
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
     * @param ContactPersonStoreRequest $request
     * @param $model
     * @param $modelId
     * @return ContactPersonResource
     */

    public function store(ContactPersonStoreRequest $request, $model, $modelId)
    {
        $model = studly_case($model);
        $modelName = 'App\\' . $model;
        $newModal = app($modelName)->find($modelId);
        $contactPerson = $this->contactPerson->saveSingle($request, $modelName, $newModal);
        return new ContactPersonResource($contactPerson);
    }

    /**
     * Handle the index page data table data
     * @param $model
     * @param $modelId
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index($model, $modelId)
    {
        $model = studly_case($model);
        $model = app('App\\' . $model)->find($modelId);
        if ($model){
            return ContactPersonResource::collection($model->contactPersons);
        }
        return ContactPersonResource::collection(collect([]));
    }

    /**
     * @param ContactPerson $contactPerson
     * @return ContactPersonResource
     */
    public function show(ContactPerson $contactPerson){
        return new ContactPersonResource($contactPerson);
    }

    /**
     * Handle update contact person
     * @param ContactPersonStoreRequest $request
     * @param ContactPerson $contactPerson
     * @return ContactPersonResource
     */

    public function update(ContactPersonStoreRequest $request, ContactPerson $contactPerson)
    {
        $contactPerson = $this->contactPerson->saveSingle($request, null, null, $contactPerson);
        return new ContactPersonResource($contactPerson);
    }

    /**
     * Handle the delete contact person
     * @param ContactPerson $contactPerson
     * @return RedirectResponse
     */

    public function delete(ContactPerson $contactPerson)
    {
        $this->contactPerson->setModel($contactPerson);
        $response = $this->contactPerson->delete();
        return response()->json($response);
    }
}
