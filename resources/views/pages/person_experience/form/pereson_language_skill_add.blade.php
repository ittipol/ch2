@extends('layouts.blackbox.main')
@section('content')
  
<div class="container">

  <div class="container-header">
    <div class="row">
      <div class="col-md-8 col-xs-12">
        <div class="title">
          ภาษาที่สามารถสื่อสารได้
        </div>
      </div>
    </div>
  </div>

  <div class="line"></div>

  <?php 
    echo Form::open(['id' => 'main_form','method' => 'post', 'enctype' => 'multipart/form-data']);
  ?>

  <?php
    echo Form::hidden('_model', $_formModel['modelName']);
  ?>

  <div class="form-section">

    <div class="form-row">
      <div id="language_input" class="text-group">
        <div class="text-group-panel">
        </div>
        <a href="javascript:void(0);" class="text-add">เพิ่ม +</a>
      </div>
    </div>

  </div>

  <?php
    echo Form::submit('บันทึก', array(
      'class' => 'button'
    ));
  ?>

  <?php
    echo Form::close();
  ?>

</div>

<script type="text/javascript">

  class LanguageInputStack {
    constructor(panel,languages,levels) {
      this.panel = panel;
      this.languages = languages;
      this.levels = levels;
      this.selectName = 'languages';
      this.index = 1;
      this.runningNumber = 0;
    }

    load() {
      this.bind();
      this.createSelect();
    }

    bind() {

      let _this = this;

      $('.text-group > .text-add').on('click',function(){
        _this.createSelect();
      });

      $(document).on('click','.button-clear-text',function(){
        --_this.index;
        $(this).parent().remove();
      });

    }

    createSelect() {

      let html = '';
      html += '<div class="text-input-wrapper">';

      if(this.index > 1){
        html += '<span class="button-clear-text round" style="visibility: visible;">×</span>';
      }

      html += '<div>';
      html += '<h5>ภาษา</h5>'
      html += '<select name="'+this.selectName+'['+this.runningNumber+'][language]">';
      for (var i = 0; i < this.languages.length; i++) {
        html += '<option value="'+this.languages[i][0]+'">'+this.languages[i][1]+'</option>';
      };
      html += '</select>';
      html += '</div>';

      html += '<div>';
      html += '<h5>ระดับความสามารถในการใช้งาน</h5>'
      html += '<select name="'+this.selectName+'['+this.runningNumber+'][level]">';
      for (var i = 0; i < this.levels.length; i++) {
        html += '<option value="'+this.levels[i][0]+'">'+this.levels[i][1]+'</option>';
      };
      html += '</select>';
      html += '</div>';

      html += '<div class="line grey space-top-bottom-20"></div>';

      html += '</div>';

      this.runningNumber++;
      this.index++;
      $('#'+this.panel+' .text-group-panel').append(html);

    }

  }

  $(document).ready(function(){
    const languageInputStack = new LanguageInputStack('language_input',{!!$languages!!},{!!$levels!!});
    languageInputStack.load();
  });

</script>

@stop