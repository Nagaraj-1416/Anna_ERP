<?php

namespace App\Repositories\Settings;

use App\Repositories\BaseRepository;
use App\{
    FaceId, User
};
use Illuminate\Support\Facades\Storage;
use Jeylabs\AwsRekognition\AwsRekognition;

/**
 * Class FaceRecognitionRepository
 * @package App\Repositories\Settings
 */
class FaceRecognitionRepository extends BaseRepository
{
    /**
     * S3 collection id
     * @var string
     */
    public $collectionId;
    /**
     * S3 image path
     * @var string
     */
    public $imagePath = 'faceId/';
    /**
     * S3 images prefix
     * @var string
     */
    public $prefix = 'face_id';

    /**
     * Login images store in
     * @var string
     */
    public $loginImagesPath = 'loginFaceId/';

    /**
     * FaceRecognitionRepository constructor.
     * @param FaceId|null $faceId
     */
    public function __construct(FaceId $faceId = null)
    {
        $this->setModel($faceId ? $faceId : new FaceId());
        $this->collectionId = config('rekognition.collection_id');
    }

    /**
     * add new face to user
     * @param $faceImage
     * @param User $user
     * @return \Illuminate\Support\Collection
     */
    public function uploadFace($faceImage, User $user)
    {
        $image = $this->decodeImage($faceImage);
        $fileName = uniqid(rand(), false);
        $fullImagePath = $this->imagePath . $fileName . '.png';
        $imageUploaded = Storage::disk('s3')->put($fullImagePath, $image);
        if ($imageUploaded) {
            $awsRekognition = new AwsRekognition();
            $results = $awsRekognition->indexFaces($fullImagePath, $this->collectionId);
            $faceResults = collect($results->toArray());
            $userFaceId = $results['FaceRecords'][0]['Face']['FaceId'] ?? null;
            if ($userFaceId) {
                $this->model->setAttribute('face_id', $userFaceId);
                $this->model->setAttribute('face_data', $faceResults->toArray());
                $this->model->setAttribute('image_path', $fullImagePath);
                $this->model->setAttribute('user_id', $user->id);
                $this->model->save();
            }
            return $faceResults;
        }
        return collect();
    }

    /**
     * find the user by image
     * @param $faceImage
     * @return null
     */
    public function findTheUserByImage($faceImage)
    {
        $image = $this->decodeImage($faceImage);
        $fileName = uniqid(rand(), false);
        $fullImagePath = $this->loginImagesPath . $fileName . '.png';
        $uploaded = Storage::disk('s3')->put($fullImagePath, $image);
        if ($uploaded) {
            $awsRekognition = new AwsRekognition();
            $results = $awsRekognition->searchFacesByImage($this->collectionId, $fullImagePath);
            $results = $results->toArray();
            $matchFace = $results['FaceMatches'][0] ?? null;
            if ($matchFace) {
                $similarity = $matchFace['Similarity'] ?? 0;
                if ($similarity > 90) {
                    $matchFaceId = $matchFace['Face']['FaceId'] ?? null;
                    $matchedFace = FaceId::where('face_id', $matchFaceId)->first();
                    $matchedUser = $matchedFace ? $matchedFace->user : null;
                    if ($matchedUser) {
                        return $matchedUser;
                    }
                }
            }
        }
        return null;
    }

    /**
     * get face image by id
     * @param FaceId $faceId
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getFaceImage(FaceId $faceId)
    {
        return Storage::disk('s3')->get($faceId->image_path);
    }

    /**
     * delete added faces
     * @param FaceId $faceId
     * @return array
     * @throws \Exception
     */
    public function deleteFace(FaceId $faceId)
    {
        $awsRekognition = new AwsRekognition();
        $results = $awsRekognition->deleteFaces($this->collectionId, [$faceId->face_id]);
        $results = $results->toArray();
        if (isset($results['@metadata']['statusCode']) && $results['@metadata']['statusCode'] == 200) {
            Storage::disk('s3')->delete($faceId->image_path);
            $faceId->delete();
            return ['success' => true];
        }
        return ['success' => false];
    }

    /**
     * decode image to base64
     * @param $data
     * @return bool|string
     */
    protected function decodeImage($data)
    {
        list($type, $imageData) = explode(';', $data);
        list(, $extension) = explode('/', $type);
        list(, $imageData) = explode(',', $imageData);
        return base64_decode($imageData);
    }

    /**
     * create face id collection
     * @return bool
     */
    public function createCollection()
    {
        $awsRekognition = new AwsRekognition();
        $awsRekognition->createCollection($this->collectionId);
        return true;
    }

    /**
     * Get the breadcrumbs of the user module
     * @param string $method
     * @param User|null $user
     * @return array|mixed
     */
    public function breadcrumbs(string $method, User $user = null): array
    {
        $breadcrumbs = [
            'index' => [
                ['text' => 'Dashboard', 'route' => 'dashboard'],
                ['text' => 'Settings', 'route' => 'setting.index'],
                ['text' => $user->name],
                ['text' => 'Faces'],
            ]
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }
}
