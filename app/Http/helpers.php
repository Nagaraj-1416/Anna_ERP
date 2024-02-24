<?php
/**
 * Generate breadcrumb view
 */

use App\Account;
use App\Comment;
use App\Customer;
use App\CustomerCredit;
use App\DailySale;
use App\DailySaleItem;
use App\InvoicePayment;
use App\Jobs\RecordTransactionJob;
use App\PriceBook;
use App\ProductCategory;
use App\Staff;
use App\Rep;
use App\SupplierCredit;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

if (!function_exists('breadcrumbRender')) {
    function breadcrumbRender($data, $title = null)
    {
        $breadcrumb = [];
        foreach ($data as $key => $item) {
            if (!isset($item['text'])) {
                continue;
            }
            if (!isset($item['class'])) {
                $breadcrumb[$key]['class'] = '';
            } else {
                $breadcrumb[$key]['class'] = $item['class'];
            }
            if (!isset($item['parameters'])) {
                $breadcrumb[$key]['parameters'] = [];
            } else {
                $breadcrumb[$key]['parameters'] = $item['parameters'];
            }
            if (isset($item['route'])) {
                $breadcrumb[$key]['route'] = $item['route'];
            }
            $breadcrumb[$key]['text'] = ucwords($item['text']);
        }
        return view('_inc.breadcrumb.index', compact('breadcrumb', 'title'))->render();
    }
}
/**
 * create carbon object
 * @return \Carbon\Carbon
 */
if (!function_exists('carbon')) {
    function carbon($time = null, $tz = null)
    {
        return new \Carbon\Carbon($time, $tz);
    }
}

/**
 * create a form object
 */
if (!function_exists('form')) {
    function form()
    {
        return app('form');
    }
}

/**
 * File size converter
 * @param string $format
 * @return bool|float
 */
function fileUploadSize($format = 'M')
{
    $val = trim(ini_get('post_max_size'));
    $last = strtolower($val[strlen($val) - 1]);
    $val = intval($val);
    switch ($last) {
        case 'g':
            $val *= (1024 * 1024 * 1024);
            break;
        case 'm':
            $val *= (1024 * 1024);
            break;
        case 'k':
            $val *= (1024);
            break;
    }
    $return = $val;
    $format = strtoupper($format);
    if (!in_array($format, ['K', 'M', 'G'])) {
        return false;
    }
    switch ($format) {
        case 'G':
            $return /= (1024 * 1024 * 1024);
            break;
        case 'M':
            $return /= (1024 * 1024);
            break;
        case 'K':
            $return /= (1024);
            break;
    }
    return round($return, 2);
}

/**
 * Get country drop down data
 */
if (!function_exists('countryDropDown')) {
    function countryDropDown()
    {
        return \App\Country::all()->pluck('name', 'id')->toArray();
    }
}

/**
 * Get role drop down data
 */
if (!function_exists('roleDropDown')) {
    function roleDropDown()
    {
        return \App\Role::all()->pluck('name', 'id')->toArray();
    }
}

/**
 * create a form object
 */
if (!function_exists('currencyDropDown')) {
    function currencyDropDown()
    {
        return \App\Country::all()->pluck('currency_code', 'id')->toArray();
    }
}


/**
 * account group dropdown
 */
if (!function_exists('accountGroupDropDown')) {
    function accountGroupDropDown($groupId = null)
    {
        return \App\AccountGroup::where('id', '<>', $groupId)->get()->pluck('name', 'id')->toArray();
    }
}


/**
 * account category dropdown
 */
if (!function_exists('accountCategoryDropDown')) {
    function accountCategoryDropDown()
    {
        return \App\AccountCategory::get()->pluck('name', 'id')->toArray();
    }
}


/**
 * create a form object
 */
if (!function_exists('dateTimeFormatDropDown')) {
    function dateTimeFormatDropDown()
    {
        return [
            'Y-m-d' => '2018-02-20',
            'd-m-Y' => '20-02-2018',
            'jS F Y' => '20th February 2018',
            'Y-m-d H:i:s' => '2018-02-20 07:00:00',
            'd/m/Y H:i:s' => '20/02/2018 07:00:00',
            'Y/m/d H:i:s' => '2018/02/20 07:00:00',
            'M j, Y \\a\\t h:i A' => 'Feb 20, 2018 at 07:00 AM',
            'F j, Y, g:i A' => 'February 20, 2018, 7:00 AM',
            'g:ia \o\n l jS F Y' => '7:00am on Tuesday 20th February 2018',
        ];
    }
}

/**
 * Generate action button for data table
 */
if (!function_exists('actionBtn')) {
    function actionBtn(string $label, string $icon = null, array $route = [], $attributes = []): string
    {
        $class = null;
        $attributeString = "";
        $url = 'javascript:void(0)';
        if (count($route)) {
            $url = route($route[0] ?? null, $route[1] ?? []);
        }
        foreach ($attributes as $key => $value) {
            if (strtolower($key) == 'class') {
                $class = $value;
            } else {
                $attributeString .= ' ' . strtolower($key) . '="' . $value . '"';
            }
        }
        $class = $class ? $class : "btn-primary";
        $icon = $icon ? '<i class="fa fa-' . $icon . '"></i>' : '';
        return '<a href="' . $url . '"   class="btn waves-effect waves-light btn-sm ' . $class . '" ' . $attributeString . '> ' . $icon . ' ' . $label . '</a>';
    }
}

/**
 * Get company drop down data
 */
if (!function_exists('companyDropDown')) {
    function companyDropDown()
    {
        return \App\Company::whereIn('id', userCompanyIds(loggedUser()))
            ->get()->pluck('name', 'id')->toArray();
    }
}

/**
 * Get company drop down data
 */
if (!function_exists('allCompanyDropDown')) {
    function allCompanyDropDown()
    {
        return \App\Company::get()->pluck('name', 'id')->toArray();
    }
}

/**
 * Get company drop down data
 */
if (!function_exists('receiverCompanyDropDown')) {
    function receiverCompanyDropDown()
    {
        return \App\Company::whereNotIn('id', userCompanyIds(loggedUser()))
            ->get()->pluck('name', 'id')->toArray();
    }
}

/**
 * Get make drop down data
 */
if (!function_exists('makeDropDown')) {
    function makeDropDown()
    {
        return \App\VehicleMake::all()->pluck('name', 'id')->toArray();
    }
}

/**
 * Load all policy groups
 */
if (!function_exists('getPolicies')) {
    function getPolicies(): array
    {
        $polices = [];
        $policyDir = app_path('Policies');
        $policyNameSpace = 'App\Policies';
        // Get all sub dir of polices dir ana map polices and model
        $policyGroupDirectories = glob($policyDir . '/*', GLOB_ONLYDIR);
        foreach ($policyGroupDirectories as $policyGroupDir) {
            $groupName = basename($policyGroupDir);
            foreach (scandir($policyGroupDir) as $policyFiles) {
                if (!strpos($policyFiles, '.php')) continue;
                $policy = str_replace('.php', '', $policyFiles);
                $policy = $policyNameSpace . '\\' . $groupName . '\\' . $policy;
                $policyObject = new $policy();
                $polices[$policyObject->model] = $policy;
            }
        }
        return $polices;
    }
}

/**
 * Load all policy groups
 */
if (!function_exists('getPolicyGroups')) {
    function getPolicyGroups(): array
    {
        $policyDir = app_path('Policies');
        $policyNameSpace = 'App\Policies';
        $policyGroups = [];
        // Get all sub dir of polices dir ana map data
        $policyGroupDirectories = glob($policyDir . '/*', GLOB_ONLYDIR);
        foreach ($policyGroupDirectories as $policyGroupDir) {
            $groupName = basename($policyGroupDir);
            $polices = [];
            foreach (scandir($policyGroupDir) as $files) {
                if (!strpos($files, '.php')) continue;
                array_push($polices, $files);
            }
            array_push($policyGroups, [
                'group_name' => $groupName,
                'polices' => $polices,
                'name_space' => $policyNameSpace . '\\' . $groupName
            ]);
        }
        return $policyGroups;
    }
}


if (!function_exists('fuelTypeDropDown')) {
    function fuelTypeDropDown()
    {
        return ['Petrol' => 'Petrol', 'Diesel' => 'Diesel', 'Electric' => 'Electric', 'Other' => 'Other'];
    }
}

if (!function_exists('vehicleTypeDropDown')) {
    function vehicleTypeDropDown()
    {
        return \App\VehicleType::all()->pluck('name', 'id')->toArray();
    }
}

if (!function_exists('vehicleModelDropDown')) {
    function vehicleModelDropDown()
    {
        return \App\VehicleModel::all()->pluck('name', 'id')->toArray();
    }
}

/**
 * create a form object
 */
if (!function_exists('salutationDropDown')) {
    function salutationDropDown()
    {
        return [
            'Mr.' => 'Mr.',
            'Mrs.' => 'Mrs.',
            'Ms.' => 'Ms.',
            'Miss.' => 'Miss.',
            'Dr.' => 'Dr.'
        ];
    }
}

/**
 * The Current User Can do the action
 */
if (!function_exists('can')) {
    function can($action, Model $model)
    {
        return auth()->user()->can($action, $model);
    }
}
/**
 * measurement DropDown
 */
if (!function_exists('measurementDropDown')) {
    function measurementDropDown()
    {
        return \App\Measurement::all()->pluck('name', 'code')->toArray();
    }
}

/**
 * create a form object
 * is active
 */
if (!function_exists('isActiveDropDown')) {
    function isActiveDropDown()
    {
        return [
            'Yes' => 'Yes',
            'No' => 'No',
        ];
    }
}

/**
 * create a form object
 * Staffs
 */
if (!function_exists('staffsDropdown')) {
    function staffsDropdown()
    {
        return Staff::all()->pluck('short_name', 'id')->toArray();
    }
}
/**
 * Generate email content
 * @param \App\EmailTemplate $template
 * @param array $data
 * @return mixed|string
 */
if (!function_exists('generateEmailContent')) {
    function generateEmailContent(\App\EmailTemplate $template, $data = [])
    {
        extract($data);
        $content = $template->content;

        // Variable rendering
        $matches = [];
        preg_match_all('/_VAR_+(.*?)_/', $content, $matches);
        $matchedVariableKeys = $matches[1];
        $matchedVariables = $matches[0];
        $variables = $template->variables;
        foreach ($matchedVariables as $key => $match) {
            $matchedVariableKey = $matchedVariableKeys[$key];
            $variable = $variables[$matchedVariableKey];
            $variableName = $variable['variable'];
            $value = $$variableName;

            if (!isset($variable['attributes'])) goto replace;
            foreach ($variable['attributes'] as $attribute) {
                $attributeName = $attribute['attribute'];
                if ($attribute['type'] == 'object') {
                    $value = $value->$attributeName ?? '';
                } elseif ($attribute['type'] == 'array') {
                    $value = $value[$attributeName] ?? '';
                } elseif ($attribute['type'] == 'function') {
                    $value = call_user_func($attributeName, $value);
                }
            }
            replace:
            $content = str_replace($match, $value, $content);
        }

        // Loop rendering
        $matches = [];
        preg_match_all('/_LOOP_+(.*?)_/', $content, $matches);
        $matchedLoopKeys = $matches[1];
        $matchedLoops = $matches[0];
        $loops = $template->loops;
        foreach ($matchedLoops as $key => $match) {
            $matchedLoopKey = $matchedLoopKeys[$key];
            $loopMeta = $loops[$matchedLoopKey];
            $collectionName = $loopMeta['collection'];
            $collection = $$collectionName;
            $table = '';
            try {
                $table = view('_inc.components.email._email_template_loop', compact('collection', 'loopMeta'))->render();
            } catch (Throwable $e) {
            }
            $content = str_replace($match, $table, $content);
        }

        // Link rendering
        $matches = [];
        preg_match_all('/_LINK_+(.*?)_/', $content, $matches);
        $matchedLinkKeys = $matches[1];
        $matchedLinks = $matches[0];
        $links = $template->links;
        foreach ($matchedLinks as $key => $match) {
            $matchedLinkKey = $matchedLinkKeys[$key];
            $linkMeta = $links[$matchedLinkKey];
            $parameters = [];
            foreach ($linkMeta['parameters'] as $parameter) {
                $variableName = $parameter['variable'];
                $parameters[$parameter['name']] = (!$variableName) ? valueGet($parameter['value']) : objectGet($$variableName, $parameter['value']);
            }
            $link = '';
            try {
                $link = view('_inc.components.email._email_template_link', compact('linkMeta', 'parameters'))->render();
            } catch (Throwable $e) {
            }
            $content = str_replace($match, $link, $content);
        }
        return $content;
    }
}

/**
 * Generate email subject
 * @param \App\EmailTemplate $template
 * @param array $data
 * @return mixed
 */
if (!function_exists('generateEmailSubject')) {
    function generateEmailSubject(\App\EmailTemplate $template, $data = [])
    {
        extract($data);
        $subject = $template->getAttribute('subject');

        // Variable rendering
        $matches = [];
        preg_match_all('/_VAR_+(.*?)_/', $subject, $matches);
        $matchedVariableKeys = $matches[1];
        $matchedVariables = $matches[0];
        $variables = $template->variables;
        foreach ($matchedVariables as $key => $match) {
            $matchedVariableKey = $matchedVariableKeys[$key];
            $variable = $variables[$matchedVariableKey];
            $variableName = $variable['variable'];
            $value = $$variableName;

            if (!isset($variable['attributes'])) goto replace;
            foreach ($variable['attributes'] as $attribute) {
                $attributeName = $attribute['attribute'];
                if ($attribute['type'] == 'object') {
                    $value = $value->$attributeName;
                } else if ($attribute['type'] == 'array') {
                    $value = $value[$attributeName];
                } else if ($attribute['type'] == 'function') {
                    $value = call_user_func($attributeName, $value);
                }
            }
            replace:
            $subject = str_replace($match, $value, $subject);
        }
        return $subject;
    }
}

/**
 * get values from object
 * @param $object
 * @param $attributes
 * @return mixed
 */
if (!function_exists('objectGet')) {
    function objectGet($object, $attributes)
    {
        $value = $object;
        foreach ($attributes as $attribute) {
            $attributeName = $attribute['attribute'];
            if ($attribute['type'] == 'object') {
                $value = clone $value;
                $value = $value->$attributeName;
            } else if ($attribute['type'] == 'array') {
                $value = $value[$attributeName];
            } else if ($attribute['type'] == 'value') {
                $value = $attribute['value'];
            } else if ($attribute['type'] == 'function') {
                $value = call_user_func($attributeName, $value);
            }
        }
        return $value;
    }
}
/**
 * get values
 * @param $attributes
 * @return string
 */
if (!function_exists('valueGet')) {
    function valueGet($attributes)
    {
        $value = '';
        foreach ($attributes as $attribute) {
            if ($attribute['type'] == 'value') {
                $value = $attribute['value'];
            }
        }
        return $value;
    }
}

/**
 * Render email template
 * @param \App\EmailTemplate $template
 * @return \App\EmailTemplate
 */
if (!function_exists('render_email_template')) {
    function render_email_template(\App\EmailTemplate $template)
    {
        $replaceableFields = ['content', 'subject'];
        $variables = $template->getAttribute('variables');
        $loops = $template->getAttribute('loops');
        $links = $template->getAttribute('links');

        foreach ($replaceableFields as $field) {
            $content = $template->getAttribute($field);
            if (is_array($variables)) $content = variables_to_names_email_template($content, $variables);
            if (is_array($loops)) $content = loops_to_names_email_template($content, $loops);
            if (is_array($links)) $content = links_to_names_email_template($content, $links);
            $template->setAttribute($field, $content);
        }

        return $template;
    }
}

/**
 * Process variable name
 * @param string $content
 * @param array $variables
 * @return mixed|string
 */
if (!function_exists('variables_to_names_email_template')) {
    function variables_to_names_email_template(string $content, array $variables)
    {
        $container = '<code data-id="VARIABLE_ID" class="code variable">VARIABLE_NAME</code>';
        preg_match_all('/_VAR_+(.*?)_/', $content, $matches);
        if (!count($matches)) goto content;
        foreach ($matches[1] as $matchKey => $variableId) {
            if (!isset($variables[$variableId])) continue;
            $variable = $variables[$variableId];
            $variableContainer = str_replace('VARIABLE_ID', $variableId, $container);
            $variableContainer = str_replace('VARIABLE_NAME', $variable['name'], $variableContainer);
            $content = str_replace($matches[0][$matchKey], $variableContainer, $content);
        }
        content:
        return $content;
    }
}

/**
 * Process loop
 * @param string $content
 * @param array $loops
 * @return mixed|string
 */
if (!function_exists('loops_to_names_email_template')) {
    function loops_to_names_email_template(string $content, array $loops)
    {
        $container = '<code data-id="LOOP_ID" class="code loop">LOOP_NAME</code>';
        preg_match_all('/_LOOP_+(.*?)_/', $content, $matches);
        if (!count($matches)) goto content;
        foreach ($matches[1] as $matchKey => $loopId) {
            if (!isset($loops[$loopId])) continue;
            $loop = $loops[$loopId];
            $loopContainer = str_replace('LOOP_ID', $loopId, $container);
            $loopContainer = str_replace('LOOP_NAME', $loop['name'], $loopContainer);
            $content = str_replace($matches[0][$matchKey], $loopContainer, $content);
        }
        content:
        return $content;
    }
}

/**
 * Process email links
 * @param string $content
 * @param array $links
 * @return mixed|string
 */
if (!function_exists('links_to_names_email_template')) {
    function links_to_names_email_template(string $content, array $links)
    {
        $container = '<code data-id="LINK_ID" class="code link">LINK_NAME</code>';
        preg_match_all('/_LINK_+(.*?)_/', $content, $matches);
        if (!count($matches)) goto content;
        foreach ($matches[1] as $matchKey => $linkId) {
            if (!isset($links[$linkId])) continue;
            $link = $links[$linkId];
            $linkContainer = str_replace('LINK_ID', $linkId, $container);
            $linkContainer = str_replace('LINK_NAME', $link['name'], $linkContainer);
            $content = str_replace($matches[0][$matchKey], $linkContainer, $content);
        }
        content:
        return $content;
    }
}

/**
 * transform email template
 * @param \App\EmailTemplate $template
 * @param string|null $content
 * @return mixed|string
 */
if (!function_exists('transform_email_template_content')) {
    function transform_email_template(\App\EmailTemplate $template, string $content = null)
    {
        $variables = $template->getAttribute('variables');
        $loops = $template->getAttribute('loops');
        $links = $template->getAttribute('links');

        if (is_array($variables)) $content = names_to_variables_email_template($content, $variables);
        if (is_array($loops)) $content = names_to_loops_email_template($content, $loops);
        if (is_array($links)) $content = names_to_links_email_template($content, $links);
        return $content;
    }
}

/**
 * Replace name to variable
 * @param string $content
 * @param array $variables
 * @return mixed|string
 */
if (!function_exists('names_to_variables_email_template')) {
    function names_to_variables_email_template(string $content, array $variables)
    {
        $pattern = '#<code.*?class="code variable".*?data-id="(.*?)">.*?</code>#';
        preg_match_all($pattern, $content, $matches);
        if (!count($matches)) goto content;
        foreach ($matches[1] as $key => $variableId) {
            if (!isset($variables[$variableId])) continue;
            $replace = '_VAR_' . $variableId . '_';
            $content = str_replace($matches[0][$key], $replace, $content);
        }
        content:
        return $content;
    }
}

/**
 * Replace name to loop
 * @param string $content
 * @param array $loops
 * @return mixed|string
 */
if (!function_exists('names_to_loops_email_template')) {
    function names_to_loops_email_template(string $content, array $loops)
    {
        $pattern = '#<code.*?class="code loop".*?data-id="(.*?)">.*?</code>#';
        preg_match_all($pattern, $content, $matches);
        if (!count($matches)) goto content;
        foreach ($matches[1] as $key => $loopId) {
            if (!isset($loops[$loopId])) continue;
            $replace = '_LOOP_' . $loopId . '_';
            $content = str_replace($matches[0][$key], $replace, $content);
        }
        content:
        return $content;
    }
}

/**
 * Replace name to link
 * @param string $content
 * @param array $links
 * @return mixed|string
 */
if (!function_exists('names_to_links_email_template')) {
    function names_to_links_email_template(string $content, array $links)
    {
        $pattern = '#<code.*?class="code link".*?data-id="(.*?)">.*?</code>#';
        preg_match_all($pattern, $content, $matches);
        if (!count($matches)) goto content;
        foreach ($matches[1] as $key => $linkId) {
            if (!isset($links[$linkId])) continue;
            $replace = '_LINK_' . $linkId . '_';
            $content = str_replace($matches[0][$key], $replace, $content);
        }
        content:
        return $content;
    }
}

/**
 * Clear string
 * @param string $content
 * @return string
 */
if (!function_exists('clean_string')) {
    function clean_string(string $content)
    {
        $content = strip_tags($content);
        $content = html_entity_decode($content);
        return $content;
    }
}

/**
 * Get product drop down data
 */
