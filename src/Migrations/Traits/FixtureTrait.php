<?php

declare(strict_types=1);

namespace App\Migrations\Traits;

trait FixtureTrait
{
    /**
     * Заполняет указанную таблицу данными
     *
     * @param string $table
     * @param array $data
     */
    private function insert(string $table, array $data): void
    {
        $id = 1;

        foreach ($data as $value) {
            if (!is_array($value)) {
                $this->addSql("INSERT INTO $table VALUES (?, ?)", [
                    $id, $value,
                ]);
            } else {
                $values = $this->getValues($value);
                $this->addSql("INSERT INTO $table VALUES ($values)", $this->getData($id, $value));
            }
            $id++;
        }

        $table .= '_id_seq';

        $this->addSql("ALTER SEQUENCE $table RESTART WITH $id");
    }

    /**
     * Удаляет данные из указанной таблицы
     *
     * @param string $table
     */
    private function delete(string $table): void
    {
        $this->addSql("DELETE FROM $table");
        $table .= '_id_seq';
        $this->addSql("ALTER SEQUENCE $table RESTART WITH 1");
    }

    /**
     * Собирает строку вида '?, ?, ?, ?' на основе количества передаваемых параметров
     *
     * @param array $params
     * @return string
     */
    private function getValues(array $params): string
    {
        $count = count($params) + 1;
        $string = '';
        for ($i = 0; $i < $count; $i++) {
            $string .= '?, ';
        }

        return mb_substr($string, 0, -2);
    }

    /**
     * Добавляет к данным id первым значением
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    private function getData(int $id, array $data): array
    {
        array_unshift($data, $id);
        return $data;
    }
}
