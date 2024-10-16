<?php

namespace app\Traits;

trait DatabaseManipulate
{
    private function insert(array $values): string
    {
        $sql = "INSERT INTO {$this->table} (";
        $sql .= implode(', ', array_keys($values));
        $sql .= ") VALUES (";
        $sql .= implode(', ', array_map(function ($value) {
            return match (gettype($value)) {
                'string' => "'$value'",
                'NULL' => 'NULL',
                default => $value
            };
        }, $values));
        $sql .= ")";

        return $sql;
    }

    private function update(array $values): string
    {
        $sql = "UPDATE {$this->table} SET ";
        $sql .= implode(', ', array_map(function ($key, $value) {
            return "$key = " . match (gettype($value)) {
                'string' => "'$value'",
                'NULL' => 'NULL',
                default => $value
            };
        }, array_keys($values), $values));
        $sql .= " WHERE id = {$this->id}";

        return $sql;
    }

    public function delete(): string
    {
        $sql = "DELETE FROM {$this->table} WHERE id = {$this->id}";

        return $sql;
    }
}