if (!function_exists('productDropDown')) {
    function productDropDown()
    {
        return \App\Product::all()->pluck('name', 'id')->toArray();
    }
}

/**
 * Get store drop down data
 */
if (!function_exists('storeDropDown')) {
    function storeDropDown()
    {
        return \App\Store::whereIn('company_id', userCompanyIds(loggedUser()))
            ->where('type', 'General')
            ->get()->pluck('name', 'id')->toArray();
    }
}

if (!function_exists('allStoreDropDown')) {
    function allStoreDropDown()
    {
        return \App\Store::where('type', 'General')
            ->get()->pluck('name', 'id')->toArray();
    }
}

if (!function_exists('damagedStoreDropDown')) {
    function damagedStoreDropDown()
    {
        return \App\Store::whereIn('company_id', userCompanyIds(loggedUser()))
            ->where('type', 'Damage')
            ->get()->pluck('name', 'id')->toArray();
    }
}

/**
 *
 */
if (!function_exists('storeDropDownFiltered')) {
    function storeDropDownFiltered($store)
    {
        return \App\Store::where('id', '!=', $store->id)
            ->where('type', 'General')
            ->get()->pluck('name', 'id')->toArray();
    }
}

if (!function_exists('storeDropDownByAllocation')) {
    function storeDropDownByAllocation($allocation)
    {
        return \App\Store::where('company_id', $allocation->company_id)
            ->where('type', 'General')
            ->get()->pluck('name', 'id')->toArray();
    }
}

/**
 * Get vehicle drop down data
 */
if (!function_exists('vehicleDropDown')) {
    function vehicleDropDown()
    {
        return \App\Vehicle::whereIn('company_id', userCompanyIds(loggedUser()))
            ->get()->pluck('vehicle_no', 'id')->toArray();
    }
}

/**
 * Get business types drop down data
 */
if (!function_exists('businessTypeDropDown')) {
    function businessTypeDropDown()
    {
        return \App\BusinessType::all()->pluck('name', 'id')->toArray();
    }
}

/**
 * Get sales route drop down data
 */
if (!function_exists('routeDropDown')) {
    function routeDropDown()
    {
        return \App\Route::whereIn('company_id', userCompanyIds(loggedUser()))
            ->get()->pluck('name', 'id')->toArray();
    }
}

/**
 * Get sales route drop down data
 */
if (!function_exists('routeDropDownByAllocation')) {
    function routeDropDownByAllocation($allocation)
    {
        return \App\Route::where('company_id', $allocation->company_id)
            ->get()->pluck('name', 'id')->toArray();
    }
}

/**
 * Get sales route drop down data
 */
if (!function_exists('locationDropDown')) {
    function locationDropDown()
    {
        return \App\Location::all()->pluck('name', 'id')->toArray();
    }
}

/**
 * Get customers drop down data
 */
if (!function_exists('customerDropDown')) {
    function customerDropDown()
    {
        return \App\Customer::whereIn('company_id', userCompanyIds(loggedUser()))
            ->get()->pluck('display_name', 'id')->toArray();
    }
}


/**
 * Get customers drop down data
 */
if (!function_exists('supplierDropDown')) {
    function supplierDropDown()
    {
        return \App\Supplier::whereIn('company_id', userCompanyIds(loggedUser()))
            ->get()->pluck('display_name', 'id')->toArray();
    }
}

/**
 * Get customers drop down data
 */
if (!function_exists('pUnitSuppliersDropDown')) {
        function pUnitSuppliersDropDown()
    {
        return \App\Supplier::whereIn('company_id', userCompanyIds(loggedUser()))
            ->get()->pluck('display_name', 'id')->toArray();
    }
}

/**
 * Get customers drop down data
 */
if (!function_exists('storeSuppliersDropDown')) {
    function storeSuppliersDropDown()
    {
        return \App\Supplier::whereIn('supplierable_type', ['App\ProductionUnit'])
            ->get()->pluck('display_name', 'id')->toArray();
    }
}

/**
 * Get customers drop down data
 */
if (!function_exists('shopSuppliersDropDown')) {
    function shopSuppliersDropDown()
    {
        return \App\Supplier::whereIn('supplierable_type', ['App\Store'])
            ->get()->pluck('display_name', 'id')->toArray();
    }
}

if (!function_exists('pUnitAndStoreSuppliersDropDown')) {
    function pUnitAndStoreSuppliersDropDown()
    {
        return \App\Supplier::whereIn('supplierable_type', ['App\ProductionUnit', 'App\Store'])
            ->get()->pluck('display_name', 'id')->toArray();
    }
}


/**
 * Get now carbon object
 */
if (!function_exists('now')) {
    function now(): Carbon
    {
        return Carbon::now();
    }
}

/**
 *
 */
if (!function_exists('typeDD')) {
    function typeDD()
    {
        return [
            'Yearly' => 'Yearly',
            'Monthly' => 'Monthly',
            'Weekly' => 'Weekly',
            'Daily' => 'Daily',
        ];
    }
}

/**
 *
 */
if (!function_exists('supOutstanding')) {
    function supOutstanding(\App\Supplier $supplier)
    {
        $outstanding = [];

        $orders = $supplier->orders;
        $bills = $supplier->bills;
        $payments = $supplier->payments;

        $ordered = $orders->sum('total');
        $billed = $bills->sum('amount');
        $paidAmount = $payments->where('status', 'Paid')->sum('payment');

        $outstanding['ordered'] = $ordered;
        $outstanding['billed'] = $billed;
        $outstanding['paid'] = $paidAmount;
        $outstanding['balance'] = ($ordered - $paidAmount);

        return $outstanding;
    }
}

/**
 *
 */
if (!function_exists('poOutstanding')) {
    function poOutstanding(\App\PurchaseOrder $order)
    {
        $outstanding = [];

        $bills = $order->bills;
        $payments = $order->payments;

        $billed = $bills->sum('amount');
        $paidAmount = $payments->where('status', 'Paid')->sum('payment');

        $outstanding['billed'] = $billed;
        $outstanding['paid'] = $paidAmount;
        $outstanding['balance'] = ($order->getAttribute('total') - $paidAmount);

        return $outstanding;
    }
}

/**
 *
 */
if (!function_exists('billOutstanding')) {
    function billOutstanding(\App\Bill $bill)
    {
        $outstanding = [];

        $payments = $bill->payments;
        $paidAmount = $payments->where('status', 'Paid')->sum('payment');

        $outstanding['paid'] = $paidAmount;
        $outstanding['balance'] = ($bill->getAttribute('amount') - $paidAmount);

        return $outstanding;
    }
}

/**
 *
 */
if (!function_exists('cusOutstanding')) {
    function cusOutstanding(\App\Customer $customer)
    {
        $outstanding = [];

        $orders = $customer->orders->whereIn('status', ['Open', 'Closed']);
        $invoices = $customer->invoices->whereIn('status', ['Open', 'Partially Paid', 'Paid']);
        $payments = $customer->payments->where('status', 'Paid');

        $ordered = $orders->sum('total');
        $invoiced = $invoices->sum('amount');
        $paidAmount = $payments->sum('payment');

        $paidAsCash = $payments->where('status', 'Paid')->where('payment_mode', 'Cash')->sum('payment');
        $paidAsCheque = $payments->where('status', 'Paid')->where('payment_mode', 'Cheque')->sum('payment');
        $paidAsDD = $payments->where('status', 'Paid')->where('payment_mode', 'Direct Deposit')->sum('payment');
        $paidAsCD = $payments->where('status', 'Paid')->where('payment_mode', 'Credit Card')->sum('payment');

        $outstanding['ordered'] = $ordered;
        $outstanding['invoiced'] = $invoiced;
        $outstanding['paid'] = $paidAmount;
        $outstanding['paidAsCash'] = $paidAsCash;
        $outstanding['paidAsCheque'] = $paidAsCheque;
        $outstanding['paidAsDD'] = $paidAsDD;
        $outstanding['paidAsCD'] = $paidAsCD;
        $outstanding['balance'] = ($ordered - $paidAmount);

        return $outstanding;
    }
}

if (!function_exists('cusOutstanding2')) {
    function cusOutstanding2(\App\Customer $customer)
    {
        $outstanding = [];

        $orders = $customer->orders;
        $invoices = $customer->invoices;
        $payments = $customer->payments;

        $ordered = $orders->sum('total');
        $invoiced = $invoices->sum('amount');
        $paidAmount = $payments->where('status', 'Paid')->sum('payment');
        $paidAsCash = $payments->where('status', 'Paid')->where('payment_mode', 'Cash')->sum('payment');
        $paidAsCheque = $payments->where('status', 'Paid')->where('payment_mode', 'Cheque')->sum('payment');
        $paidAsDD = $payments->where('status', 'Paid')->where('payment_mode', 'Direct Deposit')->sum('payment');
        $paidAsCD = $payments->where('status', 'Paid')->where('payment_mode', 'Credit Card')->sum('payment');

        $outstanding['ordered'] = $ordered;
        $outstanding['invoiced'] = $invoiced;
        $outstanding['paid'] = $paidAmount;
        $outstanding['paidAsCash'] = $paidAsCash;
        $outstanding['paidAsCheque'] = $paidAsCheque;
        $outstanding['paidAsDD'] = $paidAsDD;
        $outstanding['paidAsCD'] = $paidAsCD;
        $outstanding['balance'] = ($ordered - $paidAmount);

        return $outstanding;
    }
}

//if (!function_exists('cusOutstandingOrders')) {
//    function cusOutstandingOrders(\App\Customer $customer)
//    {
//
//        $orders = $customer->orders()->whereIn('status', ['Scheduled', 'Draft', 'Awaiting Approval', 'Open'])->with('payments')->get();
//        $cheques = [];
//        $orders = $orders->reject(function ($item) use (&$cheques) {
//            $item->cheques = $item->payments()->where('payment_mode', 'Cheque')
//                ->where('cheque_date', '>', now()->toDateString())
//                ->with(['depositedTo' => function ($query) {
//                    $query->select(['id', 'code', 'name', 'short_name']);
//                }, 'bank' => function ($query) {
//                    $query->select(['id', 'code', 'name']);
//                }])
//                ->select(['id', 'payment', 'payment_date', 'cheque_type',
//                    'cheque_no', 'cheque_date', 'bank_id', 'deposited_to', 'payment_type'])
//                ->get();
//            $chequePayments = $item->cheques->pluck('id')->toArray();
//            $paymentSum = $item->payments()->whereNotIn('id', $chequePayments)->get()->sum('payment');
//            return $paymentSum >= $item->total;
//        });
//
//        return $orders->transform(function ($item) use ($cheques) {
//            $paymentSum = $item->payments->sum('payment');
//            $item->amount = $item->total - $paymentSum;
//            return [
//                'id' => $item->id,
//                'ref' => $item->ref,
//                'order_no' => $item->order_no,
//                'amount' => $item->amount,
//                'cheques' => $item->cheques
//            ];
//        });
//    }
//}


if (!function_exists('cusOutstandingOrders')) {
    function cusOutstandingOrders(\App\Customer $customer)
    {
        $orders = $customer->orders()->whereIn('status', ['Scheduled', 'Draft', 'Awaiting Approval', 'Open'])->with('payments')->get();
        $orders = $orders->reject(function ($item) {
            $paymentSum = $item->payments->sum('payment');
            return $paymentSum >= $item->total;
        });
        return $orders->transform(function ($item) {
            $paymentSum = $item->payments->sum('payment');
            $item->amount = $item->total - $paymentSum;
            return [
                'id' => $item->id,
                'ref' => $item->ref,
                'order_no' => $item->order_no,
                'order_date' => $item->order_date,
                'amount' => $item->amount
            ];
        })->values();
    }
}

if (!function_exists('cusOutstandingCreditOrders')) {
    function cusOutstandingCreditOrders(\App\DailySale $allocation, Customer $item)
    {
        $ordersID = $allocation->dailySaleCreditOrders->pluck('sales_order_id');
        $orders = \App\SalesOrder::whereIn('id', $ordersID)->where('customer_id', $item->id)->whereIn('status', ['Scheduled', 'Draft', 'Awaiting Approval', 'Open'])->with('payments')->get();
        $cheques = [];
        $orders = $orders->transform(function ($item) use (&$cheques) {
            $item->cheques = $item->payments()->where('payment_mode', 'Cheque')
                ->where('cheque_date', '>', now()->toDateString())
                ->with(['depositedTo' => function ($query) {
                    $query->select(['id', 'code', 'name', 'short_name']);
                }, 'bank' => function ($query) {
                    $query->select(['id', 'code', 'name']);
                }])
                ->select(['id', 'payment', 'payment_date', 'cheque_type',
                    'cheque_no', 'cheque_date', 'bank_id', 'deposited_to', 'payment_type'])
                ->get();
            return $item;
        });

        return $orders->transform(function ($item) use ($cheques) {
            $paymentSum = $item->payments->sum('payment');
            $item->amount = $item->total - $paymentSum;
            return [
                'id' => $item->id,
                'ref' => $item->ref,
                'order_no' => $item->order_no,
                'amount' => $item->amount,
                'cheques' => $item->cheques
            ];
        });
    }
}

/**
 *
 */
if (!function_exists('soOutstanding')) {
    function soOutstanding(\App\SalesOrder $order)
    {
        $outstanding = [];

        $invoices = $order->invoices;
        $payments = $order->payments;

        $invoiced = $invoices->sum('amount');
        $paidAmount = $payments->where('status', 'Paid')->sum('payment');
        $cashAmount = $payments->where('payment_mode', 'Cash')->sum('payment');
        $chequeAmount = $payments->where('payment_mode', 'Cheque')->sum('payment');
        $depositAmount = $payments->where('payment_mode', 'Direct Deposit')->sum('payment');
        $cardAmount = $payments->where('payment_mode', 'Credit Card')->sum('payment');
        $customerCredit = $payments->where('payment_mode', 'Customer Credit')->sum('payment');

        $outstanding['invoiced'] = $invoiced;
        $outstanding['paid'] = $paidAmount;
        $outstanding['byCash'] = $cashAmount;
        $outstanding['byCheque'] = $chequeAmount;
        $outstanding['byDeposit'] = $depositAmount;
        $outstanding['byCard'] = $cardAmount;
        $outstanding['byReturn'] = $customerCredit;
        $outstanding['balance'] = ($order->getAttribute('total') - $paidAmount);

        return $outstanding;
    }
}

if (!function_exists('soOutstandingById')) {
    function soOutstandingById($orderId)
    {
        $outstanding = [];

        $order = \App\SalesOrder::where('id', $orderId)->with('invoices', 'payments')->first();

        $invoices = $order->invoices;
        $payments = $order->payments;

        $invoiced = $invoices->sum('amount');
        $paidAmount = $payments->where('status', 'Paid')->sum('payment');
        $cashAmount = $payments->where('payment_mode', 'Cash')->sum('payment');
        $chequeAmount = $payments->where('payment_mode', 'Cheque')->sum('payment');
        $depositAmount = $payments->where('payment_mode', 'Direct Deposit')->sum('payment');
        $cardAmount = $payments->where('payment_mode', 'Credit Card')->sum('payment');
        $customerCredit = $payments->where('payment_mode', 'Customer Credit')->sum('payment');

        $outstanding['invoiced'] = $invoiced;
        $outstanding['paid'] = $paidAmount;
        $outstanding['byCash'] = $cashAmount;
        $outstanding['byCheque'] = $chequeAmount;
        $outstanding['byDeposit'] = $depositAmount;
        $outstanding['byCard'] = $cardAmount;
        $outstanding['byReturn'] = $customerCredit;
        $outstanding['balance'] = ($order->getAttribute('total') - $paidAmount);

        return $outstanding;
    }
}

if (!function_exists('soOutstandingByAllocation')) {
    function soOutstandingByAllocation(\App\SalesOrder $order, DailySale $allocation)
    {
        $outstanding = [];

        $invoices = $order->invoices;
        $payments = $order->payments;

        $invoiced = $invoices->sum('amount');
        $paidAmount = $payments->where('status', 'Paid')
            ->where('daily_sale_id', $allocation->getAttribute('id'))
            ->sum('payment');
        $cashAmount = $payments->where('payment_mode', 'Cash')->where('status', 'Paid')
            ->where('daily_sale_id', $allocation->getAttribute('id'))
            ->sum('payment');
        $chequeAmount = $payments->where('payment_mode', 'Cheque')->where('status', 'Paid')
            ->where('daily_sale_id', $allocation->getAttribute('id'))
            ->sum('payment');
        $depositAmount = $payments->where('payment_mode', 'Direct Deposit')->where('status', 'Paid')
            ->where('daily_sale_id', $allocation->getAttribute('id'))
            ->sum('payment');
        $cardAmount = $payments->where('payment_mode', 'Credit Card')->where('status', 'Paid')
            ->where('daily_sale_id', $allocation->getAttribute('id'))
            ->sum('payment');
        $customerCredit = $payments->where('payment_mode', 'Customer Credit')->where('status', 'Paid')
            ->where('daily_sale_id', $allocation->getAttribute('id'))
            ->sum('payment');

        $outstanding['invoiced'] = $invoiced;
        $outstanding['paid'] = $paidAmount;
        $outstanding['byCash'] = $cashAmount;
        $outstanding['byCheque'] = $chequeAmount;
        $outstanding['byDeposit'] = $depositAmount;
        $outstanding['byCard'] = $cardAmount;
        $outstanding['byReturn'] = $customerCredit;
        $outstanding['balance'] = ($order->getAttribute('total') - $paidAmount);

        return $outstanding;
    }
}

if (!function_exists('soOutstandingByDate')) {
    function soOutstandingByDate($orderId, $date)
    {
        $outstanding = [];

        $order = \App\SalesOrder::where('order_date', $date)
            ->where('id', $orderId)->with('invoices', 'payments')->first();

        $invoices = $order->invoices;
        $payments = $order->payments;

        $invoiced = $invoices->sum('amount');
        $paidAmount = $payments->where('payment_date', $date)->where('status', 'Paid')->sum('payment');
        $cashAmount = $payments->where('payment_date', $date)->where('payment_mode', 'Cash')->sum('payment');
        $chequeAmount = $payments->where('payment_date', $date)->where('payment_mode', 'Cheque')->sum('payment');
        $depositAmount = $payments->where('payment_date', $date)->where('payment_mode', 'Direct Deposit')->sum('payment');
        $cardAmount = $payments->where('payment_date', $date)->where('payment_mode', 'Credit Card')->sum('payment');
        $customerCredit = $payments->where('payment_date', $date)->where('payment_mode', 'Customer Credit')->sum('payment');

        $outstanding['invoiced'] = $invoiced;
        $outstanding['paid'] = $paidAmount;
        $outstanding['byCash'] = $cashAmount;
        $outstanding['byCheque'] = $chequeAmount;
        $outstanding['byDeposit'] = $depositAmount;
        $outstanding['byCard'] = $cardAmount;
        $outstanding['byReturn'] = $customerCredit;
        $outstanding['balance'] = ($order->getAttribute('total') - $paidAmount);

        return $outstanding;
    }
}

if (!function_exists('soOutstandingByDateBetween')) {
    function soOutstandingByDateBetween($orderId, $startDate, $endDate)
    {
        $outstanding = [];

        $order = \App\SalesOrder::whereBetween('order_date', [$startDate, $endDate])
            ->where('id', $orderId)->with('invoices', 'payments')->first();

        $invoices = $order->invoices;
        $payments = $order->payments;

        $invoiced = $invoices->sum('amount');
        $paidAmount = $payments->whereBetween('order_date', [$startDate, $endDate])
            ->where('status', 'Paid')->sum('payment');
        $cashAmount = $payments->whereBetween('order_date', [$startDate, $endDate])
            ->where('payment_mode', 'Cash')->sum('payment');
        $chequeAmount = $payments->whereBetween('order_date', [$startDate, $endDate])
            ->where('payment_mode', 'Cheque')->sum('payment');
        $depositAmount = $payments->whereBetween('order_date', [$startDate, $endDate])
            ->where('payment_mode', 'Direct Deposit')->sum('payment');
        $cardAmount = $payments->whereBetween('order_date', [$startDate, $endDate])
            ->where('payment_mode', 'Credit Card')->sum('payment');
        $customerCredit = $payments->whereBetween('order_date', [$startDate, $endDate])
            ->where('payment_mode', 'Customer Credit')->sum('payment');

        $outstanding['invoiced'] = $invoiced;
        $outstanding['paid'] = $paidAmount;
        $outstanding['byCash'] = $cashAmount;
        $outstanding['byCheque'] = $chequeAmount;
        $outstanding['byDeposit'] = $depositAmount;
        $outstanding['byCard'] = $cardAmount;
        $outstanding['byReturn'] = $customerCredit;
        $outstanding['balance'] = ($order->getAttribute('total') - $paidAmount);

        return $outstanding;
    }
}

/**
 *
 */
