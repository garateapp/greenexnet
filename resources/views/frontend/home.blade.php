@extends('layouts.frontend')

@section('content')
    <div class="container">
        <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    window.onload = function() {
        document.getElementById("logoutform").submit();
    };
</script>
