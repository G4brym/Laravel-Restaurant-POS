<?php

namespace App\Http\Controllers;

class TicTacToeController extends Controller
{
    public function index()
    {
        return view('tictactoe.index');
    }
}
