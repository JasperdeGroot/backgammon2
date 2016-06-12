<?php

namespace Model\Registry;


/**
 *
 * @author
 */
interface IRegistry {
    const DATETIME_FORMAT = \DateTime::ISO8601;

    public function insert($row);
    public function delete($primaryKey, $value);
    public function update($primaryKey, $primaryValue, $updatedValue);
    public function get($primaryKey, $value);

}
