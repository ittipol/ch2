<!doctype html>
<html>
<head>
  <!-- Meta data -->
  @include('script.meta') 
  <!-- CSS & JS -->
  @include('script.script')
  <!-- Title  -->
  <title>Chonburi Square</title>
  <!-- use only in default layout -->
  <link rel="stylesheet" href="{{ URL::asset('__css/layouts/default/header.css') }}" />
  <link rel="stylesheet" href="{{ URL::asset('__css/layouts/default/footer.css') }}" />
  <link rel="stylesheet" href="{{ URL::asset('__css/pages/user/register.css') }}" />
  <link rel="stylesheet" href="{{ URL::asset('__css/pages/user/login.css') }}" />
  
</head>
<body>

	<?php if(!empty($header)): ?>
    <header> 
		 @include('layouts.default.components.header')
    </header> 
	<?php endif; ?>
	
	<main>
		@yield('content')
  </main>
  
	<?php if(!empty($footer)): ?>
    <footer> 
		@include('layouts.default.components.footer')
    </footer> 
	<?php endif; ?>

</body>
</html>