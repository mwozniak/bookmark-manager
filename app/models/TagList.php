<?php

class TagList extends DB\SQL\Mapper {

    public function __construct(DB\SQL $db) {
        parent::__construct($db,'taglist');
    }

    public function getTags() {
        $this->load();
        $this->copyTo('TAGS');
        return $this->query;
    }

    public function getItemTags($tok) {
        $this->load(array('itok=?',$tok));
        $this->copyTo('TAGS');
        return $this->query;
    }

    public function getCatTags($tok) {
        $this->load(array('ctok=?',$tok));
        $this->copyTo('TAGS');
        return $this->query;
    }
 
}



