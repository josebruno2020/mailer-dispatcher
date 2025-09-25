<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @template T
 */
class BaseService
{
  public function __construct(protected Model $model, protected string $name)
  {

  }

  public function getAll(array $filters = []): \Illuminate\Database\Eloquent\Collection
  {
    return $this->model->where($filters)
      ->orderBy('created_at', 'desc')
      ->get();
  }

  public function paginate(array $filters = [], array $order = ['created_at' => 'desc'], int $perPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
  {
    $query = $this->model->newQuery();

    foreach ($filters as $key => $value) {
      $op = '=';
      if (is_string($value) && str_starts_with($value, 'like:')) {
        $op = 'like';
        $value = substr($value, 5);
        $value = '%' . $value . '%';
      }
      $query->where($key, $op, $value);
    }

    foreach ($order as $column => $direction) {
      $query->orderBy($column, $direction);
    }

    return $query->paginate($perPage);
  }

  /**
   * @return T
   */
  public function create(array $data): Model
  {
    return $this->model->create($data);
  }

  public function updatePartial(string $id, array $data): Model
  {
    $model = $this->findOrThrow($id);
    $model->fill($data);
    $model->save();
    return $model;
  }

  /**
   * @param string $id
   * @return T|null
   */
  public function find(string $id): ?Model
  {
    return $this->model->find($id);
  }

  /**
   * Summary of findOrThrow
   * @param string $id
   * @param array $filters
   * @return T
   */
  public function findOrThrow(string $id, array $filters = []): Model
  {
    $model = $this->model->where($filters)->find($id);
    if (!$model) {
      throw new NotFoundHttpException("{$this->name} not found");
    }
    return $model;
  }

  public function deleteById(string $id, array $filters = []): void
  {
    $model = $this->findOrThrow($id, $filters);
    $model->delete();
  }
}