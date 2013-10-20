//<![CDATA[
var inputfieldStorage = [];
function ucFirst(string){
	return string.charAt(0).toUpperCase() + string.substr(1);
}
function listOptions(data, textStatus){
	var response = JSON.parse(data);
	data = response.data;

	// no results found so swapping would be stupid
	if(data.length <= 0){
		alert("No options found");
		return;
	}

	// clone the structure and fill it up
	var structure = $(".options-template").clone(true);
	structure.find("input").attr('name', 'data['+response.viewName+']['+ ucFirst(response.type)+'][' + response.varName + '][existing]');
	
	for(var i = 0; i < data.length; i++){
		var current = structure.find('.input').first().clone(true); // first is the template
		current.find("input").attr('value', data[i].id);
		current.find(".name").append('<strong>' + data[i].key + '</strong><br /><code>' +JSON.stringify(data[i].value) + '</code>');
		structure.find('.input').last().after(current);
	}
	structure.find('.input').first().remove(); // remove the template

	// store the value fields into memory
	var index = inputfieldStorage.length;
	var selector = ".value-" + response.viewName + "-" + response.varName;
	inputfieldStorage[index] = $(selector).children().clone(true);
	
	// swap field and bind the button to go back
	$(selector).empty();
	structure.find("button").bind("click",
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
