<div class="row">
  <h4>ตัวกรอง</h4>

  <button class="button wide-button">
    กรองการค้นหา
  </button>
</div>

<div class="row">

  @foreach($filterOptions['filters'] as $filters)

    <h4>{{$filters['title']}}</h4>
    <div class="line"></div>

    @foreach($filters['options'] as $option)

    <div class="col-sm-12">
      <label class="choice-box">
        <?php
          echo Form::checkbox('', $option['value'], $option['select'], array(
            'class' => 'filter-model'
          ));
        ?>
        <div class="inner">{{$option['name']}}</div>
      </label>
    </div>

    @endforeach

  @endforeach

</div>

<h4>จัดเรียงตาม</h4>
<div class="line"></div>

<div class="row">

  @foreach($filterOptions['sort'] as $sort)

  <div class="col-sm-12">
    <label class="choice-box">
      <?php
        echo Form::radio('sort', $sort['value'], $sort['select']);
      ?>
      <div class="inner">{{$sort['name']}}</div>
    </label>
  </div>

  @endforeach

</div>