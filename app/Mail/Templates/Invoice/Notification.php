<?php

namespace App\Mail\Templates\Invoice;

use App\EmailTemplate;
use App\Mail\Templates\Template;

class Notification implements Template
{
    private $content;
    private $variables;
    private $loops;
    private $links;
    private $template;

    public function __construct()
    {
        $this->build();
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getLinks()
    {
        return $this->links;
    }

    public function getLoops()
    {
        return $this->loops;
    }

    public function getVariables()
    {
        return $this->variables;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    private function build()
    {
        $this->buildLinks();
        $this->buildLoops();
        $this->buildVariables();

        $className = get_class($this);
        $template = EmailTemplate::where('class', $className)->first();
        if (!$template) {
            $this->buildDefaultContent();
            $template = new EmailTemplate();
            $template->setAttribute('class', $className);
            $template->setAttribute('name', 'Invoice Notification');
            $template->setAttribute('description', 'Invoice email notification template');
            $template->setAttribute('subject', 'Invoice Notification');
            $template->setAttribute('content', $this->content);
            $template->setAttribute('variables', $this->variables);
            $template->setAttribute('loops', $this->loops);
            $template->setAttribute('links', $this->links);
            $template->save();
        } else {
            $this->content = $template->content;
        }
        $this->template = $template;
    }

    private function buildDefaultContent()
    {
        try {
            $this->content = view('emails.templates.default.invoice.notification')->render();
        } catch (\Throwable $e) {
        }
    }

    private function buildLinks()
    {
        $this->links = [];
    }

    private function buildLoops()
    {
        $this->loops = [];
    }

    private function buildVariables()
    {
        $this->variables = [];
    }
}