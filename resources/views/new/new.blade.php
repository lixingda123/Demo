
<link rel="stylesheet" type="text/css" href="/css/page.css">
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<form action="/new/new">
		<input type="text" name="search">
		<input type="submit" value="搜索">
	</form>
	<table border="1">
		<tr>
			<td>id</td>
			<td>新闻名</td>
			<td>新闻内容</td>
		</tr>
		@foreach($data as $k=>$v)
		<tr>
			<td>{{$v->essay_id}}</td>
			<td>{{$v->essay_title}}</td>
			<td>{{$v->essay_content}}</td>
		</tr>
		@endforeach
	</table>
	{{$data->appends($search)->links()}}
</body>
</html>