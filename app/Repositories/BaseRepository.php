<?php

namespace App\Repositories;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

/**
 * Class BaseRepository
 * @package App\Repositories
 */
class BaseRepository
{
    /**
     * The Model name.
     * @var \Illuminate\Database\Eloquent\Model;
     */
    protected $model;

    /**
     * Unique code prefix
     * @var string
     */
    protected $codePrefix;
    protected $refPrefix;

    /**
     * @var array $codeColumn
     */
    public $codeColumn;
    public $codeColumns;
    public $refColumn;

    /**
     * Set the model object
     * @param Model $model
     */
    public function setModel(Model $model)
    {
        $this->model = $model;
    }

    /**
     * get the model object
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * get the model count
     * @return Model
     */
    public function count()
    {
        return $this->model->count();
    }

    /**
     * Given all records
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     * @return mixed
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * Paginate the given query.
     * @param int $n The number of models to return for pagination $n integer
     * @return mixed
     */
    public function getPaginate($n)
    {
        return $this->model->paginate($n);
    }

    /**
     * Create a new model and return the instance.
     * @param array $inputs
     * @return Model instance
     */
    public function storeItem(array $inputs)
    {
        return $this->model->create($inputs);
    }

    /**
     * FindOrFail Model and return the instance.
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getById($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @param $id
     * @param array $inputs
     * @return bool
     */
    public function updateItem($id, array $inputs)
    {
        return $this->getById($id)->update($inputs);
    }

    /**
     * @param null $id
     * @return array
     * @throws array
     */
    public function destroy($id = null)
    {
        if (!$id) {
            $id = $this->model->id;
        }
        try {
            $this->getById($id)->delete();
            return ['success' => true, 'message' => 'deleted success'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'deleted failed'];
        }
    }

    /**
     * Get index data for data table
     * @param Request $request
     * @param array $columns
     * @param array $searchingColumns
     * @param array $relationColumns
     * @param bool $dataAsArray
     * @param string $relationMethod
     * @param null $model
     * @param null $queries
     * @return array
     */
    public function getTableData(
        Request $request,
        array $columns = [],
        array $searchingColumns = [],
        array $relationColumns = [],
        bool $dataAsArray = true,
        string $relationMethod = null,
        $model = null,
        $queries = null
    ): array
    {
        array_push($columns, 'id');
        if ($model) $this->model = $model;
        $totalData = $this->count();
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        /// custom columns search (mapping the columns)
        $searchData = [];
        $searchRelationalData = [];
        $requestColumns = $request->input('columns');
        if ($requestColumns) {
            foreach ($requestColumns as $requestColumn) {
                if ($requestColumn['search']['value']) {
                    if (in_array($requestColumn['data'], $columns)) {
                        $searchData[$requestColumn['data']] = $requestColumn['search']['value'];
                    } else {
                        foreach ($relationColumns as $relation => $relationColumn) {
                            foreach ($relationColumn as $column) {
                                if (!isset($column['as']) || !isset($column['column'])) continue;
                                if ($requestColumn['data'] == $column['as']) {
                                    array_push($searchRelationalData, [
                                        'relation' => $relation,
                                        'column' => $column['column'],
                                        'value' => $requestColumn['search']['value']
                                    ]);
                                }
                            }
                        }
                    }
                };
            }
        }

        $with = array_keys($relationColumns);
        // pluck relation Columns as
        $relationColumnsAs = [];
        foreach ($relationColumns as $relationColumn) {
            foreach ($relationColumn as $column) {
                if (!isset($column['as'])) continue;
                array_push($relationColumnsAs, $column['as']);
            }
        }
        if ($relationMethod) {
            $model = $this->model->$relationMethod();
        } else {
            $model = $this->model;
        }

        if (count($searchData) || $searchRelationalData) {
            /** searching Query build  */

            $q = $model->where(function ($q) use ($searchData, $searchRelationalData) {
                foreach ($searchData as $column => $value) {
                    $q = $q->where($column, 'LIKE', "%{$value}%");
                }
            });
            /** generate query for relation */
            foreach ($searchRelationalData as $searchRelational) {
                $q = $q->whereHas($searchRelational['relation'], function ($q) use ($searchRelational) {
                    $q->where($searchRelational['column'], 'LIKE', "%{$searchRelational['value']}%");
                });
            }

            $query = $q->offset($start)->limit($limit)->orderBy($order, $dir);
            $totalFiltered = $q->count();

        } else if (empty($request->input('search.value'))) {
            /** get all data  */
            $query = $model->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->with($with);
        } else {
            /** searching global  */
            $search = $request->input('search.value');
            $q = $model->where(function ($q) use ($searchingColumns, $search) {
                foreach ($searchingColumns as $searchingColumn) {
                    $q = $q->orWhere($searchingColumn, 'LIKE', "%{$search}%");
                }
            });
            /** search relational data */
            foreach ($relationColumns as $relation => $relationColumn) {
                foreach ($relationColumn as $column) {
                    if (!isset($column['as']) || !isset($column['column'])) continue;
                    $column = $column['column'];
                    $q = $q->orWhere(function ($q) use ($relation, $column, $search) {
                        $q->whereHas($relation, function ($q) use ($column, $search) {
                            $q->where($column, 'LIKE', "%{$search}%");
                        });
                    });
                }
            }

            $query = $q->offset($start)->with($with)->limit($limit)->orderBy($order, $dir);
            $totalFiltered = $q->count();
        }
        // transform relational data
        // Custom query
        if (is_array($queries)) {
            foreach ($queries as $CustomQuery) {
                if (is_array($CustomQuery)) {
                    if (count($CustomQuery) == 3) {
                        if (is_array($CustomQuery[2])) {
                            $query = $query->whereIn($CustomQuery[0], $CustomQuery[1], $CustomQuery[2]);
                        } else {
                            $query = $query->where($CustomQuery[0], $CustomQuery[1], $CustomQuery[2]);
                        }
                    } elseif (count($CustomQuery) == 2) {
                        if (is_array($CustomQuery[1])) {
                            $query = $query->whereIn($CustomQuery[0], $CustomQuery[1]);
                        } else {
                            $query = $query->where($CustomQuery[0], $CustomQuery[1]);
                        }
                    }
                }
            }
            $totalFiltered = $query->count();
        }
        $results = $query->get();
        $onlyColumn = array_merge($relationColumnsAs, $columns);
        $results = $results->transform(function ($item) use ($relationColumns, $columns, $onlyColumn) {
            if (count($relationColumns)) {
                foreach ($relationColumns as $relation => $columnData) {
                    foreach ($columnData as $column) {
                        if (isset($column['column']) && isset($column['as'])) {
                            $as = $column['as'];
                            $column = $column['column'];
                            $item->$as = $item->$relation->$column ?? '';
                        }
                    }
                }
            }
            $item = $item->only($onlyColumn);
            return $item;
        });


        return [
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $dataAsArray ? $results->toArray() : $results,
            "columns" => $columns
        ];
    }

