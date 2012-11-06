<?php
namespace Custom\Model;

class Entity
{

    public function exchangeArray($data, $overwrite=true)
    {
        foreach($this->columns as $col) {
            if (array_key_exists($col, $data)) {
                $this->$col = $data[$col];
            } elseif ($overwrite) {
                $this->$col = null;
            }
        }
    }

    public function toArray() {
        $result = array();
        foreach($this->columns as $col) {
            $result[$col] = $this->$col;
        }
        return $result;
    }

    public function toUpdatableArray() {
        $result = array();
        foreach($this->updatable_columns as $col) {
            $result[$col] = $this->$col;
        }
        return $result;
    }

    public function toPrimaryArray() {
        $result = array();
        foreach($this->primary_columns as $col) {
            $result[$col] = $this->$col;
        }
        return $result;
    }
}
