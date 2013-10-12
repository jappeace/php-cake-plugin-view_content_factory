//<![CDATA[
var inputfieldStorage = [];
function listOptions(data, textStatus){
	var response = JSON.parse(data);
	data = response.data;
	if(data.length <= 0){
		alert("No options found");
		return;
	}
	var structure = $(".options-template > div").clone(true);
	var selector = "value-" + response.viewName + "-" + response.varName;

	var index = inputfieldStorage.length;
	for(var i = 0; i < data.length; i++){
		
	}

	// store the fields into memory
	inputfieldStorgage[index] = $(selector).children().clone(true);
	$(selector).empty();
	structure.$("button").bind("click",
		function(event){
			$(selector).empty();
			$(selector).append(inputfieldStorage[index]);
		}
	);
	$(selector).append(structure.children());
}

function useExisting(button){
	$.ajax(
		{
			async:true,
			dataType:"html",
			success:
				function (data, textStatus) {
					listOptions(data, textStatus);
				},
			url:"\/view_content_factory\/Sheets\/findOptions\/"+$('#SheetViewName').val()+"\/"+button.value
		}
	);
}
//]]>
