<div class="row">
  <h4>ตัวกรอง</h4>

  <button class="button wide-button">
    กรองการค้นหา
  </button>
</div>

<div class="row">

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

</div>

<div class="row">

  @foreach($searchOptions['sort'] as $key => $sort)

    <h4>{{$sort['title']}}</h4>
    <div class="line"></div>

    @foreach($sort['options'] as $option)

    <div class="col-sm-12">
      <label class="choice-box">
        <?php
          echo Form::radio('', $option['value'], $option['select'], array(
            'class' => 'search-sorting-value'
          ));
        ?>
        <div class="inner">{{$option['name']}}</div>
      </label>
    </div>

    @endforeach

  @endforeach

</div>