<?php

class TagGroup extends DB\SQL\Mapper {

    public function __construct(DB\SQL $db) {
        parent::__construct($db,'taggroup');
    }

    public function all() {
        $this->load();
        return $this->query;
    }


    public function loadpages($pageoffset,$pagelimit) {
		if($pageoffset<0 || $pageoffset>999999999) $pageoffset=0;
        $this->load(NULL,array('order'=>'tagcount DESC','offset'=>$pageoffset,'limit'=>$pagelimit));
        return $this->query;
    } 

    public function tagcount() {
        return $this->count();

    }


}
