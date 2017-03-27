<h3>ตัวกรอง</h3>
<button class="button wide-button">
  กรองการค้นหา
</button>

<h4>แสดงข้อมูล</h4>
<div class="line"></div>

<div class="row">

  @foreach($filters['model'] as $filter)

  <div class="col-sm-12">
    <label class="choice-box">
      <?php
        echo Form::checkbox('', $filter['value'], $filter['select'], array(
          'class' => 'filter-model'
        ));
      ?>
      <div class="inner">{{$filter['name']}}</div>
    </label>
  </div>

  @endforeach

</div>

<h4>จัดเรียงตาม</h4>
<div class="line"></div>

<div class="row">

  @foreach($filters['sort'] as $filter)

  <div class="col-sm-12">
    <label class="choice-box">
      <?php
        echo Form::radio('sort', $filter['value'], $filter['select']);
      ?>
      <div class="inner">{{$filter['name']}}</div>
    </label>
  </div>

  @endforeach

</div>