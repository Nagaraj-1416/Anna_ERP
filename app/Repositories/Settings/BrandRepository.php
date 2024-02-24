<?php

namespace App\Repositories\Settings;

use App\Brand;
use App\Repositories\BaseRepository;

/**
 * Class BrandRepository
 * @package App\Repositories\Settings
 */
class BrandRepository extends BaseRepository
{
    /**
     * BrandRepository constructor.
     * @param Brand|null $brand
     */
    public function __construct(Brand $brand = null)
    {
        $this->setModel($brand ?? new Brand());
    }
}