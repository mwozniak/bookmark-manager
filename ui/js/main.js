

 
/* Delete Item */
$('.deleteitem').click(function(){

	var e = $(this).attr("id").split('itemtok_')
	var tok = e[1]
	
	var retval = confirm("Do you want to delete this item?");
	if( retval == true ){
		window.location = base+'/i/delete/'+tok
	  return true;
   }else return false;


	});


/* Delete Tag */
$('.deletetag').click(function(){

	var e = $(this).attr("id").split('tagtok_')
	var tok = e[1]
	
	var retval = confirm("Do you want to delete this tag?");
	if( retval == true ){
		window.location = base+'/t/delete/'+tok
	  return true;
   }else return false;


	});



/* Delete Category */
$('.deletecat').click(function(){

	var e = $(this).attr("id").split('cattok_')
	var tok = e[1]
	
	var retval = confirm("Do you want to delete this category?");
	if( retval == true ){
		window.location = base+'/c/delete/'+tok
	  return true;
   }else return false;


	});



/* Hide messages after X s. */
$(".hide5s").fadeOut(5000);
$(".hide10s").fadeOut(10000);




 

/* Validate Category */
$('.validcat').submit(function(){

 	$( "#message" ).html('');
 	
	if( $('#name').val()=='' ){
	$( "#message" ).append( '<div class="alert warning hide5s">'+
			  '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
				'The filed name cannot be empty!'+
			  '</div>' );
	
	$('#name').focus();
	$(".hide5s").fadeOut(10000);	 
	 return false;
   }

	return true;
	alert(1)
	});




/* Validate Item */
$('.validitem').submit(function(){

	$( "#message" ).html('');
	
	if( $('#title').val()=='' ){
	$( "#message" ).html( '<div class="alert danger">'+
			  '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
				'The filed title cannot be empty!'+
			  '</div>' );	 
	$('#title').focus();		  
	$(".hide5s").fadeOut(10000);				  
	 return false;
   }

	return true;
	
	});
	
	
	
	
/* Validate Tag */
$('.validtag').submit(function(){

	$( "#message" ).html('');
	
	if( $('#title').val()=='' ){
	$( "#message" ).html( '<div class="alert danger">'+
			  '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
				'The filed title cannot be empty!'+
			  '</div>' );	 
	$('#title').focus();		  
	$(".hide5s").fadeOut(10000);				  
	 return false;
   }
	if( $('#label').val()=='' ){
	$( "#message" ).html( '<div class="alert danger">'+
			  '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
				'The filed label cannot be empty!'+
			  '</div>' );	 
	$('#label').focus();		  
	$(".hide5s").fadeOut(10000);				  
	 return false;
   }

	return true;
	
	});

