class Filter {

  constructor(creatingForm = false) {
    this.creatingForm = creatingForm;
    this.submitting = false;
  }

  load() {
    this.bind();
    // this.createSearchForm();
  }

  bind() {

    let _this = this;

    // let formData = new FormData();
    // formData.append('_token', $('input[name="_token"]').val());  

    $('#filter_submit').on('click',function(){

      if(_this.submitting) {
        return false;
      }

      if(_this.creatingForm) {
        _this.submittingWithOutCreatingSearchForm();
      }else{
        $('#search_form').submit();
      }

    });

    $('#search_form').on('submit',function(){

      _this.submitting = true;

      let filters = [];
      let sorts = [];

      $('.search-filter-value:checked').each(function(i, obj) {
          filters.push($(this).val());
      });

      if(filters.length > 0) {
        var inputFilters = document.createElement('input');
        inputFilters.setAttribute('type', 'hidden');
        inputFilters.setAttribute('name', 'fq');
        inputFilters.setAttribute('value', filters.join());

        this.appendChild(inputFilters);
      }

      if($('.search-sorting-value').val() != '') {
        var inputSorts = document.createElement('input');
        inputSorts.setAttribute('type', 'hidden');
        inputSorts.setAttribute('name', 'sort');
        inputSorts.setAttribute('value', $('.search-sorting-value').val());

        this.appendChild(inputSorts);
      }
      
    });

  }

  submittingWithOutCreatingSearchForm() {

    let _this = this;

    let form = document.createElement("form");
    form.setAttribute('method','get');
    // form.setAttribute('id','search_form');
    // form.setAttribute('enctype','multipart/form-data');

    $('body').append(form);

    $(form).on('submit',function(){

      _this.submitting = true;

      let filters = [];
      let sorts = [];

      $('.search-filter-value:checked').each(function(i, obj) {
          filters.push($(this).val());
      });

      if(filters.length > 0) {
        var inputFilters = document.createElement('input');
        inputFilters.setAttribute('type', 'hidden');
        inputFilters.setAttribute('name', 'fq');
        inputFilters.setAttribute('value', filters.join());

        this.appendChild(inputFilters);
      }

      if($('.search-sorting-value').val() != '') {
        var inputSorts = document.createElement('input');
        inputSorts.setAttribute('type', 'hidden');
        inputSorts.setAttribute('name', 'sort');
        inputSorts.setAttribute('value', $('.search-sorting-value').val());

        this.appendChild(inputSorts);
      }

    });

    $(form).trigger('submit');

  }

}