if (!function_exists('invOutstanding')) {
    function invOutstanding(\App\Invoice $invoice)
    {
        $outstanding = [];

        $payments = $invoice->payments;
        $paidAmount = $payments->where('status', 'Paid')->sum('payment');
        $lastPay = $invoice->payments()->orderBy('id', 'desc')->first();
        $lastPayDate = $lastPay ? $lastPay->payment_date : '';

        $outstanding['paid'] = $paidAmount;
        $outstanding['lastPayDate'] = $lastPayDate;
        $outstanding['balance'] = ($invoice->getAttribute('amount') - $paidAmount);

        return $outstanding;
    }
}

if (!function_exists('invOutstandingAsAt')) {
    function invOutstandingAsAt(\App\Invoice $invoice, $asAt)
    {
        $outstanding = [];

        $payments = $invoice->payments;
        $paidAmount = $payments->where('status', 'Paid')->sum('payment');
        $lastPay = $invoice->payments()->orderBy('id', 'desc')->first();
        $lastPayDate = $lastPay ? $lastPay->payment_date : '';

        $outstanding['paid'] = $paidAmount;
        $outstanding['lastPayDate'] = $lastPayDate;
        $outstanding['balance'] = ($invoice->getAttribute('amount') - $paidAmount);

        return $outstanding;
    }
}

if (!function_exists('getPoSummary')) {
    function getPoSummary()
    {
        $purchaseOrders = \App\PurchaseOrder::all();
        $totalPurchase = $purchaseOrders->pluck('total')->sum();
        $totalDelivered = \App\PurchaseOrder::where('status', 'Closed')->where('delivery_status', 'Delivered')->sum('total');
        $poSummary = [];
        $poSummary['totalPurchase'] = number_format($totalPurchase, 2);
        $poSummary['totalDelivered'] = number_format($totalDelivered, 2);
        return $poSummary;
    }
}

if (!function_exists('getBillSummary')) {
    function getBillSummary()
    {
        $billSummary = [];

        $bills = \App\Bill::all();
        $totalBillAmount = $bills->pluck('amount')->sum();
        $totalBillPaid = \App\BillPayment::where('status', 'Paid')->sum('payment');

        $billSummary['totalBillAmount'] = number_format($totalBillAmount, 2);
        $billSummary['totalBillPaid'] = number_format($totalBillPaid, 2);

        return $billSummary;
    }
}

/**
 *
 */
if (!function_exists('statusLabelColor')) {
    function statusLabelColor($status)
    {
        $color = 'text-muted';
        if ($status == 'Draft') {
            $color = 'text-info';
        } else if ($status == 'Awaiting Approval') {
            $color = 'text-warning';
        } else if ($status == 'Open') {
            $color = 'text-warning';
        } else if ($status == 'Overdue') {
            $color = 'text-danger';
        } else if ($status == 'Partially Paid') {
            $color = 'text-warning';
        } else if ($status == 'Paid') {
            $color = 'text-green';
        } else if ($status == 'Canceled') {
            $color = 'text-danger';
        } else if ($status == 'Refunded') {
            $color = 'text-danger';
        } else if ($status == 'Pending') {
            $color = 'text-warning';
        } else if ($status == 'Partially Billed') {
            $color = 'text-warning';
        } else if ($status == 'Sent') {
            $color = 'text-warning';
        } else if ($status == 'Accepted') {
            $color = 'text-green';
        } else if ($status == 'Declined') {
            $color = 'text-danger';
        } else if ($status == 'Ordered') {
            $color = 'text-green';
        } else if ($status == 'Converted to Estimate') {
            $color = 'text-green';
        } else if ($status == 'Converted to Order') {
            $color = 'text-green';
        } else if ($status == 'Closed') {
            $color = 'text-green';
        } else if ($status == 'Invoiced') {
            $color = 'text-green';
        } else if ($status == 'Unreported') {
            $color = 'text-warning';
        } else if ($status == 'Unsubmitted') {
            $color = 'text-warning';
        } else if ($status == 'Submitted') {
            $color = 'text-info';
        } else if ($status == 'Approved') {
            $color = 'text-green';
        } else if ($status == 'Rejected') {
            $color = 'text-danger';
        } else if ($status == 'Reimbursed') {
            $color = 'text-green';
        } else if ($status == 'Billed') {
            $color = 'text-green';
        } else if ($status == 'Partially Invoiced') {
            $color = 'text-warning';
        } else if ($status == 'Confirmed') {
            $color = 'text-green';
        } else if ($status == 'Completed') {
            $color = 'text-green';
        } else if ($status == 'Progress') {
            $color = 'text-warning';
        } else if ($status == 'Active') {
            $color = 'text-info';
        } else if ($status == 'Delivered') {
            $color = 'text-green';
        } else if ($status == 'Processed') {
            $color = 'text-green';
        } else if ($status == 'Not Realised') {
            $color = 'text-warning';
        } else if ($status == 'Deposited') {
            $color = 'text-info';
        } else if ($status == 'Realised') {
            $color = 'text-green';
        } else if ($status == 'Bounced') {
            $color = 'text-danger';
        } else if ($status == 'Received') {
            $color = 'text-green';
        } else if ($status == 'Drafted') {
            $color = 'text-warning';
        } else if ($status == 'Allocated') {
            $color = 'text-green';
        }
        return $color;
    }
}

/**
 * Get account type drop down data
 */
if (!function_exists('accTypeDropDown')) {
    function accTypeDropDown()
    {
        return \App\AccountType::all()->pluck('name', 'id')->toArray();
    }
}

/**
 * Get account drop down data
 */
if (!function_exists('accDropDown')) {
    function accDropDown()
    {
        return \App\Account::all()->pluck('name', 'id')->toArray();
    }
}

if (!function_exists('accDropDownByCompany')) {
    function accDropDownByCompany()
    {
        return \App\Account::whereIn('company_id', userCompanyIds(loggedUser()))
            ->get()->pluck('name', 'id')->toArray();
    }
}

if (!function_exists('getSoSummary')) {
    function getSoSummary()
    {
        $soSummary = [];

        $salesOrders = \App\SalesOrder::all();
        $totalSales = $salesOrders->sum('total');
        $totalClosed = $salesOrders->where('status', 'Closed')->sum('total');
        $totalDelivered = $salesOrders->where('status', 'Closed')
            ->where('delivery_status', 'Delivered')->sum('total');

        $soSummary['totalSales'] = number_format($totalSales, 2);
        $soSummary['totalClosed'] = number_format($totalClosed, 2);
        $soSummary['totalDelivered'] = number_format($totalDelivered, 2);

        return $soSummary;
    }
}

if (!function_exists('getInvSummary')) {
    function getInvSummary()
    {
        $invSummary = [];

        $invoices = \App\Invoice::all();
        $payments = \App\InvoicePayment::where('status', 'Paid')->get();
        $totalInvoiced = $invoices->sum('amount');
        $totalPaid = $payments->sum('payment');


        $invSummary['totalInvoiced'] = number_format($totalInvoiced, 2);
        $invSummary['totalPaid'] = number_format($totalPaid, 2);

        return $invSummary;
    }
}

/**
 * Get account drop down data
 */
if (!function_exists('paidThroughAccDropDown')) {
    function paidThroughAccDropDown()
    {
        return \App\Account::where('account_type_id', 1)->get()->pluck('name', 'id')->toArray();
    }
}

if (!function_exists('paidThroughAccDropDownNew')) {
    function paidThroughAccDropDownNew()
    {
        return \App\Account::whereIn('account_type_id', [1, 2])->get()->pluck('name', 'id')->toArray();
    }
}

if (!function_exists('paidThroughAccByCompanyDropDown')) {
    function paidThroughAccByCompanyDropDown()
    {
        return \App\Account::whereIn('account_type_id', [1, 2])
            ->whereIn('company_id', userCompanyIds(loggedUser()))
            ->get()
            ->pluck('name', 'id')
            ->toArray();
    }
}

if (!function_exists('paidThroughForCommissionDropDown')) {
    function paidThroughForCommissionDropDown()
    {
        return \App\Account::whereIn('account_type_id', [1, 2])
            ->whereIn('company_id', userCompanyIds(loggedUser()))
            ->get()
            ->pluck('name', 'id')
            ->toArray();
    }
}

if (!function_exists('paidThroughAccByCompanyIdDropDown')) {
    function paidThroughAccByCompanyIdDropDown($companyId)
    {
        return \App\Account::where('account_type_id', 2)
            ->where('company_id', $companyId)
            ->get()
            ->pluck('name', 'id')
            ->toArray();
    }
}

/**
 * Get account drop down data
 */
if (!function_exists('depositedToAccDropDown')) {
    function depositedToAccDropDown()
    {
        return \App\Account::where('account_type_id', 1)->get()->pluck('name', 'id')->toArray();
    }
}

if (!function_exists('depositedToAccDropDownNew')) {
    function depositedToAccDropDownNew()
    {
        return \App\Account::whereIn('account_type_id', [1, 2, 19])->get()->pluck('name', 'id')->toArray();
    }
}

if (!function_exists('depositedToAccDropDownCheque')) {
    function depositedToAccDropDownCheque()
    {
        return \App\Account::whereIn('account_type_id', [19])->get()->pluck('name', 'id')->toArray();
    }
}

if (!function_exists('depositedToForCommissionDropDown')) {
    function depositedToForCommissionDropDown()
    {
        return \App\Account::whereIn('account_type_id', [3])
            ->where('accountable_type', 'App\Staff')
            ->get()->pluck('name', 'id')->toArray();
    }
}

/**
 * Get account drop down data
 */
if (!function_exists('inventoryAccDropDown')) {
    function inventoryAccDropDown()
    {
        return \App\Account::where('account_type_id', 5)->get()->pluck('name', 'id')->toArray();
    }
}

/**
 * Get account drop down data
 */
if (!function_exists('incomeAccDropDown')) {
    function incomeAccDropDown()
    {
        return \App\Account::where('account_category_id', 3)->get()->pluck('name', 'id')->toArray();
    }
}

/**
 * Get account drop down data
 */
if (!function_exists('expenseAccDropDown')) {
    function expenseAccDropDown()
    {
        return \App\Account::where('account_category_id', 4)->get()->pluck('name', 'id')->toArray();
    }
}

if (!function_exists('expenseAccByCompanyDropDown')) {
    function expenseAccByCompanyDropDown()
    {
        return \App\Account::where('account_category_id', 4)
            ->whereIn('company_id', userCompanyIds(loggedUser()))
            ->get()
            ->pluck('name', 'id')
            ->toArray();
    }
}

/**
 * Get account drop down data
 */
if (!function_exists('expenseTypesDropDown')) {
    function expenseTypesDropDown()
    {
        return \App\ExpenseType::all()->pluck('name', 'id')->toArray();
    }
}

/**
 * Get account drop down data
 */
if (!function_exists('productCategoryDropDown')) {
    function productCategoryDropDown()
    {
        return ProductCategory::all()->pluck('name')->toArray();
    }
}

/**
 * Get account drop down data
 */
if (!function_exists('productionUnitDropDown')) {
    function productionUnitDropDownByCompany()
    {
        return \App\ProductionUnit::whereIn('company_id', userCompanyIds(loggedUser()))
            ->get()->pluck('name', 'id')->toArray();
    }
}

if (!function_exists('productionUnitDropDown')) {
    function productionUnitDropDown()
    {
        return \App\ProductionUnit::all()->pluck('name', 'id')->toArray();
    }
}

/**
 * Get account drop down data
 */
if (!function_exists('productCategoryWithIDDropDown')) {
    function productCategoryWithIDDropDown()
    {
        return ProductCategory::all()->pluck('name', 'id')->toArray();
    }
}

if (!function_exists('getCodeForModal')) {
    function getCodeForModal($modal, $prefix)
    {
        $lastItem = $modal->orderBy('created_at', 'desc')->get()->toArray();
        $lastItem = array_values($lastItem);
        $lastItem = array_get($lastItem, '0');
        $number = 0;
        if ($lastItem && array_get($lastItem, 'code')) {
            $code = array_get($lastItem, 'code');
            $number = preg_replace('/\D/', '', $code);
        }
        return $prefix . sprintf('%07d', intval($number) + 1);
    }
}

/**
 * Get account drop down data
 */
if (!function_exists('bankDropDown')) {
    function bankDropDown()
    {
        return \App\Bank::all()->pluck('name', 'id')->toArray();
    }
}

/**
 * Get user's company
 */
if (!function_exists('userCompany')) {
    function userCompany($user = null)
    {
        if (!$user) {
            $user = auth()->user();
        }
        $staff = $user->staffs->first();
        if (!$staff) return null;
        return $staff->companies->first();
    }
}

if (!function_exists('userCompanies')) {
    function userCompanies($user = null)
    {
        if (!$user) {
            $user = auth()->user();
        }
        $staff = $user->staffs->first();
        if (!$staff) return null;
        return $staff->companies;
    }
}

if (!function_exists('userCompanyIds')) {
    function userCompanyIds($user = null)
    {
        if (!$user) {
            $user = auth()->user();
        }
        $staff = $user->staffs->first();
        if (!$staff) return null;
        return $staff->companies->pluck('id')->toArray();
    }
}

if (!function_exists('userUnitIds')) {
    function userUnitIds($user = null)
    {
        if (!$user) {
            $user = auth()->user();
        }
        $staff = $user->staffs->first();
        if (!$staff) return null;
        return $staff->units->pluck('id')->toArray();
    }
}

if (!function_exists('userStoreIds')) {
    function userStoreIds($user = null)
    {
        if (!$user) {
            $user = auth()->user();
        }
        $staff = $user->staffs->first();
        if (!$staff) return null;
        return $staff->stores->pluck('id')->toArray();
    }
}

/**
 * Get user's company
 */
if (!function_exists('userSalesLocation')) {
    function userSalesLocation($user = null)
    {
        if (!$user) {
            $user = auth()->user();
        }
        $staff = $user->staffs->first();
        if (!$staff) return null;
        return $staff->salesLocations->first();
    }
}

/**
 * Get user's company
 */
if (!function_exists('userShopLocation')) {
    function userShopLocation($user = null)
    {
        if (!$user) {
            $user = auth()->user();
        }
        $staff = $user->staffs->first();
        if (!$staff) return null;
        return $staff->salesLocations->where('type', 'Shop')->first();
    }
}

/**
 * Get user's van location
 */
if (!function_exists('userVanLocation')) {
    function userVanLocation($user = null)
    {
        if (!$user) {
            $user = auth()->user();
        }
        $staff = $user->staffs->first();
        if (!$staff) return null;
        return $staff->salesLocations->where('type', 'Sales Van')->first();
    }
}

/**
 * getCustomerCreditLimit
 */
if (!function_exists('getCustomerCreditLimit')) {
    /**
     * @param CustomerCredit $credit
     * @return string
     */
    function getCustomerCreditLimit(CustomerCredit $credit)
    {
        $totalRefunds = getCustomerCreditUsed($credit);
        return $credit->amount - $totalRefunds;
    }
}
/**
 *getCustomerCreditUsed
 */
if (!function_exists('getCustomerCreditUsed')) {
    /**
     * @param CustomerCredit $credit
     * @param array $payment
     * @return string
     */
    function getCustomerCreditUsed(CustomerCredit $credit, $payment = [])
    {
        $totalRefunds = $credit->refunds->sum('amount');
        $totalInvoiced = $credit->payments->whereNotIn('id', $payment)->sum('payment') ?? 0;
        return $totalRefunds + $totalInvoiced;
    }
}


/**
 * getCustomerCreditLimit
 */
if (!function_exists('getSupplierCreditLimit')) {
    /**
     * @param SupplierCredit $credit
     * @return string
     */
    function getSupplierCreditLimit(SupplierCredit $credit)
    {
        $totalRefunds = getSupplierCreditUsed($credit);
        return $credit->amount - $totalRefunds;
    }
}
/**
 *getCustomerCreditUsed
 */
if (!function_exists('getSupplierCreditUsed')) {
    /**
     * @param SupplierCredit $credit
     * @param array $payment
     * @return string
     */
    function getSupplierCreditUsed(SupplierCredit $credit, $payment = [])
    {
        $totalRefunds = $credit->refunds->sum('amount') ?? 0;
        $totalBilled = $credit->payments->whereNotIn('id', $payment)->sum('payment') ?? 0;
        return $totalRefunds + $totalBilled;
    }
}

if (!function_exists('overdueInvoices')) {
    function overdueInvoices()
    {
        $now = carbon()->now()->toDateString();
        $invoices = \App\Invoice::whereIn('company_id', userCompanyIds(loggedUser()))
            ->whereIn('status', ['Open', 'Partially Paid'])->where('due_date', '<', $now)->get();
        $invoices = $invoices->reject(function (\App\Invoice $item) {
            $paymentsSum = $item->payments->where('status', 'Paid')->sum('payment');
            return $item->amount == $paymentsSum;
        });
        return $invoices;
    }
}

if (!function_exists('overdueBills')) {
    function overdueBills()
    {
        $now = carbon()->now()->toDateString();
        $bills = \App\Bill::whereIn('company_id', userCompanyIds(loggedUser()))
            ->whereIn('status', ['Open', 'Partially Paid'])->where('due_date', '<', $now)->get();
        return $bills;
    }
}

if (!function_exists('todayCollectionByCompany')) {
    function todayCollectionByCompany($company)
    {
        $collection = [];
        $now = carbon()->now()->toDateString();

        $orders = \App\SalesOrder::where('company_id', $company)->where('order_date', $now)->get();
        $invoices = \App\Invoice::where('company_id', $company)->where('invoice_date', $now)->get();
        $payments = \App\InvoicePayment::where('company_id', $company)->where('payment_date', $now)->get();

        $totalSales = $orders->sum('total');
        $totalInvoiced = $invoices->sum('amount');
        $totalPaid = $payments->sum('payment');
        $totalCash = $payments->where('payment_mode', 'Cash')->sum('payment');
        $totalCheque = $payments->where('payment_mode', 'Cheque')->sum('payment');
        $totalDeposit = $payments->where('payment_mode', 'Direct Deposit')->sum('payment');

        $collection['totalSales'] = $totalSales;
        $collection['totalInvoiced'] = $totalInvoiced;
        $collection['totalCash'] = $totalCash;
        $collection['totalCheque'] = $totalCheque;
        $collection['totalDeposit'] = $totalDeposit;
        $collection['totalPaid'] = $totalPaid;
        $collection['totalBalance'] = ($totalSales - $totalPaid);

        return $collection;
    }
}

if (!function_exists('todayCollection')) {
    function todayCollection()
    {
        $collection = [];
        $now = carbon()->now()->toDateString();

        $orders = \App\SalesOrder::whereIn('company_id', userCompanyIds(loggedUser()))->where('order_date', $now)->get();
        $invoices = \App\Invoice::whereIn('company_id', userCompanyIds(loggedUser()))->where('invoice_date', $now)
            ->whereHas('order', function ($q) use ($now) {
                $q->whereDate('order_date', $now);
            })
            ->get();
        $payments = \App\InvoicePayment::whereIn('company_id', userCompanyIds(loggedUser()))->where('payment_date', $now)
            ->whereHas('order', function ($q) use ($now) {
                $q->whereDate('order_date', $now);
            })
            ->get();

        $totalSales = $orders->sum('total');
        $totalInvoiced = $invoices->sum('amount');
        $totalPaid = $payments->sum('payment');
        $totalCash = $payments->where('payment_mode', 'Cash')->sum('payment');
        $totalCheque = $payments->where('payment_mode', 'Cheque')->sum('payment');
        $totalDeposit = $payments->where('payment_mode', 'Direct Deposit')->sum('payment');
        $totalCard = $payments->where('payment_mode', 'Credit Card')->sum('payment');

        $collection['totalSales'] = $totalSales;
        $collection['totalInvoiced'] = $totalInvoiced;
        $collection['totalPaid'] = $totalPaid;
        $collection['totalCash'] = $totalCash;
        $collection['totalCheque'] = $totalCheque;
        $collection['totalCard'] = $totalCard;
        $collection['totalDeposit'] = $totalDeposit;
        $collection['totalBalance'] = ($totalSales - $totalPaid);

        return $collection;
    }
}

if (!function_exists('oldCollectionByCompany')) {
    function oldCollectionByCompany($company)
    {
        $collection = [];
        $now = carbon()->now()->toDateString();

        $payments = \App\InvoicePayment::where('company_id', $company)->where('payment_date', $now)->get();
        $payments = $payments->filter(function ($value, $key) use ($now) {
            return $value->invoice->invoice_date < $now;
        });

        $totalPaid = $payments->sum('payment');
        $totalCash = $payments->where('payment_mode', 'Cash')->sum('payment');
        $totalCheque = $payments->where('payment_mode', 'Cheque')->sum('payment');
        $totalDeposit = $payments->where('payment_mode', 'Direct Deposit')->sum('payment');
        $totalCard = $payments->where('payment_mode', 'Credit Card')->sum('payment');

        $collection['totalPaid'] = $totalPaid;
        $collection['totalCash'] = $totalCash;
        $collection['totalCheque'] = $totalCheque;
        $collection['totalDeposit'] = $totalDeposit;
        $collection['totalCard'] = $totalCard;

        return $collection;
    }
}

