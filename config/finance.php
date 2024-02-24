<?php
return [
    'expense_account_category_id' => explode(',', env('EXPENSE_ACCOUNT_CATEGORY_ID', '4')),
    'paid_through_account_type_id' => explode(',', env('PAID_THROUGH_ACCOUNT_TYPE_ID', '1')),
    'paid_deposit_to_account_type_id' => explode(',', env('PAID_DEPOSIT_TO_ACCOUNT_TYPE_ID', '1')),
];