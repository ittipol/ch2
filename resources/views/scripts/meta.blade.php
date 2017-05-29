<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

@if(!empty($_page_description))
<meta name="description" content="{{$_page_description}}">
@else
<meta name="description" content="สร้างร้านค้าออนไลน์ในแบบของคุณ เปิดโอกาสและเพิ่มช่องทางการขายสินค้าให้กับธุรกิจของคุณ เพื่อให้ธุรกิจของคุณเชื่อมต่อไปยังคนนับล้านบนอินเตอร์เน็ต">
@endif

<meta name="keywords" content="สร้างร้านค้าออนไลน์,ร้านค้าออนไลน์,สินค้า">

<!-- Facebook -->
@if(!empty($_page_url))
<meta property="og:url" content="{{$_page_url}}" />
@endif
<meta property="og:type" content="product" />
@if(!empty($_page_title))
<meta property="og:title" content="{{$_page_title}}" />
@else
<meta property="og:title" content="Sunday Square | จุดเริ่มต้นสำหรับคุณ" />
@endif        
@if(!empty($_page_image))
<meta property="og:image" content="{{Request::root()}}{{$_page_image}}" />
@else
<meta property="og:image" content="{{Request::root()}}/images/sunday-square.png" />
@endif  
@if(!empty($_page_description))
<meta property="og:description" content="{{$_page_description}}" />
@endif

<!-- Twitter -->          
<meta name="twitter:card" content="summary" />
@if(!empty($_page_title))
<meta name="twitter:title" content="{{$_page_title}}" />
@else
<meta name="twitter:title" content="Sunday Square | จุดเริ่มต้นสำหรับคุณ" />
@endif    
@if(!empty($_page_image))
<meta name="twitter:image" content="{{Request::root()}}{{$_page_image}}" />
@else
<meta name="twitter:image" content="{{Request::root()}}/images/sunday-square.png" />
@endif
@if(!empty($_page_description))
<meta name="twitter:description" content="{{$_page_description}}" />
@endif