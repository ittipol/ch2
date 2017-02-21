<div class="global-search-panel">

  <?php 
    echo Form::open(['method' => 'get', 'route' => 'search','enctype' => 'multipart/form-data']);
  ?>
  
  <div class="global-search-box-panel">
    <input type="text" id="global_search_query_input" name="search_query" placeholder="ค้นหา..." autocomplete="off" class="search-box">
    <button class="global-button-search">
      <img src="/images/icons/search-white.png">
    </button>
  </div>

  <?php
    echo Form::close();
  ?>

  <div class="search-panel-close-button"></div>

</div>