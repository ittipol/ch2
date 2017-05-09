<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<meta name="description" content="">
<meta name="keywords" content="" />

<!-- Facebook -->
@if(!empty($_page_title))
<meta property="og:title" content="{{$_page_title}}" />
@else
<meta property="og:title" content="Sunday Square | จุดเริ่มต้นสำหรับคุณ" />
@endif        
<meta property="og:type" content="product" />
<meta property="og:image" content="{{Request::root()}}/images/sunday-square.png" />
<meta property="og:url" content="{{$_page_url}}" />
<meta property="og:description" content="" />

<!-- Twitter -->          
<meta name="twitter:card" content="summary" />
@if(!empty($_page_title))
<meta name="twitter:title" content="{{$_page_title}}" />
@else
<meta name="twitter:title" content="Sunday Square | จุดเริ่มต้นสำหรับคุณ" />
@endif    
<meta name="twitter:description" content="" />
<meta name="twitter:image" content="{{Request::root()}}/images/sunday-square.png" />