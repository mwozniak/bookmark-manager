<?php

class ItemController extends Controller {

	public function index()
    {


		$item = new Item($this->db);
        //$loaditems = $item->all(); //or load without pagination
		$loaditems = $item->loadpages(0*$this->f3->get('itemlimit'),$this->f3->get('itemlimit'));
        $this->f3->set('items',$loaditems);
        $this->f3->set('page_head','Item List');
    
        //template    
        $this->f3->set('view','item/list.htm');

		//assigne tags to items
		$tgs = new TagList($this->db);
		$tgslst = $tgs->gettags();
		$tgsarray = array();
		$j=0;
		foreach($tgslst as $i){
			$tgsarray[$i['itok']][$j]['tok']=$i['tok'];
			$tgsarray[$i['itok']][$j]['url']=$i['url'];
			$tgsarray[$i['itok']][$j]['label']=$i['label'];
			$j++;
		}

		$this->f3->set('tgsarray',$tgsarray);
		$this->f3->set('tgs',$tgslst);

		//breadcrumbs
        $this->f3->set('breadcrumb', array( array("url" => NULL, "name" => "Bookmark List") ));

		//pagination
		$this->f3->set('page',0);

	}



	public function page()
    {

		//number of items
		$this->f3->set('itemcount',$this->f3->get('cgall'));

		 //0-based pagination
		if($this->f3->get('PARAMS.number')!=''){
			$pagenumber = $this->f3->get('PARAMS.number')-1;
 		}else{
			$pagenumber=0;
		}
		
		$item = new Item($this->db);
		$loaditems = $item->loadpages($pagenumber*$this->f3->get('itemlimit'),$this->f3->get('itemlimit'));
        $this->f3->set('items',$loaditems);
        $this->f3->set('page_head','Item List');

        //template        
        $this->f3->set('view','item/list.htm');

		//assigne tags to items
		$tgs = new TagList($this->db);
		$tgslst = $tgs->gettags();

		$tgsarray = array();
		$j=0;
		foreach($tgslst as $i){
			$tgsarray[$i['itok']][$j]['tok']=$i['tok'];
			$tgsarray[$i['itok']][$j]['url']=$i['url'];
			$tgsarray[$i['itok']][$j]['label']=$i['label'];
			$j++;
		}
		
		$this->f3->set('tgsarray',$tgsarray);
		$this->f3->set('tgs',$tgslst);

		//pagination
		$this->f3->set('pagecount',ceil($this->f3->get('itemcount')/$this->f3->get('itemlimit')));
		$this->f3->set('page',$pagenumber);
		

		//breadcrumbs
		$this->f3->set('breadcrumb', array( array("url" => NULL, "name" => "Bookmarks") ));
		

		//display messages (if not empty) and clear values
		if($this->f3->get('COOKIE.message')){ $this->f3->set('message',$this->f3->get('COOKIE.message')); $this->f3->set('COOKIE.message','');}
		if($this->f3->get('COOKIE.messagetype')){ $this->f3->set('messagetype',$this->f3->get('COOKIE.messagetype')); $this->f3->set('COOKIE.messagetype',''); }


	}


