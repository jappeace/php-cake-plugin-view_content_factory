function listOptions(data, textStatus){

}
function useExisting(button){
	$.ajax(
	    {
		async:true, dataType:"html",success:function (data, textStatus) {listOptions(data, textStatus)}, url:"\/view_content_factory\/Sheets\/findOptions\/"+$('#SheetViewName').val()+"\/"+button.value});
}