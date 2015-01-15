<a href="<?php echo JRoute::_('index.php?option=com_one&view=oneconfig&layout=list'); ?>" class="btn pull-right"><i class="icon icon-chevron-left"></i> <?php echo JText::_('COM_ONE_BACK'); ?></a>
<h2 class="page-header"><?php echo $this->oneconfig->option; ?></h2>
<div class="row-fluid">
  <div class="span9 well well-small">
    <dl class="dl-horizontal">
      <dt><?php echo JText::_('COM_ONE_ONECONFIG_VALUE'); ?></dt>
      <dd><?php echo $this->oneconfig->value; ?></dd>
    </dl>
  </div>
</div>
