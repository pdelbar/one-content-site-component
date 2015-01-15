<h2 class="page-header">list.php <?php echo JText::_('COM_ONE_CONFIG'); ?></h2>
<div class="row-fluid">
  <?php for($i=0, $n = count($this->oneconfigs);$i<$n;$i++) {
    $this->_oneconfigListView->oneconfig = $this->oneconfigs[$i];
    echo $this->_oneconfigListView->render();
  } ?>
</div>