@extends('layouts.admin')
@section('content')
<style>
    .limited-height td {
        max-height: 3rem; /* Imposta l'altezza massima */
        overflow: hidden; /* Nasconde il contenuto che supera l'altezza */
        white-space: nowrap; /* Impedisce il ritorno a capo del testo */
        text-overflow: ellipsis; /* Aggiunge "..." se il testo è troppo lungo */
    }
</style>
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>All users</h3>
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
                        <div class="text-tiny">Users</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <form class="form-search">
                            <fieldset class="name">
                                <input type="text" placeholder="Search here..." class="" name="name"
                                    tabindex="2" value="" aria-required="true" required="">
                            </fieldset>
                            <div class="button-submit">
                                <button class="" type="submit"><i class="icon-search"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="wg-table table-all-user">
                    <div class="table-responsive">
                        @if(Session::has('status'))
                            <p class="alert alert-success">{{Session::get('status')}}</p>
                        @endif
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>E-mail</th>
                                    <th>User type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{$user->id}}</td>
                                        <td class="pname">
                                            <div class="image">
                                                @if($user->image)
                                                    <img src="{{asset('uploads/users/')}}/{{$user->image}}" alt="{{$user->name}}" class="image">
                                                @else
                                                    <img src="{{ asset('uploads/users/default-user.png') }}" alt="Default User Image" class="profile-image">
                                                @endif
                                            </div>
                                            <div class="name">
                                                <a href="#" class="body-title-2">{{$user->name}}</a>
                                            </div>
                                        </td>
                                        <td>{{$user->email}}</td>
                                        <td>{{$user->utype == "ADM" ? 'ADMIN' : 'USER'}}</td>
                                        <td>
                                            <div class="list-icon-function">
                                            <a href="{{route('admin.user.edit', ['id' => $user->id])}}">
                                                <div class="item edit">
                                                    <i class="icon-edit-3"></i>
                                                </div>
                                            </a>
                                            <form action="{{route('admin.user.delete' ,  ['id' => $user->id])}}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="item text-danger delete">
                                                    <i class="icon-trash-2"></i>
                                                </div>
                                            </form>
                                        </div>
                                        </td>
                                    </tr>
                                    
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                    {{$users->links('pagination::bootstrap-5')}}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('.delete').on('click', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');
                swal({
                    title: "Are you sure?",
                    text: "You want to delete the user?",
                    type: "warning",
                    buttons: ["No", "Yes"],
                    confirmButtonColor: '#adc3545'
                }).then(function(result){
                    if(result){
                        form.submit();
                    }
                })
            });
        });
    </script>
@endpush