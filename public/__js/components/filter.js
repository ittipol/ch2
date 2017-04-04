class Filter {

  constructor() {
    this.submitting = false;
  }

  load() {
    this.bind();
  }

  bind() {

    let _this = this;

    // http://lv5.local/list?filter=type:value,type1:value1&q=company&sort=name:asc

    // ?fq=genre:action,genre:adventure,genre:puzzle&sort=dynamicPrice desc

    // ?search_query=Iphone 7 128GB Limited Edition&model=shop&model=product&model=job&model=advertising&model=item&model=real_estate&sort=name:asc

    // let formData = new FormData();
    // formData.append('_token', $('input[name="_token"]').val());  

    $('#filter_submit').on('click',function(){

      if(_this.submitting) {
        return false;
      }

      $('#search_form').submit();

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

}