if (!function_exists('oldCollection')) {
    function oldCollection()
    {
        $collection = [];
        $now = carbon()->now()->toDateString();

        $payments = \App\InvoicePayment::whereIn('company_id', userCompanyIds(loggedUser()))->where('payment_date', $now)->get();
        $payments = $payments->filter(function ($value, $key) use ($now) {
            return $value->invoice->invoice_date < $now;
        });

        $totalPaid = $payments->sum('payment');
        $totalCash = $payments->where('payment_mode', 'Cash')->sum('payment');
        $totalCheque = $payments->where('payment_mode', 'Cheque')->sum('payment');
        $totalDeposit = $payments->where('payment_mode', 'Direct Deposit')->sum('payment');
        $totalCard = $payments->where('payment_mode', 'Credit Card')->sum('payment');

        $collection['totalPaid'] = $totalPaid;
        $collection['totalCash'] = $totalCash;
        $collection['totalCheque'] = $totalCheque;
        $collection['totalDeposit'] = $totalDeposit;
        $collection['totalCard'] = $totalCard;

        return $collection;
    }
}


if (!function_exists('oldCollectionByCustomer')) {
    function oldCollectionByCustomer(Customer $customer)
    {
        $collection = [];
        $now = carbon()->now()->toDateString();

        $payments = \App\InvoicePayment::where('payment_date', $now)->where('customer_id', $customer->id)->get();
        $payments = $payments->filter(function ($value, $key) use ($now) {
            return $value->invoice->invoice_date < $now;
        });

        $totalPaid = $payments->sum('payment');
        $totalCash = $payments->where('payment_mode', 'Cash')->sum('payment');
        $totalCheque = $payments->where('payment_mode', 'Cheque')->sum('payment');
        $totalDeposit = $payments->where('payment_mode', 'Direct Deposit')->sum('payment');
        $totalCard = $payments->where('payment_mode', 'Credit Card')->sum('payment');

        $collection['totalPaid'] = $totalPaid;
        $collection['totalCash'] = $totalCash;
        $collection['totalCheque'] = $totalCheque;
        $collection['totalDeposit'] = $totalDeposit;
        $collection['totalCard'] = $totalCard;

        return $collection;
    }
}

/**
 * get Due model datas
 */

if (!function_exists('getDueCollection')) {
    /**
     * @param $collections
     * @param $data
     * @param null $returnData
     * @return mixed
     */
    function getDueCollection($collections, &$data, $returnData = null)
    {
        $data['1-30'] = [];
        $data['31-60'] = [];
        $data['61-90'] = [];
        $data['91'] = [];
        $collections->map(function ($collection) use (&$data) {
            $dueDate = $collection->due_date;
            $diff = carbon()->diffInDays(carbon($dueDate));
            if ($diff <= 30) {
                array_push($data['1-30'], $collection->payment_remaining);
            } elseif ($diff <= 60) {
                array_push($data['31-60'], $collection->payment_remaining);
            } elseif ($diff <= 90) {
                array_push($data['61-90'], $collection->payment_remaining);
            } else {
                array_push($data['91'], $collection->payment_remaining);
            }
        });
        if ($returnData && array_get($data, $returnData)) return array_get($data, $returnData);
        return $data;
    }
}

if (!function_exists('cusCreditOutstanding')) {
    function cusCreditOutstanding(\App\Customer $customer)
    {
        $outstanding = [];

        $credits = $customer->credits;
        $refunds = $credits->pluck('refunds')->collapse();
        $refunds = $refunds->sum('amount');
        $payments = $credits->pluck('payments')->collapse();
        $used = $payments->sum('payment');

        $outstanding['credits'] = $credits->sum('amount');
        $outstanding['refunded'] = $refunds;
        $outstanding['used'] = $used;
        $outstanding['balance'] = ($credits->sum('amount') - ($refunds + $used));

        return $outstanding;
    }
}


if (!function_exists('supCreditOutstanding')) {
    function supCreditOutstanding(\App\Supplier $supplier)
    {
        $outstanding = [];

        $credits = $supplier->credits;
        $refunds = $credits->pluck('refunds')->collapse();
        $refunds = $refunds->sum('amount');
        $payments = $credits->pluck('payments')->collapse();
        $used = $payments->sum('payment');

        $outstanding['credits'] = $credits->sum('amount');
        $outstanding['refunded'] = $refunds;
        $outstanding['used'] = $used;
        $outstanding['balance'] = ($credits->sum('amount') - ($refunds + $used));

        return $outstanding;
    }
}

if (!function_exists('reportReimbursementAmount')) {
    function reportReimbursementAmount(\App\ExpenseReport $report)
    {
        $expenses = $report->expenses->where('claim_reimburse', 'Yes');
        return $expenses->sum('amount');
    }
}


if (!function_exists('reportReimbursementPendingAmount')) {
    function reportReimbursementPendingAmount(\App\ExpenseReport $report)
    {
        $reimburses = \App\ExpenseReport::find($report->id)->reimburses;
        return reportReimbursementAmount($report) - $reimburses->sum('amount');
    }
}


if (!function_exists('cusEstimateSummary')) {
    function cusEstimateSummary(\App\Customer $customer)
    {
        $summary = [];

        $estimates = $customer->estimates;
        $summary['estimation'] = $estimates->sum('total');

        return $summary;
    }
}

if (!function_exists('supEstimateSummary')) {
    function supEstimateSummary(\App\Supplier $supplier)
    {
        $summary = [];

        $estimates = $supplier->estimates;
        $summary['estimation'] = $estimates ? $estimates->sum('total') : 0;

        return $summary;
    }
}

if (!function_exists('getDueData')) {
    function getDueData($data, $param)
    {
        $array = array_get($data, $param) ?? [];
        return array_sum($array);
    }
}

if (!function_exists('getTotalDue')) {
    function getTotalDue($data)
    {
        $array = [];
        array_push($array, getDueData($data, '1-30'));
        array_push($array, getDueData($data, '31-60'));
        array_push($array, getDueData($data, '61-90'));
        array_push($array, getDueData($data, '91'));

        return array_sum($array);
    }
}

if (!function_exists('dateRangeDropDown')) {
    function dateRangeDropDown()
    {
        $data = [
            'Current' => [
                'Today',
                'This Week',
                'This Month',
                'This Year',
            ],
            'Previous' => [
                'Yesterday',
                'Previous Week',
                'Previous Month',
                'Previous Year',
            ],
            'Custom' => [
                'Custom',
            ]
        ];

        return $data;
    }
}

if (!function_exists('mileageTypeId')) {
    function mileageTypeId()
    {
        return config('app.mileage_type_id');
    }
}

if (!function_exists('fuelTypeId')) {
    function fuelTypeId()
    {
        return config('app.fuel_type_id');
    }
}

if (!function_exists('allowanceTypeId')) {
    function allowanceTypeId()
    {
        return config('app.allowance_type_id');
    }
}

if (!function_exists('generalTypeId')) {
    function generalTypeId()
    {
        return config('app.general_type_id');
    }
}

if (!function_exists('monthsDropDown')) {
    function monthsDropDown()
    {
        return [
            '1' => 'January',
            '2' => 'February',
            '3' => 'March',
            '4' => 'April',
            '5' => 'May',
            '6' => 'June',
            '7' => 'July',
            '8' => 'August',
            '9' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        ];
    }
}

if (!function_exists('shortMonthsDropDown')) {
    function shortMonthsDropDown()
    {
        return [
            '1' => 'Jan',
            '2' => 'Feb',
            '3' => 'Mar',
            '4' => 'Apr',
            '5' => 'May',
            '6' => 'June',
            '7' => 'July',
            '8' => 'Aug',
            '9' => 'Sep',
            '10' => 'Oct',
            '11' => 'Nov',
            '12' => 'Dec',
        ];
    }
}

if (!function_exists('monthIdentifier')) {
    function monthIdentifier($month)
    {
        $monthString = carbon()->month($month)->format('F');
        return $monthString;
    }
}

if (!function_exists('paymentModeDD')) {
    function paymentModeDD()
    {
        return [
            'Cash' => 'Cash',
            'Cheque' => 'Cheque',
            'Direct Deposit' => 'Direct Deposit'
        ];
    }
}

if (!function_exists('paymentTypeDD')) {
    function paymentTypeDD()
    {
        return [
            'Advanced' => 'Advanced',
            'Partial Payment' => 'Partial Payment',
            'Final Payment' => 'Final Payment'
        ];
    }
}

if (!function_exists('yearlyIncome')) {
    function yearlyIncome()
    {
        $yearlyIncome = [];

        $preYear = \carbon()->now()->subYear(1);
        $preYearStart = $preYear->copy()->startOfYear()->toDateString();
        $preYearEnd = $preYear->copy()->endOfYear()->toDateString();

        $year = carbon()->year;
        $month = carbon()->month;
        $thisYear = \carbon()->setDate($year, $month, 1);
        $thisYearStart = $thisYear->copy()->startOfYear()->toDateString();
        $thisYearEnd = $thisYear->copy()->endOfYear()->toDateString();

        $preYearIncome = \App\InvoicePayment::where('status', 'Paid')
            ->whereBetween('payment_date', [$preYearStart, $preYearEnd])
            ->sum('payment');
        $thisYearIncome = \App\InvoicePayment::where('status', 'Paid')
            ->whereBetween('payment_date', [$thisYearStart, $thisYearEnd])
            ->sum('payment');

        $yearlyIncome['preYearIncome'] = $preYearIncome;
        $yearlyIncome['thisYearIncome'] = $thisYearIncome;

        return $yearlyIncome;
    }
}

if (!function_exists('yearlyExpenses')) {
    function yearlyExpenses()
    {
        $yearlyExpenses = [];

        $preYear = \carbon()->now()->subYear(1);
        $preYearStart = $preYear->copy()->startOfYear()->toDateString();
        $preYearEnd = $preYear->copy()->endOfYear()->toDateString();

        $year = carbon()->year;
        $month = carbon()->month;
        $thisYear = \carbon()->setDate($year, $month, 1);
        $thisYearStart = $thisYear->copy()->startOfYear()->toDateString();
        $thisYearEnd = $thisYear->copy()->endOfYear()->toDateString();

        $preYearExpense = \App\Expense::whereIn('status', ['Approved', 'Reimbursed'])
            ->whereBetween('expense_date', [$preYearStart, $preYearEnd])
            ->sum('amount');
        $thisYearExpense = \App\Expense::whereIn('status', ['Approved', 'Reimbursed'])
            ->whereBetween('expense_date', [$thisYearStart, $thisYearEnd])
            ->sum('amount');

        $yearlyExpenses['preYearExpense'] = $preYearExpense;
        $yearlyExpenses['thisYearExpense'] = $thisYearExpense;

        return $yearlyExpenses;
    }
}

if (!function_exists('salesLocationDropDown')) {
    function salesLocationDropDown()
    {
        return \App\SalesLocation::whereIn('company_id', userCompanyIds(loggedUser()))
            ->get()->pluck('name', 'id')->toArray();
    }
}

if (!function_exists('shopDropDown')) {
    function shopDropDown()
    {
        return \App\SalesLocation::whereIn('company_id', userCompanyIds(loggedUser()))
            ->where('type', 'Shop')->get()->pluck('name', 'id')->toArray();
    }
}

if (!function_exists('vanDropDown')) {
    function vanDropDown()
    {
        return \App\SalesLocation::whereIn('company_id', userCompanyIds(loggedUser()))
            ->where('type', 'Sales Van')->get()->pluck('name', 'id')->toArray();
    }
}

if (!function_exists('repDropDown')) {
    function repDropDown()
    {
        return \App\Rep::whereIn('company_id', userCompanyIds(loggedUser()))
            ->get()->pluck('name', 'id')->toArray();
    }
}

if (!function_exists('locationWithTypeDropDown')) {
    function locationWithTypeDropDown()
    {
        return \App\SalesLocation::select(['id', 'name', 'type'])->get()->toArray();
    }
}

if (!function_exists('getRep')) {
    function getRep($user = null)
    {
        if (!$user) $user = auth()->user();
        $staff = $user->staffs()->first();
        return $staff ? $staff->rep : null;
    }
}

/**
 * Get allocation for rep
 */
if (!function_exists('getRepAllocation')) {
    function getRepAllocation($start = null, $end = null, User $user = null, $retrurnAsQuery = false)
    {
        if (!$start) $start = \carbon()->now()->toDateString();
        if (!$end) $end = \carbon()->now()->toDateString();
        if (!$user) $user = auth()->user();
        if (!$user) return collect([]);
        $staff = $user->staffs()->first();

        $rep = $staff ? $staff->rep : null;
        if (!$rep) return collect();
        $allocations = $rep->dailySales()->where(function ($query) use ($start, $end) {
            $query->where(function ($q) use ($start, $end) {
                $q->where('from_date', '>=', $start)
                    ->where('from_date', '<', $end);
            })->orWhere(function ($q) use ($start, $end) {
                $q->where('from_date', '<=', $start)
                    ->where('to_date', '>', $end);
            })->orWhere(function ($q) use ($start, $end) {
                $q->where('to_date', '>', $start)
                    ->where('to_date', '<=', $end);
            })->orWhere(function ($q) use ($start, $end) {
                $q->where('from_date', '>=', $start)
                    ->where('to_date', '<=', $end);
            });
        })->where('sales_location', 'Van')->where('status', 'Progress');
        return $retrurnAsQuery ? $allocations : $allocations->get();
    }
}


/**
 * Get allocation for rep
 */
if (!function_exists('getRepOldAllocation')) {
    function getRepOldAllocation($start = null, User $user = null)
    {
        if (!$start) $start = \carbon()->now()->toDateString();
        if (!$user) $user = auth()->user();
        $staff = $user->staffs()->first();
        $rep = $staff ? $staff->rep : null;
        if (!$rep) return collect();
        return $rep->dailySales()->where(function ($q) use ($start) {
            $q->where('from_date', '<', $start)->orWhere('to_date', '<', $start);
        })->where('sales_location', 'Van')->where('status', 'Completed')->get();
    }
}


/** Get products from allocation */
if (!function_exists('getProductsFromAllocation')) {
    function getProductsFromAllocation($allocations)
    {
        $allocationItems = $allocations->pluck('items')->collapse();
        return $allocationItems->pluck('product');
    }
}

/** Get price book by allocations */
if (!function_exists('getPriceBooksByAllocation')) {
    function getPriceBooksByAllocation($allocations = null)
    {
        if (!$allocations) $allocations = getRepAllocation();
        $allocation = $allocations->first();
        if (!$allocation) return null;
        //$salesLocation = $allocation->salesLocation;
        $rep = $allocation->rep;
        $company = $allocation->company;
        return PriceBook::whereType('Selling Price')
            ->whereCategory('Van Selling Price')
            ->whereCompanyId($company->id)
            ->whereRelatedToId($rep->id)
            ->whereRelatedToType('App\Rep')
            ->with('prices')
            ->first();
    }
}

/** Get today allocated customers */
if (!function_exists('todayAllocatedCustomers')) {
    function todayAllocatedCustomers($allocations = null, $withRelated = true, $returnAsQuery = false)
    {
        if (!$allocations) $allocations = getRepAllocation();
        $allocationIds = $allocations->pluck('id')->toArray();
        $customer = \App\Customer::where(function ($q) use ($allocationIds) {
            $q->whereHas('dailySalesCustomers', function ($q) use ($allocationIds) {
                $q->whereHas('dailySale', function ($q) use ($allocationIds) {
                    $q->whereIn('id', $allocationIds);
                });
            });
        });
        if ($withRelated) {
            $customer->with([
                'route', 'location', 'company', 'contactPersons', 'addresses.country'
            ]);
        }
        return $returnAsQuery ? $customer : $customer->get()->map(function ($item) {
            $item->is_today_allocation = "Yes";
            return $item;
        });
    }
}

/** Get Pending allocated customers */
if (!function_exists('allocatedPendingCustomers')) {
    function allocatedPendingCustomers(User $user = null, $withRelated = true)
    {
        if (!$user) $user = auth()->user();
        $oldAllocations = getRepOldAllocation(null, $user);
        $oldAllocationIds = $oldAllocations->pluck('id')->toArray();
        $pendingCustomers = \App\Customer::where(function ($q) use ($oldAllocationIds) {
            $q->whereHas('dailySalesCustomers', function ($q) use ($oldAllocationIds) {
                $q->whereHas('dailySale', function ($q) use ($oldAllocationIds) {
                    $q->whereIn('id', $oldAllocationIds);
                });
            });
        })->where(function ($q) use ($user) {
            $q->where(function ($q) use ($user) {
                $q->whereHas('orders', function ($q) use ($user) {
                    $q->where('prepared_by', $user->id)->doesntHave('invoices');
                });
            })->orWhere(function ($q) use ($user) {
                $q->whereHas('orders', function ($q) use ($user) {
                    $q->whereHas('invoices', function ($q) use ($user) {
                        $q->where('prepared_by', $user->id)->doesntHave('payments');
                    });
                });
            });
        });
        if ($withRelated) {
            $pendingCustomers->with([
                'route', 'location', 'company', 'contactPersons', 'addresses.country'
            ]);
        }
        $pendingCustomers = $pendingCustomers->get();
        $pendingCustomers = $pendingCustomers->filter(function ($item) {
            if ($item->orders->count() == 0) return true;
            foreach ($item->orders as $order) {
                if ($order->invoices->count() == 0) return true;
                if ($order->amount < $item->invoices->sum('amount')) return true;
                foreach ($order->invoices as $invoice) {
                    if ($invoice->payments->count() == 0) return true;
                    if ($invoice->amount < $invoice->payments->sum('amount')) return true;
                }
            }
            return false;
        });
        $pendingCustomers = $pendingCustomers->map(function ($item) {
            $item->is_today_allocation = "No";
            return $item;
        });
        return $pendingCustomers;
    }
}

/** Get customers from allocation */
if (!function_exists('getAllAllocatedCustomers')) {
    function getAllAllocatedCustomers($allocations = null, User $user = null, $withRelated = true)
    {
        if (!$user) $user = auth()->user();
//        $cacheKey = $user->id.'-allocated-customers';
//        return Cache::rememberForever($cacheKey, function() use($allocations, $user, $withRelated){
        if (!$allocations) $allocations = getRepAllocation(null, null, $user);
        $customer = todayAllocatedCustomers($allocations, $withRelated);
        //$pendingCustomers = allocatedPendingCustomers($user, $withRelated);
        //$customer = $customer->merge($pendingCustomers);
        return $customer;
//        });
    }
}


/** Get customers from allocation */
if (!function_exists('getAvailableQty')) {
    function getAvailableQty(DailySaleItem $product)
    {
        $total = ($product->quantity + $product->cf_qty + $product->returned_qty + $product->excess_qty);
        //$deduct = ($product->sold_qty + $product->restored_qty + $product->replaced_qty + $product->shortage_qty + $product->damaged_qty);
        $deduct = ($product->sold_qty + $product->restored_qty + $product->replaced_qty + $product->shortage_qty);
        return ($total - $deduct);
    }
}


/** Get customers from allocation */
if (!function_exists('getOldValueForHandover')) {
    function getOldValueForHandover($old, $name, $getName, $id)
    {
        if (!array_get($old, '_token')) return null;
        $products = array_get($old, $name);
        if (!$products) return null;
        $values = array_get($products, $getName);
        if (!$values) return null;
        if (!array_key_exists($id, $values)) return null;
        if (array_get($values, $id)) return array_get($values, $id);
        return null;

    }
}

if (!function_exists('transTypeDropDown')) {
    function transTypeDropDown()
    {
        return \App\TransactionType::where('is_active', 'Yes')->get()->pluck('name', 'id')->toArray();
    }
}

if (!function_exists('allocations')) {
    function allocations()
    {
        $allocations = \App\DailySale::whereIn('status', ['Active', 'Progress'])->orderBy('from_date', 'desc')->get();
        return $allocations;
    }
}

if (!function_exists('createComment')) {
    function createComment($request, $model, $commentText)
    {
        $comment = new Comment();
        $comment->setAttribute('comment', $commentText);
        $comment->setAttribute('user_id', auth()->id());
        $comment->setAttribute('commentable_id', $model->id);
        $comment->setAttribute('commentable_type', 'App\\' . class_basename($model));
        $comment->save();

        return $comment;
    }
}

if (!function_exists('allocations')) {
    function allocations()
    {
        $allocations = \App\DailySale::where('sales_location', 'Van')->whereIn('status', ['Draft', 'Active', 'Progress'])->get();
        return $allocations;
    }
}

if (!function_exists('todayAllocations')) {
    function todayAllocations()
    {
        $allocations = \App\DailySale::whereIn('company_id', userCompanyIds(loggedUser()))
            ->where('from_date', '>=', now()->toDateString())
            ->where('to_date', '<=', now()->toDateString())
            ->where('sales_location', 'Van')
            ->where('status', 'Progress')
            ->get();
        return $allocations;
    }
}

if (!function_exists('getAgingSummaryData')) {
    function getAgingSummaryData($array, $name)
    {
        return array_sum(array_get($array, $name));
    }
}