    public function create()
    {
					
            $this->f3->set('page_head','Create Bookmark');
            
            //template
            $this->f3->set('view','item/create.htm');
            
			//menu
			$this->f3->set('topmenu','i');

        if($this->f3->exists('POST.title') )
        {



            if($this->f3->get('POST.title')!=''){

			//strip all tags and unsafe characters

			$t = $this->f3->get('POST.title');
			$this->f3->set('POST.title', $this->f3->scrub($t));
			$t = $this->f3->get('POST.url');
			$this->f3->set('POST.url', $this->f3->scrub($t));
			$t = $this->f3->get('POST.note');
			$this->f3->set('POST.note', $this->f3->scrub($t));
			$t = $this->f3->get('POST.cid');
			$this->f3->set('POST.cid', $this->f3->scrub($t));
			$t = $this->f3->get('POST.tags');
			$this->f3->set('POST.tags', $this->f3->scrub($t));
			

			//server side validation


			//if too long title
			if( strlen($this->f3->get('POST.title'))>256 ){
				$this->f3->set('COOKIE.message','the title cannot be longer than 256 chars!');
				$this->f3->set('COOKIE.messagetype','alert-danger hide10s');
				$this->f3->reroute('/i/create');
			}


			//if too long url
			if( strlen($this->f3->get('POST.url'))>256 ){
				$this->f3->set('COOKIE.message','the url cannot be longer than 256 chars!');
				$this->f3->set('COOKIE.messagetype','alert-danger hide10s');
				$this->f3->reroute('/i/create');
			}


			//if too long note
			if( strlen($this->f3->get('POST.note'))>20000 ){
				$this->f3->set('COOKIE.message','the note cannot be longer than 20000 chars!');
				$this->f3->set('COOKIE.messagetype','alert-danger hide10s');
				$this->f3->reroute('/i/create');
			}


			//if cat is not numeric
			if( !is_numeric($this->f3->get('POST.cid')) ){
				
				//$this->f3->set('COOKIE.message','the category ID must be numeric!');
				//$this->f3->set('COOKIE.messagetype','alert-danger hide10s');
				//$this->f3->reroute('/i/create');
				$this->f3->set('POST.cid',0);
			}


			//if too long tags
			if( strlen($this->f3->get('POST.tags'))>5000 ){
				$this->f3->set('COOKIE.message','tags cannot be longer than 5000 chars!');
				$this->f3->set('COOKIE.messagetype','alert-danger hide10s');
				$this->f3->reroute('/i/create');
			}





			//get unique tok
            $utok = new Item($this->db);
			$randtok = rand(100000000,999999999);
			while($utok->itemcountByTok($randtok)>0){
				$randtok = rand(100000000,999999999);
			}


			//variables

            $item = new Item($this->db);
			$item->tok=$randtok;

            $item->add();
            
            //last inserted id
			$iid = $item->_id;

			//add tags

			if($this->f3->exists('POST.tags'))
			{
				$tid = 0;
				$tags = explode(',',$this->f3->get('POST.tags'));

				foreach ($tags as $t) {
					$t = trim($t);
					if($t!=''){
					
						$this->f3->clear('TAGS');
						$ifexists = new Tag($this->db);
					    $ifexists->getByName(strtolower($t));

						//get id
						if($this->f3->exists('TAGS.id')){
							$tid = $this->f3->get('TAGS.id');
						}else{
						
						//insert new tag
						$newtag = new Tag($this->db);
					    $newtag->title=strtolower(preg_replace('|[^0-9A-Za-z \-\/+]|', '', $t));
					    $newtag->label=preg_replace('|[^0-9A-Za-z \-\/+]|', '', $t);
					    $newtag->url=toUrl($t);

						//get unique tok
						$utok = new Tag($this->db);
						$randtok = rand(100000000,999999999);
						while($utok->tagcountByTok($randtok)>0){
							$randtok = rand(100000000,999999999);
						}
						
						
						$newtag->tok=$randtok;
					    
						$newtag->add();
						
						//get last inserted id
						$tid = $newtag->_id;


						}


						
						//add to Tag2Item
						$t2i = new Tag2Item($this->db);

						//insert lastinsertedid
					    $t2i->tid=$tid;
					    $t2i->iid=$iid;
						$t2i->add();
					}



				}

			}

			}
			
			 if($this->f3->get('POST.title')!=''){
				$this->f3->set('COOKIE.message','Bookmark was created');
				$this->f3->set('COOKIE.messagetype','alert-success hide5s');
				$this->f3->reroute('/');
			 }else{

			//if not valid
			$this->f3->set('message','The field title is required!');
			$this->f3->set('messagetype','alert-error hide5s');

			 }
        }  

		//breadcrumbs
        $this->f3->set('breadcrumb', array( array("url" => NULL, "name" => "Create bookmark") ));



    }



