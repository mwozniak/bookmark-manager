<?php

class CatGroup extends DB\SQL\Mapper {

    public function __construct(DB\SQL $db) {
        parent::__construct($db,'catgroup');
    }

    public function all() {
        $this->load();
        return $this->query;
    }

 
}
