<?php

class Cat extends DB\SQL\Mapper {

    public function __construct(DB\SQL $db) {
        parent::__construct($db,'cats');
    }

    public function all() {
        $this->load();
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

    public function catcountByUrl($url) {
		return $this->count(array('url=?',$url));
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
    public function catcountByTok($tok) {
        return $this->count(array('tok=?',$tok));

    }
    public function delete($id) {
        $this->load(array('tok=?',$id));
        $this->erase();
    }
}
