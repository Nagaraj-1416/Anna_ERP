<?php

namespace App\PDF;

use App\PDFTemplate;

/**
 * Class PDF
 * @package App\PDF
 */
class PDF
{
    /** @var string */
    protected $title = '';
    /** @var null|PDFTemplate */
    protected $template = null;
    /** @var array */
    protected $templateProperties = [];
    /** @var array */
    protected $headerProperties = [];
    /** @var array */
    protected $footerProperties = [];
    /** @var array */
    protected $contentProperties = [];

    /**
     * PDF constructor.
     * @param string $title
     */
    public function __construct($title = '')
    {
        $this->setTitle($title);
        $this->initiateTemplate();
    }

    /**
     * Create or find pdf template and initiate template data
     */
    private function initiateTemplate(): void
    {
        $this->template = PDFTemplate::where('class', self::class)->first();
        if (!$this->template) {
            $this->template = new PDFTemplate();
            $this->template->setAttribute('class', self::class);
            $this->template->setAttribute('name', $this->getTitle());
            $this->template->setAttribute('description', $this->getTitle());
            $this->template->setAttribute('template_properties', $this->defaultTemplateProperties());
            $this->template->setAttribute('header_properties', $this->defaultHeaderProperties());
            $this->template->setAttribute('footer_properties', $this->defaultFooterProperties());
            $this->template->setAttribute('content_properties', $this->defaultContentProperties());
            $this->template->setAttribute('read_only', 'No');
            $this->template->save();
        }
        $this->setTemplateProperties($this->template->template_properties);
        $this->setHeaderProperties($this->template->header_properties);
        $this->setFooterProperties($this->template->footer_properties);
        $this->setContentProperties($this->template->content_properties);
    }

    /**
     * Set pdf title
     * @param $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * get pdf title
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * get pdf template property
     * @return array
     */
    public function getTemplateProperties(): array
    {
        return $this->templateProperties;
    }

    /**
     * set pdf template property
     * @param array $templateProperties
     */
    public function setTemplateProperties($templateProperties = []): void
    {
        $this->templateProperties = $templateProperties;
    }

    /**
     * set pdf header property
     * @param array $headerProperties
     */
    public function setHeaderProperties($headerProperties = []): void
    {
        $this->headerProperties = $headerProperties;
    }

    /**
     * get pdf header property
     * @return array
     */
    public function getHeaderProperties(): array
    {
        return $this->headerProperties;
    }

    /**
     * set pdf footer property
     * @param array $footerProperties
     */
    public function setFooterProperties($footerProperties = []): void
    {
        $this->footerProperties = $footerProperties;
    }

    /**
     * get pdf footer property
     * @return array
     */
    public function getFooterProperties(): array
    {
        return $this->footerProperties;
    }

    /**
     * set pdf content property
     * @param array $contentProperties
     */
    public function setContentProperties($contentProperties = []): void
    {
        $this->contentProperties = $contentProperties;
    }

    /**
     * set pdf content property
     * @return array
     */
    public function getContentProperties(): array
    {
        return $this->contentProperties;
    }

    /**
     * define default template property
     * @return array
     */
    public function defaultTemplateProperties(): array
    {
        return [
            'paper_size' => 'A4',
            'orientation' => 'Portrait',
            'margins' => [
                'top' => '0.7',
                'bottom' => '0.7',
                'left' => '0.55',
                'right' => '0.4',
            ],
            'font' => [
                'family' => '',
                'color' => '#333333',
                'size' => ' 9pt'
            ],
            'background_image' => [
                'enable' => 'false',
                'image' => '',
                'position' => 'center center'
            ],
            'background_color' => [
                'enable' => 'false',
                'color' => '#ffffff'
            ],
            'label' => [
                'color' => '#333333',
            ]
        ];
    }

    /**
     * define default header property
     * @return array
     */
    public function defaultHeaderProperties(): array
    {
        return [
            'background_image' => [
                'enable' => 'false',
                'image' => '',
                'position' => 'center center'
            ],
            'background_color' => [
                'enable' => 'false',
                'color' => '#ffffff'
            ],
            'first_page-only' => 'false',
            'title' => [
                'enable' => 'true',
                'label' => $this->title,
                'font' => [
                    'color' => '#000000',
                    'size' => ' 9pt'
                ]
            ],
            'balance_due' => [
                'enable' => true,
            ],
            'organization_logo' => [
                'enable' => true,
            ],
            'organization_name' => [
                'enable' => true,
                'font' => [
                    'color' => '#000000',
                    'size' => ' 10pt'
                ]
            ],
            'organization_address' => [
                'enable' => true,
            ],
            'customer_name' => [
                'font' => [
                    'color' => '#000000',
                    'size' => ' 9pt'
                ]
            ],
            'number_field' => [
                'enable' => 'true',
                'label' => '#',
            ],
            'date_field' => [
                'enable' => 'true',
                'label' => $this->title . ' Date',
            ],
            'terms' => [
                'enable' => 'true',
                'label' => 'Terms',
            ],
            'due_date' => [
                'enable' => 'true',
                'label' => 'Due Date',
            ],
            'reference_field' => [
                'enable' => 'true',
                'label' => 'Reference Field',
            ],
            'salesperson' => [
                'enable' => 'false',
                'label' => 'Salesperson',
            ],
            'project' => [
                'enable' => 'false',
                'label' => 'Project Name',
            ],
            'bill_to' => [
                'enable' => 'true',
                'label' => 'Bill To',
            ],
            'ship_to' => [
                'enable' => 'false',
                'label' => 'Ship To',
            ],
            'show_status_stamp' => [
                'enable' => 'false',
                'label' => 'Show Status Stamp',
            ],
        ];
    }

    /**
     * define default footer property
     * @return array
     */
    public function defaultFooterProperties(): array
    {
        return [
            'notes' => [
                'enable' => 'true',
                'label' => 'Invoice',
                'font' => [
                    'color' => '#000000',
                    'size' => ' 9pt'
                ]
            ],
            'terms_and_conditions' => [
                'enable' => 'true',
                'label' => 'Invoice',
                'font' => [
                    'color' => '#000000',
                    'size' => ' 9pt'
                ]
            ],
            'font' => [
                'color' => '#333333',
                'size' => ' 9pt'
            ],
            'background_image' => [
                'enable' => 'false',
                'image' => '',
                'position' => 'center center'
            ],
            'background_color' => [
                'enable' => 'false',
                'color' => '#ffffff'
            ],
            'page_number' => [
                'enable' => 'true',
            ],
        ];
    }

    /**
     * define default content property
     * @return array
     */
    public function defaultContentProperties(): array
    {
        return [];
    }

}