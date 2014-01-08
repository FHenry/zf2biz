<?php

namespace Galerie\Model;

class GalerieA
{
    public $id;
    public $id_user;
    public $name;
    public $description;
    public $created;
    public $updated;

    public function exchangeArray($data)
    {
        $this->id = isset($data['id']) ? $data['id'] : null;
        $this->id_user = isset($data['is_user']) ? $data['id_user'] : null;
        $this->name = isset($data['name']) ? $data['name'] : null;
        $this->description = isset($data['description']) ? $data['description'] : null;
        $this->created = isset($data['created']) ? $data['created'] : null;
        $this->updated = isset($data['updated']) ? $data['updated'] : null;
    }

    public function toArray($data=null)
    {
        return array(
            'id' => $this->id,
            'is_user' => $this->id_user,
            'name' => $this->name,
            'description' => $this->description,
            'created' => $this->created,
            'updated' => $this->updated,
        );
    }

}
