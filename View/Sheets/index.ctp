<?php
$this->extend('/Sheets/Structs/article-nav');
$this->start('article');
?>
<div class="sheets index">
	<h2><?php echo __('Sheets'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('view_name'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($sheets as $sheet): ?>
	<tr>
		<td><?php echo h($sheet['Sheet']['id']); ?>&nbsp;</td>
		<td><?php echo h($sheet['Sheet']['name']); ?>&nbsp;</td>
		<td><?php echo h($sheet['Sheet']['view_name']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $sheet['Sheet']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $sheet['Sheet']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $sheet['Sheet']['id']), null, __('Are you sure you want to delete # %s?', $sheet['Sheet']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Sheet'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Sheet Contents'), array('controller' => 'sheet_contents', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Sheet Content'), array('controller' => 'sheet_contents', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Sheet Structures'), array('controller' => 'sheet_structures', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Sheet Structure'), array('controller' => 'sheet_structures', 'action' => 'add')); ?> </li>
	</ul>
</div>
<?php
$this->end();
$this->assign('nav', $this->element('nav/acount'));
