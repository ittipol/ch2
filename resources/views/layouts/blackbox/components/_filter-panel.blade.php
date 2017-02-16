<div class="clearfix">
  <label class="filter-icon pull-right" for="filter_panel_trigger">
    <img src="/images/icons/sort.png">
    <input type="checkbox" id="filter_panel_trigger" class="filter-panel-trigger" />
  </label>
</div>

<script type="text/javascript">

$(document).ready(function(){
  const filter = new Filter;
  filter.load();
});

</script>

<div id="filter_panel" class="filter-panel nano">
  <div class="nano-content">
    <div class="filter-panel-inner">
      <?php
        echo Form::open(['method' => 'get','id' => 'sorting_form']);
      ?>
      <?php
        echo Form::hidden('q', 'company');
      ?>
      <?php foreach ($sortingOptions as $value): ?>
        <?php if(!empty($value['options'])): ?>
          <h5><?php echo $value['title']; ?></h5>
          <div class="line"></div>
          <ul class="nav-stack-item ">
            <?php foreach ($value['options'] as $option): ?>

              <?php $checked = false; ?>
              <?php if(!empty($option['checked']) && $option['checked']): ?>
                <?php $checked = true; ?>
              <?php endif; ?>

              <li class="item">
                <label <?php if($checked) echo 'class="checked"'; ?> for="<?php echo $option['id']; ?>">
                  <span><?php echo $option['name']; ?></span>
                  <input type="<?php echo $value['type']; ?>" id="<?php echo $option['id']; ?>" <?php if($checked) echo 'checked'; ?> class="sorting-option-radio" value="<?php echo $option['value']; ?>" name="sort" />
                </label>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      <?php endforeach; ?>
      <?php
        echo Form::close();
      ?>
    </div>
  </div>
</div>
