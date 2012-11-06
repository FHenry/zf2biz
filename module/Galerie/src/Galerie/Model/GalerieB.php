<?php

namespace Galerie\Model;

class GalerieB
{
    protected $id;
    protected $id_user;
    protected $name;
    protected $description;
    protected $created;
    protected $updated;


    public function exchangeArray($data)
    {
        $this->id = isset($data['id']) ? $data['id'] : null;
        $this->id_user = isset($data['is_user']) ? $data['id_user'] : null;
        $this->name = isset($data['name']) ? $data['name'] : null;
        $this->description = isset($data['description']) ? $data['description'] : null;
        $this->created = isset($data['created']) ? $data['created'] : null;
        $this->updated = isset($data['updated']) ? $data['updated'] : null;
    }

    public function toArray($data)
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


    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getIdUser()
    {
        return $this->id_user;
    }

    public function setIdUser($id_user)
    {
        $this->id_user = $id_user;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription ($description)
    {
        $this-> description = $description;
        return $this;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    public function setUpdated($updated)
    {
        $this->updated = $updated;
        return $this;
    }

}