if (!function_exists('getAgingSummaryTotal')) {
    function getAgingSummaryTotal($array)
    {
        $count = getAgingSummaryData($array, '1-30');
        $count += getAgingSummaryData($array, '31-60');
        $count += getAgingSummaryData($array, '61-90');
        $count += getAgingSummaryData($array, '91');

        return $count;
    }
}

if (!function_exists('getAgingSummaryIndividualTotal')) {
    function getAgingSummaryIndividualTotal($array, $name)
    {
        return array_sum(array_collapse(array_pluck($array, $name)));
    }
}

if (!function_exists('getAgingSummaryAllTotal')) {
    function getAgingSummaryAllTotal($array)
    {
        $count = getAgingSummaryIndividualTotal($array, '1-30');
        $count += getAgingSummaryIndividualTotal($array, '31-60');
        $count += getAgingSummaryIndividualTotal($array, '61-90');
        $count += getAgingSummaryIndividualTotal($array, '91');

        return $count;
    }
}

if (!function_exists('showLocationDropdown')) {
    function showLocationDropdown()
    {
        /** @var User $user */
        $user = auth()->user();
        $role = $user->role;
        if (!$role) return false;
        $aL = $role->access_level;
        $storeLevelStaffAccessLevels = config('app.shop_level_staff_access_level');
        $shopLocation = userShopLocation();
        if (!in_array($aL, $storeLevelStaffAccessLevels) && !$shopLocation) {
            return true;
        }
        return false;
    }
}

if (!function_exists('activities')) {
    function activities($user)
    {
        $activities = \App\Activity::where('causer_id', $user->id)->orderBy('id', 'desc')->get();
        return $activities;
    }
}

if (!function_exists('activitiesForProfile')) {
    function activitiesForProfile($user, $staff)
    {
        $activities = \App\Activity::where('causer_id', $user->id)->with('causer')->orderBy('id', 'desc')->get();
        $activities = $activities->map(function ($activity) use ($staff) {
            $activity->diffForHumans = carbon()->now()->sub($activity->created_at->diff(carbon()->now()))->diffForHumans();
            $activity->profile = route('setting.staff.image', [$staff]);
            return $activity;
        });
        return $activities;
    }
}

if (!function_exists('accBalance')) {
    function accBalance($account)
    {
        $accBalance = [];

        $trans = \App\TransactionRecord::where('account_id', $account->id)->get();
        $debit = $trans->where('type', 'Debit')->sum('amount');
        if ($account->opening_balance_type == 'Debit') {
            $debit = ($debit + $account->opening_balance);
        }
        $credit = $trans->where('type', 'Credit')->sum('amount');
        if ($account->opening_balance_type == 'Credit') {
            $debit = ($credit + $account->opening_balance);
        }
        $balance = (abs($debit - $credit));

        $accBalance['debit'] = $debit;
        $accBalance['credit'] = $credit;
        $accBalance['balance'] = $balance;

        return $accBalance;
    }
}

if (!function_exists('recordTransaction')) {
    function recordTransaction(
        Model $transactionable,
        Account $debitAccount,
        Account $creditAccount,
        $data,
        $action = null,
        $isEdit = false)
    {
        if (array_get($data, 'prepared_by')) {
            $data['prepared_by'] = auth()->id();
        }
        dispatch(new RecordTransactionJob($transactionable, $debitAccount, $creditAccount, $data, $action, $isEdit));
    }
}

if (!function_exists('accRunningBal')) {
    function accRunningBal($account, Carbon $from = null, Carbon $to = null)
    {
        $accRunningBal = [];

        $trans = $account->transactions()->with('transaction');
        if ($from) {
            $trans = $trans->whereDate('date', '>=', $from->toDateString());
        }
        if ($to) {
            $trans = $trans->whereDate('date', '<=', $to->toDateString());
        }
        $trans = $trans->get();
        $intBalance = 0;
        $trans->map(function ($tran) use (&$intBalance) {
            if ($tran->type == 'Debit') {
                $balance = ($intBalance + $tran->amount);
            } else {
                $balance = ($intBalance - $tran->amount);
            }
            $intBalance = $balance;
            $tran->balance = $intBalance;
            return $tran;
        });

        $debitBal = $trans->where('type', 'Debit')->sum('amount');
        if ($account->opening_balance_type == 'Debit') {
            $debitBal = ($debitBal + $account->opening_balance);
        }
        $creditBal = $trans->where('type', 'Credit')->sum('amount');
        if ($account->opening_balance_type == 'Credit') {
            $creditBal = ($creditBal + $account->opening_balance);
        }
        $endBal = ($debitBal - $creditBal);

        $accRunningBal['trans'] = $trans;
        $accRunningBal['debitBal'] = $debitBal;
        $accRunningBal['creditBal'] = $creditBal;
        $accRunningBal['endBal'] = $endBal;
        $accRunningBal['chart'] = [];

        if ($from && $to) {
            $chartData = [];
            foreach ($trans as $tran) {
                $date = \carbon($tran->date)->toFormattedDateString();
                $chartData[$date] = [
                    'data' => $tran->balance,
                    'labels' => $date
                ];
            }
            $dates = new DatePeriod($from, new DateInterval('P1D'), $to);
            $finalChartData = [];
            foreach ($dates as $date) {
                $date = $date->toFormattedDateString();
                if (isset($chartData[$date]) && $chartData[$date]) {
                    $finalChartData[$date] = $chartData[$date];
                } else {
                    $finalChartData[$date] = [
                        'data' => 0.00,
                        'labels' => $date
                    ];
                }

            }
            $accRunningBal['chart'] = [
                'labels' => array_pluck($finalChartData, 'labels'),
                'data' => array_pluck($finalChartData, 'data'),
            ];
        }
        return $accRunningBal;
    }
}

if (!function_exists('accRunningBal_')) {
    function accRunningBal_($account, Carbon $from = null, Carbon $to = null)
    {
        $accRunningBal = [];

        $accOpBal = $account->opening_balance;
        $accOpBalType = $account->opening_balance_type;
        $accFirstTxDate = $account->first_tx_date;
        $company = $account->company_id;
        $preFrom = $from->copy()->subDay()->toDateString();

        /** get initial account balance as at date */
        $transactions = \App\TransactionRecord::where('date', '<=', $preFrom)
            ->where('account_id', $account->id)
            ->get();

        $debitBal1 = $transactions->where('type', 'Debit')->sum('amount');
        if ($accOpBalType == 'Debit') {
            $debitBal1 = ($accOpBal + $debitBal1);
        }

        $creditBal1 = $transactions->where('type', 'Credit')->sum('amount');
        if ($accOpBalType == 'Credit') {
            $creditBal1 = ($accOpBal + $creditBal1);
        }

        $intBal = $debitBal1 - $creditBal1;
        $intBal2 = $debitBal1 - $creditBal1;

        if($debitBal1 > $creditBal1){
            $intBalType = 'Debit';
        }
        elseif($debitBal1 < $creditBal1){
            $intBalType = 'Credit';
        }
        else{
            $intBalType = 'Debit';
        }
        $accRunningBal['intBal'] = $intBal;
        $accRunningBal['intBalView'] = abs($intBal);
        $accRunningBal['intBalType'] = $intBalType;
        /** END */

        /** get given date range trans and balances */
        $trans = \App\TransactionRecord::where('date', '>=', $from->toDateString())
            ->where('date', '<=', $to->toDateString())
            ->where('account_id', $account->id)
            ->with('transaction', 'transaction.txType')->orderBy('date', 'asc')->get();

        $trans->map(function ($tran) use (&$intBal2, $intBalType) {
            if ($tran->type == 'Debit') {
                $balance = $intBal2 + $tran->amount;
            }else{
                $balance = $intBal2 ? ($intBal2 - $tran->amount) : 0;
            }
            $intBal2 = $balance;
            $tran->balance = $balance;
            $tran->balanceView = abs($balance);
            return $tran;
        });

        $debitBal = $trans->where('type', 'Debit')->sum('amount');
        if ($intBalType == 'Debit') {
            $debitBal = ($intBal + $debitBal);
        }
        $creditBal = $trans->where('type', 'Credit')->sum('amount');
        if ($intBalType == 'Credit') {
            $creditBal = ($intBal + $creditBal);
        }

        $endBal = $debitBal > abs($creditBal) ? ($debitBal - abs($creditBal)) : (abs($creditBal) - $debitBal);

        $accRunningBal['trans'] = $trans->sortBy('date');
        $accRunningBal['debitBal'] = $debitBal;
        $accRunningBal['creditBal'] = $creditBal;
        $accRunningBal['endBal'] = $endBal;

        $accRunningBal['chart'] = [];

        if ($from && $to) {
            $chartData = [];
            foreach ($trans as $tran) {
                $date = \carbon($tran->date)->toFormattedDateString();
                $chartData[$date] = [
                    'data' => $tran->balance,
                    'labels' => $date
                ];
            }
            $dates = new DatePeriod($from, new DateInterval('P1D'), $to);
            $finalChartData = [];
            foreach ($dates as $date) {
                $date = $date->toFormattedDateString();
                if (isset($chartData[$date]) && $chartData[$date]) {
                    $finalChartData[$date] = $chartData[$date];
                } else {
                    $finalChartData[$date] = [
                        'data' => 0.00,
                        'labels' => $date
                    ];
                }

            }
            $accRunningBal['chart'] = [
                'labels' => array_pluck($finalChartData, 'labels'),
                'data' => array_pluck($finalChartData, 'data'),
            ];
        }
        return $accRunningBal;
    }
}

if (!function_exists('accRunningBalAsDate')) {
    function accRunningBalAsDate($account, Carbon $date = null)
    {
        $accRunningBal = [];

        $accOpBal = $account->opening_balance;
        $accOpBalType = $account->opening_balance_type;
        $accFirstTxDate = $account->first_tx_date;
        $company = $account->company_id;
        $preFrom = $date->copy()->subDay()->toDateString();


        /** get given date range trans and balances */
        $trans = \App\TransactionRecord::where('date', '<=', $date->toDateString())
            ->where('account_id', $account->id)
            ->with('transaction', 'transaction.txType')->orderBy('date', 'asc')->get();

        $trans->map(function ($tran) use (&$intBal2, $accOpBalType) {
            if ($tran->type == 'Debit') {
                $balance = $intBal2 + $tran->amount;
            }else{
                $balance = $intBal2 ? ($intBal2 - $tran->amount) : 0;
            }
            $intBal2 = $balance;
            $tran->balance = $balance;
            $tran->balanceView = abs($balance);
            return $tran;
        });

        $debitBal = $trans->where('type', 'Debit')->sum('amount');
        if ($accOpBalType == 'Debit') {
            $debitBal = ($accOpBal + $debitBal);
        }
        $creditBal = $trans->where('type', 'Credit')->sum('amount');
        if ($accOpBalType == 'Credit') {
            $creditBal = ($accOpBal + $creditBal);
        }

        $endBal = $debitBal > abs($creditBal) ? ($debitBal - abs($creditBal)) : (abs($creditBal) - $debitBal);

        $accRunningBal['trans'] = $trans->sortBy('date');
        $accRunningBal['debitBal'] = $debitBal;
        $accRunningBal['creditBal'] = $creditBal;
        $accRunningBal['endBal'] = $endBal;

        return $accRunningBal;
    }
}

if (!function_exists('cashAccBalance')) {
    function cashAccBalance()
    {
        $cashAccBalance = [];

        $cashAccounts = Account::where('account_type_id', 1)->pluck('id');
        $trans = \App\TransactionRecord::whereIn('account_id', $cashAccounts)->get();
        $debit = $trans->where('type', 'Debit')->sum('amount');
        $credit = $trans->where('type', 'Credit')->sum('amount');
        $balance = (abs($debit - $credit));

        $cashAccBalance['debit'] = $debit;
        $cashAccBalance['credit'] = $credit;
        $cashAccBalance['balance'] = $balance;

        return $cashAccBalance;
    }
}

if (!function_exists('bankAccBalance')) {
    function bankAccBalance()
    {
        $bankAccBalance = [];

        $bankAccounts = Account::where('account_type_id', 2)->get();
        $trans = \App\TransactionRecord::whereIn('account_id', $bankAccounts)->get();
        $debit = $trans->where('type', 'Debit')->sum('amount');
        $credit = $trans->where('type', 'Credit')->sum('amount');
        $balance = (abs($debit - $credit));

        $bankAccBalance['debit'] = $debit;
        $bankAccBalance['credit'] = $credit;
        $bankAccBalance['balance'] = $balance;

        return $bankAccBalance;
    }
}

if (!function_exists('getAccCat')) {
    function getAccCat($catId)
    {
        $category = \App\AccountCategory::where('id', $catId)->first();
        return $category;
    }
}

/** Check the user is shop level staff */
if (!function_exists('isShopLevelStaff')) {
    function isShopLevelStaff(User $user = null)
    {
        $levels = config('app.shop_level_staff_access_level');
        $authRole = $user ? $user->role : auth()->user()->role;
        $accessLevel = $authRole ? $authRole->access_level : 0;
        return in_array($accessLevel, $levels);
    }
}
/** Check the user is shop manager level staff */
if (!function_exists('isShopManagerLevelStaff')) {
    function isShopManagerLevelStaff(User $user = null)
    {
        $levels = config('app.shop_manager_level_staff_access_level');
        $authRole = $user ? $user->role : auth()->user()->role;
        $accessLevel = $authRole ? $authRole->access_level : 0;
        return in_array($accessLevel, $levels);
    }
}
/** Check the user is cashier level staff */
if (!function_exists('isCashierLevelStaff')) {
    function isCashierLevelStaff(User $user = null)
    {
        $levels = config('app.cashier_level_staff_access_level');
        $authRole = $user ? $user->role : auth()->user()->role;
        $accessLevel = $authRole ? $authRole->access_level : 0;
        return in_array($accessLevel, $levels);
    }
}
/** Check the user is director level staff */
if (!function_exists('isDirectorLevelStaff')) {
    function isDirectorLevelStaff(User $user = null)
    {
        $levels = config('app.director_level_staff_access_level');
        $authRole = $user ? $user->role : auth()->user()->role;
        $accessLevel = $authRole ? $authRole->access_level : 0;
        return in_array($accessLevel, $levels);
    }
}
/** Check the user is head level staff */
if (!function_exists('isHeadLevelStaff')) {
    function isHeadLevelStaff(User $user = null)
    {
        $levels = config('app.head_level_staff_access_level');
        $authRole = $user ? $user->role : auth()->user()->role;
        $accessLevel = $authRole ? $authRole->access_level : 0;
        return in_array($accessLevel, $levels);
    }
}
/** Check the user is account level staff */
if (!function_exists('isAccountLevelStaff')) {
    function isAccountLevelStaff(User $user = null)
    {
        $levels = config('app.account_level_staff_access_level');
        $authRole = $user ? $user->role : auth()->user()->role;
        $accessLevel = $authRole ? $authRole->access_level : 0;
        return in_array($accessLevel, $levels);
    }
}
/** Check the user is administrator level staff */
if (!function_exists('isAdministratorLevelStaff')) {
    function isAdministratorLevelStaff(User $user = null)
    {
        $levels = config('app.administrator_level_staff_access_level');
        $authRole = $user ? $user->role : auth()->user()->role;
        $accessLevel = $authRole ? $authRole->access_level : 0;
        return in_array($accessLevel, $levels);
    }
}

/** Check the user is store level staff */
if (!function_exists('isStoreLevelStaff')) {
    function isStoreLevelStaff(User $user = null)
    {
        $levels = config('app.store_level_staff_access_level');
        $authRole = $user ? $user->role : auth()->user()->role;
        $accessLevel = $authRole ? $authRole->access_level : 0;
        return in_array($accessLevel, $levels);
    }
}

/** Check the user is production level staff */
if (!function_exists('isProductionLevelStaff')) {
    function isProductionLevelStaff(User $user = null)
    {
        $levels = config('app.production_level_staff_access_level');
        $authRole = $user ? $user->role : auth()->user()->role;
        $accessLevel = $authRole ? $authRole->access_level : 0;
        return in_array($accessLevel, $levels);
    }
}

/** Check the user is store level staff */
if (!function_exists('todayHandOver')) {
    function todayHandOver($user = null)
    {
        if (!$user) $user = auth()->user();
        $allocation = getRepAllocation(null, null, $user)->first();
        if (!$allocation) {
            return null;
        }
        return \App\SalesHandover::whereDate('date', now()->toDateString())
            ->where('daily_sale_id', $allocation ? $allocation->id : null)
            ->first();
    }
}

if (!function_exists('getShopAllocation')) {
    function getShopAllocation($start = null, $end = null, User $user = null, $retrurnAsQuery = false)
    {
        if (!$start) $start = \carbon()->now()->toDateString();
        if (!$end) $end = \carbon()->now()->toDateString();
        if (!$user) $user = auth()->user();
        $staff = $user->staffs()->first();
        $salesLocation = $staff ? $staff->salesLocations->first() : null;
        if ($salesLocation) {
            $dailySales = $salesLocation->dailySales();
        } else {
            $dailySales = new \App\DailySale();
        };

        $allocations = $dailySales->where(function ($q) use ($start, $end) {
            $q->where(function ($q) use ($start, $end) {
                $q->where('from_date', '>=', $start)->orWhere('from_date', '<=', $end);
            })->orWhere(function ($q) use ($start, $end) {
                $q->where('to_date', '>=', $start)->orWhere('to_date', '<=', $end);
            });
        })->where('sales_location', 'Shop')->where('status', 'Progress');
        return $allocations->first();
    }
}

if (!function_exists('getBreakDownTotal')) {
    function getBreakDownTotal($breakDowns)
    {
        $amount = 0;
        foreach ($breakDowns as $breakDown) {
            $amount += ($breakDown->rupee_type * $breakDown->count);
        }
        return $amount;
    }
}


if (!function_exists('getProgressValue')) {
    function getProgressValue($totalAmount, $valueAmount, $reverse = null)
    {
        if ($totalAmount == 0) {
            return 0;
        }
        $value = (($valueAmount / $totalAmount) * 100);
        if ($reverse) {
            $value = (100 - $value);
        }
        return $value;
    }
}

if (!function_exists('repLoggedInSuccess')) {
    function repLoggedInSuccess($allocation = null)
    {
        $allocation = $allocation ? $allocation : getRepAllocation();
        /** @var \App\DailySale $firstAllocation */
        $firstAllocation = $allocation->first();
        $firstAllocation->setAttribute('logged_in_at', now());
        $firstAllocation->setAttribute('is_logged_in', 'Yes');
        $firstAllocation->save();
    }
}

if (!function_exists('AfterLogin')) {
    function AfterLogin(User $user = null)
    {
        $user = $user ? $user : auth()->user();
        $user->tfa_expiry = \Carbon\Carbon::now();
        $user->save();
        session(['tfa_expiry' => \Carbon\Carbon::now()]);
    }
}

if (!function_exists('repLoggedOutSuccess')) {
    function repLoggedOutSuccess($allocation = null)
    {
        $allocation = $allocation ? $allocation : getRepAllocation();
        /** @var \App\DailySale $firstAllocation */
        $firstAllocation = $allocation->first();
        $firstAllocation->setAttribute('logged_out_at', now());
        $firstAllocation->setAttribute('is_logged_out', 'Yes');
        $firstAllocation->save();
    }
}

if (!function_exists('allocationOrders')) {
    function allocationOrders($allocation)
    {
        $orders = \App\SalesOrder::where('daily_sale_id', $allocation->id)->orderBy('created_at', 'desc')->with(['customer'])->get();
        return $orders;
    }
}

if (!function_exists('allocationReturns')) {
    function allocationReturns($allocation)
    {
        $returns = \App\SalesReturn::where('daily_sale_id', $allocation->id)->orderBy('created_at', 'desc')->with(['customer'])->get();
        $returns = $returns->map(function ($return) {
            $return->return_amount = $return->resolutions()->sum('amount');
            $return->no_of_items = $return->items()->count();
            return $return;
        });
        return $returns;
    }
}

if (!function_exists('addressExport')) {
    function addressExport($address)
    {
        $string = '';
        if ($address) {
            $string .= $address->street_one . ', ';
            $string .= $address->street_two . ', ';
            $string .= $address->city . ', ';
            $string .= $address->province . ', ';
            $string .= $address->postal_code . ', ';
            $string .= $address->country->name . ', ' ?? ' ';
        }
        return $string;
    }
}

if (!function_exists('salesCategorySummary')) {
    function salesCategorySummary($customer, $category)
    {
        $summary = [];
        $orders = \App\SalesOrder::where('customer_id', $customer->id)->where('sales_category', $category)->get();

        return $summary;
    }
}

if (!function_exists('ordersOutStanding')) {
    function ordersOutStanding($orders)
    {
        $payments = $orders->pluck('payments')->collapse();
        $others = $payments->whereNotIn('payment_mode', ['Cheque']);
        $cheques = $payments->where('payment_mode', 'Cheque');
        $orderTotal = $orders->sum('total');
        $othersTotal = $others->sum('payment');
        $chequesTotal = $cheques->sum('payment');
        return $orderTotal - ($othersTotal + $chequesTotal);
    }
}

