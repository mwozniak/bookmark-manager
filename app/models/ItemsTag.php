<?php

class ItemsTag extends DB\SQL\Mapper {

    public function __construct(DB\SQL $db) {
        parent::__construct($db,'itemstag');
    }

    public function getitems($tok) {
        $this->load(array('tagtok=?',$tok));
        $this->copyTo('POST');
        return $this->query;
    }
    public function loadtagpages($paramstok,$pageoffset,$pagelimit) {
		if($pageoffset<0 || $pageoffset>999999999) $pageoffset=0;
        $this->load(array('tagtok=?',$paramstok),array('offset'=>$pageoffset,'limit'=>$pagelimit));
        return $this->query;
    } 
    public function tagcount($tok) {
        return $this->count(array('tagtok=?',$tok));

    } 
}



