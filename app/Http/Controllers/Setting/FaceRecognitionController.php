<?php

namespace App\Http\Controllers\Setting;

use App\FaceId;
use App\Repositories\Settings\FaceRecognitionRepository;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class FaceRecognitionController
 * @package App\Http\Controllers\Setting
 */
class FaceRecognitionController extends Controller
{
    /**
     * @var FaceRecognitionRepository
     */
    protected  $face;

    /**
     * FaceRecognitionController constructor.
     * @param FaceRecognitionRepository $face
     */
    public function __construct(FaceRecognitionRepository $face)
    {
        $this->face = $face;
    }

    /**
     * Load index view and pass the required data
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(User $user)
    {
        $breadcrumb = $this->face->breadcrumbs('index', $user);
        $faceIds = $user->faceIds;
        return view('settings.faces.index', compact('breadcrumb', 'user', 'faceIds'));
    }

    /**
     * Store new face
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(User $user, Request $request)
    {
        $this->face->uploadFace($request->input('image'), $user);
        return response()->json(['success' => true]);
    }

    /**
     * get the face image
     * @param FaceId $faceId
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function image(FaceId $faceId){
        $image = $this->face->getFaceImage($faceId);
        return response($image)->header('Content-Type', 'Image/png');
    }

    /**
     * delete the face image
     * @param FaceId $faceId
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(FaceId $faceId){
        $response = $this->face->deleteFace($faceId);
        return response()->json($response);
    }
}
