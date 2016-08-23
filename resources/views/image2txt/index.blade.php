@extends('layouts.app')

@section('content')
    <h3>图片转ANSII字符画</h3>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#uploadPic" aria-controls="uploadPic" role="tab" data-toggle="tab">上传文件</a></li>
        <li role="presentation"><a href="#uploadURL" aria-controls="uploadURL" role="tab" data-toggle="tab">输入URL</a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="uploadPic">
            <form action="{{ route('image2txtByUpload') }}" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="pic">请选择图片</label>
                    <input type="file" id="pic" name="pic">
                </div>
                {{ csrf_field() }}
                <button class="btn btn-primary">生成</button>
            </form>
        </div>
        <div role="tabpanel" class="tab-pane" id="uploadURL">
            <form action="{{ route('image2txtByURL') }}" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="pic">请输入URL</label>
                    <input type="text" class="form-control" name="url">
                </div>
                {{ csrf_field() }}
                <button class="btn btn-primary">生成</button>
            </form>
        </div>
    </div>

@stop