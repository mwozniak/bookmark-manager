<?php

class TagController extends Controller {

	public function index()
    {

		$tgs = new TagGroup($this->db);
        $this->f3->set('tgs',$tgs->all());
        $this->f3->set('header','Tag List');

        //template        
        $this->f3->set('view','tags/list.htm');

		//menu
		$this->f3->set('topmenu','t');

		//breadcrumbs
		$this->f3->set('breadcrumb', array( array("url" => NULL, "name" => "Tags") ));

		//display messages (if not empty) and clear values
		if($this->f3->get('COOKIE.message')){ $this->f3->set('message',$this->f3->get('COOKIE.message')); $this->f3->set('COOKIE.message','');}
		if($this->f3->get('COOKIE.messagetype')){ $this->f3->set('messagetype',$this->f3->get('COOKIE.messagetype')); $this->f3->set('COOKIE.messagetype',''); }

	}

	public function page()
    {


		 //0-based pagination
		if($this->f3->get('PARAMS.number')!=''){
			$pagenumber = $this->f3->get('PARAMS.number')-1;
 		}else{
			$pagenumber=0;
		}

		$tgsc = new TagGroup($this->db);
		$tgscount = $tgsc->tagcount();


		$tgs = new TagGroup($this->db);
		//$tgsall = $tgs->all();
		$tgsall = $tgs->loadpages($pagenumber*$this->f3->get('alltagslimit'),$this->f3->get('alltagslimit'));

		//number of items
		$this->f3->set('alltagscount',$tgscount);

		$this->f3->set('tgs',$tgsall);
        $this->f3->set('header','Tag List');
        
        //template        
        $this->f3->set('view','tags/list.htm');

		//menu
		$this->f3->set('topmenu','t');


		//pagination
		$this->f3->set('pagecount',ceil($this->f3->get('alltagscount')/$this->f3->get('alltagslimit')));
		$this->f3->set('page',$pagenumber);
		$this->f3->set('pagemodule','t');
		
		//breadcrumbs
		$this->f3->set('breadcrumb', array( array("url" => NULL, "name" => "Tags") ));

		//display messages (if not empty) and clear values
		if($this->f3->get('COOKIE.message')){ $this->f3->set('message',$this->f3->get('COOKIE.message')); $this->f3->set('COOKIE.message','');}
		if($this->f3->get('COOKIE.messagetype')){ $this->f3->set('messagetype',$this->f3->get('COOKIE.messagetype')); $this->f3->set('COOKIE.messagetype',''); }

	}


	public function tag()
    {

		$tgs = new ItemsTag($this->db);
        $this->f3->set('its',$tgs->getitems($this->f3->get('PARAMS.tok')));
        $this->f3->set('header','Item List');
        
        $this->f3->set('label', $this->f3->get('POST.label'));
        
        //template        
        $this->f3->set('view','tags/items.htm');

		//menu
		$this->f3->set('topmenu','t');

		//breadcrumbs
		$this->f3->set('breadcrumb', array( array("url" => "/t", "name" => "Tags"), array("url" => NULL, "name" => $this->f3->get('POST.label')) ));

	}



