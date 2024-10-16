<?php

namespace app;

use app\Traits\DatabaseManipulate;
use app\Traits\DataParsers;
use app\Traits\Validate;
use Exception;

class Model
{
    use Validate, DataParsers, DatabaseManipulate;

    protected array $data = [];

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function __get(string $name)
    {
        return $this->data[$name] ?? null;
    }

    public function __set(string $name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __isset(string $name): bool
    {
        return isset($this->data[$name]);
    }

    public function getValue(string $key): mixed
    {
        return $this->data[$key] ?? null;
    }

    public function save(): string
    {
        if (!$this->validate()) {
            return json_encode([
                'status' => 'error',
                'message' => 'Não foi possivel salvar os dados',
                'data' => $this->getErrors()
            ]);
        }

        $values = $this->getValids(true);
        $sql = is_null($this->id) ? $this->insert($values) : $this->update($values);

        try {
            (new Database())->run($sql);
        } catch (Exception $e) {
            return json_encode([
                'status' => 'error',
                'message' => 'Não foi possivel salvar os dados',
                'data' => null
            ]);
        }

        return json_encode([
            'status' => 'success',
            'message' => 'Dados salvos',
            'data' => null
        ]);
    }

    public function remove(): string
    {
        if (is_null($this->id)) {
            return json_encode([
                'status' => 'error',
                'message' => 'ID não informado',
            ]);
        }

        $sql = $this->update(['removido_em' => date('Y-m-d H:i:s')]);

        try {
            (new Database())->run($sql);
        } catch (Exception $e) {
            return json_encode([
                'status' => 'error',
                'message' => 'Não foi possivel remover os dados',
                'data' => null
            ]);
        }

        return json_encode([
            'status' => 'success',
            'message' => 'Dados removidos',
            'data' => null
        ]);
    }

    public function restore(): string
    {
        if (is_null($this->id)) {
            return json_encode([
                'status' => 'error',
                'message' => 'ID não informado',
            ]);
        }

        $sql = $this->update(['removido_em' => null]);

        try {
            (new Database())->run($sql);
        } catch (Exception $e) {
            return json_encode([
                'status' => 'error',
                'message' => 'Não foi possivel restaurar os dados',
                'data' => null
            ]);
        }

        return json_encode([
            'status' => 'success',
            'message' => 'Dados restaurados',
            'data' => null
        ]);
    }

    public function destroy(): string
    {
        if (is_null($this->id)) {
            return json_encode([
                'status' => 'error',
                'message' => 'ID não informado',
            ]);
        }

        $sql = $this->delete();

        try {
            (new Database())->run($sql);
        } catch (Exception $e) {
            return json_encode([
                'status' => 'error',
                'message' => 'Não foi possivel remover os dados',
                'data' => null
            ]);
        }

        return json_encode([
            'status' => 'success',
            'message' => 'Dados removidos',
            'data' => null
        ]);
    }

    public static function find(int $id): Model
    {
        $it = new static();
        $sql = "SELECT * FROM {$it->table} WHERE id = $id";
        $result = (new Database())->fetch($sql);

        if ($result) {
            $data = $result[0];
            $dataRow = new \stdClass();

            foreach ($data as $key => $value) {
                $field = $it->fieldParser($key);
                $value = $it->valueParser($field, $value);
                $dataRow->$field = $value;
            }

            return new static((array) $dataRow);
        }

        return new static();
    }

    public static function get($where = []): array
    {
        $it = new static();
        $sql = "SELECT * FROM {$it->table}";

        if ($where) {
            $wheres = [];
            foreach ($where as $key => $value) {
                $field = $it->fieldParser($value[0], true);
                $operator = $value[1];

                $searchValue = match (gettype($value[2])) {
                    'string' => "'{$value[2]}'",
                    'NULL' => 'NULL',
                    default => $value[2]
                };

                $wheres[] = "$field $operator $searchValue";
            }

            $sql .= " WHERE " . implode(' AND ', $wheres);
        }

        $result = (new Database())->fetch($sql);

        if ($result) {
            $data = [];

            foreach ($result as $resultValues) {
                $dataRow = new \stdClass();

                foreach ($resultValues as $key => $value) {
                    $field = $it->fieldParser($key);
                    $value = $it->valueParser($field, $value);
                    $dataRow->$field = $value;
                }

                $data[] = new static((array) $dataRow);
            }

            return $data;
        }

        return [];
    }

    public static function all(): array
    {
        $it = new static();
        $sql = "SELECT * FROM {$it->table}";

        $result = (new Database())->fetch($sql);

        if ($result) {
            $data = [];

            foreach ($result as $resultValues) {
                $dataRow = new \stdClass();

                foreach ($resultValues as $key => $value) {
                    $field = $it->fieldParser($key);
                    $value = $it->valueParser($field, $value);
                    $dataRow->$field = $value;
                }

                $data[] = $dataRow;
            }

            return $data;
        }

        return [];
    }
}
