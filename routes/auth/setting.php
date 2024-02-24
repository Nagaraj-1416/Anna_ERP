<?php
Route::group(['prefix' => '/setting', 'namespace' => 'Setting'], function ($routes) {
    $routes->get('', 'SettingController@index')->name('setting.index');
    /** companies related routes */
    $routes->group(['prefix' => '/company'], function ($routes) {
        $routes->get('', 'CompanyController@index')->name('setting.company.index');
        $routes->post('/data', 'CompanyController@dataTableData')->name('setting.company.table.data');
        $routes->get('/create', 'CompanyController@create')->name('setting.company.create');
        $routes->post('/', 'CompanyController@store')->name('setting.company.store');
        $routes->get('search/{q?}', 'CompanyController@search')->name('setting.company.search');
        $routes->group(['prefix' => '{company}'], function ($routes) {
            $routes->get('/', 'CompanyController@show')->name('setting.company.show');
            $routes->get('/edit', 'CompanyController@edit')->name('setting.company.edit');
            $routes->patch('/', 'CompanyController@update')->name('setting.company.update');
            $routes->delete('/', 'CompanyController@delete')->name('setting.company.delete');
            $routes->post('/assign-staff', 'CompanyController@assignStaff')->name('setting.company.assign.staff');
            $routes->delete('/remove-staff/{staff}', 'CompanyController@removeStaff')->name('setting.company.staff.remove');
            $routes->get('search/staff/{q?}', 'CompanyController@searchStaff')->name('setting.company.staff.search');
            $routes->get('/get/company/logo', 'CompanyController@getLogo')->name('setting.company.logo');
            $routes->get('/remove/company/logo', 'ProfileController@removeLogo')->name('setting.company.logo.remove');
        });
    });

    /** departments related routes */
    $routes->group(['prefix' => '/department'], function ($routes) {
        $routes->get('', 'DepartmentController@index')->name('setting.department.index');
        $routes->post('/data', 'DepartmentController@dataTableData')->name('setting.department.table.data');
        $routes->get('/create', 'DepartmentController@create')->name('setting.department.create');
        $routes->post('/', 'DepartmentController@store')->name('setting.department.store');
        $routes->group(['prefix' => '{department}'], function ($routes) {
            $routes->get('/', 'DepartmentController@show')->name('setting.department.show');
            $routes->get('/edit', 'DepartmentController@edit')->name('setting.department.edit');
            $routes->patch('/', 'DepartmentController@update')->name('setting.department.update');
            $routes->delete('/', 'DepartmentController@delete')->name('setting.department.delete');
            $routes->post('/assign-staff', 'DepartmentController@assignStaff')->name('setting.department.assign.staff');
            $routes->delete('/remove-staff/{staff}', 'DepartmentController@removeStaff')->name('setting.department.staff.remove');
            $routes->get('search/staff/{q?}', 'DepartmentController@searchStaff')->name('setting.department.staff.search');
        });
    });

    /** store related routes */
    $routes->group(['prefix' => '/store'], function ($routes) {
        $routes->get('', 'StoreController@index')->name('setting.store.index');
        $routes->post('/data', 'StoreController@dataTableData')->name('setting.store.table.data');
        $routes->get('/create', 'StoreController@create')->name('setting.store.create');
        $routes->post('/', 'StoreController@store')->name('setting.store.store');
        $routes->get('/search/{q?}', 'StoreController@search')->name('setting.store.search');
        $routes->group(['prefix' => '{store}'], function ($routes) {
            $routes->get('/', 'StoreController@show')->name('setting.store.show');
            $routes->get('/edit', 'StoreController@edit')->name('setting.store.edit');
            $routes->patch('/', 'StoreController@update')->name('setting.store.update');
            $routes->delete('/', 'StoreController@delete')->name('setting.store.delete');
            $routes->post('/assign-staff', 'StoreController@assignStaff')->name('setting.store.assign.staff');
            $routes->delete('/remove-staff/{staff}', 'StoreController@removeStaff')->name('setting.store.staff.remove');
            $routes->get('search/staff/{q?}', 'StoreController@searchStaff')->name('setting.store.staff.search');
        });
    });

    /** sales locations related routes */
    $routes->group(['prefix' => '/sales-location'], function ($routes) {
        $routes->get('', 'SalesLocationController@index')->name('setting.sales.location.index');
        $routes->post('/data', 'SalesLocationController@dataTableData')->name('setting.sales.location.table.data');
        $routes->get('/search/{type}/{q?}', 'SalesLocationController@searchWithType')->name('setting.sales.location.search.type');
        $routes->get('/create', 'SalesLocationController@create')->name('setting.sales.location.create');
        $routes->post('/', 'SalesLocationController@store')->name('setting.sales.location.store');
        $routes->get('/search/{q?}', 'SalesLocationController@search')->name('setting.sales.location.search');
        $routes->group(['prefix' => '{salesLocation}'], function ($routes) {
            $routes->get('/', 'SalesLocationController@show')->name('setting.sales.location.show');
            $routes->get('/edit', 'SalesLocationController@edit')->name('setting.sales.location.edit');
            $routes->patch('/', 'SalesLocationController@update')->name('setting.sales.location.update');
            $routes->delete('/', 'SalesLocationController@delete')->name('setting.sales.location.delete');
            $routes->post('/assign-staff', 'SalesLocationController@assignStaff')->name('setting.sales.location.assign.staff');
            $routes->delete('/remove-staff/{staff}', 'SalesLocationController@removeStaff')->name('setting.sales.location.staff.remove');
            $routes->get('search/staff/{q?}', 'SalesLocationController@searchStaff')->name('setting.sales.location.staff.search');
            $routes->post('/assign-product', 'SalesLocationController@assignProduct')->name('setting.sales.location.assign.product');
        });
    });

    /** business types related routes */
    $routes->group(['prefix' => '/business-type'], function ($routes) {
        $routes->get('', 'BusinessTypeController@index')->name('setting.business.type.index');
        $routes->post('/data', 'BusinessTypeController@dataTableData')->name('setting.business.type.table.data');
        $routes->get('/create', 'BusinessTypeController@create')->name('setting.business.type.create');
        $routes->post('/', 'BusinessTypeController@store')->name('setting.business.type.store');
        $routes->get('/search/{q?}', 'BusinessTypeController@search')->name('setting.business.type.search');
        $routes->group(['prefix' => '{businessType}'], function ($routes) {
            $routes->get('/', 'BusinessTypeController@show')->name('setting.business.type.show');
            $routes->get('/edit', 'BusinessTypeController@edit')->name('setting.business.type.edit');
            $routes->patch('/', 'BusinessTypeController@update')->name('setting.business.type.update');
            $routes->delete('/', 'BusinessTypeController@delete')->name('setting.business.type.delete');
        });
    });

    /** products related routes */
    $routes->group(['prefix' => '/product'], function ($routes) {
        $routes->get('', 'ProductController@index')->name('setting.product.index');
        $routes->post('/data', 'ProductController@dataTableData')->name('setting.product.table.data');
        $routes->get('/create', 'ProductController@create')->name('setting.product.create');
        $routes->post('/', 'ProductController@store')->name('setting.product.store');
        $routes->get('/export', 'ProductController@export')->name('setting.product.export');
        $routes->get('type/{type}/search/{q?}', 'ProductController@search')->name('setting.product.search');
        $routes->get('/search/{ids?}/{q?}', 'ProductController@searchSalesProduct')->name('setting.sales.product.search');
        $routes->group(['prefix' => 'category', 'as' => 'setting.product.category.'], function ($routes) {
            $routes->get('/', 'ProductCategoryController@index')->name('index');
            $routes->post('/data', 'ProductCategoryController@dataTableData')->name('table.data');
            $routes->get('/create', 'ProductCategoryController@create')->name('create');
            $routes->post('/', 'ProductCategoryController@store')->name('store');
            $routes->get('/search/{q?}', 'ProductCategoryController@search')->name('search');

            $routes->group(['prefix' => '{category}'], function ($routes) {
                $routes->get('/', 'ProductCategoryController@show')->name('show');
                $routes->get('/edit', 'ProductCategoryController@edit')->name('edit');
                $routes->patch('/', 'ProductCategoryController@update')->name('update');
                $routes->delete('/', 'ProductCategoryController@delete')->name('delete');
            });
        });
        $routes->group(['prefix' => '{product}'], function ($routes) {
            $routes->get('/', 'ProductController@show')->name('setting.product.show');
            $routes->get('/last-purchased-prices', 'ProductController@lastPurchasedPrices')->name('setting.product.last.purchased.prices');
            $routes->get('/edit', 'ProductController@edit')->name('setting.product.edit');
            $routes->patch('/', 'ProductController@update')->name('setting.product.update');
            $routes->delete('/', 'ProductController@delete')->name('setting.product.delete');
            $routes->post('/upload/image', 'ProductController@uploadImage')->name('setting.product.upload');
            $routes->get('/barcode-image', 'ProductController@barcodeImage')->name('setting.product.barcode.image');
            $routes->get('/barcode-download', 'ProductController@downloadBarcodeImage')->name('setting.product.barcode.download');

            $routes->get('/get/product/image', 'ProductController@getImage')->name('setting.product.image');
            $routes->get('/remove/product/image', 'ProductController@removeImage')->name('setting.product.image.remove');
        });
    });

    /** vehicles related routes */
    $routes->group(['prefix' => '/vehicle', 'as' => 'setting.vehicle.'], function ($routes) {

        /**
         * Controller Setting/VehicleTypeController
         * Repository Setting/VehicleTypeRepository
         * Model VehicleType
         * vehicleType related routes
         */
        $routes->group(['prefix' => '/type', 'as' => 'type.'], function ($routes) {
            $routes->get('/', 'VehicleTypeController@index')->name('index');
            $routes->post('/data', 'VehicleTypeController@dataTableData')->name('table.data');
            $routes->get('/create', 'VehicleTypeController@create')->name('create');
            $routes->post('/', 'VehicleTypeController@store')->name('store');
            $routes->group(['prefix' => '{vehicleType}'], function ($routes) {
                $routes->get('/', 'VehicleTypeController@show')->name('show');
                $routes->get('/edit', 'VehicleTypeController@edit')->name('edit');
                $routes->patch('/', 'VehicleTypeController@update')->name('update');
                $routes->delete('/', 'VehicleTypeController@delete')->name('delete');
            });
        });

        /**
         * Controller Setting/VehicleMakeController
         * Repository Setting/VehicleMakeRepository
         * Model VehicleMake
         * VehicleMake related routes
         */
        $routes->group(['prefix' => '/make', 'as' => 'make.'], function ($routes) {
            $routes->get('/', 'VehicleMakeController@index')->name('index');
            $routes->post('/data', 'VehicleMakeController@dataTableData')->name('table.data');
            $routes->get('/create', 'VehicleMakeController@create')->name('create');
            $routes->post('/', 'VehicleMakeController@store')->name('store');
            $routes->group(['prefix' => '{vehicleMake}'], function ($routes) {
                $routes->get('/', 'VehicleMakeController@show')->name('show');
                $routes->get('/edit', 'VehicleMakeController@edit')->name('edit');
                $routes->patch('/', 'VehicleMakeController@update')->name('update');
                $routes->delete('/', 'VehicleMakeController@delete')->name('delete');
            });
        });

        /**
         * Controller Setting/VehicleModelController
         * Repository Setting/VehicleModelRepository
         * Model VehicleModel
         * VehicleModel related routes
         */
        $routes->group(['prefix' => '/model', 'as' => 'model.'], function ($routes) {
            $routes->get('/', 'VehicleModelController@index')->name('index');
            $routes->post('/data', 'VehicleModelController@dataTableData')->name('table.data');
            $routes->get('/create', 'VehicleModelController@create')->name('create');
            $routes->post('/', 'VehicleModelController@store')->name('store');
            $routes->group(['prefix' => '{vehicleModel}'], function ($routes) {
                $routes->get('/', 'VehicleModelController@show')->name('show');
                $routes->get('/edit', 'VehicleModelController@edit')->name('edit');
                $routes->patch('/', 'VehicleModelController@update')->name('update');
                $routes->delete('/', 'VehicleModelController@delete')->name('delete');
            });
        });

        /** vehicle related routes */
        $routes->get('', 'VehicleController@index')->name('index');
        $routes->post('/data', 'VehicleController@dataTableData')->name('table.data');
        $routes->get('/create', 'VehicleController@create')->name('create');
        $routes->post('/', 'VehicleController@store')->name('store');
        $routes->get('/search/{q?}', 'VehicleController@search')->name('search');
        $routes->group(['prefix' => '{vehicle}'], function ($routes) {
            $routes->get('/', 'VehicleController@show')->name('show');
            $routes->get('/edit', 'VehicleController@edit')->name('edit');
            $routes->patch('/', 'VehicleController@update')->name('update');
            $routes->delete('/', 'VehicleController@delete')->name('delete');
            $routes->get('/image', 'VehicleController@getImage')->name('image');
            $routes->post('/renewal', 'VehicleController@addRenewal')->name('add.renewal');
        });
    });

    /**
     * Controller Setting/StaffController
     * Repository Setting/StaffRepository
     * Model Staff
     * staffs related routes
     */
    $routes->group(['prefix' => '/staff'], function ($routes) {
        $routes->get('/', 'StaffController@index')->name('setting.staff.index');
        $routes->get('/new', 'StaffController@newIndex')->name('setting.staff.new.index');
        $routes->get('/new/{staff}', 'StaffController@newIndex')->name('setting.staff.new.show');
        $routes->post('/data', 'StaffController@dataTableData')->name('setting.staff.table.data');
        $routes->get('/create', 'StaffController@create')->name('setting.staff.create');
        $routes->post('/', 'StaffController@store')->name('setting.staff.store');
        $routes->get('/export', 'StaffController@export')->name('setting.staff.export');
        $routes->get('search/{q?}', 'StaffController@search')->name('setting.staff.search');
        $routes->group(['prefix' => '{staff}'], function ($routes) {
            $routes->get('/', 'StaffController@show')->name('setting.staff.show');
            $routes->get('/edit', 'StaffController@edit')->name('setting.staff.edit');
            $routes->patch('/', 'StaffController@update')->name('setting.staff.update');
            $routes->delete('/', 'StaffController@delete')->name('setting.staff.delete');
            $routes->post('/upload/image', 'StaffController@uploadImage')->name('setting.staff.upload');
            $routes->get('/get/staff/image', 'StaffController@getImage')->name('setting.staff.image');
            $routes->get('/remove/staff/image', 'StaffController@removeImage')->name('setting.staff.image.remove');
        });
    });

    /**
     * Controller Setting/UserController
     * Repository Setting/UserRepository
     * Model User
     * Users related routes
     */
    $routes->group(['prefix' => '/user'], function ($routes) {
        $routes->get('/', 'UserController@index')->name('setting.user.index');
        $routes->get('/search/{q?}', 'UserController@search')->name('setting.user.search');
        $routes->get('/login-as/search/{q?}', 'UserController@searchWithoutRep')->name('setting.user.login.as.search');
        $routes->post('/data', 'UserController@dataTableData')->name('setting.user.table.data');
        $routes->group(['prefix' => '{user}'], function ($routes) {
            $routes->get('/', 'UserController@show')->name('setting.user.show');
        });
    });

    /**
     * Controller Setting/RoleController
     * Repository Setting/RoleRepository
     * Model Role
     * Roles related routes
     */
    $routes->group(['prefix' => '/role', 'as' => 'setting.role.'], function ($routes) {
        $routes->get('/', 'RoleController@index')->name('index');
        $routes->get('/create', 'RoleController@create')->name('create');
        $routes->post('/data', 'RoleController@dataTableData')->name('table.data');
        $routes->post('/', 'RoleController@store')->name('store');
        $routes->group(['prefix' => '{role}'], function ($routes) {
            $routes->get('/', 'RoleController@show')->name('show');
            $routes->get('/edit', 'RoleController@edit')->name('edit');
            $routes->patch('/', 'RoleController@update')->name('update');
            $routes->delete('/', 'RoleController@delete')->name('delete');
        });
    });

    /** sales routes related routes */
    $routes->group(['prefix' => '/route', 'as' => 'setting.route.'], function ($routes) {
        $routes->get('/', 'RouteController@index')->name('index');
        $routes->post('/data', 'RouteController@dataTableData')->name('table.data');
        $routes->get('/create', 'RouteController@create')->name('create');
        $routes->post('/', 'RouteController@store')->name('store');
        $routes->get('search/{q?}', 'RouteController@search')->name('search');
        $routes->group(['prefix' => '{route}'], function ($routes) {
            $routes->get('/', 'RouteController@show')->name('show');
            $routes->get('/edit', 'RouteController@edit')->name('edit');
            $routes->patch('/', 'RouteController@update')->name('update');
            $routes->get('/edit-qty', 'RouteController@editQty')->name('edit.qty');
            $routes->patch('/update-qty', 'RouteController@updateQty')->name('update.qty');
            $routes->delete('/', 'RouteController@delete')->name('delete');
            $routes->post('/targets', 'RouteTargetController@saveTarget')->name('target.store');
            $routes->get('/export/products', 'RouteController@exportProducts')->name('export.products');
            $routes->get('/export/customers', 'RouteController@exportCustomers')->name('export.customers');
            $routes->get('/allowance', 'RouteController@getAllowance')->name('get.allowance');
            $routes->get('/targets/{target}', 'RouteTargetController@getTarget')->name('target.get');
            $routes->post('/targets/{target}/edit', 'RouteTargetController@editTarget')->name('target.edit');
            $routes->post('/assign-product', 'RouteController@assignProduct')->name('assign.product');
            $routes->delete('/remove-product/{product}', 'RouteController@removeProduct')->name('remove.product');
        });
    });

    /** sales reps related routes */
    $routes->group(['prefix' => '/rep', 'as' => 'setting.rep.'], function ($routes) {
        $routes->get('/', 'RepController@index')->name('index');
        $routes->post('/data', 'RepController@dataTableData')->name('table.data');
        $routes->get('/create', 'RepController@create')->name('create');
        $routes->post('/', 'RepController@store')->name('store');
        $routes->group(['prefix' => '{rep}'], function ($routes) {
            $routes->get('/', 'RepController@show')->name('show');
            $routes->get('/edit', 'RepController@edit')->name('edit');
            $routes->patch('/', 'RepController@update')->name('update');
            $routes->delete('/', 'RepController@delete')->name('delete');
            $routes->post('/targets', 'RepTargetController@saveTarget')->name('target.store');
            $routes->get('/targets/{target}', 'RepTargetController@getTarget')->name('target.get');
            $routes->post('/targets/{target}/edit', 'RepTargetController@editTarget')->name('target.edit');
        });
    });

    /**
     * Staff list for giving models
     */
    $routes->post('staff/{model}/{modelId}/{relation}', 'SettingController@staffList')->name('setting.staff.list');
    $routes->post('route/{model}/{modelId}/{relation}', 'SettingController@routeList')->name('setting.route.list');

    /** Production unit related routes */
    $routes->group(['prefix' => '/production-unit', 'as' => 'setting.production.unit.'], function ($routes) {
        $routes->get('', 'ProductionUnitController@index')->name('index');
        $routes->post('/data', 'ProductionUnitController@dataTableData')->name('table.data');
        $routes->get('/create', 'ProductionUnitController@create')->name('create');
        $routes->post('/', 'ProductionUnitController@store')->name('store');
        $routes->group(['prefix' => '{productionUnit}'], function ($routes) {
            $routes->get('/', 'ProductionUnitController@show')->name('show');
            $routes->get('/edit', 'ProductionUnitController@edit')->name('edit');
            $routes->patch('/', 'ProductionUnitController@update')->name('update');
            $routes->delete('/', 'ProductionUnitController@delete')->name('delete');
            $routes->post('/assign-staff', 'ProductionUnitController@assignStaff')->name('assign.staff');
            $routes->delete('/remove-staff/{staff}', 'ProductionUnitController@removeStaff')->name('staff.remove');
            $routes->get('search/staff/{q?}', 'ProductionUnitController@searchStaff')->name('staff.search');
        });
    });

    /** Sales Rep related routes */
    $routes->group(['prefix' => '/sales-rep', 'as' => 'setting.rep.'], function ($routes) {
        $routes->get('', 'RepController@index')->name('index');
        $routes->post('/data', 'RepController@dataTableData')->name('table.data');
        $routes->get('/create', 'RepController@create')->name('create');
        $routes->get('/search', 'RepController@search')->name('search');
        $routes->post('/', 'RepController@store')->name('store');
        $routes->get('/search/{q?}', 'RepController@search')->name('search');
        $routes->group(['prefix' => '{rep}'], function ($routes) {
            $routes->get('/', 'RepController@show')->name('show');
            $routes->get('/edit', 'RepController@edit')->name('edit');
            $routes->patch('/', 'RepController@update')->name('update');
            $routes->delete('/', 'RepController@delete')->name('delete');
        });
    });

    /** price books related routes */
    $routes->group(['prefix' => '/price-book'], function ($routes) {
        $routes->get('', 'PriceBookController@index')->name('setting.price.book.index');
        $routes->get('/comparison', 'PriceBookController@comparison')->name('setting.price.book.comparison');
        $routes->post('/data', 'PriceBookController@dataTableData')->name('setting.price.book.table.data');
        $routes->get('/create', 'PriceBookController@create')->name('setting.price.book.create');
        $routes->post('/', 'PriceBookController@store')->name('setting.price.book.store');
        $routes->get('search/{q?}', 'PriceBookController@search')->name('setting.price.book.search');
        $routes->get('/location/{location}/search/{q?}', 'PriceBookController@searchByLocation')->name('setting.price.book.search.by.location');
        $routes->get('/location/van/{location}/search/{q?}', 'PriceBookController@searchByVanLocation')->name('setting.price.book.search.by.van.location');
        $routes->get('/rep/{rep}/search/{q?}', 'PriceBookController@searchByRep')->name('setting.price.book.search.by.rep');
        $routes->group(['prefix' => '{priceBook}'], function ($routes) {
            $routes->get('/', 'PriceBookController@show')->name('setting.price.book.show');
            $routes->get('/edit', 'PriceBookController@edit')->name('setting.price.book.edit');
            $routes->patch('/', 'PriceBookController@update')->name('setting.price.book.update');
            $routes->delete('/', 'PriceBookController@delete')->name('setting.price.book.delete');
            $routes->get('/clone', 'PriceBookController@clone')->name('setting.price.book.clone');
            $routes->post('/do-clone', 'PriceBookController@doClone')->name('setting.price.book.do.clone');
            $routes->get('export/prices', 'PriceBookController@exportBook')->name('setting.price.book.export');
        });
        $routes->group(['prefix' => 'history'], function ($routes) {
            $routes->group(['prefix' => '{priceHistory}'], function ($routes) {
                $routes->get('/', 'PriceBookController@history')->name('setting.price.history.show');
            });
        });
    });

    /** price books related routes */
    $routes->group(['prefix' => '/mileage-rate'], function ($routes) {
        $routes->get('', 'MileageRateController@index')->name('setting.mileage.rate.index');
        $routes->post('/data', 'MileageRateController@dataTableData')->name('setting.mileage.rate.table.data');
        $routes->get('/create', 'MileageRateController@create')->name('setting.mileage.rate.create');
        $routes->post('/', 'MileageRateController@store')->name('setting.mileage.rate.store');
        $routes->get('search/{q?}', 'MileageRateController@search')->name('setting.mileage.rate.search');
        $routes->group(['prefix' => '{mileageRate}'], function ($routes) {
            $routes->get('/edit', 'MileageRateController@edit')->name('setting.mileage.rate.edit');
            $routes->patch('/', 'MileageRateController@update')->name('setting.mileage.rate.update');
            $routes->delete('/', 'MileageRateController@delete')->name('setting.mileage.rate.delete');
        });
    });

    /** brand related routes */
    $routes->group(['prefix' => '/brand'], function ($routes) {
        $routes->post('/', 'BrandController@store')->name('setting.brand.store');
        $routes->get('search/{q?}', 'BrandController@search')->name('setting.brand.search');
    });

    /**
     * Controller Setting/EmailTemplateController
     * Repository Setting/EmailTemplateRepository
     * Model EmailTemplate
     * Email Template related routes
     */
    $routes->group(['prefix' => '/email-template', 'as' => 'setting.email.template.'], function ($routes) {
        $routes->get('/', 'EmailTemplateController@index')->name('index');
        $routes->group(['prefix' => '{template}'], function ($routes) {
            $routes->get('/edit', 'EmailTemplateController@edit')->name('edit');
            $routes->patch('/', 'EmailTemplateController@update')->name('update');
        });
    });

    /** data migration related routes */
    $routes->group(['prefix' => 'migration'], function ($routes) {
        $routes->get('/', 'MigrationController@index')->name('setting.migrate.index');
        /** products */
        $routes->group(['prefix' => 'products'], function ($routes) {
            $routes->get('/create', 'MigrationController@products')->name('setting.migrate.products');
            $routes->post('/', 'MigrationController@migrateProducts')->name('setting.do.migrate.products');
        });

        /** stocks */

    });

    $routes->group(['prefix' => '/account-group', 'as' => 'setting.account.group.'], function ($routes) {
        $routes->get('/', 'AccountGroupController@index')->name('index');
        $routes->get('/create', 'AccountGroupController@create')->name('create');
        $routes->post('/', 'AccountGroupController@store')->name('store');
        $routes->get('search-by-type/{type}/{q?}', 'AccountGroupController@searchByType')->name('search.by.type');
        $routes->get('search-by-category/{category}/{q?}', 'AccountGroupController@searchByCategory')->name('search.by.category');
        $routes->get('search/{q?}', 'AccountGroupController@search')->name('search');
        $routes->group(['prefix' => '{accountGroup}'], function ($routes) {
            $routes->get('/', 'AccountGroupController@show')->name('show');
            $routes->get('/edit', 'AccountGroupController@edit')->name('edit');
            $routes->patch('/', 'AccountGroupController@update')->name('update');
            $routes->delete('/', 'AccountGroupController@delete')->name('delete');
        });
    });

    $routes->group(['prefix' => 'audit-logs'], function ($routes) {
        $routes->get('/', 'AuditLogController@index')->name('setting.audit.log.index');
        $routes->get('/{log}', 'AuditLogController@show')->name('setting.audit.log.show');
    });

    $routes->group(['prefix' => 'faces'], function ($routes) {
        $routes->group(['prefix' => 'image/{faceId}'], function ($routes) {
            $routes->get('/', 'FaceRecognitionController@image')->name('setting.face.id.image');
            $routes->delete('/', 'FaceRecognitionController@delete')->name('setting.face.id.delete');
        });
        $routes->group(['prefix' => '/{user}'], function ($routes) {
            $routes->get('/', 'FaceRecognitionController@index')->name('setting.face.id.index');
            $routes->post('/', 'FaceRecognitionController@store')->name('setting.face.id.store');
        });
    });

    $routes->group(['prefix' => '/designation', 'as' => 'setting.designation.'], function ($routes) {
        $routes->post('', 'DesignationController@store')->name('store');
        $routes->get('{q?}', 'DesignationController@search')->name('search');
    });

    /** work hours related routes */
    $routes->group(['prefix' => '/work-hour'], function ($routes) {
        $routes->get('', 'WorkHourController@index')->name('setting.work.hour.index');
        $routes->post('/', 'WorkHourController@store')->name('setting.work.hour.store');
        $routes->group(['prefix' => '{workHour}'], function ($routes) {
            $routes->post('/change-status', 'WorkHourController@changeStatus')->name('setting.work.hour.change.status');
        });
    });

    /**
     * vehicle rep routes
     */
    $routes->get('/search/vehicle-rep/{model}/{modelId}/{searchModal}/{relation}/{column}/{q?}', 'VehicleRepController@search')->name('setting.search.vehicle.rep');
    $routes->post('/attach/vehicle-rep/{model}/{modelId}/{relation}', 'VehicleRepController@attach')->name('setting.search.vehicle.rep.attach');
    $routes->post('status/change/{method}/{modal}/{vehicle}', 'VehicleRepController@statusChange')->name('setting.vehicle.status.change');
    $routes->post('vehicle/status/change/{method}/{rep}', 'VehicleRepController@vehicleStatusChange')->name('setting.rep.vehicle.status.change');

    /**
     * Attach modal
     */
    $routes->post('/{attachModal}/{attachModalId}/{relation}', 'RouteRepController@attach')->name('setting.attach.modal');

    /**
     * Detach modal
     */
    $routes->delete('{modal}/{modalId}/detach/route/{relation}/{relationId}', 'RouteRepController@detach')->name('setting.detach.modal');

    /** create route location */
    $routes->group(['prefix' => '/location', 'as' => 'setting.route.location.'], function ($routes) {
        $routes->group(['prefix' => '{route}'], function ($routes) {
            $routes->post('', 'LocationController@store')->name('store');
            $routes->delete('delete/{location}', 'LocationController@delete')->name('delete');
        });
    });

    //loginAs
    $routes->post('/loginAs', 'UserController@loginAs')->name('setting.user.login.as');

    //Get Route For location
    $routes->get('/route/{route}/get', 'RouteController@getRoute')->name('setting.route.get');

    //Get Unit types
    $routes->get('/search/unit-type/{q?}', 'SettingController@searchUnitType')->name('setting.unit.type.search');
    $routes->get('/search/related-to/{q?}', 'SettingController@searchRelated')->name('setting.related.to.search');
    /**
     * edit relation table modal datas
     */
    $routes->patch('/update/data/{modal}/{modalId}/', 'SettingController@updateData')->name('setting.relation.table.data.update');
    $routes->delete('/delete/data/{modal}/{modalId}/', 'SettingController@deleteData')->name('setting.relation.table.data.delete');

    /** filter location from route drop-down selection  */
    $routes->get('/route/{route}/location/search/{q?}/', 'RouteController@searchLocation')->name('setting.route.location.search');
    $routes->get('/route/{company}/route/search/{q?}/', 'RouteController@searchByCompany')->name('setting.route.by.company.search');
    $routes->get('/rep/{company}/rep/search/{q?}/', 'RepController@searchByCompany')->name('setting.rep.by.company.search');
    $routes->get('/van/{company}/van/search/{q?}/', 'SalesLocationController@searchVanByCompany')->name('setting.van.by.company.search');
    $routes->get('/shop/{company}/shop/search/{q?}/', 'SalesLocationController@searchShopByCompany')->name('setting.shop.by.company.search');
    $routes->get('/store/{company}/store/search/{q?}/', 'StoreController@searchByCompany')->name('setting.store.by.company.search');
    $routes->get('api-clients', 'ApiClientsController@index')->name('api.clients.index');
    $routes->get('/{modal}/{modalId}/{searchableModal}/{relation}/{column}/{q?}', 'RouteRepController@search')->name('setting.search.modal');
    $routes->get('{model}/{take?}/{with?}', 'SettingController@summary')->name('setting.summary.index');

});
