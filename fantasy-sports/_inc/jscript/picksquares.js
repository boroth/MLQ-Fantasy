var aIndex = [];
jQuery(document).ready(function(){
     aIndex = jQuery('#pick_squares').val();
     if(aIndex != ''){
         aIndex = jQuery.parseJSON(aIndex);
     }else{
         aIndex = [];
     }
     jQuery('.picksquare_table tr td').css('cursor','pointer');
});
jQuery('.picksquare_table tr td').live('click',function(){
   // var tdIndex =jQuery(this).closest('td').index();
  //  var trIndex = jQuery(this).closest('tr').index();
    var index = jQuery(this).closest('td').html();


   if(!inArray(index,aIndex)){
           aIndex.push(index);
           jQuery(this).css('background','yellow');
   }else{
       aIndex.splice( aIndex.indexOf(index), 1 );
        jQuery(this).css('background','white');
    }
   jQuery("#pick_squares").val(JSON.stringify(aIndex));
   console.log(JSON.stringify(aIndex));
});

function inArray(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(haystack[i] == needle) return true;
    }
    return false;
}
function checkFormPickSquare(){
    if(aIndex.length < 1){
        alert('please select at least one square');
        return false;
    }
  
    
}