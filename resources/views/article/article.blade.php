@extends('layouts.app')

@section('content')
    <form class="form-signin" action="/login" method="post" style="margin-left:500px;">
        {{csrf_field()}}
        <label for="">文章标题</label>
        <input type="email" name="title" id="title" class="form-control" placeholder="请输入文章标题" required autofocus style="width:500px;">
        <label for="" >图片</label>
        <input type="file" name="img" id="img"><br>
        <label for="">标签</label>
        <select name="tag" id="tag">
            <option value="">小说</option>
            <option value="">散文</option>
        </select><br>
        <label for="" >内容</label>
        <input type="text" name="content" id="content" class="form-control"required style="width:500px;">
        <button class="btn btn-lg btn-primary btn-block" type="submit" style="width:100px;;float: left">提交</button>
    </form>
@endsection