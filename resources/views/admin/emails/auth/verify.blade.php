<!DOCTYPE>
<html lang="en">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>{{ $title }}</h2>

		<div>
			{!! $intro !!}.<br>
			<a href="{{route('confirm-registration', $confirmation_code)}}/{{$confirmation_code}}">{{$link}}</a>
		</div>
	</body>
</html>
