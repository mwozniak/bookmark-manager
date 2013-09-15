<?php

class Tag extends DB\SQL\Mapper {

    public function __construct(DB\SQL $db) {
        parent::__construct($db,'tags');
    }

    public function all() {
        $this->load();
        return $this->query;
    }


    public function add() {
        //$this->copyFrom('TAGS');
        $this->save();
    }

	#tags for item
    public function itemtags($tok) {

		$this->exec('SELECT label, url, tok  FROM tags e JOIN tag2item ON e.id=tag2item.tid WHERE tag2item.iid=?',$tok);
        return $this->query;
    }

    public function tagcountByTok($tok) {
        return $this->count(array('tok=?',$tok));

    }
    
	public function getIdByTok($tok) {
        $this->load(array('tok=?',$tok));
        $this->copyTo('ID');
    }

    public function getById($id) {
        $this->load(array('tok=?',$id));
        $this->copyTo('POST');
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
        $this->load(array('tok=?',$id));
        $this->erase();
    }
}
