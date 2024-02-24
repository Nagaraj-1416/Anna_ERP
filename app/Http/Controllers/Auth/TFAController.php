<?php

namespace App\Http\Controllers\Auth;

use App\Repositories\Settings\FaceRecognitionRepository;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class TFAController
 * @package App\Http\Controllers\Auth
 */
class TFAController extends Controller
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
     * Load login index view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function index()
    {
        if (auth()->user()->tfa == 'No') return redirect('/');
        return view('auth.tfa.index');
    }

    /**
     * Verify the face and login
     * @param Request $request
     * @return array
     */
    public function verify(Request $request)
    {
        /** @var User $user */
        $user = $this->face->findTheUserByImage($request->input('image'));
        if ($user && auth()->id()  == $user->id){
            session(['tfa_expiry' =>  now()->addMinutes(config('session.tfa_lifetime'))]);
            $user->tfa_expiry = now()->addMinutes(config('session.tfa_lifetime'));
            $user->save();
            return ['success' => true];
        }
        return ['success' => false];
    }
}
