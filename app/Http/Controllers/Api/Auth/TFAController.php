<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\ApiController;
use App\Repositories\Settings\FaceRecognitionRepository;
use App\User;
use Illuminate\Http\Request;

class TFAController extends ApiController
{
    /**
     * @var FaceRecognitionRepository
     */
    protected $face;

    /**
     * TFAController constructor.
     * @param FaceRecognitionRepository $faceRecognitionRepository
     */
    public function __construct(FaceRecognitionRepository $faceRecognitionRepository)
    {
        $this->face = $faceRecognitionRepository;
    }

    /**
     * Verify the face and login
     * @param Request $request
     * @return array
     */
    public function verify(Request $request)
    {
        $request->validate([
            'image' => 'required',
        ]);
        /** @var User $user */
        $user = $this->face->findTheUserByImage($request->input('image'));
        if ($user && auth()->id()  == $user->id){
            $user->tfa_expiry = now()->addMinutes(config('session.tfa_lifetime'));
            $user->save();
            return response()->json(array(
                'success' => true,
                'message' => 'Authentication success',
            ), 200);
        }
        return response()->json(array(
            'message' => 'invalid_face_id',
            'errors' => 'The user\'s face were not matched.'
        ), 401);
    }
}
