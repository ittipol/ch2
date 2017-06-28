<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

@if(!isset($_bot_disallowed) || $_bot_disallowed)
<meta name="robots" content="noindex,nofollow">
@endif

@if(!empty($_page_description))
<meta name="description" content="{{$_page_description}}">
@else
<meta name="description" content="เว็บไซต์ที่เปิดโอกาสให้คุณสามารถสร้างธุรกิจออนไลน์ในแบบของคุณ ด้วยระบบที่ใช้งานง่าย ไม่ซับซ้อน รองรับทุกอุปกรณ์ พร้อมด้วยฟังค์ชั่นมากมายให้คุณทำตลาดและขายสินค้าออนไลน์ได้อย่างอิสระ ตอบทุกโจทย์ของการทำธุรกิจออนไลน์">
@endif

@if(!empty($_meta_keywords))
<meta name="keywords" content="{{$_meta_keywords}}">
@else
<meta name="keywords" content="สร้างร้านค้า,สร้างร้านค้าออนไลน์,ร้านค้าออนไลน์,ขายของออนไลน์,สินค้า">
@endif

<!-- Facebook -->
@if(!empty($_page_url))
<meta property="og:url" content="{{$_page_url}}" />
@endif

<meta property="og:type" content="{{$_og_type}}" />

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

@if(!empty($_og_product))
<meta property="product:retailer_item_id" content="{{$_og_product['id']}}" /> 
<meta property="product:price:amount"     content="{{$_og_product['price']}}" /> 
<meta property="product:price:currency"   content="{{$_og_product['currency']}}" /> 
<meta property="product:availability"     content="{{$_og_product['availability']}}" /> 
<meta property="product:condition"        content="{{$_og_product['condition']}}" />
<meta property="product:category"        content="{{$_og_product['category']}}" /> 
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