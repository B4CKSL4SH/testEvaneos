<?php

class Quote
{
    private $id;
    private $siteId;
    private $destinationId;
    private $dateQuoted;

    public function __construct($id, $siteId, $destinationId, $dateQuoted)
    {
        $this->id = $id;
        $this->siteId = $siteId;
        $this->destinationId = $destinationId;
        $this->dateQuoted = $dateQuoted;
    }

    public static function renderHtml(Quote $quote)
    {
        return '<p>' . $quote->id . '</p>';
    }

    public static function renderText(Quote $quote)
    {
        return (string)$quote->id;
    }

    public function getSiteId()
    {
        return $this->siteId;
    }

    public function getDestinationId()
    {
        return $this->destinationId;
    }

    public function getDateQuoted()
    {
        return $this->dateQuoted;
    }

    public function getId()
    {
        return $this->id;
    }
}