if (!function_exists('getAllocationCreditOrdersId')) {
    function getAllocationCreditOrdersId()
    {
        $allocation = getRepAllocation()->first();
        $orderIds = [];
        if ($allocation) {
            $orderIds = $allocation->dailySaleCreditOrders->pluck('sales_order_id')->toArray();
        }
        return $orderIds;
    }
}

if (!function_exists('customerSalesSummary')) {
    function customerSalesSummary(Customer $customer, $get)
    {
        $salesOrder = $customer->orders()->with('payments')->get();
        $van = $salesOrder->where('sales_category', 'Van');
        $shop = $salesOrder->where('sales_category', 'Shop');
        $office = $salesOrder->where('sales_category', 'Office');
        $data = [];
        $data['van'] = [];
        $data['shop'] = [];
        $data['office'] = [];

        $vanTotal = $van->sum('total');
        $vanPaymentTotal = $van->pluck('payments')->collapse()->sum('payment');
        $data['van']['sales'] = $vanTotal;
        $data['van']['paid'] = $vanPaymentTotal;
        $data['van']['balance'] = $vanTotal - $vanPaymentTotal;

        $shopTotal = $shop->sum('total');
        $shopPaymentTotal = $shop->pluck('payments')->collapse()->sum('payment');
        $data['shop']['sales'] = $shopTotal;
        $data['shop']['paid'] = $shopPaymentTotal;
        $data['shop']['balance'] = $shopTotal - $shopPaymentTotal;

        $officeTotal = $office->sum('total');
        $officePaymentTotal = $office->pluck('payments')->collapse()->sum('payment');
        $data['office']['sales'] = $officeTotal;
        $data['office']['paid'] = $officePaymentTotal;
        $data['office']['balance'] = $officeTotal - $officePaymentTotal;

        return array_get($data, $get, []);
    }
}


if (!function_exists('cusNotRealizedCheque')) {
    function cusNotRealizedCheque(Customer $customer)
    {
        return $customer->payments()->where('payment_mode', 'Cheque')
            ->where('is_cheque_realized', 'No')
            ->with(['depositedTo' => function ($query) {
                $query->select(['id', 'code', 'name', 'short_name']);
            }, 'bank' => function ($query) {
                $query->select(['id', 'code', 'name']);
            }])
            ->select(['id', 'payment', 'payment_date', 'cheque_type',
                'cheque_no', 'cheque_date', 'bank_id', 'deposited_to', 'payment_type'])
            ->get()->toArray();
    }
}

if (!function_exists('notAllocatedRepDropDown')) {
    function notAllocatedRepDropDown($allocation)
    {
        $start = $allocation->from_date;
        $end = $allocation->to_date;

        $availableReps = \App\Rep::whereNotIn('id', [$allocation->rep_id])->get();
        $availableReps = $availableReps->reject(function ($item) use ($start, $end) {
            /** if allocation available */
            $availableAllocation = \App\DailySale::where('rep_id', $item->id)
                ->where('from_date', '>=', $start)
                ->where('to_date', '<=', $end)->first();

            return $availableAllocation;
        });
        return $availableReps->pluck('name', 'id')->toArray();
    }
}

if (!function_exists('notAllocatedDriverDropDown')) {
    function notAllocatedDriverDropDown($allocation)
    {
        $start = $allocation->from_date;
        $end = $allocation->to_date;

        $availableDrivers = \App\Staff::whereNotIn('id', [$allocation->driver_id])->get();
        $availableDrivers = $availableDrivers->reject(function ($item) use ($start, $end) {
            /** if allocation available */
            $availableAllocation = \App\DailySale::where('driver_id', $item->id)
                ->where('from_date', '>=', $start)
                ->where('to_date', '<=', $end)->first();

            return $availableAllocation;
        });
        return $availableDrivers->pluck('short_name', 'id')->toArray();
    }
}

if (!function_exists('notAllocatedLabourDropDown')) {
    function notAllocatedLabourDropDown($allocation)
    {
        $start = $allocation->from_date;
        $end = $allocation->to_date;

        $availableLabours = \App\Staff::whereNotIn('id', explode(',', $allocation->labour_id))->get();
        $availableLabours = $availableLabours->reject(function ($item) use ($start, $end) {
            /** if allocation available */
            $availableAllocation = \App\DailySale::whereIn('labour_id', [$item->id])
                ->where('from_date', '>=', $start)
                ->where('to_date', '<=', $end)->first();

            return $availableAllocation;
        });
        return $availableLabours->pluck('short_name', 'id')->toArray();
    }
}

if (!function_exists('getAllocationLabours')) {
    function getAllocationLabours($allocation)
    {
        $labours = explode(',', $allocation->labour_id);
        $labours = Staff::whereIn('id', $labours)->get();
        return $labours;
    }
}

if (!function_exists('getNonRoutedCusOrders')) {
    function getNonRoutedCusOrders($customer, $allocation)
    {
        $orders = \App\SalesOrder::where('customer_id', $customer->id)
            ->where('daily_sale_id', $allocation->id)
            ->orderBy('created_at', 'desc')
            ->with(['customer'])
            ->get();
        return $orders;
    }
}


if (!function_exists('inRange')) {
    function inRange($number, $min, $max, $inclusive = true)
    {
        if (is_numeric($number) && is_numeric($min) && is_numeric($max)) {
            return $inclusive
                ? ($number >= $min && $number <= $max)
                : ($number > $min && $number < $max);
        }

        return false;
    }
}

if (!function_exists('getAllPolicyMethods')) {
    function getAllPolicyMethods($policyGroup)
    {
        $polices = array_get($policyGroup, 'policies');
        $policyModels = array_pluck($polices, 'policy');
        $allPolices = [];
        foreach ($policyModels as $policyModel) {
            $policies = app($policyModel)->policies;
            $allPolices = array_merge($allPolices, $policies);
        }
        return array_unique($allPolices);
    }
}

if (!function_exists('getParentAccountGroups')) {
    function getParentAccountGroups(\App\AccountCategory $category)
    {
        return \App\AccountGroup::where('parent_id', null)->where('category_id', $category->id)->get();
    }
}

if (!function_exists('getChildAccountGroup')) {
    function getChildAccountGroup(\App\AccountGroup $group)
    {
        return \App\AccountGroup::where('parent_id', $group->id)->get();
    }
}


function permissionTips($item)
{
    $tip = '';
    switch ($item) {
        case "index":
            $tip = 'User will be able to navigate list of items, this will enable menu item in the main menu.';
            break;
        case "create":
            $tip = 'User will be able to create new item.';
            break;
        case "edit":
            $tip = 'User will be able to edit item.';
            break;
        case "show":
            $tip = 'User will be able to navigate to detailed view of a item';
            break;
        case "view":
            $tip = 'User will be able to navigate to detailed view of a item';
            break;
        case "delete":
            $tip = 'User will be able to delete item.';
            break;
        case "reply":
            $tip = 'User will be able to reply for a item.';
            break;
        case "preview":
            $tip = 'User will be able to preview the item.';
            break;
        case "share":
            $tip = 'User will be able to share the item.';
            break;
        case "export":
            $tip = 'User will be able to export the data.';
            break;
        case "print":
            $tip = 'User will be able to print the data.';
            break;
        case "approval":
            $tip = 'User will be able to navigate to approval view of a item.';
            break;
        case "approve":
            $tip = 'User will be able to approve the item.';
            break;
        case "convert":
            $tip = 'User will be able to convert the item.';
            break;
        case "statement":
            $tip = 'User will be able to see the statement.';
            break;
        case "refund":
            $tip = 'User will be able to refund for a item.';
            break;
        case "cancel":
            $tip = 'User will be able to cancel the item.';
            break;
        case "clone":
            $tip = 'User will be able to clone the item.';
            break;
        case "send":
            $tip = 'User will be able to send the item to approvals.';
            break;
        case "accept":
            $tip = 'User will be able to accept the approval.';
            break;
        case "decline":
            $tip = 'User will be able to decline the approval.';
            break;
    }
    return $tip;
}

if (!function_exists('permissionSubtitle')) {
    function permissionSubtitle($title)
    {
        switch ($title) {
            case "Sales order":
                return "Sales -> Cash Sales";
            case "Expense":
                return "Payments -> All";
            default:
                return false;
        }
    }
}

if (!function_exists('makeNested')) {

    function makeNested(\Illuminate\Support\Collection $source, $parentId = 'parent_id', $id = 'id')
    {
        $source = $source->keyBy($id)->toArray();
        $nested = array();
        foreach ($source as &$s) {
            if (is_null($s[$parentId])) {
                // no parent_id so we put it in the root of the array
                $nested[] = &$s;
            } else {
                $pid = $s[$parentId];
                if (isset($source[$pid])) {
                    if (!isset($source[$pid]['children'])) {
                        $source[$pid]['children'] = array();
                    }
                    $source[$pid]['children'][] = &$s;
                }
            }
        }
        return collect($nested);
    }
}

if (!function_exists('loggedUser')) {
    function loggedUser()
    {
        return auth()->user();
    }
}

if (!function_exists('chequesSubjectToRealise')) {
    function chequesSubjectToRealise($customer)
    {
        $chequePayments = \App\InvoicePayment::where('customer_id', $customer->id)
            ->where('payment_mode', 'Cheque')->get();

        $payments = $chequePayments->pluck('id');

        $cheques = \App\ChequeInHand::whereIn('chequeable_id', $payments)
            ->whereIn('status', ['Not Realised', 'Deposited'])->get();

        return $cheques;
    }
}

if (!function_exists('logo')) {
    function logo()
    {
        return '/images/logo-icon.png';
    }
}

if (!function_exists('logoSrc')) {
    function logoSrc()
    {
        return env('APP_URL') . '/images/logo-icon.png';
    }
}

if (!function_exists('getDifferentTime')) {
    function getDifferentTime($first, $second)
    {
        $diffSeconds = \carbon($first)->diffInSeconds(\carbon($second));
        $time = gmdate('s\s', $diffSeconds);
        if ($diffSeconds >= 60) {
            $time = gmdate('i\m\ s\s', $diffSeconds);
        }
        if ($diffSeconds >= 3600) {
            $time = gmdate('H\h\ i\m\ s\s', $diffSeconds);
        }
        return $time;
    }
}

/**
 * Get account drop down data
 */
if (!function_exists('accGroupDropDown')) {
    function accGroupDropDown()
    {
        return \App\AccountGroup::all()->pluck('name', 'id')->toArray();
    }
}

if (!function_exists('customerVisits')) {
    function customerVisits($customer, $fromDate, $toDate)
    {
        $visits = [];

        $customers = \App\DailySale::whereIn('status', ['Progress', 'Completed'])->whereBetween('from_date', [$fromDate, $toDate])
            ->get()->pluck('customers')->collapse();

        $stat = $customers->where('customer_id', $customer->id);

        $allocated = $stat->count();
        $visited = $stat->where('is_visited', 'Yes')->count();
        $notVisited = $stat->where('is_visited', 'No')->count();

        $visits['allocated'] = $allocated;
        $visits['visited'] = $visited;
        $visits['not_visited'] = $notVisited;

        return $visits;
    }
}

if (!function_exists('getBarCodeBase64')) {
    function getBarCodeBase64($code, $format = 'UPCA') {
        return 'data:image/png;base64,'.getBarCodeImage($code, $format);
    }
}

if (!function_exists('getBarCodeImage')) {
    function getBarCodeImage($code, $format = 'UPCA') {
        return DNS1D::getBarcodePNG($code, $format ,1.5,30,array(1,1,1), true);
    }
}

if (!function_exists('generateProductBarcodeNumber')) {
    function generateProductBarcodeNumber($type)
    {
        switch ($type){
            case 'Raw Material':
                $number = mt_rand(1000000000, 1999999999);
                break;
            case 'Finished Good':
                $number = mt_rand(2000000000, 2999999999);
                break;
            default:
                $number = mt_rand(3000000000, 3999999999);
                break;
        }
        if (\App\Product::whereBarcodeNumber($number)->exists()) {
            return generateBarcodeNumber($type);
        }
        return $number;
    }
}

/**
 * Get customer drop down data
 */
if (!function_exists('customerDropDownByCompany')) {
    function customerDropDownByCompany()
    {
        return \App\Customer::whereIn('company_id', userCompanyIds(loggedUser()))
            ->get()->pluck('display_name', 'id')->toArray();
    }
}

if (!function_exists('accRunningBal2')) {
    function accRunningBal2($account, Carbon $from = null, Carbon $to = null)
    {
        $accRunningBal = [];

        /** get initial account balance as at date */
        $accOpBal = $account->opening_balance;
        $accOpBalType = $account->opening_balance_type;
        $accFirstTxDate = $account->first_tx_date ? $account->first_tx_date : \carbon($account->created_at)->toDateString();

        $tranRecords = $account->transactions()->whereHas('transaction', function ($query) use($accFirstTxDate, $from) {
            $query->whereBetween('date', [$accFirstTxDate, $from->toDateString()]);
        })->get();

        $debitBal1 = $tranRecords->where('type', 'Debit')->sum('amount');
        if ($accOpBalType == 'Debit') {
            $debitBal1 = ($debitBal1 + $accOpBal);
        }
        $creditBal1 = $tranRecords->where('type', 'Credit')->sum('amount');
        if ($accOpBalType == 'Credit') {
            $creditBal1 = ($creditBal1 + $accOpBal);
        }
        $intBal = ($debitBal1 - $creditBal1);

        if($debitBal1 > $creditBal1){
            $intBalType = 'Debit';
        }else{
            $intBalType = 'Credit';
        }
        $accRunningBal['intBal'] = $intBal;
        $accRunningBal['intBalType'] = $intBalType;
        /** END */

        /** get given date range trans and balances */
        $trans = $account->transactions()->with('transaction');
        if ($from) {
            $trans = $trans->whereDate('date', '>=', $from->toDateString());
        }
        if ($to) {
            $trans = $trans->whereDate('date', '<=', $to->toDateString());
        }
        $trans = $trans->get();

        $trans->map(function ($tran) use (&$intBal, $intBalType) {
            if ($tran->type == 'Debit') {
                $balance = ($intBal + $tran->amount);
            } else {
                $balance = ($intBal - $tran->amount);
            }
            $intBal = $balance;
            $tran->balance = $balance;

            $tran->tran_type = $tran->transaction->txType->name;

            /** make description to each action */
            if($tran->transaction->action == 'InvoiceCreation'){
                $tran->tran_des_short = 'Sales In';
                $tran->tran_ref_no = $tran->transaction->transactionable->order->ref;
                $tran->tran_ref_id = $tran->transaction->transactionable->order->id;
                $tran->tran_ref_url = '/sales/order/';
            }else if($tran->transaction->action == 'PaymentCreation'){
                $tran->tran_des_short = 'Cash In';
                $tran->tran_ref_no = $tran->transaction->transactionable->invoice->ref;
                $tran->tran_ref_id = $tran->transaction->transactionable->invoice->id;
                $tran->tran_ref_url = '/sales/invoice/';
            }

            return $tran;
        });

        $debitBal = $trans->where('type', 'Debit')->sum('amount');
        if ($intBalType == 'Debit') {
            $debitBal = ($debitBal + $intBal);
        }
        $creditBal = $trans->where('type', 'Credit')->sum('amount');
        if ($intBalType == 'Credit') {
            $creditBal = ($creditBal + $intBal);
        }
        $endBal = ($debitBal - $creditBal);

        $accRunningBal['trans'] = $trans;
        $accRunningBal['debitBal'] = $debitBal;
        $accRunningBal['creditBal'] = $creditBal;
        $accRunningBal['endBal'] = $endBal;

        return $accRunningBal;
    }
}

if (!function_exists('customerLedger')) {
    function customerLedger($customer, Carbon $from = null, Carbon $to = null)
    {
        $accRunningBal = [];

        /** get initial account balance as at date */
        $accOpBal = $customer->opening_balance;
        $accOpBalType = $customer->opening_balance_type;
        $accFirstTxDate = $customer->opening_balance_at ? $customer->opening_balance_at : \carbon($customer->opening_balance_at)->toDateString();

        $transactions = \App\Transaction::whereBetween('date', [$accFirstTxDate, $from->toDateString()])
            ->where('customer_id', $customer->id)->with('records')->get();
        $tranRecords = $transactions->pluck('records');

        $debitBal1 = $tranRecords->where('type', 'Debit')->sum('amount');
        if ($accOpBalType == 'Debit') {
            $debitBal1 = ($debitBal1 + $accOpBal);
        }
        $creditBal1 = $tranRecords->where('type', 'Credit')->sum('amount');
        if ($accOpBalType == 'Credit') {
            $creditBal1 = ($creditBal1 + $accOpBal);
        }
        $intBal = ($debitBal1 - $creditBal1);

        if($debitBal1 > $creditBal1){
            $intBalType = 'Debit';
        }else{
            $intBalType = 'Credit';
        }
        $accRunningBal['intBal'] = $intBal;
        $accRunningBal['intBalType'] = $intBalType;
        /** END */

        /** get given date range trans and balances */
        $trans = $customer->journals()->with('records');
        if ($from) {
            $trans = $trans->whereDate('date', '>=', $from->toDateString());
        }
        if ($to) {
            $trans = $trans->whereDate('date', '<=', $to->toDateString());
        }
        $trans = $trans->get();
        $trans = $trans->pluck('records')->collapse();

        $trans->map(function ($tran) use (&$intBal, $intBalType) {
            if ($tran->type == 'Debit') {
                $balance = ($intBal + $tran->amount);
            } else {
                $balance = ($intBal - $tran->amount);
            }
            $intBal = $balance;
            $tran->balance = $balance;

            $tran->tran_type = $tran->transaction->txType->name;

            /** make description to each action */
            if($tran->transaction->action == 'InvoiceCreation'){
                $tran->tran_des_short = 'Sales In';
                $tran->tran_ref_no = $tran->transaction->transactionable->order->ref;
                $tran->tran_ref_id = $tran->transaction->transactionable->order->id;
                $tran->tran_ref_url = '/sales/order/';
            }else if($tran->transaction->action == 'PaymentCreation'){
                $tran->tran_des_short = 'Cash In';
                $tran->tran_ref_no = $tran->transaction->transactionable->invoice->ref;
                $tran->tran_ref_id = $tran->transaction->transactionable->invoice->id;
                $tran->tran_ref_url = '/sales/invoice/';
            }
        });

        $debitBal = $trans->where('type', 'Debit')->sum('amount');
        if ($intBalType == 'Debit') {
            $debitBal = ($debitBal + $intBal);
        }
        $creditBal = $trans->where('type', 'Credit')->sum('amount');
        if ($intBalType == 'Credit') {
            $creditBal = ($creditBal + $intBal);
        }
        $endBal = ($debitBal - $creditBal);

        $accRunningBal['trans'] = $trans;
        $accRunningBal['debitBal'] = $debitBal;
        $accRunningBal['creditBal'] = $creditBal;
        $accRunningBal['endBal'] = $endBal;

        return $accRunningBal;
    }
}

