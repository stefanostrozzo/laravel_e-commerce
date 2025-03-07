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
            <form class="form-new-product form-style-1" method="POST" action="{{route('admin.user.update')}}">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{$user->id}}">
                <fieldset class="name">
                    <div class="body-title">User Name <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Contact name" name="name" tabindex="0" value="{{$user->name}}" aria-required="true">
                </fieldset>
                @error('name') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror
                <fieldset class="name">
                    <div class="body-title">User phone <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Contact phone" name="phone" tabindex="0" value="{{$user->phone}}" aria-required="true">
                </fieldset>
                @error('phone') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror
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
                <div class="bot">
                    <div></div>
                    <button class="tf-button w208" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection