<?php

namespace app\Traits;

trait DataParsers
{
    public function toArray(): array
    {
        return $this->data;
    }

    public function toJson(): string
    {
        return json_encode($this->data);
    }

    private function fieldParser(string $field, bool $reverse = false): string
    {
        $parser = $this->fieldParsers ?? [];

        if ($reverse) {
            $search = array_search($field, $parser);

            if ($search !== false) {
                return $search;
            }

            return $field;
        }

        return $parser[$field] ?? $field;
    }

    private function valueParser(string $field, mixed $value): mixed
    {
        $type = $this->fields[$field] ?? 'string';

        return match ($type) {
            'string' => is_null($value) ? null : (string) $value,
            'float' => is_null($value) ? null : (float) $value,
            'datetime' => is_null($value) ? null : new \DateTime($value),
            default => $value,
        };
    }
}
