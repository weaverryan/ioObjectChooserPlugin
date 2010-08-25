<?php if ($filter_enabled): ?>
  <?php $hide_code = 'style="display:none"' ?>
  <div class="io_object_chooser_filter" <?php echo $hide_search_box ? '' : $hide_code ?>>
    <a href="#" class="toggle show">Search</a>
  </div>
  <div class="io_object_chooser_filter" <?php echo $hide_search_box ? $hide_code : '' ?>>
    <div class="filter_values">
      <?php echo $filter ?>
    </div>
    <input type="submit" value="Search" href="<?php echo url_for('io_object_chooser_index', array('model'=>$model)) ?>"/>
    <input type="button" value="Reset" class="filter_reset"/>
  </div>
<?php endif; ?>

<ul>
  <?php foreach ($pager->getResults() as $object): ?>
  <li rel="<?php echo $object->id ?>" class="io_object_chooser_selection">
    <a href="#">
      <?php echo $object; ?>
    </a>
  </li>
  <?php endforeach; ?>
</ul>

<div class="io_object_chooser_pagination">
  <?php if ($pager->haveToPaginate()): ?>
    <div>
      Page:
      <?php foreach($pager->getLinks() as $link): ?>
        <a href="<?php echo url_for('io_object_chooser_index', array('model'=>$model, 'page'=>$link)) ?>"><?php echo $link ?></a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
  <div>
    Show:
    <?php foreach(array('2', '10', '20', '30') as $link): ?>
      <a href="<?php echo url_for('io_object_chooser_index', array('model'=>$model, 'per_page'=>$link)) ?>"><?php echo $link ?></a>
    <?php endforeach; ?>
  </div>
</div>

