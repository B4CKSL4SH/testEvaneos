<?php

class Template
{
    private $id;
    private $subject;
    private $content;

    public function __construct($id, $subject, $content)
    {
        $this->id = $id;
        $this->subject = $subject;
        $this->content = $content;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }
}