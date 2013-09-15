<?php

class Item extends DB\SQL\Mapper {

    public function __construct(DB\SQL $db) {
        parent::__construct($db,'items');
    }

    public function all() {
        $this->load();
        return $this->query;
    }

    public function loadpages($pageoffset,$pagelimit) {
		if($pageoffset<0 || $pageoffset>999999999) $pageoffset=0;
        $this->load(array('active>?',0),array('order'=>'datetime DESC','offset'=>$pageoffset,'limit'=>$pagelimit));
        return $this->query;
    }

    public function loadpagesByTok($pageoffset,$pagelimit,$ctok) {
		if($pageoffset<0 || $pageoffset>999999999) $pageoffset=0;
        $this->load(array('cid=?',$ctok),array('order'=>'datetime DESC','offset'=>$pageoffset,'limit'=>$pagelimit));
        return $this->query;
    }


    public function itemcount() {
        return $this->count();

    }

    public function itemcountByTok($tok) {
        return $this->count(array('tok=?',$tok));

    }

    public function catcountByTok($tok) {
        return $this->count(array('cid=?',$tok));

    }

    public function cat($id) {
        $this->load(array('cid=?',$id));
		return $this->query;
    }


    public function add() {
        $this->copyFrom('POST');
        $this->save();
		
    }

    public function getById($id) {
        $this->load(array('tok=?',$id));
        $this->copyTo('POST');
    }

    public function getIdByTok($tok) {
        $this->load(array('tok=?',$tok));
        $this->copyTo('ID');
    }

    public function edit($id) {
        $this->load(array('tok=?',$id));
        $this->copyFrom('POST');
        $this->update();
    }

    public function search($query,$searchlimit) {
        $this->load(array('title LIKE ? OR note LIKE ?',"%$query%","%$query%"),array('order'=>'datetime DESC','limit'=>$searchlimit));
        return $this->query;
    }

    public function deleteCat($cid) {
        $this->load(array('cid=?',$cid));
        $this->copyFrom('POST');
        $this->update();
    }

    public function delete($id) {
        $this->load(array('tok=?',$id));
        $this->erase();
    }
}
