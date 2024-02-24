<?php

use Illuminate\Database\Seeder;

class EmailtemplatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $emailtemplates = [
            [
                'class' => 'Marketing',
                'name' => 'Email Campaign',
                'description' => 'Promotional email campaign for new product launch',
                'subject' => 'New Product Launch Announcement',
                'content' => 'Exciting news! We are thrilled to announce the launch of our latest product. Click here to learn more.',
                'variables' => 'Name, Product, Link',
                'loops' => 'Products, Features, Benefits',
                'links' => 'http://example.com/email-campaign',
                'read_only' => 'Yes',
            ],
            [
                'class' => 'Customer Support',
                'name' => 'Customer Feedback Survey',
                'description' => 'Email survey to gather feedback from customers',
                'subject' => 'Your Feedback Matters!',
                'content' => 'We value your opinion! Please take a moment to complete our short feedback survey.',
                'variables' => 'Name, Email, Rating',
                'loops' => 'Questions, Ratings, Comments',
                'links' => 'http://example.com/customer-survey',
                'read_only' => 'Yes',
            ],
            [
                'class' => 'HR',
                'name' => 'Employee Benefits Update',
                'description' => 'Email communication regarding updates to employee benefits program',
                'subject' => 'Important: Updates to Employee Benefits Program',
                'content' => 'Dear Team, We are pleased to announce updates to our employee benefits program. Please review the changes outlined in this email.',
                'variables' => 'Employee Name, Benefit Type, Effective Date',
                'loops' => 'Employees, Benefits, Changes',
                'links' => 'http://example.com/employee-benefits',
                'read_only' => 'Yes',
            ],
            [
                'class' => 'Sales',
                'name' => 'Weekly Newsletter',
                'description' => 'Weekly email newsletter featuring sales updates and promotions',
                'subject' => 'Your Weekly Sales Update: Don\'t Miss Out!',
                'content' => 'Stay informed about our latest sales promotions, product updates, and industry news. Subscribe now!',
                'variables' => 'Product Name, Discount Percentage, Expiry Date',
                'loops' => 'Products, Offers, Categories',
                'links' => 'http://example.com/weekly-newsletter',
                'read_only' => 'Yes',
            ],
            [
                'class' => 'Finance',
                'name' => 'Invoice Reminder',
                'description' => 'Email reminder for overdue invoices',
                'subject' => 'Friendly Reminder: Overdue Invoice',
                'content' => 'This is a friendly reminder that your invoice is now past due. Please settle the payment at your earliest convenience.',
                'variables' => 'Invoice Number, Due Date, Amount',
                'loops' => 'Invoices, Clients, Amounts',
                'links' => 'http://example.com/invoice-reminder',
                'read_only' => 'Yes',
            ],
            [
                'class' => 'IT',
                'name' => 'System Maintenance Notification',
                'description' => 'Email notification regarding scheduled system maintenance',
                'subject' => 'Scheduled System Maintenance: Prepare Accordingly',
                'content' => 'Dear User, Our system will undergo scheduled maintenance on [date]. Please plan accordingly and expect temporary service interruptions.',
                'variables' => 'Maintenance Date, Duration, Affected Services',
                'loops' => 'Systems, Services, Dates',
                'links' => 'http://example.com/system-maintenance',
                'read_only' => 'Yes',
            ],
            [
                'class' => 'Education',
                'name' => 'Course Enrollment Confirmation',
                'description' => 'Email confirmation for course enrollment',
                'subject' => 'Course Enrollment Confirmed: Get Ready to Learn!',
                'content' => 'Congratulations! Your enrollment in [course name] has been confirmed. Get ready to embark on an exciting learning journey!',
                'variables' => 'Course Name, Start Date, Instructor',
                'loops' => 'Courses, Instructors, Dates',
                'links' => 'http://example.com/course-enrollment',
                'read_only' => 'Yes',
            ],
            [
                'class' => 'Legal',
                'name' => 'Terms of Service Update',
                'description' => 'Email notification regarding updates to terms of service',
                'subject' => 'Important: Updates to Terms of Service',
                'content' => 'Dear User, We have made updates to our Terms of Service. Please review the changes outlined in this email.',
                'variables' => 'User, Terms, Changes',
                'loops' => 'Users, Changes, Dates',
                'links' => 'http://example.com/terms-of-service-update',
                'read_only' => 'Yes',
            ],
            [
                'class' => 'Health',
                'name' => 'Health Tips Newsletter',
                'description' => 'Monthly email newsletter featuring health tips and advice',
                'subject' => 'Stay Healthy with Our Monthly Newsletter!',
                'content' => 'Subscribe to our monthly newsletter for valuable health tips, recipes, and wellness advice.',
                'variables' => 'Health Tip, Recipe, Exercise',
                'loops' => 'Tips, Recipes, Exercises',
                'links' => 'http://example.com/health-tips-newsletter',
                'read_only' => 'Yes',
            ],
            [
                'class' => 'Recruitment',
                'name' => 'Job Application Confirmation',
                'description' => 'Email confirmation for job application submission',
                'subject' => 'Your Job Application Has Been Received!',
                'content' => 'Thank you for submitting your job application. We will review your qualifications and contact you if your profile matches our requirements.',
                'variables' => 'Applicant Name, Position, Application Date',
                'loops' => 'Applicants, Positions, Dates',
                'links' => 'http://example.com/job-application-confirmation',
                'read_only' => 'Yes',
            ],
        ];

        $now = \Carbon\Carbon::now();
        foreach ($emailtemplates as $key => $brand) {
            $emailtemplates[$key]['created_at'] = $now;
            $emailtemplates[$key]['updated_at'] = $now;
        }

        \App\EmailTemplate::insert($emailtemplates);
    }
}
