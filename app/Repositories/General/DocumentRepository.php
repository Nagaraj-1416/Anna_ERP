<?php

namespace App\Repositories\General;

use App\Document;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

/**
 * Class DocumentRepository
 * @package App\Repositories\General
 */
class DocumentRepository extends BaseRepository
{
    /**
     * ContactPersonRepository constructor.
     * @param Document|null $document
     */

    protected $path = 'documents/';
    protected $storagePath = 'app/documents/';
    protected $storageLocation = 'storage/app/documents/';
    protected $documentable;
    protected $document;

    /**
     * DocumentRepository constructor.
     * @param Document|null $document
     */
    public function __construct(Document $document = null)
    {
        $this->setModel($document ?? new Document());
    }

    /**
     * Get all documents
     * @return \Illuminate\Support\Collection
     */
    public function getAllDocuments(): Collection
    {
        return $this->documentable->documents()->orderBy('id', 'desc')->get();
    }

    /**
     * Save new document
     * @param UploadedFile $file
     * @return Model
     */
    public function save(UploadedFile $file): Model
    {
        $this->createDocument($file);
        $this->putFile($file);
        return $this->model;
    }

    /**
     * Download the document
     * @return Response
     */
    public function download(): Response
    {
        $fileContent = $this->getFile();
        $response = response($fileContent, 200, [
            'Content-Type' => $this->model->mime,
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => "attachment; filename={$this->getFileNameForDownload()}",
            'Content-Transfer-Encoding' => 'binary',
        ]);
        ob_end_clean();
        return $response;
    }

    /**
     * Show the document
     * @return Response
     */
    public function show(): Response
    {
        $fileContent = $this->getFile();
        return response($fileContent)->header('Content-Type', $this->model->mime);
    }

    /**
     * Delete the document
     * @return bool
     */
    public function delete(): bool
    {
        try {
            $this->model->delete();
            $this->deleteFile();
        } catch (\Exception $e) {
        }
        return true;
    }

    /**
     * Create a document object
     * @param UploadedFile $file
     */
    private function createDocument(UploadedFile $file): void
    {
        $newDocument = (new Document())->create([
            'name' => $file->getClientOriginalName(),
            'mime' => $file->getClientMimeType(),
            'extension' => $file->getClientOriginalExtension(),
            'size' => filesize($file),
            'user_id' => auth()->user()->id ?? null,
        ]);
        $this->documentable->documents()->save($newDocument);
        $this->setModel($newDocument);
    }

    /**
     * Set documentable model
     * @param $model
     * @param null $modelId
     */
    public function setDocumentable($model, $modelId = null): void
    {
        if (is_string($model) && is_numeric($modelId) && $model && $modelId) {
            $this->documentable = app('App\\' . $model)->find($modelId);
        } else {
            $this->documentable = $model;
        }
    }

    /**
     * get documentable model
     * @return Model
     */
    public function getDocumentable(): Model
    {
        return $this->documentable;
    }

    /**
     * Get document file name
     * @return string
     */
    public function getFileName(): string
    {
        return $this->model->getAttribute('id') . '.' . $this->model->getAttribute('extension');
    }

    /**
     * @return string
     */
    public function getFileNameForDownload()
    {
        return class_basename($this->model->documentable) . '-' . $this->model->getAttribute('id') . '.' . $this->model->getAttribute('extension');
    }

    /**
     * Put the file to storage
     * @param UploadedFile $file
     */
    public function putFile(UploadedFile $file): void
    {
        try {
            Storage::put($this->getFilePath(), file_get_contents($file));
        } catch (\Exception $e) {
            try {
                $this->model->delete();
            } catch (\Exception $e) {
            }
        }
    }

    /**
     * Get the file path
     * @return string
     */
    private function getFilePath(): string
    {
        return $this->path . $this->getFileName();
    }

    /**
     * get file content form storage
     * @return string
     */
    public function getFile(): string
    {
        return Storage::get($this->getFilePath());
    }

    /**
     * check if file exist
     * @return bool
     */
    public function fileExists(): bool
    {
        return Storage::exists($this->getFilePath());
    }

    /**
     * delete file from storage
     * @return bool
     */
    public function deleteFile(): bool
    {
        if ($this->fileExists()) {
            return Storage::delete($this->getFilePath());
        }
        return false;
    }

    /**
     * Copy file to new location
     * @param string $fileName
     * @param string $path
     * @return mixed
     */
    public function copyFile(string $fileName, string $path): mixed
    {
        $oldPath = $this->getFilePath();
        $newPath = $path . '/' . $fileName;
        return Storage::copy($oldPath, $newPath);
    }

    /**
     * Move file to new location
     * @param string $fileName
     * @param string $path
     * @return mixed
     */
    public function move(string $fileName, string $path)
    {
        $oldPath = $this->getFilePath();
        $newPath = $path . '/' . $fileName;
        return Storage::move($oldPath, $newPath);
    }

    /**
     * get file full location
     * @return string
     */
    public function getFileLocation()
    {
        return storage_path($this->storagePath . $this->getFileName());
    }

    /**
     * get document path
     * @return string
     */
    public function getDocumentStoragePath()
    {
        return $this->storagePath;
    }

    /**
     * get document file path
     * @return string
     */
    public function getDocumentFilePath()
    {
        return $this->path;
    }
}