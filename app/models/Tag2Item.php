<?php

class Tag2Item extends DB\SQL\Mapper {

    public function __construct(DB\SQL $db) {
        parent::__construct($db,'tag2item');
    }

    public function all() {
        $this->load();
        return $this->query;
    }

    public function add() {
        $this->save();
    }

    public function getById($id) {
        $this->load(array('tok=?',$id));
        $this->copyTo('POST');
    }

    public function getByItemId($id) {
        $this->load(array('iid=?',$id));
        return $this->query;
    }
    public function getByTagId($id) {
        $this->load(array('tid=?',$id));
        return $this->query;
    }

    public function getByName($name) {
        $this->load(array('title=?',$name));
        $this->copyTo('TAGS');
    }

    public function edit($id) {
        $this->load(array('tok=?',$id));
        $this->copyFrom('POST');
        $this->update();
    }

	 public function delete($id) {
        $this->load(array('id=?',$id));
        $this->erase();
    }
 
 


}
