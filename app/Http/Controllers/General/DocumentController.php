<?php

namespace App\Http\Controllers\General;

use App\Document;
use App\Http\Controllers\Controller;
use App\Http\Requests\General\DocumentStoreRequest;
use App\Repositories\General\DocumentRepository;

/**
 * Class DocumentController
 * @package App\Http\Controllers\General
 */
class DocumentController extends Controller
{

    /** @var DocumentRepository */
    protected $document;

    /**
     * DocumentController constructor.
     * @param DocumentRepository $documentRepository
     */
    public function __construct(DocumentRepository $documentRepository)
    {
        $this->document = $documentRepository;
    }

    /**
     * get all documents of a model
     * @param $model
     * @param $modelId
     * @return \Illuminate\Support\Collection
     */
    public function index($model, $modelId)
    {
        $this->document->setDocumentable($model, $modelId);
        $response = $this->document->getAllDocuments();
        return response()->json($response->toArray());
    }

    /**
     * Upload file to documentable model
     * @param $model
     * @param $modelId
     * @param DocumentStoreRequest $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function upload($model, $modelId, DocumentStoreRequest $request)
    {
        $this->document->setDocumentable($model, $modelId);
        $documents = collect([]);
        if ($request->file('files')) {
            foreach ($request->input('files') as $file) {
                $document = $this->document->save($file);
                $documents->push($document);
            }
        }
        if ($request->file('file')) {
            return $this->document->save($request->file('file'));
        }
    }

    /**
     * Download the document
     * @param Document $document
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function download(Document $document)
    {
        $this->document->setModel($document);
        return $this->document->download();
    }

    /**
     * Show the document
     * @param Document $document
     * @return mixed
     */
    public function show(Document $document)
    {
        $this->document->setModel($document);
        return $this->document->show();
    }

    /**
     * delete the document
     * @param Document $document
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Document $document)
    {
        $this->document->setModel($document);
        $this->document->delete();
        return response()->json(['success' => true]);
    }
}
