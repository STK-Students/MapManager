<?php

/**
 * Holds metadata about a GeoService that is managed by the MapManager.
 */
class GeoService
{
    private $uuid;
    private $name;
    private $description;
    private $creationDate;
    private $groupUUID;


    function __construct($uuid, $name, $description, $creationDate, $groupUUID)
    {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->description = $description;
        $this->creationDate = $creationDate;
        $this->groupUUID = $groupUUID;
    }

    // getter
    function getUUID()
    {
        return $this->uuid;
    }

    function getName()
    {
        return $this->name;
    }

    function getDescription()
    {
        return $this->description;
    }

    function getCreationDate()
    {
        return $this->creationDate;
    }

    function getGroupUUID()
    {
        return $this->groupUUID;
    }

    // setter
    function setName($name)
    {
        $this->name = $name;
    }

    function setDescription($description)
    {
        $this->description = $description;
    }

    function setCreation($creationDate)
    {
        $this->creationDate = $creationDate;
    }

    /**
     * @return string the path to the directory of the group this service belongs to. Relative to the document root.
     *
     */
    function getGroupPath(): string {
        return $groupPath = "/mapfiles/" . $this->getGroupUUID();
    }

    /**
     * @return string the path to the mapfile of this service. Relative to the document root.
     */
    function getPath(): string {
        return $this->getGroupPath() . "/" . $this->getUUID() .".map";
    }
}