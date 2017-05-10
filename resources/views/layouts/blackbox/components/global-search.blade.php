<div class="global-panel global-search-panel">

  <?php 
    echo Form::open(['method' => 'get', 'route' => 'search','enctype' => 'multipart/form-data']);
  ?>

  <div class="panel-header search-panel-header">
    <div class="panel-header-title">ค้นหา</div>
    <div class="panel-close-button search-panel-close-button"></div>
  </div>

  <div class="global-search-input-box">
    <div class="global-search-input-field">
      <input type="text" id="global_search_query_input" name="search_query" placeholder="ค้นหา" autocomplete="off" class="search-box">
      <button class="global-button-search">
        <img src="/images/icons/search.png">
      </button>
    </div>
  </div>
  
  <!-- <div class="panel-header global-search-header">

    <div class="global-search-header-action-bar">
      <div class="panel-header-title">ค้นหา</div>
      <div class="panel-close-button search-panel-close-button"></div>
    </div>

    <div class="global-search-input-box">
      <div class="global-search-input-field">
        <input type="text" id="global_search_query_input" name="search_query" placeholder="ค้นหา" autocomplete="off" class="search-box">
        <button class="global-button-search">
          <img src="/images/icons/search.png">
        </button>
      </div>
    </div>
  </div> -->

  <?php
    echo Form::close();
  ?>

</div>