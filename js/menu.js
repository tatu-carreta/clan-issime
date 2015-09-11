/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready( function(){
     flag = false;
     
    $(".mainButtons").mouseenter(function(){
        var id = $(this).attr("id");
       
        if( (id == "aCatalogo") || (id == "aContacto"))
        {
                
          flag = true;
        }
      
        if (flag)
        {
            $(".desplegable").show();
        }
      
    })
                     .mouseleave( function(){
        var id = $(this).attr("id");

         if( (id != "aCatalogo") && (id != "aContacto"))
        {
          flag = false;
         
        }
 
        $(".desplegable").hide();
       
    });
    
    
    
     $(".mainButtons2").mouseenter(function(){
        var id = $(this).attr("id");
       
        if(id == "aContacto")
        {
                
          flag = true;
        }
      
        if (flag)
        {
            $(".desplegable2").show();
        }
      
    })
                     .mouseleave( function(){
        var id = $(this).attr("id");

         if( id != "aContacto")
        {
          flag = false;
         
        }
 
        $(".desplegable2").hide();
       
    });
});

