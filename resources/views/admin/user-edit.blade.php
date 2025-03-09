@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>User infomation</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{route('admin.index')}}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <a href="{{route('admin.users')}}">
                        <div class="text-tiny">Users</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Edit User</div>
                </li>
            </ul>
        </div>
        <!-- new-category -->
        <div class="wg-box">
            <form class="form-new-product form-style-1" method="POST" action="{{route('admin.user.update')}}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{$user->id}}">
                <fieldset class="name">
                    <div class="body-title">User Name <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Contact name" name="name" tabindex="0" value="{{$user->name}}" aria-required="true">
                </fieldset>
                @error('name') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror
                <fieldset class="name">
                    <div class="body-title">User email <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Contact email" name="email" tabindex="0" value="{{$user->email}}" aria-required="true">
                </fieldset>
                @error('email') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror
                <fieldset class="name">
                    <div class="body-title">User type <span class="tf-color-1">*</span></div>
                    <select class="flex-grow" name="utype" tabindex="0" aria-required="true">
                        <option value="ADM" {{$user->utype == 'ADM' ? 'selected' : ''}}>ADMIN</option>
                        <option value="USR" {{$user->utype == 'USR' ? 'selected' : ''}}>USER</option>
                    </select>
                </fieldset>
                <fieldset>
                    <div class="body-title">Upload image <span class="tf-color-1">*</span>
                    </div>
                    <div class="upload-image flex-grow">
                        <div class="item" id="imgpreview" style="display:none">
                            <img src="upload-1.html" class="effect8" alt="">
                        </div>
                        <div id="upload-file" class="item up-load">
                            <label class="uploadfile" for="myFile">
                                <span class="icon">
                                    <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="body-text">Drop your images here or select <span
                                        class="tf-color">click to browse</span></span>
                                <input type="file" id="myFile" name="image" accept="image/*">
                            </label>
                        </div>
                    </div>
                </fieldset>
                @error('image') 
                    <span class='alert alert-danger text-center'>{{$message}}</span>
                @enderror
                <div class="bot">
                    <div></div>
                    <button class="tf-button w208" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(function name(params) {
            $('#myFile').on('change',function(e){
                const image = $("#myFile");
                const [file] = this.files;
                if(file){
                    $("#imgpreview img").attr('src',URL.createObjectURL(file));
                    $("#imgpreview").show();
                }
            });
        })
    </script>
@endpush