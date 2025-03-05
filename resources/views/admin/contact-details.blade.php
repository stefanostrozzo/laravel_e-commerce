@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Category infomation</h3>
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
                    <a href="{{route('admin.contacts')}}">
                        <div class="text-tiny">Contacts</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">View Contact</div>
                </li>
            </ul>
        </div>
        <!-- new-category -->
        <div class="wg-box">
            <form class="form-new-product form-style-1">
                <fieldset class="name">
                    <div class="body-title">Contact Name <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Contact name" name="name" tabindex="0" value="{{$contact->name}}" aria-required="true">
                </fieldset>
                <fieldset class="name">
                    <div class="body-title">Contact phone <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Contact phone" name="slug" tabindex="0" value="{{$contact->phone}}" aria-required="true">
                </fieldset>
                <fieldset class="name">
                    <div class="body-title">Contact email <span class="tf-color-1">*</span></div>
                    <input class="flex-grow" type="text" placeholder="Contact email" name="slug" tabindex="0" value="{{$contact->email}}" aria-required="true">
                </fieldset>
                <fieldset class="name">
                    <div class="body-title">Contact message <span class="tf-color-1">*</span></div>
                    <textarea class="flex-grow">{{$contact->comment}}</textarea>
                </fieldset>
            </form>
        </div>
    </div>
</div>
@endsection