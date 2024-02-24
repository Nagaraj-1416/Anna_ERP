<?php

use Illuminate\Database\Seeder;

class PdftemplatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pdfTemplates = [
            [
                'class' => 'Invoice',
                'name' => 'Standard Invoice',
                'description' => "Basic invoice template for standard transactions",
                'template_properties' => "Clean layout, essential invoice elements",
                'header_properties' => 'Company logo, invoice date',
                'footer_properties' => 'Payment terms, total amount due',
                'content_properties' => 'Itemized billing, subtotal, tax',
                'read_only' => 'Yes',
            ],
            [
                'class' => 'Report',
                'name' => 'Monthly Sales Report',
                'description' => "Monthly summary report for sales performance",
                'template_properties' => "Comprehensive analysis, graphical representations",
                'header_properties' => 'Report title, reporting period',
                'footer_properties' => 'Generated date, page numbers',
                'content_properties' => 'Sales breakdown by product/category, trends',
                'read_only' => 'Yes',
            ],
            [
                'class' => 'Certificate',
                'name' => 'Training Completion Certificate',
                'description' => "Certificate template for completion of training programs",
                'template_properties' => "Professional design, customizable fields",
                'header_properties' => 'Certificate title, recipient name',
                'footer_properties' => 'Issued by, date of completion',
                'content_properties' => 'Training program details, instructor signature',
                'read_only' => 'Yes',
            ],
            [
                'class' => 'Agreement',
                'name' => 'Service Agreement',
                'description' => "Standard agreement template for service contracts",
                'template_properties' => "Legally binding format, terms and conditions",
                'header_properties' => 'Agreement title, parties involved',
                'footer_properties' => 'Effective date, termination clause',
                'content_properties' => 'Service scope, payment terms',
                'read_only' => 'Yes',
            ],
            [
                'class' => 'Form',
                'name' => 'Customer Feedback Form',
                'description' => "Form template for collecting customer feedback",
                'template_properties' => "User-friendly layout, structured fields",
                'header_properties' => 'Feedback form title, instructions',
                'footer_properties' => 'Submission deadline, contact information',
                'content_properties' => 'Rating scale, open-ended questions',
                'read_only' => 'Yes',
            ],
            [
                'class' => 'Contract',
                'name' => 'Employment Contract',
                'description' => "Template for employment contracts",
                'template_properties' => "Comprehensive clauses, legal compliance",
                'header_properties' => 'Contract title, parties involved',
                'footer_properties' => 'Start date, duration of contract',
                'content_properties' => 'Job role description, salary details',
                'read_only' => 'Yes',
            ],
            [
                'class' => 'Proposal',
                'name' => 'Project Proposal',
                'description' => "Proposal template for project bids",
                'template_properties' => "Detailed project scope, pricing breakdown",
                'header_properties' => 'Proposal title, client information',
                'footer_properties' => 'Validity period, acceptance terms',
                'content_properties' => 'Project objectives, deliverables',
                'read_only' => 'Yes',
            ],
            [
                'class' => 'Receipt',
                'name' => 'Payment Receipt',
                'description' => "Receipt template for payment confirmations",
                'template_properties' => "Receipt number, payment details",
                'header_properties' => 'Receipt title, transaction date',
                'footer_properties' => 'Thank you message, contact information',
                'content_properties' => 'Payment amount, mode of payment',
                'read_only' => 'Yes',
            ],
            [
                'class' => 'Certificate',
                'name' => 'Appreciation Certificate',
                'description' => "Certificate template for employee appreciation",
                'template_properties' => "Elegant design, customizable text",
                'header_properties' => 'Certificate title, recipient name',
                'footer_properties' => 'Issued by, date of appreciation',
                'content_properties' => 'Reason for appreciation, company logo',
                'read_only' => 'Yes',
            ],
            [
                'class' => 'Agreement',
                'name' => 'Rental Agreement',
                'description' => "Standard agreement template for rental contracts",
                'template_properties' => "Clear terms, responsibilities outlined",
                'header_properties' => 'Agreement title, parties involved',
                'footer_properties' => 'Lease term, renewal conditions',
                'content_properties' => 'Rental property details, payment schedule',
                'read_only' => 'Yes',
            ],
        ];
        $now = \Carbon\Carbon::now();
        foreach ($pdfTemplates as $key => $pdfTemplate) {
            $pdfTemplates[$key]['created_at'] = $now;
            $pdfTemplates[$key]['updated_at'] = $now;
        }

        \App\PDFTemplate::insert($pdfTemplates);
    }
}
