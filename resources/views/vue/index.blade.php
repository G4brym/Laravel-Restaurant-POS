@extends('master')

@section('title', 'Vue.js App')

@section('content')
    <router-link to="/users">Users</router-link> -  
    <router-link to="/departments">Departments</router-link> -
    <router-link to="/tictactoe">Tic Tac Toe</router-link> -
    <router-link to="/profile" v-show="this.$store.state.user">Profile</router-link> -
    <router-link to="/login"  v-show="!this.$store.state.user">Login</router-link> -
    <router-link to="/logout" v-show="this.$store.state.user">Logout</router-link>
    <br>
    <em>User: @{{this.$store.state.user != null ? this.$store.state.user.name : " - None - " }}</em>
    <hr>
    <router-view></router-view>
@endsection

@section('pagescript')
<script src="js/vue.js"></script>
 @stop  
