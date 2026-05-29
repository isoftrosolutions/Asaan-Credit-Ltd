<?php

namespace App\Models;

use App\Core\Database;

class QueryBuilder
{
    protected string $modelClass;
    protected string $table;
    protected array $wheres = [];
    protected array $whereHasConstraints = [];
    protected array $withs = [];
    protected ?string $orderByColumn = null;
    protected string $orderByDir = 'ASC';
    protected ?int $limit = null;

    public function __construct(string $modelClass)
    {
        $this->modelClass = $modelClass;
        $this->table = $modelClass::table();
    }

    public function where($column, $operator = null, $value = null): self
    {
        if ($value === null) {
            if (is_array($operator)) {
                $value = $operator;
                $operator = 'IN';
            } else {
                $value = $operator;
                $operator = '=';
            }
        }
        if (is_bool($value)) {
            $value = $value ? 1 : 0;
        }
        $this->wheres[] = [$column, $operator, $value];
        return $this;
    }

    public function whereIn(string $column, array $values): self
    {
        return $this->where($column, 'IN', $values);
    }

    public function whereHas($relation, ?callable $callback = null): self
    {
        $this->whereHasConstraints[] = [$relation, $callback];
        return $this;
    }

    public function with($relations): self
    {
        if (is_string($relations)) {
            $relations = [$relations];
        }
        $this->withs = array_merge($this->withs, $relations);
        return $this;
    }