    /**
     * Set the unique code prefix
     * @param $prefix
     * @param string $column
     * @param array $columns
     */
    public function setCodePrefix($prefix, $column = 'code', $columns = []): void
    {
        $this->codePrefix = $prefix;
        $this->codeColumns = $columns;
        $this->codeColumn = $column;
    }

    /**
     * Get the unique code prefix
     * @return string
     */
    public function getCodePrefix(): string
    {
        return $this->codePrefix;
    }

    /**
     * Generate unique code
     * @return string
     */
    public function getCode(): string
    {
        $this->model = $this->model->refresh();
        $prefix = $this->codePrefix;
        // Get the last created order
        if ($this->codeColumns && count($this->codeColumns)) {
            $q = $this->model;
            foreach ($this->codeColumns as $column => $value) {
                $q = $q->where($column, $value);
            };
            $lastItem = $q->withTrashed()->orderBy('id', 'desc')->first();
        } else {
            $lastItem = $this->model->withTrashed()->orderBy('id', 'desc')->first();
        }
        // get last record cord no
        if (!$lastItem)
            $number = 0;
        else
            $number = preg_replace('/\D/', '', $lastItem->getAttribute($this->codeColumn));

        // generate code
        return $prefix . sprintf('%07d', intval($number) + 1);
    }

    /**
     * search model items
     * @param null $q
     * @param string $labelColumn
     * @param array $searchingColumns
     * @param array $except
     * @param array $queries
     * @return array
     */

