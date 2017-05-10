<!doctype html>
<html>
<head>
  <!-- Meta data -->
  @include('scripts.meta') 
  <!-- CSS & JS -->
  @include('scripts.script')
  <!-- Title  -->
  @include('page_title')
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