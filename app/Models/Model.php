<?php

namespace App\Models;

use App\Core\Database;

abstract class Model
{
    protected static string $table = '';
    protected static string $primaryKey = 'id';
    protected static array $fillable = [];
    protected static array $casts = [];
    protected static array $relationConfig = [];

    protected array $relations = [];

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    public function fill(array $attributes): void
    {
        $fillable = array_merge(static::$fillable, [static::$primaryKey]);
        foreach ($attributes as $key => $value) {
            if (in_array($key, $fillable, true)) {
                $this->$key = $this->castFromDb($key, $value);
            }
        }
    }

    public function setRelation(string $name, $value): void
    {
        $this->relations[$name] = $value;
    }

    public function __get(string $name)
    {
        if (isset(static::$relationConfig[$name])) {
            if (!array_key_exists($name, $this->relations)) {
                $this->relations[$name] = $this->$name();
            }
            return $this->relations[$name];
        }
        return null;
    }

    public function __isset(string $name): bool
    {
        if (isset(static::$relationConfig[$name])) {
            if (!array_key_exists($name, $this->relations)) {
                $this->relations[$name] = $this->$name();
            }
            return !empty($this->relations[$name]);
        }
        return property_exists($this, $name);
    }

    public static function table(): string
    {
        return static::$table;
    }

    public static function getFillable(): array
    {
        return static::$fillable;
    }

    public static function getRelationConfig(): array
    {
        return static::$relationConfig;
    }

    public static function query(): QueryBuilder
    {
        return new QueryBuilder(static::class);
    }

    public static function where($column, $operator = null, $value = null): QueryBuilder
    {
        $qb = new QueryBuilder(static::class);
        $qb->where($column, $operator, $value);
        return $qb;
    }

    public static function whereIn(string $column, array $values): QueryBuilder
    {
        return (new QueryBuilder(static::class))->whereIn($column, $values);
    }

    public static function whereHas(string $relation, ?callable $callback = null): QueryBuilder
    {
        return (new QueryBuilder(static::class))->whereHas($relation, $callback);
    }

    public static function with($relations): QueryBuilder
    {
        return (new QueryBuilder(static::class))->with($relations);
    }

    public static function latest(string $column = 'created_at'): QueryBuilder
    {
        return (new QueryBuilder(static::class))->latest($column);
    }

    public static function orderBy(string $column, string $direction = 'ASC'): QueryBuilder
    {
        return (new QueryBuilder(static::class))->orderBy($column, $direction);
    }

    public static function paginate(int $perPage = 15): array
    {
        return (new QueryBuilder(static::class))->paginate($perPage);
    }

    public static function count(): int
    {
        return (new QueryBuilder(static::class))->count();
    }

    public static function pluck(string $column, ?string $key = null): array
    {
        $results = Database::fetchAll("SELECT * FROM " . static::$table);
        if ($key === null) {
            return array_map(fn($r) => $r->$column, $results);
        }
        $plucked = [];
        foreach ($results as $row) {
            $plucked[$row->$key] = $row->$column;
        }
        return $plucked;
    }

    public static function all(): array
    {
        return (new QueryBuilder(static::class))->get();
    }

    public static function find($id): ?static
    {
        if ($id === null) {
            return null;
        }
        $pk = static::$primaryKey;
        $result = Database::fetch(
            "SELECT * FROM " . static::$table . " WHERE {$pk} = ?",
            [$id]
        );
        if (!$result) {
            return null;
        }
        $model = new static();
        $model->fill((array)$result);
        return $model;
    }

    public static function findOrFail($id): static
    {
        $model = static::find($id);
        if (!$model) {
            throw new \RuntimeException("No record found for id {$id} in " . static::$table);
        }
        return $model;
    }

    public static function create(array $data): ?static
    {
        $fillableData = [];
        $model = new static();
        foreach (static::$fillable as $field) {
            if (array_key_exists($field, $data)) {
                $fillableData[$field] = $model->castToDb($field, $data[$field]);
            }
        }
        $id = Database::insert(static::$table, $fillableData);
        return static::find($id);
    }

    public function update(array $data): static
    {
        $fillableData = [];
        foreach (static::$fillable as $field) {
            if (array_key_exists($field, $data)) {
                $dbValue = $this->castToDb($field, $data[$field]);
                $fillableData[$field] = $dbValue;
                $this->$field = $this->castFromDb($field, $data[$field]);
            }
        }
        if (!empty($fillableData)) {
            $pk = static::$primaryKey;
            Database::update(static::$table, $fillableData, "{$pk} = :id", ['id' => $this->$pk]);
        }
        return $this;
    }

    public function delete(): int
    {
        $pk = static::$primaryKey;
        return Database::delete(static::$table, "{$pk} = ?", [$this->$pk]);
    }

    public function save(): static
    {
        $pk = static::$primaryKey;
        $data = [];
        foreach (static::$fillable as $field) {
            if (property_exists($this, $field)) {
                $data[$field] = $this->castToDb($field, $this->$field);
            }
        }
        if ($this->$pk ?? null) {
            Database::update(static::$table, $data, "{$pk} = :id", ['id' => $this->$pk]);
        } else {
            $id = Database::insert(static::$table, $data);
            $this->$pk = $id;
        }
        return $this;
    }

    public function load($relations): static
    {
        if (is_string($relations)) {
            $relations = [$relations];
        }
        foreach ($relations as $relation) {
            if (isset(static::$relationConfig[$relation])) {
                $this->relations[$relation] = $this->$relation();
            }
        }
        return $this;
    }

    public function toArray(): array
    {
        $data = [];
        foreach (array_merge(static::$fillable, [static::$primaryKey]) as $field) {
            if (property_exists($this, $field)) {
                $data[$field] = $this->$field;
            }
        }
        $data['relations'] = $this->relations;
        return $data;
    }

    protected function castFromDb(string $key, $value)
    {
        $type = static::$casts[$key] ?? null;
        if ($type === null) {
            return $value;
        }

        if (str_starts_with($type, 'decimal') || $type === 'float' || $type === 'double') {
            return (float)$value;
        }
        if (str_starts_with($type, 'datetime') || str_starts_with($type, 'date') || $type === 'timestamp') {
            return $value;
        }

        return match ($type) {
            'boolean' => (bool)$value,
            'integer', 'int' => (int)$value,
            'array' => json_decode($value ?? '[]', true),
            'object' => json_decode($value ?? '{}'),
            'hashed' => $value,
            default => $value,
        };
    }

    protected function castToDb(string $key, $value)
    {
        $type = static::$casts[$key] ?? null;
        if ($type === null) {
            return $value;
        }

        if (str_starts_with($type, 'decimal') || $type === 'float' || $type === 'double') {
            return (float)$value;
        }

        return match ($type) {
            'boolean' => $value ? 1 : 0,
            'array', 'object' => json_encode($value ?? []),
            'hashed' => password_hash($value, PASSWORD_DEFAULT),
            'integer', 'int' => (int)$value,
            default => $value,
        };
    }
}