    public function update()
    {

        $item = new Item($this->db);

        if($this->f3->exists('POST.update'))
        {
            $item->edit($this->f3->get('POST.tok'));

            //get id getIdByTok
			$getIdByTok = new Item($this->db);
			$getIdByTok->getIdByTok($this->f3->get('POST.tok'));
			$iid = $this->f3->get('ID.id');


			//del tags
			$tgs = new Tag2Item($this->db);
			$tgs->getByItemId($iid);
			if(count($tgs->getByItemId($iid))>0){
				 
				foreach ($tgs->getByItemId($iid) as $t) 
				{
				$itemid = $t['id'];
				$delti = new Tag2Item($this->db);
				$delti->delete($itemid);
				
				}
				 
			}


			//update tags
			$tags = explode(',',$this->f3->get('POST.tags'));
			foreach ($tags as $t) 
			{
					$t = trim($t);
					if($t!=''){
					
						$this->f3->clear('TAGS');
						$ifexists = new Tag($this->db);
					    $ifexists->getByName(strtolower($t));

						if($this->f3->exists('TAGS.id')){
							$tid = $this->f3->get('TAGS.id');
						}else{
						
						//insert new tag
						$newtag = new Tag($this->db);
					    $newtag->title=strtolower(preg_replace('|[^0-9a-z \-\/+]|', '', $t));
					    $newtag->label=preg_replace('|[^0-9A-Za-z \-\/+]|', '', $t);
					    $newtag->url=toUrl($t);
						
						//get unique tok
						$utok = new Tag($this->db);
						$randtok = rand(100000000,999999999);
						while($utok->tagcountByTok($randtok)>0){
							$randtok = rand(100000000,999999999);
						}
						
						$newtag->tok=$randtok;

					    $newtag->add();
						
						$tid = $newtag->_id;


						}
						
						//add to Tag2Item
						$t2i = new Tag2Item($this->db);
						
						//insert lastinsertedid
					    $t2i->tid=$tid;
					    $t2i->iid=$iid;
						$t2i->add();
					}
			}	

			
			$this->f3->set('COOKIE.message','The bookmark has been successfully saved!');
			$this->f3->set('COOKIE.messagetype','alert-success hide5s');
            $this->f3->reroute('/');
            
        } else
        {
            $item->getById($this->f3->get('PARAMS.tok'));
            $this->f3->set('item',$item);
			$this->f3->set('active',$this->f3->get('PARAMS.tok'));
            $this->f3->set('page_head','Update Item');
            
            //template
            $this->f3->set('view','item/update.htm');

			$tgs = new TagList($this->db);
			$this->f3->set('tgs',$tgs->getitemtags($this->f3->get('POST.tok')));
			$this->f3->set('ctrcount',count($tgs->getitemtags($this->f3->get('POST.tok'))));

			//menu
			$this->f3->set('topmenu','i');

			//breadcrumbs
			$this->f3->set('breadcrumb', array( array("url" => NULL, "name" => "Update bookmark") ));

 
		}


    }

    public function delete()
    {
        if($this->f3->exists('PARAMS.tok'))
        {

            //getIdByTok
			$getIdByTok = new Item($this->db);
			$getIdByTok->getIdByTok($this->f3->get('PARAMS.tok'));
			$iid = $this->f3->get('ID.id');

			 
			// del by ID (not by TOK)       
			$tags = new Tag2Item($this->db);
            $loaderase = $tags->getByItemId($iid);

			if(count($loaderase)>0){
				 
				foreach ($loaderase as $t) 
				{
				$itemid = $t['id'];
 				$deltags = new Tag2Item($this->db);
				$deltags->delete($itemid);
				
				}
				 
			}


            $item = new Item($this->db);
            $item->delete($this->f3->get('PARAMS.tok'));
        }


		$this->f3->set('COOKIE.message','The bookmark was deleted');
		$this->f3->set('COOKIE.messagetype','alert-info hide5s');
        $this->f3->reroute('/');
    }

 



    public function search()
    {
 
		$this->f3->reroute('/q/'.urlencode(preg_replace("/[^a-zA-Z0-9]+/", " ", $this->f3->get('POST.query'))));

	}



    public function query()
    {
 

		$item = new Item($this->db);
		$loaditems = $item->search($this->f3->get('PARAMS.query'),$this->f3->get('searchlimit'));

        //template
		$this->f3->set('view','item/list.htm');
		
		$this->f3->set('items',$loaditems);
		$this->f3->set('q',$this->f3->get('PARAMS.query'));

		//breadcrumbs
		$this->f3->set('breadcrumb', array( array("url" => NULL, "name" => "Search") ));
		$this->f3->set('header',$this->f3->get('PARAMS.query'));

	}




}
