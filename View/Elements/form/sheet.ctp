<?php
function existingButton($name, $view){
    return $view->Form->button(
	 'use existing',
	 array(
	    'class' => 'form-find',
	    'type' => 'button',
	    'value' => $name
	)
    );  
}
$this->Html->script(
	array(
	'ViewContentFactory.list-options',
	'ViewContentFactory.menu-grow-min',
	'ViewContentFactory.initialize'
    ), 
    array(
	'block' => 'script'
    )
);
if(isset($values)){
    $this->Structure->setValues($values);
}
?>
<div class="sheets form">
<?php echo $this->Form->create('Sheet'); ?>
	<fieldset>
		<legend><?php echo __('Add Sheet'); ?></legend>
	<?php
        
		echo $this->Form->input('name');
                $keys = array();
                foreach(array_keys ($views) as $name){
                    $keys[$name] = $name;
                }
		echo $this->Form->input('view_name', 
                    array(
                        // only show the file names
                        'options' => $keys
                    )
                );
	?>
	</fieldset>
<?php 

foreach($views as $fileName => $contents){
    /**
     * create an input form per file. js will hide it later on
     */
    ?>
    <fieldset class="hidden hide-target" id="cms-for-<?php echo $fileName;?>">
        <legend><?php echo $fileName; ?></legend>
        <?php
        foreach($contents as $type => $element){
            
            $length = count($element);
            if($type === 'contents'){
                
                // formulation of contents is prety straight forward
                for($i = 0; $i < $length; $i++){
                    ?>
                    <div class="input textearea">
                    <label><?php echo $element[$i]; ?></label>
					<div class="value-<?=$fileName?>-<?=$element[$i]?>"><?php
                    
                    echo $this->Form->textarea(
                        $fileName .'.'. ucfirst($type) . $element[$i], 
                        array(
                            'label' => $element[$i],
                            'value' => (isset($values[$element[$i]]))? $values[$element[$i]] : ''
                        )
                    );
                    
					echo existingButton('content.'.$element[$i], $this);
					?>
					</div>
                    </div>
                    <?php
                }
            }else if($type === 'structures'){
                
                ?>
                <ul class="structure-input">
                <?php
                // formulation of structures is more complex, because it has recursion in it
                foreach($element as $varName => $part){
                    $path = $fileName.'.'.ucfirst($type);
                    // allows top level anonymus arrays
                    $render = $this->Structure->createMenu($varName, $path);
                    $path .= '.'.$varName. $render->getNumberString();
					
					echo 
						'<div class="value-'.$fileName.'-'.$varName.'">'.
						$render->renderLi().
                            $varName.
                            $render->getButtons().
							existingButton('structure.'.$varName, $this).
							$this->Structure->input($part, $path). 
                        '</li>'.
						'</div>';
                }
                ?>
                </ul>
                <?php
            }
            
        }
        ?>
    </fieldset>
    <?php
}
echo $this->Form->end(__('Submit')); ?>
</div>
<?php
	// allows options to be placed instead of an value 
?>
<template class="hidden options-template">
	<div>
	<button>Use new</button>
	<div class="input radio">
		<label>
			<input type="radio" />
			<span class="name"></span>
		</label>
	</div>
	</div>	
</template>
