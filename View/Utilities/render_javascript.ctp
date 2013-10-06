<p>
    See the pagesource for the script...
</p>
<script> <!-- empty script tags for code highlighting in the source. Fooled the editor-->
<?php ob_start();?>
var cssEscapeChar = '<?php echo StructureHelper::CSS_ESCAPE_CHAR; ?>'
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
function useExisting(button){
	<?php echo $this->Js->request(
		array(
		    'controller' => 'Sheets',
		    'action' => 'findOptions'		    
		),
		array(
		    'data' => array(
			'viewName' => '$(\'#SheetViewName\').val()', 
			'varName' => 'button.value'
		    ),
		    'async' => true,
		    'type' => 'html',
		    'success', 'listOptions()'
		)
	    );
	?>
}
function listOptions(){
    alert('show list');
}
changeForm();
<?php
$this->Js->buffer(ob_get_clean());
echo
$this->Js->get('.form-find')->event('click', 'useExisting(this)').
$this->Js->get('#SheetViewName')->event('change', 'changeForm();') .
$this->Js->get('.form-grow')->event('click', 'growForm(this);') .
$this->Js->get('.form-shrink')->event('click', 'shrinkForm(this);');
?>
</script>