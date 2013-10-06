//<![CDATA[
$(document).ready(function () {var cssEscapeChar = '_'
var callcount = 0;
function getElement(button){
    return $('.'+'form-element-' + button.value).first();
}
function growForm(button){        
    original = getElement(button);
    clone = original.clone(true);
    callcount++;
    var vals = button.value.split(cssEscapeChar);
    var string = 'data';
    for(i=0; i<vals.length; i++){
        string += '\[' +vals[i] + '\]';
    }
    clone.find('input[name^=\"'+string+'\"]').each(
        function( index ) {
            rename = string;
            name = $(this).attr('name').valueOf();
            name = name.substr(rename.length);
            pieces = name.split('][');
            rename += '['+callcount+'][';

            for(i=1; i<pieces.length; i++){
                rename += pieces[i] + '][';
            }

            rename = (pieces.length <= 1)? rename.substr(0,rename.length -1) : rename.substr(0, rename.length -2);
            $(this).attr('name', rename);
        }
    );
    original.after(clone);
}
function shrinkForm(button){
    if($('.'+'form-element-' + button.value).length > 1){
        getElement(button).remove();
    }else{
        if(confirm('Total eradication of form elements is not recomended, a page reload is required to get \'em back, continue?')){
            getElement(button).remove();
        }
    }
}
function changeForm(){
    var file = $('#SheetViewName option:selected').val();
    $('.hide-target').removeClass('hidden').addClass('hidden');
    $('#cms-for-'+file).removeClass('hidden');
}
function listOptions(data, textStatus){
    alert(textStatus + ':' + data);
}
function useExisting(button){
	$.ajax({async:true, dataType:"html",success:function (data, textStatus) {listOptions(data, textStatus)}, url:"\/view_content_factory\/Sheets\/findOptions\/"+$('#SheetViewName').val()+"\/"+button.value});
    }
changeForm();

$(".form-find").bind("click", function (event) {useExisting(this)
return false;});
$("#SheetViewName").bind("change", function (event) {changeForm();
return false;});
$(".form-grow").bind("click", function (event) {growForm(this);
return false;});
$(".form-shrink").bind("click", function (event) {shrinkForm(this);
return false;});});
//]]>