    public function latest(string $column = 'created_at'): self
    {
        $this->orderByColumn = $column;
        $this->orderByDir = 'DESC';
        return $this;
    }

    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->orderByColumn = $column;
        $this->orderByDir = strtoupper($direction);
        return $this;
    }

    public function take(int $n): self
    {
        $this->limit = $n;
        return $this;
    }

    public function get(): array
    {
        $where = $this->buildWhereClause();
        $sql = "SELECT * FROM {$this->table}";
        if (!empty($where['conditions'])) {
            $sql .= " WHERE " . implode(' AND ', $where['conditions']);
        }
        if ($this->orderByColumn) {
            $sql .= " ORDER BY {$this->orderByColumn} {$this->orderByDir}";
        }
        if ($this->limit !== null) {
            $sql .= " LIMIT {$this->limit}";
        }

        $results = Database::fetchAll($sql, $where['params']);
        $models = array_map([$this, 'hydrate'], $results);
        $this->loadRelations($models);
        return $models;
    }

    public function first(): ?object
    {
        $this->limit = 1;
        $results = $this->get();
        return $results[0] ?? null;
    }

    public function count(): int
    {
        $where = $this->buildWhereClause();
        $sql = "SELECT COUNT(*) AS count FROM {$this->table}";
        if (!empty($where['conditions'])) {
            $sql .= " WHERE " . implode(' AND ', $where['conditions']);
        }
        $result = Database::fetch($sql, $where['params']);
        return $result ? (int)$result->count : 0;
    }

    public function paginate(int $perPage = 15): array
    {
        $page = max(1, (int)($_GET['page'] ?? 1));
        $where = $this->buildWhereClause();

        $countSql = "SELECT COUNT(*) AS count FROM {$this->table}";
        if (!empty($where['conditions'])) {
            $countSql .= " WHERE " . implode(' AND ', $where['conditions']);
        }
        $total = (int)Database::fetch($countSql, $where['params'])->count;

        $sql = "SELECT * FROM {$this->table}";
        if (!empty($where['conditions'])) {
            $sql .= " WHERE " . implode(' AND ', $where['conditions']);
        }
        if ($this->orderByColumn) {
            $sql .= " ORDER BY {$this->orderByColumn} {$this->orderByDir}";
        }
        $offset = ($page - 1) * $perPage;
        $sql .= " LIMIT {$perPage} OFFSET {$offset}";

        $results = Database::fetchAll($sql, $where['params']);
        $models = array_map([$this, 'hydrate'], $results);
        $this->loadRelations($models);

        return [
            'items' => $models,
            'total' => $total,
            'perPage' => $perPage,
            'currentPage' => $page,
        ];
    }

    public function delete(): int
    {
        $where = $this->buildWhereClause();
        if (empty($where['conditions'])) {
            return 0;
        }
        $whereSql = implode(' AND ', $where['conditions']);
        return Database::delete($this->table, $whereSql, $where['params']);
    }

    protected function buildWhereClause(): array
    {
        $conditions = [];
        $params = [];

        foreach ($this->wheres as $where) {
            [$column, $operator, $value] = $where;
            $upperOp = strtoupper(trim($operator));

            if ($upperOp === 'IN' && is_array($value)) {
                $placeholders = implode(', ', array_fill(0, count($value), '?'));
                $conditions[] = "{$column} IN ({$placeholders})";
                $params = array_merge($params, array_values($value));
            } else {
                $conditions[] = "{$column} {$operator} ?";
                $params[] = $value;
            }
        }

        foreach ($this->whereHasConstraints as [$relation, $callback]) {
            $resolved = $this->resolveWhereHas($relation, $callback);
            if ($resolved['sql']) {
                $conditions[] = $resolved['sql'];
                $params = array_merge($params, $resolved['params']);
            }
        }

        return ['conditions' => $conditions, 'params' => $params];
    }

    protected function resolveWhereHas(string $relation, ?callable $callback): array
    {
        $config = $this->modelClass::getRelationConfig()[$relation] ?? null;
        if (!$config) {
            return ['sql' => '', 'params' => []];
        }

        $relatedTable = $config['class']::table();

        if ($config['type'] === 'belongsTo') {
            $subSql = "SELECT 1 FROM {$relatedTable} WHERE {$relatedTable}.{$config['ownerKey']} = {$this->table}.{$config['foreignKey']}";
        } else {
            $subSql = "SELECT 1 FROM {$relatedTable} WHERE {$relatedTable}.{$config['foreignKey']} = {$this->table}.{$config['localKey']}";
        }

        $subParams = [];

        if ($callback) {
            $subBuilder = new QueryBuilder($config['class']);
            $callback($subBuilder);
            $subWhere = $subBuilder->buildWhereClause();
            if (!empty($subWhere['conditions'])) {
                $subSql .= " AND (" . implode(' AND ', $subWhere['conditions']) . ")";
                $subParams = $subWhere['params'];
            }
        }

        return ['sql' => "EXISTS ({$subSql})", 'params' => $subParams];
    }

    protected function hydrate(object $data): object
    {
        $model = new $this->modelClass();
        $model->fill((array)$data);
        return $model;
    }

    protected function loadRelations(array $models): void
    {
        if (empty($this->withs) || empty($models)) {
            return;
        }

        $configs = $this->modelClass::getRelationConfig();

        foreach ($this->withs as $relation) {
            $config = $configs[$relation] ?? null;
            if (!$config) {
                continue;
            }

            $relatedClass = $config['class'];

            if ($config['type'] === 'belongsTo') {
                $ids = array_unique(array_filter(array_map(fn($m) => $m->{$config['foreignKey']} ?? null, $models)));
                if (empty($ids)) {
                    continue;
                }
                $relatedModels = $relatedClass::where($config['ownerKey'], 'IN', $ids)->get();
                $relatedMap = [];
                foreach ($relatedModels as $rm) {
                    $relatedMap[$rm->{$config['ownerKey']}] = $rm;
                }
                foreach ($models as $m) {
                    $m->setRelation($relation, $relatedMap[$m->{$config['foreignKey']} ?? null] ?? null);
                }
            } else {
                $ids = array_unique(array_filter(array_map(fn($m) => $m->{$config['localKey']} ?? null, $models)));
                if (empty($ids)) {
                    continue;
                }
                $relatedModels = $relatedClass::where($config['foreignKey'], 'IN', $ids)->get();
                $grouped = [];
                foreach ($relatedModels as $rm) {
                    $grouped[$rm->{$config['foreignKey']}][] = $rm;
                }
                foreach ($models as $m) {
                    $items = $grouped[$m->{$config['localKey']} ?? null] ?? [];
                    $m->setRelation($relation, $config['type'] === 'hasOne' ? ($items[0] ?? null) : $items);
                }
            }
        }
    }
}
