jQuery(document).ready(function(){
    jQuery(".generate_invoice").on('click',function(){
    	if(jQuery(this).data('is-invoiced') == true){
    		var r=window.confirm("An invoice has already been generated for this order. Continue anaway?");
    		return r;
    	}
    });
});