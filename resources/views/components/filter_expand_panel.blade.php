<div class="row">
  <h4>ตัวกรอง</h4>

  <button class="button wide-button" id="filter_submit">
    กรองการค้นหา
  </button>
</div>

<div class="row">

  @if(!empty($searchOptions['filters']))
    @foreach($searchOptions['filters'] as $filters)

      <h4>{{$filters['title']}}</h4>
      <div class="line"></div>

      @foreach($filters['options'] as $option)

      <div class="col-sm-12">
        @if($filters['input'] === 'checkbox')
          <label class="choice-box">
            <?php
              echo Form::checkbox('', $option['value'], $option['select'], array(
                'class' => 'search-filter-value'
              ));
            ?>
            <div class="inner">{{$option['name']}}</div>
          </label>
        @elseif($filters['input'] === 'radio')
          <label class="choice-box">
            <?php
              echo Form::radio('', $option['value'], $option['select'], array(
                'class' => 'search-filter-value'
              ));
            ?>
            <div class="inner">{{$option['name']}}</div>
          </label>
        @endif
      </div>

      @endforeach

    @endforeach
  @endif

</div>

<div class="row">

  <h4>{{$searchOptions['sort']['title']}}</h4>
  <div class="line"></div>

  @foreach($searchOptions['sort']['options'] as $option)

  <div class="col-sm-12">
    <label class="choice-box">
      <?php
        echo Form::radio('sort', $option['value'], $option['select'], array(
          'class' => 'search-sorting-value'
        ));
      ?>
      <div class="inner">{{$option['name']}}</div>
    </label>
  </div>

  @endforeach

</div>