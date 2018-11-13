@extends('master')

@section('title', 'Vue.js App')

@section('content')

<router-link to="/users">Users</router-link>
<router-link to="/tictactoe">Tictactoe</router-link>

<router-view></router-view>

@endsection
@section('pagescript')
<script src="js/vue.js"></script>
 @stop
