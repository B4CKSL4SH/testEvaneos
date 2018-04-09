<?php

class Destination
{
    private $id;
    private $countryName;
    private $conjunction;
    private $computerName;

    public function __construct($id, $countryName, $conjunction, $computerName)
    {
        $this->id = $id;
        $this->countryName = $countryName;
        $this->conjunction = $conjunction;
        $this->computerName = $computerName;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCountryName()
    {
        return $this->countryName;
    }

    public function getConjunction()
    {
        return $this->conjunction;
    }

    public function getComputerName()
    {
        return $this->computerName;
    }
}
