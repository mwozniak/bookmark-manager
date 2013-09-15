<?php

class CategoryController extends Controller {

	public function index()
    {

		$category = new Cat($this->db);
        $this->f3->set('categoires',$category->all());
        $this->f3->set('header','Category List');
        
        //template
        $this->f3->set('view','cats/list.htm');

		//menu
		$this->f3->set('topmenu','c');

		//breadcrumbs
		$this->f3->set('breadcrumb', array( array("url" => NULL, "name" => "Categories") ));

		//display messages (if not empty) and clear values
		if($this->f3->get('COOKIE.message')){ $this->f3->set('message',$this->f3->get('COOKIE.message')); $this->f3->set('COOKIE.message','');}
		if($this->f3->get('COOKIE.messagetype')){ $this->f3->set('messagetype',$this->f3->get('COOKIE.messagetype')); $this->f3->set('COOKIE.messagetype',''); }

	}

	public function category()
    {
		$onec = new Item($this->db);
        $this->f3->set('items',$onec->cat($this->f3->get('PARAMS.tok')));
        $this->f3->set('catmenu',$this->f3->get('PARAMS.tok'));
        $this->f3->set('header','Category List');
        
        $this->f3->set('view','item/list.htm');

		//breadcrumbs
		$this->f3->set('breadcrumb', array( array("url" => "/c", "name" => "Categories"), array("url" => NULL, "name" => $this->f3->get('PARAMS.name')) ));

	}
 

	public function catpage()
    {


		 //0-based pagination
		if($this->f3->get('PARAMS.number')!=''){
			$pagenumber = $this->f3->get('PARAMS.number')-1;
 		}else{
			$pagenumber=0;
		}

 

		//number of items
		$catcount = new Item($this->db);
		$catcount_number = $catcount->catcountByTok($this->f3->get('PARAMS.tok'));
		$this->f3->set('catcount_number',$catcount_number);


		$tgs = new Item($this->db);
		//$catsall = $tgs->all(); //without pagination
		$catsall = $tgs->loadpagesByTok($pagenumber*$this->f3->get('itemlimit'),$this->f3->get('itemlimit'),$this->f3->get('PARAMS.tok'));
		$this->f3->set('items',$catsall);

		//number of items
		$this->f3->set('itemcount',$catcount_number);
		

		//assigne tags to items
		$tgs = new TagList($this->db);
		$tgslst = $tgs->getCatTags($this->f3->get('PARAMS.tok'));
		
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


        $this->f3->set('header','Tag List');
        
        $this->f3->set('view','item/list.htm');
		$this->f3->set('catmenu',$this->f3->get('PARAMS.tok'));


		//get category label
		$tgsl = new Cat($this->db);
		$tgsl->getById($this->f3->get('PARAMS.tok'));
        $this->f3->set('label', $this->f3->get('POST.name'));

		//menu
		$this->f3->set('topmenu','c');


		//pagination
		$this->f3->set('pagecount',ceil($this->f3->get('itemcount')/$this->f3->get('itemlimit')));
		$this->f3->set('page',$pagenumber);
		$this->f3->set('pagemodule','c/'.$this->f3->get('PARAMS.tok'));
		
		//breadcrumbs
		$this->f3->set('breadcrumb', array( array("url" => '/c', "name" => "Categories"),  array("url" => NULL, "name" => $this->f3->get('POST.name')) ));


	}



    public function create()
    {

        if($this->f3->exists('POST.name'))
        {


			if($this->f3->get('POST.name')!=''){
				
			$uurl=toUrl($this->f3->get('POST.name'));
			 	
			//check if the category already exists	
			//if not unique name
            $uname = new Cat($this->db);
			if($uname->catcountByUrl($uurl)>0){
				$this->f3->set('COOKIE.message','The category name already exists!');
				$this->f3->set('COOKIE.messagetype','alert-danger hide10s');
				$this->f3->reroute('/c');

			}				
				
				
			//get unique tok
			$utok = new Cat($this->db);
			$randtok = rand(100000000,999999999);
			while($utok->catcountByTok($randtok)>1){
				$randtok = rand(100000000,999999999);
			}
 

            //variables
            $category = new Cat($this->db);
			
			$category->tok=$randtok;
			$category->url=toUrl($this->f3->get('POST.name'));
			$category->name=preg_replace('|[^0-9A-Za-z\-\/+]|', '', $this->f3->get('POST.name'));  

            $category->add();

			$this->f3->set('COOKIE.message','Category was created');
			$this->f3->set('COOKIE.messagetype','alert-success hide5s');
            $this->f3->reroute('/c');

			}else{

				$this->f3->set('COOKIE.message','Category name cannot be empty!');
				$this->f3->set('COOKIE.messagetype','alert-error hide5s');
				$this->f3->reroute('/c');
			}


        }  

    }

    public function update()
    {

        $category = new Cat($this->db);

        if($this->f3->exists('POST.update'))
        {


			$uurl=toUrl($this->f3->get('POST.name'));
			 	
			//check if the category already exists	
			//if not unique name
            $uname = new Cat($this->db);
			if($uname->catcountByUrl($uurl)>0){
				$this->f3->set('COOKIE.message','The category name already exists!');
				$this->f3->set('COOKIE.messagetype','alert-danger hide10s');
				$this->f3->reroute('/c/update/'.$this->f3->get('POST.tok'));

			}	


			$this->f3->set('POST.url',toUrl($this->f3->get('POST.name')));        
			$this->f3->set('POST.name',preg_replace('|[^0-9A-Za-z\-\/+]|', '', $this->f3->get('POST.name')));        
			$category->edit($this->f3->get('POST.tok'));

			$this->f3->set('COOKIE.message','Category has been successfully saved!');
			$this->f3->set('COOKIE.messagetype','alert-success hide5s');
			$this->f3->reroute('/c');



        } else
        {
            $category->getById($this->f3->get('PARAMS.tok'));
            $this->f3->set('categoires',$category);

            $this->f3->set('header','Update Category');
            $this->f3->set('view','cats/update.htm');

			//menu
			$this->f3->set('topmenu','c');

			//breadcrumbs
			$this->f3->set('breadcrumb', array( array("url" => "/c", "name" => "Categories"), array("url" => NULL, "name" => "Update Category") ));

        }


		//display messages (if not empty) and clear values
		if($this->f3->get('COOKIE.message')){ $this->f3->set('message',$this->f3->get('COOKIE.message')); $this->f3->set('COOKIE.message','');}
		if($this->f3->get('COOKIE.messagetype')){ $this->f3->set('messagetype',$this->f3->get('COOKIE.messagetype')); $this->f3->set('COOKIE.messagetype',''); }




    }

    public function delete()
    {
        if($this->f3->exists('PARAMS.tok'))
        {

			//get id getIdByTok
			$getIdByTok = new Cat($this->db);
			$getIdByTok->getIdByTok($this->f3->get('PARAMS.tok'));
			$cid = $this->f3->get('ID.id');
			
			$item = new Item($this->db);
			$this->f3->set('POST.cid',0);
			$item->deleteCat($this->f3->get('PARAMS.tok'));

            $category = new Cat($this->db);
            $category->delete($this->f3->get('PARAMS.tok'));
 
        }

        $this->f3->reroute('/c');
    }

 

}
