<?php

namespace App\Helpers\ProcedureUnits;

class CreateData
{
    private $title;
    private $state;
    private $description;
    private $secretary;
    private $forms;
    private $theme;
    private $attachments;
    private $citizen_level;

    private $price; 
    private $c;
    private $content_id;
    private $orf_id;
    private $url;
    private $created_by;
    // attachments

    public function __construct(array $data)
    {
        $this->title = $data['title'];
        $this->state = $data['state'];
        $this->secretary = $data['secretary'];
        $this->description = $data['description'];
        $this->forms = trim(json_encode($data['forms']), '"');;
        $this->attachments = trim(json_encode($data['attachments']), '"');;
        $this->citizen_level = $data['citizen_level'];

        $this->price = $data['price'];
        $this->c = $data['c'];
        $this->content_id = $data['content_id'];
        $this->orf_id = $data['orf_id'];
        $this->url = $data['url'];
        $this->created_by = $data['created_by'];

        if (isset($data['theme'])) {
            $this->theme = $data['theme'];
        }else{
            $this->theme = "";
        }

    }

    public function get(string $key)
    {
        $array = $this->toArray();

        return $array[$key];
    }

    public function toArray()
    {
        return [
            'title' => $this->title,
            'state' => $this->state,
            'secretary'=> $this->secretary,
            'description' => $this->description,
            'forms' => $this->forms,
            'theme' => $this->theme,
            'attachments' => $this->attachments,
            'citizen_level'=> $this->citizen_level,
            'price'=> $this->price,
            'url'=> $this->url,
            'c'=> $this->c,
            'content_id'=> $this->content_id,
            'orf_id'=> $this->orf_id,
            'created_by' => $this->created_by,

        ];
    }
}

