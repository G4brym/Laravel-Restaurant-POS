@extends('master')

@section('title', 'Tic Tac Toe')

@section('content')

<div>
    <h3 class="text-center">@{{ title }}</h3>
    <br>
    <h2>Current Player : @{{ currentPlayer }}</h2>
    <br>
</div>
<div class="game-zone-content">       
    <div class="alert alert-success" v-if="showSuccess">
        <button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
        <strong>@{{ successMessage }} &nbsp;&nbsp;&nbsp;&nbsp;<a v-show="gameEnded" v-on:click.prevent="restartGame">Restart</a></strong>
    </div>

    <div class="board">
        <div v-for="(piece, key) of board" >
            <img v-bind:src="pieceImageURL(piece)" v-on:click="clickPiece(key)">
        </div>
    </div>
    <hr>
</div>  


@endsection
@section('pagescript')
<script src="js/tictactoe.js"></script>
@stop  
