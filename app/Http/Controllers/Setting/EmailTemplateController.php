<?php

namespace App\Http\Controllers\Setting;

use App\EmailTemplate;
use App\Http\Requests\Setting\EmailTemplateUpdateRequest;
use App\Repositories\Settings\EmailTemplateRepository;
use Illuminate\Http\{
    JsonResponse, Request
};
use App\Http\Controllers\Controller;
use Illuminate\View\View;

/**
 * Class EmailTemplateController
 * @package App\Http\Controllers\Setting
 */
class EmailTemplateController extends Controller
{
    /**
     * @var EmailTemplateRepository
     */
    private $emailTemplate;

    /**
     * EmailTemplateController constructor.
     * @param EmailTemplateRepository $emailTemplate
     */
    public function __construct(EmailTemplateRepository $emailTemplate)
    {
        $this->emailTemplate = $emailTemplate;
    }

    /**
     * Load index view fo email template
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(): View
    {
        $breadcrumb = $this->emailTemplate->breadcrumbs();
        $templates = $this->emailTemplate->getAll();
        return view('settings.email-template.index', compact('breadcrumb', 'templates'));
    }

    /**
     * Load view fo email template edit view
     * @param EmailTemplate $template
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function edit(EmailTemplate $template): View
    {
        $breadcrumb = $this->emailTemplate->breadcrumbs();
        return view('settings.email-template.edit', compact('breadcrumb', 'template'));
    }

    /**
     * Update email template
     * @param EmailTemplateUpdateRequest $request
     * @param EmailTemplate $template
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(EmailTemplateUpdateRequest $request, EmailTemplate $template): JsonResponse
    {
        $this->emailTemplate->setModel($template);
        $template = $this->emailTemplate->update($request);
        if (!$request->ajax()) {
            return response()->redirectToRoute('setting.email.template.index');
        }
        return response()->json([
            'success' => true,
            'data' => $template->toArray()
        ]);
    }
}