    public function search($q = null, $labelColumn = 'name', $searchingColumns = ['name'], $except = [], $queries = []): array
    {
        /** @var Builder $query */
        // Search the items
        if ($q == null) {
            $query = $this->model->orderBy($labelColumn);
            // Generate query for except values
            if (is_array($except)) {
                foreach ($except as $column => $values) {
                    if (is_array($values)) {
                        $query = $query->whereNotIn($column, $values);
                    } else {
                        $query = $query->where($column, '<>', $values);
                    }
                }
            }
            // Custom query
            if (is_array($queries)) {
                foreach ($queries as $CustomQuery) {
                    if (is_array($CustomQuery)) {
                        if (count($CustomQuery) == 3) {
                            if (is_array($CustomQuery[2])) {
                                $query = $query->whereIn($CustomQuery[0], $CustomQuery[1], $CustomQuery[2]);
                            } else {
                                $query = $query->where($CustomQuery[0], $CustomQuery[1], $CustomQuery[2]);
                            }
                        } elseif (count($CustomQuery) == 2) {
                            if (is_array($CustomQuery[1])) {
                                $query = $query->whereIn($CustomQuery[0], $CustomQuery[1]);
                            } else {
                                $query = $query->where($CustomQuery[0], $CustomQuery[1]);
                            }
                        }
                    }
                }
            }

            $items = $query->get(['id', $labelColumn])->toArray();
        } else {
            $query = $this->model->orderBy($labelColumn);
            // Generate query for except values
            if (is_array($searchingColumns)) {
                foreach ($searchingColumns as $key => $column) {
                    if ($key == 0) {
                        $query = $query->where($column, 'LIKE', '%' . $q . '%');
                    } else {
                        $query = $query->orWhere($column, 'LIKE', '%' . $q . '%');
                    }
                }
            }
            // Generate query for except values
            if (is_array($except)) {
                foreach ($except as $column => $values) {
                    if (is_array($values)) {
                        $query = $query->whereNotIn($column, $values);
                    } else {
                        $query = $query->where($column, '<>', $values);
                    }
                }
            }

            // Custom query
            if (is_array($queries)) {
                foreach ($queries as $CustomQuery) {
                    if (is_array($CustomQuery)) {
                        if (count($CustomQuery) == 3) {
                            if (is_array($CustomQuery[2])) {
                                $query = $query->whereIn($CustomQuery[0], $CustomQuery[1], $CustomQuery[2]);
                            } else {
                                $query = $query->where($CustomQuery[0], $CustomQuery[1], $CustomQuery[2]);
                            }
                        } elseif (count($CustomQuery) == 2) {
                            if (is_array($CustomQuery[1])) {
                                $query = $query->whereIn($CustomQuery[0], $CustomQuery[1]);
                            } else {
                                $query = $query->where($CustomQuery[0], $CustomQuery[1]);
                            }
                        }
                    }
                }
            }

            $items = $query->get(['id', $labelColumn])->toArray();
        }
        // mapping the data
        $items = array_map(function ($obj) use ($labelColumn) {
            return ["name" => $obj[$labelColumn], "value" => $obj['id']];
        }, $items);
        return ["success" => true, "results" => $items];
    }

    /**
     * Set user base ref prefix
     * @param $prefix
     * @param string $column
     */
    public function setRefPrefix($prefix, $column = 'ref')
    {
        $this->refPrefix = $prefix;
        $this->refColumn = $column;
    }

    /**
     * Get user base ref prefix
     * @return string
     */
    public function getRefPrefix()
    {
        return $this->refPrefix;
    }

    /**
     * Generate user base ref
     * @param User|null $user
     * @param null $column
     * @return null|string
     */
    public function generateRef(User $user = null, $column = null)
    {
        if (!$column) $column = $this->refColumn;
        if (!$user) $user = auth()->user();
        if (!$user) return null;
        $prefix = $user->prefix . '/' . $this->refPrefix . '/';
        // Get the last created order
        $lastItem = $this->model->withTrashed()->orderBy('id', 'desc')->where($column, 'LIKE', $prefix . '%')->first();
        // get last record cord no
        if (!$lastItem)
            $number = 0;
        else
            $number = preg_replace('/\D/', '', $lastItem->getAttribute($column));
        // generate code
        return $prefix . sprintf('%06d', intval($number) + 1);
    }

    /**
     * @param $model
     * @param $search
     * @return mixed
     */
    public function getModelQuery($search, $model = null)
    {
        $model = $model ? $model : $this->model;
        $query = $model->query();
        $searchable = $model->searchable;
        if ($searchable) {
            foreach ($searchable as $column) {
                $query->orWhere($column, 'LIKE', '%' . $search . '%');
            }
        }
        return $query;
    }
}