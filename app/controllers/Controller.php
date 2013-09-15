<?php

class Controller {

	protected $f3;
	protected $db;

	function beforeroute() {
		

 		//list of categories
		$cats = new Cat($this->db);
        $this->f3->set('cs',$cats->all());

		//item count (menu)
		$items = new Item($this->db);
		$nb = $items->itemcount();
        $this->f3->set('cgall',$nb);      
 
		//category menu
		$cgs = new CatGroup($this->db);
        $this->f3->set('cgs',$cgs->all());

	}

	function afterroute() {
		echo Template::instance()->render($this->f3->get('tpl'));
	}

	function __construct() {

        $f3=Base::instance();

        $db=new DB\SQL(
            $f3->get('db_dns') . $f3->get('db_name'),
            $f3->get('db_user'),
            $f3->get('db_pass')
        );	

		$this->f3=$f3;
		$this->db=$db;
	}
}