if (!function_exists('customerLedger2')) {
    function customerLedger2($customer, Carbon $from = null, Carbon $to = null)
    {
        $accRunningBal = [];

        /** get initial account balance as at date */
        $accOpBal = $customer->opening_balance;
        $accOpBalType = $customer->opening_balance_type;
        $accFirstTxDate = $customer->opening_balance_at ? $customer->opening_balance_at : \carbon($customer->opening_balance_at)->toDateString();

        $transactions = \App\Transaction::where('date', '<', $from->subDay()->toDateString())
            ->where('customer_id', $customer->id)->with('records')->get();

        $debitBal1 = $transactions
            ->whereIn('action', ['InvoiceCreation', 'PaymentCancel', 'ChequeBounced', 'ReturnedChequePaymentCancel'])->sum('amount');
        if ($accOpBalType == 'Debit') {
            $debitBal1 = ($accOpBal + $debitBal1);
        }

        $creditBal1 = $transactions
            ->whereIn('action', ['PaymentCreation', 'InvoiceCancel', 'SalesReturn', 'ChequeRealised', 'ManualChequeRegistered', 'ReturnedChequePayment'])->sum('amount');
        if ($accOpBalType == 'Credit') {
            $creditBal1 = ($accOpBal + $creditBal1);
        }
        $intBal = $debitBal1 - $creditBal1;
        $intBal2 = $debitBal1 - $creditBal1;

        if($debitBal1 > $creditBal1){
            $intBalType = 'Debit';
        }else{
            $intBalType = 'Credit';
        }
        $accRunningBal['intBal'] = $intBal;
        $accRunningBal['intBalType'] = $intBalType;
        /** END */

        /** get given date range trans and balances */
        $trans = $customer->journals;
        if ($from) {
            $trans = $trans->where('date', '>=', $from->toDateString());
        }
        if ($to) {
            $trans = $trans->where('date', '<=', $to->toDateString());
        }

        $trans->map(function ($tran) use (&$intBal2, $intBalType) {
            /*if ($tran->action == 'InvoiceCreation' || $tran->action == 'PaymentCancel'
                || $tran->action == 'InvoiceCancel' || $tran->action == 'ChequeBounced' && $intBalType == 'Debit') {
                $balance = $intBal2 + $tran->amount;
            } else {
                $balance = $intBal2 ? ($intBal2 - $tran->amount) : $tran->amount;
            }*/
            if ($tran->action == 'InvoiceCreation' || $tran->action == 'PaymentCancel'
                || $tran->action == 'ChequeBounced' || $tran->action == 'ReturnedChequePaymentCancel'
                && $intBalType == 'Debit') {
                $balance = $intBal2 + $tran->amount;
            } else {
                $balance = $intBal2 ? ($intBal2 - $tran->amount) : $tran->amount;
            }
            $intBal2 = $balance;
            $tran->balance = $balance;

            $tran->tran_type = $tran->txType->name;

            /** make description to each action */
            if($tran->action == 'InvoiceCreation'){
                $tran->tran_des_short = 'Sales In';
                $tran->tran_ref_no = $tran->transactionable && $tran->transactionable->order ? $tran->transactionable->order->ref : '';
                $tran->tran_ref_id = $tran->transactionable && $tran->transactionable->order ? $tran->transactionable->order->id : '';
                $tran->tran_ref_url = '/sales/order/';
            }else if($tran->action == 'PaymentCreation'){
                $tran->tran_des_short = 'Cash In';
                $tran->tran_ref_no = $tran->transactionable && $tran->transactionable->invoice ? $tran->transactionable->invoice->ref : '';
                $tran->tran_ref_id = $tran->transactionable && $tran->transactionable->invoice ? $tran->transactionable->invoice->id : '';
                $tran->tran_ref_url = '/sales/invoice/';
            }else if($tran->action == 'SalesReturn'){
                $tran->tran_des_short = 'Sales Return';
                $tran->tran_ref_no = $tran->transactionable ? $tran->transactionable->code : '';
                $tran->tran_ref_id = $tran->transactionable ? $tran->transactionable->id : '';
                $tran->tran_ref_url = '/sales/return/';
            }else if($tran->action == 'PaymentCancel'){
                $tran->tran_des_short = 'Payment Cancel';
                $tran->tran_ref_no = $tran->transactionable && $tran->transactionable->invoice ? $tran->transactionable->invoice->ref : '';
                $tran->tran_ref_id = $tran->transactionable && $tran->transactionable->invoice ? $tran->transactionable->invoice->id : '';
                $tran->tran_ref_url = '/sales/invoice/';
            }else if($tran->action == 'InvoiceCancel'){
                $tran->tran_des_short = 'Invoice Cancel';
                $tran->tran_ref_no = $tran->transactionable ? $tran->transactionable->ref : '';
                $tran->tran_ref_id = $tran->transactionable ? $tran->transactionable->id : '';
                $tran->tran_ref_url = '/sales/invoice/';
            }else if($tran->action == 'ChequeRealised'){
                $tran->tran_des_short = 'Cheque Realised';
                $tran->tran_ref_no = '';
                $tran->tran_ref_id = '';
                $tran->tran_ref_url = '';
            }else if($tran->action == 'ChequeBounced'){
                $tran->tran_des_short = 'Cheque Bounced';
                $tran->tran_ref_no = '';
                $tran->tran_ref_id = '';
                $tran->tran_ref_url = '';
            }else if($tran->action == 'ManualChequeRegistered'){
                $tran->tran_des_short = 'Manual Cheque Registered';
                $tran->tran_ref_no = '';
                $tran->tran_ref_id = '';
                $tran->tran_ref_url = '';
            }else if($tran->action == 'ReturnedChequePayment'){
                $tran->tran_des_short = 'Returned Cheque Payment';
                $tran->tran_ref_no = '';
                $tran->tran_ref_id = '';
                $tran->tran_ref_url = '';
            }else if($tran->action == 'ReturnedChequePaymentCancel'){
                $tran->tran_des_short = 'Returned Cheque Payment Canceled';
                $tran->tran_ref_no = '';
                $tran->tran_ref_id = '';
                $tran->tran_ref_url = '';
            }
            return $tran;
        });

        $debitBal = $trans->whereIn('action', ['InvoiceCreation', 'PaymentCancel', 'ChequeBounced', 'ReturnedChequePaymentCancel'])->sum('amount');
        if ($intBalType == 'Debit') {
            $debitBal = ($intBal + $debitBal);
        }
        $creditBal = $trans->whereIn('action', ['PaymentCreation', 'InvoiceCancel', 'SalesReturn', 'ChequeRealised', 'ManualChequeRegistered', 'ReturnedChequePayment'])->sum('amount');
        if ($intBalType == 'Credit') {
            $creditBal = ($intBal + $creditBal);
        }

        $endBal = $debitBal > $creditBal ? ($debitBal - $creditBal) : 0;

        $accRunningBal['trans'] = $trans;
        $accRunningBal['debitBal'] = $debitBal;
        $accRunningBal['creditBal'] = $creditBal;
        $accRunningBal['endBal'] = $endBal;

        return $accRunningBal;
    }
}

if (!function_exists('getCustomerTotalVisits')) {
    function getCustomerTotalVisits($customer)
    {
        $visits = [];

        $stats = \App\DailySaleCustomer::where('customer_id', $customer->id);

        $allocated = $stats->count();
        $visited = $stats->where('is_visited', 'Yes')->count();

        $visits['allocated'] = $allocated;
        $visits['visited'] = $visited;
        $visits['not_visited'] = ($allocated - $visited);

        return $visits;
    }
}

if (!function_exists('getPriceBooksLabel')) {
    function getPriceBooksLabel($companyId)
    {
        if($companyId == 'All'){
            $books = \App\PriceBook::where('category', 'Van Selling Price')
                ->pluck('name', 'id')->toArray();
        } else {
            $books = \App\PriceBook::where('company_id', $companyId)
                ->where('category', 'Van Selling Price')
                ->pluck('name', 'id')->toArray();
        }
        return $books;
    }
}

if (!function_exists('getProductPrices')) {
    function getProductPrices($priceBook, $product)
    {
        return \App\Price::where('price_book_id', $priceBook)->where('product_id', $product)->get();
    }
}

/**
 * Get product drop down data
 */
if (!function_exists('getFinishedGoods')) {
    function getFinishedGoods()
    {
        return \App\Product::where('type', 'Finished Good')->orderBy('name')->with('prices')->get();
    }
}

/**
 * get sold QTY by using order and product
 */
if (!function_exists('getProductSoldQty')) {
    function getProductSoldQty($order, $product)
    {
        $product = $order->products()->wherePivot('product_id', $product->product_id)->first();
        $qty = '';
        if($product)
        {
            $qty = $product->pivot->quantity;
        }
        return $qty;
    }
}

if (!function_exists('getProductQtyStats')) {
    function getProductQtyStats($allocation, $product)
    {
        $item = \App\DailySaleItem::where('daily_sale_id', $allocation->id)
            ->where('product_id', $product->product_id)->first();

        $qty = [];

        $qty['cf'] = $item->cf_qty != 0 ? $item->cf_qty : '';
        $qty['issued'] = $item->quantity != 0 ? $item->quantity : '';
        $qty['allocated'] = ($item->cf_qty + $item->quantity);
        $qty['sold'] = $item->sold_qty != 0 ? $item->sold_qty : '';
        $qty['replaced'] = $item->replaced_qty != 0 ? $item->replaced_qty : '';
        $qty['returned'] = $item->returned_qty != 0 ? $item->returned_qty : '';
        $qty['shortage'] = $item->shortage_qty != 0 ? $item->shortage_qty : '';
        $qty['damaged'] = $item->damaged_qty != 0 ? $item->damaged_qty : '';
        $qty['restored'] = $item->restored_qty != 0 ? $item->restored_qty : '';
        $qty['excess'] = $item->excess_qty != 0 ? $item->excess_qty : '';
        $qty['actual'] = $item->actual_stock;

        //$available = ($item->quantity + $item->cf_qty + $item->returned_qty) - ($item->sold_qty + $item->restored_qty + $item->replaced_qty + $item->shortage_qty + $item->damaged_qty);
        $available = ($item->quantity + $item->cf_qty + $item->returned_qty + $item->excess_qty) - ($item->sold_qty + $item->restored_qty + $item->replaced_qty + $item->shortage_qty);

        $qty['available'] = $available;

        return $qty;
    }
}

if (!function_exists('getOrderDetail')) {
    function getOrderDetail($customerId, $DailySalesId)
    {
        $order = \App\SalesOrder::where('customer_id', $customerId)->where('daily_sale_id', $DailySalesId)->first();
        return $order;
    }
}

if (!function_exists('getPaymentDetail')) {
    function getPaymentDetail($customerId, $DailySalesId)
    {
        $payment = \App\InvoicePayment::where('customer_id', $customerId)->where('daily_sale_id', $DailySalesId)->first();
        return $payment;
    }
}

/**
 * Get user drop down data
 */
if (!function_exists('userDropDown')) {
    function userDropDown()
    {
        return \App\User::get()->pluck('name', 'id')->toArray();
    }
}

if (!function_exists('chequeStatusDD')) {
    function chequeStatusDD()
    {
        return [
            'Not Realised' => 'Not Realised',
            'Deposited' => 'Deposited',
            'Realised' => 'Realised',
            'Bounced' => 'Bounced',
            'Canceled' => 'Canceled'
        ];
    }
}

if (!function_exists('chequeTypeDD')) {
    function chequeTypeDD()
    {
        return [
            'Own' => 'Own',
            'Third Party' => 'Third Party'
        ];
    }
}

if (!function_exists('chequeTransferred')) {
    function chequeTransferred()
    {
        return [
            'Yes' => 'Yes',
            'No' => 'No'
        ];
    }
}

/**
 * Get user drop down data
 */
if (!function_exists('getChequeDataByNo')) {
    function getChequeDataByNo($cheque): array
    {
        //TODO groupBy('cheque_no')
        if (is_string($cheque)) {
            $chequeQuery = chequeKeyToArray($cheque, 'query');
            $cheque = \App\ChequeInHand::where($chequeQuery)->first();
        }

        $chequeData = [];

        $chequeTotalEach = \App\ChequeInHand::where('cheque_no', $cheque->cheque_no)
            ->where('bank_id', $cheque->bank_id)
            ->sum('amount');
        $chequeData['date'] = $cheque ? $cheque->cheque_date : '';
        $chequeData['formattedDate'] = $cheque ? carbon($cheque->cheque_date)->format('F j, Y') : '';
        $chequeData['regDate'] = $cheque ? $cheque->registered_date : '';
        $chequeData['formattedRegDate'] = $cheque ? carbon($cheque->registered_date)->format('F j, Y') : '';
        $chequeData['chequeType'] = $cheque ? $cheque->cheque_type : '';
        $chequeData['bank'] = $cheque ? $cheque->bank->name : '';
        $chequeData['bankId'] = $cheque ? $cheque->bank->id : '';
        $chequeData['eachTotal'] = $chequeTotalEach;
        $chequeData['customerId'] = $cheque ? $cheque->customer->id : '';
        $chequeData['customer'] = $cheque ? $cheque->customer->display_name : '';
        $chequeData['customerData'] = $cheque ? $cheque->customer : '';
        $chequeData['status'] = $cheque ? $cheque->status : '';
        $chequeData['companyId'] = $cheque ? $cheque->company->id : '';
        $chequeData['companyName'] = $cheque ? $cheque->company->name : '';
        $chequeData['creditedTo'] = $cheque ? $cheque->credited_to : '';
        $chequeData['depositedTo'] = $cheque ? $cheque->deposited_to : '';
        $chequeData['transferredFrom'] = $cheque ? $cheque->transferred_from : '';
        $chequeData['transferredTo'] = $cheque ? $cheque->transferred_to : '';
        $chequeData['settled'] = $cheque ? $cheque->settled : '';
        $chequeData['bounced_date'] = $cheque ? $cheque->bounced_date : '';

        return $chequeData;
    }
}


if (!function_exists('recentTransfers')) {
    function recentTransfers()
    {
        if(isDirectorLevelStaff() || isAccountLevelStaff()){
            $trans = \App\Transfer::whereIn('sender', userCompanyIds(loggedUser()))
                ->where('status', 'Pending')
                ->orderBy('id', 'desc')->get();
        }else{
            $trans = \App\Transfer::where('transfer_by', auth()->id())
                ->where('status', 'Pending')
                ->orderBy('id', 'desc')->get();
        }
        return $trans;
    }
}

if (!function_exists('pendingTransfers')) {
    function pendingTransfers()
    {
        $trans = \App\Transfer::whereIn('receiver', userCompanyIds(loggedUser()))
            ->where('status', 'Pending')
            ->where('received_by', null)
            ->get();
        return $trans;
    }
}

if (!function_exists('draftedTransfers')) {
    function draftedTransfers()
    {
        $trans = \App\Transfer::where('transfer_by', auth()->id())
            ->where('status', 'Drafted')
            ->orderBy('id', 'desc')->get();
        return $trans;
    }
}

if (!function_exists('bankAccDropDown')) {
    function bankAccDropDown()
    {
        return \App\Account::where('account_type_id', 2)->where('id', '!=', 2)->pluck('name', 'id')->toArray();
    }
}

if (!function_exists('handedOverDropDown')) {
    function handedOverDropDown()
    {
        $staff = auth()->user()->staffs()->first();
        return \App\Staff::pluck('full_name', 'id')->toArray();
    }
}

if (!function_exists('getVisitCounts')) {
    function getVisitCounts($allocation, $reason)
    {
        $counts = \App\DailySaleCustomer::where('daily_sale_id', $allocation)->where('reason', 'LIKE', $reason)->count();
        return $counts;
    }
}

if (!function_exists('getVisitDetails')) {
    function getVisitDetails($allocation, $reason)
    {
        $customers = \App\DailySaleCustomer::where('daily_sale_id', $allocation)->where('reason', 'LIKE', $reason)->with('customer')->get();
        return $customers;
    }
}

if (!function_exists('pluckCompanyIds')) {
    function pluckCompanyIds()
    {
        return \App\Company::pluck('id')->toArray();
    }
}

if (!function_exists('cihAccountDropDown')) {
    function cihAccountDropDown()
    {
        return \App\Account::where('parent_account_id', 50)
            ->where('account_type_id', 19)->pluck('name', 'id')->toArray();
    }
}

if (!function_exists('arAccountDropDown')) {
    function arAccountDropDown()
    {
        return \App\Account::where('id', 3)->pluck('name', 'id')->toArray();
    }
}

if (!function_exists('cihAccountDropDownByCompany')) {
    function cihAccountDropDownByCompany($company)
    {
        return \App\Account::where('company_id', $company)->where('parent_account_id', 50)
            ->where('account_type_id', 19)->pluck('name', 'id')->toArray();
    }
}

if (!function_exists('getDamageQtySum')) {
    function getDamageQtySum($product, $company)
    {
        $damagedQty = DailySaleItem::where('product_id', $product->id)
            ->where(function ($query) use ($company) {
                $query->whereHas('dailySale', function ($q) use ($company) {
                    $q->where('company_id', $company->id);
                });
            })->sum('damaged_qty');
        return $damagedQty;
    }
}

if (!function_exists('getDamageQtySumAsAt')) {
    function getDamageQtySumAsAt($product, $company, $toDate)
    {
        $damagedQty = DailySaleItem::where('product_id', $product->id)
            ->where(function ($query) use ($company, $toDate) {
                $query->whereHas('dailySale', function ($q) use ($company, $toDate) {
                    $q->where('company_id', $company->id)->where('to_date', '<=', $toDate);
                });
            })->with('dailySale')->get();

        $data['damagedItems'] = $damagedQty->where('damaged_qty', '>', 0);
        $data['damagedQty'] = $damagedQty->sum('damaged_qty');
        return $data;
    }
}

if (!function_exists('getRepIdFromAuth')) {
    function getRepIdFromAuth($user)
    {
        $staff = Staff::where('user_id', $user)->first();
        $rep = Rep::where('staff_id', $staff->id)->first();
        return $rep->id;
    }
}

if (!function_exists('getSoldQty')) {
    function getSoldQty(DailySale $allocation, DailySaleItem $item)
    {
        $orders = \App\SalesOrder::where('daily_sale_id', $allocation->id)
            ->whereIn('status', ['Open', 'Closed'])->get();
        $products = $orders->pluck('products')->collapse();
        $soldQty = $products->where('id', $item->product_id)->sum('pivot.quantity');
        return $soldQty;
    }
}

if (!function_exists('repCashAccountsDropDown')) {
    function repCashAccountsDropDown()
    {
        return \App\Account::where('accountable_type', 'App\Rep')
            ->where('account_type_id', 1)
            ->pluck('name', 'id')
            ->toArray();
    }
}

if (!function_exists('repChequeAccountsDropDown')) {
    function repChequeAccountsDropDown()
    {
        return \App\Account::where('accountable_type', 'App\Rep')
            ->where('account_type_id', 19)
            ->pluck('name', 'id')
            ->toArray();
    }
}

if (!function_exists('CashierCashAccountsDropDown')) {
    function CashierCashAccountsDropDown()
    {
        return \App\Account::where('accountable_type', 'App\Company')
            ->where('account_type_id', 1)
            ->pluck('name', 'id')
            ->toArray();
    }
}

if (!function_exists('CashierChequeAccountsDropDown')) {
    function CashierChequeAccountsDropDown()
    {
        return \App\Account::where('accountable_type', 'App\Company')
            ->where('account_type_id', 19)
            ->pluck('name', 'id')
            ->toArray();
    }
}

if (!function_exists('accOpeningBalance')) {
    function accOpeningBalance($account, $from)
    {
        $accOpeningBalance = [];

        $trans = \App\TransactionRecord::where('date', '<', $from)
            ->where('account_id', $account->id)->get();
        $debit = $trans->where('type', 'Debit')->sum('amount');
        $credit = $trans->where('type', 'Credit')->sum('amount');

        if($debit > $credit){
            $type = 'Debit';
        }else{
            $type = 'Credit';
        }

        $balance = (abs($debit - $credit));

        $accOpeningBalance['type'] = $type;
        $accOpeningBalance['opening'] = $balance;

        return $accOpeningBalance;
    }
}

if (!function_exists('balanceTypeDropDown')) {
    function balanceTypeDropDown()
    {
        return [
            'All' => 'All',
            'WithBalance' => 'With Balance',
            'ZeroBalance' => 'Zero Balance'
        ];
    }
}

if (!function_exists('availableStockAsDate')) {
    function availableStockAsDate($stock, $asAt)
    {
        $histories = \App\StockHistory::where('stock_id', $stock->id)->whereDate('trans_date', '<=', $asAt)->get();
        $stockIn = $histories->where('transaction', 'In')->sum('quantity');
        $stockOut = $histories->where('transaction', 'Out')->sum('quantity');
        $stockCal = ($stockIn - $stockOut);
        $available = $stockCal <= 0 ? 0 : $stockCal;

        $data['inStock'] = $stockIn;
        $data['outStock'] = $stockOut;
        $data['availableStock'] = $available;

        return $data;
    }
}

if (!function_exists('accountsDropDown')) {
    function accountsDropDown()
    {
        return \App\Account::pluck('name', 'id')->toArray();
    }
}

if (!function_exists('accountsByCompanyDropDown')) {
    function accountsByCompanyDropDown()
    {
        return \App\Account::whereIn('company_id', userCompanyIds(loggedUser()))
            ->get()
            ->pluck('name', 'id')
            ->toArray();
    }
}

if (!function_exists('transTypeDropDown')) {
    function transTypeDropDown()
    {
        return \App\TransactionType::pluck('name', 'id')->toArray();
    }
}

