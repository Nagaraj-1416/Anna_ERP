<?php

namespace App\Repositories\Settings;

use App\Http\Requests\Setting\{
    EmailTemplateUpdateRequest
};
use App\Repositories\BaseRepository;
use App\{
    EmailTemplate
};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class EmailTemplateRepository
 * @package App\Repositories\Settings
 */
class EmailTemplateRepository extends BaseRepository
{
    /**
     * EmailTemplateRepository constructor.
     * @param EmailTemplate|null $emailTemplate
     */
    public function __construct(EmailTemplate $emailTemplate = null)
    {
        $this->setModel($emailTemplate ?? new EmailTemplate());
    }

    /**
     * update email template
     * @param EmailTemplateUpdateRequest $request
     * @return Model
     */
    public function update(EmailTemplateUpdateRequest $request): Model
    {
        if ($request->get('content')) {
            $request->merge(['content' => $this->transformContent($request->get('content'))]);
        }
        if ($request->get('subject')) {
            $request->merge(['subject' => $this->transformSubject($request->get('subject'))]);
        }

        if ($content = $request->get('content')) {
            $this->model->setAttribute('content', $content);
        }

        if ($subject = $request->get('subject')) {
            $this->model->setAttribute('subject', $subject);
        }

        if ($links = $request->get('links')) {
            $this->model->setAttribute('links', $links);
        }

        if ($loops = $request->get('loops')) {
            $this->model->setAttribute('loops', $loops);
        }

        $this->model->save();
        return $this->model;
    }

    /**
     * Transform Content
     * @param $content
     * @return mixed|string
     */
    protected function transformContent($content): string
    {
        $content = transform_email_template($this->model, $content);
        return $content;
    }

    /**
     * transform subject
     * @param $subject
     * @return mixed|string
     */
    protected function transformSubject($subject): string
    {
        $subject = transform_email_template($this->model, $subject);
        $subject = clean_string($subject);
        return $subject;
    }

    /**
     * Get the breadcrumbs of the email template module
     * @param string $method|null $method
     * @param EmailTemplate|null $emailTemplate
     * @return array|mixed
     */
    public function breadcrumbs(string $method = null, EmailTemplate $emailTemplate = null): array
    {
        if (!$method) {
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
            $method = $backtrace[1]['function'] ?? null;
        }
        $base = [
            ['text' => 'Dashboard', 'route' => 'dashboard'],
            ['text' => 'Settings', 'route' => 'setting.index'],
        ];

        $breadcrumbs = [
            'index' => array_merge($base, [
                ['text' => 'Email Templates'],
            ]),
            'edit' => array_merge($base, [
                ['text' => 'Email Templates', 'route' => 'setting.email.template.index'],
                ['text' => $emailTemplate->name ?? ''],
                ['text' => 'Edit'],
            ])
        ];
        return isset($breadcrumbs[$method]) ? $breadcrumbs[$method] : [];
    }
}