	public function tagpage()
    {

		 //0-based pagination
		if($this->f3->get('PARAMS.number')!=''){
			$pagenumber = $this->f3->get('PARAMS.number')-1;
 		}else{
			$pagenumber=0;
		}

		$tgsc = new ItemsTag($this->db);
		$tgscount = $tgsc->tagcount($this->f3->get('PARAMS.tok'));

		//load items list
		$tgs = new ItemsTag($this->db);
		$tgsitems = $tgs->loadtagpages($this->f3->get('PARAMS.tok'), $pagenumber*$this->f3->get('onetaglimit'),$this->f3->get('onetaglimit'));

        $this->f3->set('its',$tgsitems);
        $this->f3->set('header','Item List');
        
		//load tags
		$tgsl = new ItemsTag($this->db);
		$tgsl->getitems($this->f3->get('PARAMS.tok'));
		

        $this->f3->set('label', $this->f3->get('POST.label'));
        
        //template        
        $this->f3->set('view','tags/items.htm');

		//number of items
		$this->f3->set('onetagcount',$tgscount);


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


		//menu
		$this->f3->set('topmenu','t');

		//pagination
		$this->f3->set('pagecount',ceil($this->f3->get('onetagcount')/$this->f3->get('onetaglimit')));
		$this->f3->set('page',$pagenumber);
		$this->f3->set('pagemodule','t/'.$this->f3->get('PARAMS.tok'));


		//breadcrumbs
		$this->f3->set('breadcrumb', array( array("url" => "/t", "name" => "Tags"), array("url" => NULL, "name" => $this->f3->get('POST.label')) ));

	}





	public function tg()
    {
		$onec = new Item($this->db);
        $this->f3->set('items',$onec->cat($this->f3->get('PARAMS.tok')));
        $this->f3->set('catmenu',$this->f3->get('PARAMS.tok'));
        $this->f3->set('header','tg List');

        //template        
        $this->f3->set('view','item/list.htm');
	}
 

    public function create()
    {

        if($this->f3->exists('POST.name'))
        {


			//get unique tok
			$utok = new Tag($this->db);
			$randtok = rand(100000000,999999999);
			while($utok->tagcountByTok($randtok)>1){
				$randtok = rand(100000000,999999999);
			}


            //variables
            $tg = new Tag($this->db);
			$tg->tok=$randtok;
			$tg->url=toUrl($this->f3->get('POST.name'));
            $tg->add();

            $this->f3->reroute('/t');
        }  

    }

    public function update()
    {

        $tg = new Tag($this->db);

        if($this->f3->exists('POST.update'))
        {




			$this->f3->set('POST.url',toUrl($this->f3->get('POST.title')));        
			$this->f3->set('POST.title',preg_replace('|[^0-9A-Za-z\-\/+]|', '', $this->f3->get('POST.title')) );        
			$this->f3->set('POST.label',preg_replace('|[^0-9A-Za-z\-\/+]|', '', $this->f3->get('POST.label')) );        
 

			
			$tg->edit($this->f3->get('POST.tok'));

			$this->f3->set('COOKIE.message','The tag has been successfully saved!');
			$this->f3->set('COOKIE.messagetype','alert-success hide5s');
			$this->f3->reroute('/t');
			
        } else
        {
            $tg->getById($this->f3->get('PARAMS.tok'));
            $this->f3->set('tags',$tg);
            $this->f3->set('header','Update Tag');
            
            //template            
            $this->f3->set('view','tags/update.htm');

			//menu
			$this->f3->set('topmenu','t');

			//breadcrumbs
			$this->f3->set('breadcrumb', array( array("url" => "/t", "name" => "Tags"), array("url" => NULL, "name" => "Update Tag") ));


        }

    }

    public function delete()
    {
        if($this->f3->exists('PARAMS.tok'))
        {
 
			//getIdByTok
			$getIdByTok = new Tag($this->db);
			$getIdByTok->getIdByTok($this->f3->get('PARAMS.tok'));
			$tid = $this->f3->get('ID.id');

			 
			//del by ID       
			$tags = new Tag2Item($this->db);
            $loaderase = $tags->getByTagId($tid);

			if(count($loaderase)>0){
				 
				foreach ($loaderase as $t) 
				{
				$itemid = $t['id'];
 				$deltags = new Tag2Item($this->db);
				$deltags->delete($itemid);
				
				}
				 
			}



            $tg = new Tag($this->db);
            $tg->delete($this->f3->get('PARAMS.tok'));

 

        }

		$this->f3->set('COOKIE.message','Tag was deleted');
		$this->f3->set('COOKIE.messagetype','alert-info hide5s');
        $this->f3->reroute('/t');
    }

 

}