if (!function_exists('accBalanceByDate')) {
    function accBalanceByDate($company, $account, $fromDate, $toDate)
    {
        $accBalance = [];

        /** get initial account balance as at date */
        $transRecordsOp = \App\TransactionRecord::where('date', '<=', carbon($fromDate)->subDay()->toDateString())
            ->where('account_id', $account->id)
            ->where(function ($query) use ($company) {
                $query->whereHas('transaction', function ($q) use ($company) {
                    $q->where('company_id', $company->id);
                });
            })->get();

        $debitBal1 = $transRecordsOp->where('type', 'Debit')->sum('amount');
        $creditBal1 = $transRecordsOp->where('type', 'Credit')->sum('amount');

        $intBal = $debitBal1 - $creditBal1;
        $intBal2 = $debitBal1 - $creditBal1;

        $intBalType = '';
        if($debitBal1 > $creditBal1){
            $intBalType = 'Debit';
        }
        elseif($debitBal1 < $creditBal1){
            $intBalType = 'Credit';
        }

        $accBalance['intBal'] = $intBal;
        $accBalance['intBalView'] = abs($intBal);
        $accBalance['intBalType'] = $intBalType;
        /** END */

        /** get given date range trans and balances */
        $transRecords = \App\TransactionRecord::where('date', '>=', $fromDate)
            ->where('date', '<=', $toDate)
            ->where('account_id', $account->id)
            ->where(function ($query) use ($company) {
                $query->whereHas('transaction', function ($q) use ($company) {
                    $q->where('company_id', $company->id);
                });
            })->with('transaction', 'transaction.txType')->get();

        $transRecords->map(function ($tran) use (&$intBal2, $intBalType) {
            if ($tran->type == 'Debit'  && $intBalType == 'Debit') {
                $balance = $intBal2 + $tran->amount;
            }else{
                $balance = $intBal2 ? ($intBal2 - $tran->amount) : 0;
            }
            $intBal2 = $balance;
            $tran->balance = $balance;
            $tran->balanceView = abs($balance);
            return $tran;
        });

        $debitBal = $transRecords->where('type', 'Debit')->sum('amount');
        if ($intBalType == 'Debit') {
            $debitBal = ($intBal + $debitBal);
        }
        $creditBal = $transRecords->where('type', 'Credit')->sum('amount');
        if ($intBalType == 'Credit') {
            $creditBal = $intBal + $creditBal;
        }
        $endBal = $debitBal > abs($creditBal) ? ($debitBal - abs($creditBal)) : (abs($creditBal) - $debitBal);

        $accBalance['debitBal'] = $debitBal;
        $accBalance['creditBal'] = abs($creditBal);
        $accBalance['endBal'] = abs($endBal);

        return $accBalance;
    }

    if (!function_exists('driverDropDown')) {
        function driverDropDown()
        {
            return \App\Staff::where('designation_id', 12)->pluck('short_name', 'id')->toArray();
        }
    }

    if (!function_exists('helperDropDown')) {
        function helperDropDown()
        {
            return \App\Staff::where('designation_id', 15)->pluck('short_name', 'id')->toArray();
        }
    }

    /*if (!function_exists('getItemPurchasePrice')) {
        function getItemPurchasePrice($company, $store, $product)
        {
            $priceBook = PriceBook::where('category', 'Production To Store')
                ->where('company_id', $company)
                ->where('related_to_id', $store)
                ->where('related_to_type', 'App\Store')
                ->first();
            if($priceBook){
                $price = $priceBook->prices()->where('price_book_id', $priceBook->id)
                    ->where('product_id', $product->pivot->product_id)
                    ->where('range_start_from', '<=', $product->pivot->quantity)
                    ->where('range_end_to', '>=', $product->pivot->quantity)
                    ->first();
                if($price){
                    $purchasePrice = $price->price;
                }else{
                    $purchasePrice = $product->buying_price;
                }
            }else{
                $purchasePrice = $product->buying_price;
            }
            return $purchasePrice;
        }
    }*/

    if (!function_exists('getItemPurchasePriceFromPUnit')) {
        function getItemPurchasePriceFromPUnit($company, $productionUnit, $product)
        {
            $priceBook = PriceBook::where('category', 'Production To Store')
                ->where('company_id', $company)
                ->where('related_to_id', $productionUnit)
                ->where('related_to_type', 'App\ProductionUnit')
                ->first();
            if($priceBook){
                $price = $priceBook->prices()->where('price_book_id', $priceBook->id)
                    ->where('product_id', $product->pivot->product_id)
                    ->where('range_start_from', '<=', $product->pivot->quantity)
                    ->where('range_end_to', '>=', $product->pivot->quantity)
                    ->first();
                if($price){
                    $purchasePrice = $price->price;
                }else{
                    $purchasePrice = $product->buying_price;
                }
            }else{
                $purchasePrice = $product->buying_price;
            }
            return $purchasePrice;
        }
    }

    if (!function_exists('getItemPurchasePriceFromStore')) {
        function getItemPurchasePriceFromStore($company, $store, $product)
        {
            $priceBook = PriceBook::where('category', 'Store To Store')
                ->where('company_id', $company)
                ->where('related_to_id', $store)
                ->where('related_to_type', 'App\Store')
                ->first();
            if($priceBook){
                $price = $priceBook->prices()->where('price_book_id', $priceBook->id)
                    ->where('product_id', $product->pivot->product_id)
                    ->where('range_start_from', '<=', $product->pivot->quantity)
                    ->where('range_end_to', '>=', $product->pivot->quantity)
                    ->first();
                if($price){
                    $purchasePrice = $price->price;
                }else{
                    $purchasePrice = $product->buying_price;
                }
            }else{
                $purchasePrice = $product->buying_price;
            }
            return $purchasePrice;
        }
    }

    if (!function_exists('getShopItemDefaultQty')) {
        function getShopItemDefaultQty($shop, $item)
        {
            $product = $shop->products()->wherePivot('product_id', $item->product_id)->first();
            return $product->pivot->default_qty;
        }
    }

    if (!function_exists('getItemShopSellingPrice')) {
        function getItemShopSellingPrice($company, $shop, $product)
        {
            $priceBook = PriceBook::where('category', 'Shop Selling Price')
                ->where('company_id', $company->id)
                ->where('related_to_id', $shop->id)
                ->where('related_to_type', 'App\SalesLocation')
                ->first();
            if($priceBook){
                $price = $priceBook->prices()->where('price_book_id', $priceBook->id)
                    ->where('product_id', $product->id)
                    ->first();
                if($price){
                    $sellingPrice = $price->price;
                }else{
                    $sellingPrice = $product->retail_price;
                }
            }else{
                $sellingPrice = $product->retail_price;
            }
            return $sellingPrice;
        }
    }

    if (!function_exists('distance')){
        function distance($lat1, $lon1, $lat2, $lon2, $unit = "K") {
            if (($lat1 == $lat2) && ($lon1 == $lon2)) {
                return 0;
            }
            else {
                $theta = $lon1 - $lon2;
                $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
                $dist = acos($dist);
                $dist = rad2deg($dist);
                $miles = $dist * 60 * 1.1515;
                $unit = strtoupper($unit);

                if ($unit == "K") {
                    return ($miles * 1.609344);
                } else if ($unit == "N") {
                    return ($miles * 0.8684);
                } else {
                    return $miles;
                }
            }
        }
    }

    if (!function_exists('isNextDayAllocationAvailable')) {
        function isNextDayAllocationAvailable($allocation)
        {
            $salesVan = $allocation->salesLocation;
            $getNextAllocation = DailySale::where('sales_location_id', $salesVan->id)
                ->where('from_date', '>', $allocation->to_date)
                ->get();
            if(count($getNextAllocation)) return true;
        }
    }

    if (!function_exists('getDayBookData')) {
        function getDayBookData($rep, $date)
        {
            $data = [];

            $staff = $rep->staff;
            $user = $staff->user;

            /** get cash sales */
            $cashOrders = \App\SalesOrder::where('rep_id', $rep->id)
                ->whereDate('order_date', $date)
                ->whereIn('status', ['Open', 'Closed'])
                ->get();
            $cashPayments = $cashOrders->pluck('payments')->collapse();
            $cashSales = $cashPayments->sum('payment');

            /** get credit sales */
            $creditOrders = \App\SalesOrder::where('rep_id', $rep->id)
                ->where('order_date', $date)
                ->where('status', 'Open')
                ->where('is_credit_sales', 'Yes')
                ->with('customer')->get();
            $creditSales = $creditOrders->sum('total');

            /** get cash collection */
            $payments = \App\InvoicePayment::where('prepared_by', $user->id)
                ->where('payment_date', $date)
                ->where('status', 'Paid')
                ->whereHas('order', function ($q) use ($date) {
                    $q->whereDate('order_date', '<', $date);
                })
                ->with('order', 'customer')
                ->get();
            $collection = $payments->sum('payment');

            /** get expenses */
            $expenses = \App\SalesExpense::where('prepared_by', $user->id)
                ->where('expense_date', $date)
                ->with('type', 'preparedBy')->get();
            $totalExpenses = $expenses->sum('amount');

            /** get transfers */
            $accountIds = Account::where('accountable_id', $rep->id)
                ->where('accountable_type', 'App\Rep')
                ->pluck('id')->toArray();

            $transfers = \App\TransactionRecord::where('date', $date)
                ->whereIn('account_id', $accountIds)
                ->whereHas('transaction', function ($q) {
                    $q->where('action', 'Transfer')->where('transactionable_type', 'App\SalesHandover');
                })->with('transaction')->get();
            $totalTransfers = $transfers->sum('amount');

            /** get excess */
            $excesses = \App\SalesHandoverExcess::where('rep_id', $rep->id)
                ->where('date', $date)->get();
            $totalExcesses = $excesses->sum('amount');

            /** get shortage */
            $shortages = \App\SalesHandoverShortage::where('rep_id', $rep->id)
                ->where('date', $date)->get();
            $totalShortages = $shortages->sum('amount');

            /** get sales returns | as Credit */
            $returns = \App\InvoicePayment::where('prepared_by', $user->id)
                ->where('payment_date', $date)
                ->where('payment_mode', 'Customer Credit')
                ->where('status', 'Paid')
                ->with('order', 'customer')
                ->get();
            $totalReturns = $returns->sum('payment');

            /** get debit and credit totals */
            $debitTotal = ($creditSales + $totalExpenses + $totalTransfers + $totalShortages + $totalReturns);
            $creditTotal = ($cashSales + $creditSales + $collection + $totalExcesses);

            $data['rep'] = $rep->toArray();
            $data['fromRange'] = $date;

            $data['cashOrders'] = $cashOrders;
            $data['cashSales'] = $cashSales;

            $data['creditOrders'] = $creditOrders;
            $data['creditSales'] = $creditSales;

            $data['payments'] = $payments;
            $data['collection'] = $collection;

            $data['returns'] = $returns;
            $data['totalReturns'] = $totalReturns;

            $data['expenses'] = $expenses;
            $data['totalExpenses'] = $totalExpenses;

            $data['transfers'] = $transfers;
            $data['totalTransfers'] = $totalTransfers;

            $data['excesses'] = $excesses;
            $data['totalExcesses'] = $totalExcesses;

            $data['shortages'] = $shortages;
            $data['totalShortages'] = $totalShortages;

            $data['debitTotal'] = $debitTotal;
            $data['creditTotal'] = $creditTotal;

            return $data;
        }
    }

    if (!function_exists('returnedChequeBalance')) {
        function returnedChequeBalance($cheque)
        {
            /** get cheques in hand total */
            $chequesInHand = \App\ChequeInHand::where('cheque_no', $cheque)->get();
            $chequeTotal = $chequesInHand->sum('amount');

            $chequePayments = \App\ChequePayment::where('cheque', $cheque)->get();
            $totalPayments = $chequePayments->sum('payment');

            if($totalPayments == $chequeTotal){
                if($chequesInHand){
                    $chequesInHand->each(function (\App\ChequeInHand $chequeInHand) {
                        $chequeInHand->setAttribute('settled', 'Yes');
                        $chequeInHand->save();
                    });
                }
            }else{
                return false;
            }
        }
    }

    if (!function_exists('getAllocationsByCompany')) {
        function getAllocationsByCompany()
        {
            $allocations = DailySale::whereIn('status', ['Progress','Completed'])
                ->whereIn('company_id', userCompanyIds(loggedUser()))
                ->with('route', 'rep')->orderBy('id', 'decs')->get();
            return $allocations;
        }
    }

    if (!function_exists('checkMonthPassed')) {
        function checkMonthPassed()
        {
            $month = carbon()->now()->month;
            return $month;
        }
    }

    if (!function_exists('getProductSellingPrice')) {
        function getProductSellingPrice($allocation, $product, $excessQty)
        {
            $rep = $allocation->rep;
            $company = $allocation->company;
            $productData = \App\Product::where('id', $product)->first();
            $priceBook = PriceBook::whereType('Selling Price')
                ->whereCategory('Van Selling Price')
                ->whereCompanyId($company->id)
                ->whereRelatedToId($rep->id)
                ->whereRelatedToType('App\Rep')
                ->with('prices')
                ->first();
            if($priceBook){
                $price = \App\Price::where('price_book_id', $priceBook->id)
                    ->where('product_id', $product)
                    ->first();
                if($price){
                    $sellingPrice = $price->price;
                }else{
                    $sellingPrice = $productData->distribution_price;
                }
            }else{
                $sellingPrice = $productData->distribution_price;
            }
            return $sellingPrice;
        }
    }

    if (!function_exists('checkAvailableCommission')) {
        function checkAvailableCommission($repId, $year, $month)
        {
            $commission = \App\SalesCommission::where('rep_id', $repId)
                ->where('year', $year)
                ->where('month', $month)
                ->first();
            if(isset($commission)){
                return true;
            }else{
                return false;
            }
        }
    }

    if (!function_exists('commissionData')) {
        function commissionData($repId, $year, $month)
        {
            $commission = \App\SalesCommission::where('rep_id', $repId)
                ->where('year', $year)
                ->where('month', $month)
                ->first();
            return $commission;
        }
    }

    if (!function_exists('thirdPartyChequesDropDown')) {
        function thirdPartyChequesDropDown()
        {
            $cheques = \App\ChequeInHand::whereIn('company_id', userCompanyIds(loggedUser()))
                ->where('status', 'Not Realised')
                ->where('is_transferred', 'Yes')
                ->get()->groupBy('cheque_no');
            return $cheques;
        }
    }

    if (!function_exists('getDriverData')) {
        function getDriverData($driverId)
        {
            $driver = Staff::where('id', $driverId)
                ->first();
            return $driver;
        }
    }

    if (!function_exists('getDriversWorkingDay')) {
        function getDriversWorkingDay($rep, $driverId, $startDate, $endDate)
        {
            $allocations = DailySale::where('rep_id', $rep->id)
                ->where('driver_id', $driverId)
                ->whereBetween('to_date', [$startDate, $endDate])
                ->whereIn('status', ['Progress','Completed'])
                ->count();

            return $allocations;
        }
    }

    if (!function_exists('getDriversWorkingDayAlone')) {
        function getDriversWorkingDayAlone($rep, $driverId, $startDate, $endDate)
        {
            $allocations = DailySale::where('rep_id', $rep->id)
                ->where('driver_id', $driverId)
                ->where('labour_id', null)
                ->whereBetween('to_date', [$startDate, $endDate])
                ->whereIn('status', ['Progress','Completed'])
                ->count();

            return $allocations;
        }
    }

    if (!function_exists('getLabourData')) {
        function getLabourData($labourId)
        {
            $labour = Staff::where('id', $labourId)
                ->first();
            return $labour;
        }
    }

    if (!function_exists('getLaboursWorkingDay')) {
        function getLaboursWorkingDay($rep, $labourId, $startDate, $endDate)
        {
            $allocations = DailySale::where('rep_id', $rep->id)
                ->where('labour_id', $labourId)
                ->whereBetween('to_date', [$startDate, $endDate])
                ->whereIn('status', ['Progress','Completed'])
                ->count();

            return $allocations;
        }
    }

    if (!function_exists('awardedDriverCommission')) {
        function awardedDriverCommission($rep, $driverId, $startDate, $endDate, $totalWorkingDays, $eligibleCommission)
        {
            $allocations = DailySale::where('rep_id', $rep->id)
                ->where('driver_id', $driverId)
                ->whereBetween('to_date', [$startDate, $endDate])
                ->whereIn('status', ['Progress','Completed'])
                ->count();

            $driverCommission = (($eligibleCommission * $allocations) / $totalWorkingDays);
            return $driverCommission;
        }
    }

    if (!function_exists('awardedLabourCommission')) {
        function awardedLabourCommission($rep, $labourId, $startDate, $endDate, $totalWorkingDays, $eligibleCommission)
        {
            $allocations = DailySale::where('rep_id', $rep->id)
                ->where('labour_id', $labourId)
                ->whereBetween('to_date', [$startDate, $endDate])
                ->whereIn('status', ['Progress','Completed'])
                ->count();

            $labourCommission = (($eligibleCommission * $allocations) / $totalWorkingDays);
            return $labourCommission;
        }
    }

    if (!function_exists('awardedDriversCommission')) {
        function awardedDriversCommission($rep, $driversId, $startDate, $endDate, $totalWorkingDays, $eligibleCommission)
        {
            $allocations = DailySale::where('rep_id', $rep->id)
                ->whereIn('driver_id', $driversId)
                ->whereBetween('to_date', [$startDate, $endDate])
                ->whereIn('status', ['Progress','Completed'])
                ->count();

            if($totalWorkingDays > 0){
                $driverCommission = (($eligibleCommission * $allocations) / $totalWorkingDays);
            }else{
                $driverCommission = 0;
            }
            return $driverCommission;
        }
    }

    if (!function_exists('awardedLaboursCommission')) {
        function awardedLaboursCommission($rep, $laboursId, $startDate, $endDate, $totalWorkingDays, $eligibleCommission)
        {
            $allocations = DailySale::where('rep_id', $rep->id)
                ->whereIn('labour_id', $laboursId)
                ->whereBetween('to_date', [$startDate, $endDate])
                ->whereIn('status', ['Progress','Completed'])
                ->count();

            if($totalWorkingDays > 0){
                $labourCommission = (($eligibleCommission * $allocations) / $totalWorkingDays);
            }else{
                $labourCommission = 0;
            }
            return $labourCommission;
        }
    }

    if (!function_exists('returnStoreDropDown')) {
        function returnStoreDropDown()
        {
            return \App\Store::where('type', 'Return')
                ->where('storeable_type', 'App\ProductionUnit')
                ->get()->pluck('name', 'id')->toArray();
        }
    }

    if (!function_exists('getLastPurchasePrice')) {
        function getLastPurchasePrice($product)
        {
            $price = \App\GrnItem::where('product_id', $product)
                ->orderBy('id', 'desc')
                ->first();
            if($price){
                $rate = $price->rate;
            }else{
                $rate = \App\Product::where('id', $product)->first()->buying_price;
            }
            return $rate;
        }
    }

    if (!function_exists('allocationDetails')) {
        function allocationDetails($allocation)
        {
            $orders = $allocation->orders->whereIn('status', ['Open', 'Closed']);
            $total = $orders->sum('total');
            $orderIds = $orders->pluck('id');
            $payments = InvoicePayment::whereIn('sales_order_id', $orderIds)
                ->where('daily_sale_id', $allocation->id)
                ->get();

            $expenses = \App\SalesExpense::where('daily_sale_id', $allocation->id)
                ->sum('amount');

            $returns = \App\SalesReturnItem::where('daily_sale_id', $allocation->id)
                ->sum('returned_amount');

            $received = $payments->where('status', 'Paid')->sum('payment');
            $cashReceived = $payments->where('payment_mode', 'Cash')->where('status', 'Paid')->sum('payment');
            $chequeReceived = $payments->where('payment_mode', 'Cheque')->where('status', 'Paid')->sum('payment');

            $balance = ($total - $received);

            $allocationDetail['total'] = $total;
            $allocationDetail['received'] = $received;
            $allocationDetail['cash_received'] = $cashReceived;
            $allocationDetail['cheque_received'] = $chequeReceived;
            $allocationDetail['balance'] = $balance;
            $allocationDetail['expenses'] = $expenses;
            $allocationDetail['returns'] = $returns;

            $oldCollections = InvoicePayment::where('status', 'Paid')->where('daily_sale_id', $allocation->id)
                ->whereBetween('payment_date', [$allocation->from_date, $allocation->to_date])
                ->whereHas('order', function ($q) use ($allocation) {
                    $q->whereDate('order_date', '<', $allocation->from_date);
                })
                ->with(['order', 'customer'])->get();

            $oldReceived = $oldCollections->sum('payment');

            $oldCashReceived = $oldCollections->where('payment_mode', 'Cash')->where('status', 'Paid')->sum('payment');
            $oldChequeReceived = $oldCollections->where('payment_mode', 'Cheque')->where('status', 'Paid')->sum('payment');

            $allocationDetail['old_received'] = $oldReceived;
            $allocationDetail['old_cash_received'] = $oldCashReceived;
            $allocationDetail['old_cheque_received'] = $oldChequeReceived;

            return $allocationDetail;
        }
    }

    if(!function_exists('debtorBalanceAgeAnalysis')){
        function debtorBalanceAgeAnalysis($customer)
        {
            $orderIds = \App\SalesOrder::where('customer_id', $customer->id)
                ->where('status', '!=', 'Canceled')->pluck('id');
            $invoice = \App\Invoice::whereIn('sales_order_id', $orderIds)
                ->whereIn('status', ['Open', 'Partially Paid'])->orderBy('id', 'asc')->first();
            $balanceDays = 0;
            if($invoice){
                if(invOutstanding($invoice)['balance'] != 0) {
                    $invoiceDate = carbon()->parse($invoice->invoice_date);
                    $balanceDays = $invoiceDate->diffInDays(carbon()->now());
                }
            }
            return $balanceDays;
        }
    }

}

if (!function_exists('groupByCallbackForCheque')) {
    function groupByCallbackForCheque($collection)
    {
        return $collection->groupBy(function ($item) {
            return $item['cheque_no'] . '___' . $item['bank_id'];
        });
    }
}

if (!function_exists('chequeKeyToArray')) {
    /**
     * @param $key
     * @return array
     */
    function chequeKeyToArray($key, $type = false): array
    {
        [$cheque_no, $bank_id] = explode('___', $key);

        if ($type === 'query') {
            return [
                ['cheque_no', '=', $cheque_no],
                ['bank_id', '=', $bank_id],
            ];
        }

        if ($type) return [$cheque_no, $bank_id];

        return compact('cheque_no', 'bank_id');
    }
}