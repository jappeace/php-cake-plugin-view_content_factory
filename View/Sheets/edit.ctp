<div class="structural">
    <?
	echo $this->element('form/sheet', array('views' => $template, 'values' => $values));
    ?>
</div>
<aside class="structural">
    <div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Sheet.name')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Sheet.name'))); ?></li>
		<li><?php echo $this->Html->link(__('List Sheets'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Sheet Contents'), array('controller' => 'sheet_contents', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Sheet Content'), array('controller' => 'sheet_contents', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Sheet Structures'), array('controller' => 'sheet_structures', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Sheet Structure'), array('controller' => 'sheet_structures', 'action' => 'add')); ?> </li>
	</ul>
    </div>
</aside>