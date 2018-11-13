/*jshint esversion: 6 */

require('./bootstrap');

window.Vue = require('vue');

const app = new Vue({
    el: '#app',
    data: {
        title: 'TicTacToe',
        showSuccess: false,
        showFailure: false,
        successMessage: '',
        failMessage: '',
        currentValue: 1,
        gameEnded:false,
        board: [0,0,0,0,0,0,0,0,0]
    },
    methods: {
        pieceImageURL: function (piece) {
            var imgSrc = String(piece);
            return 'img/' + imgSrc + '.png';
        },
        clickPiece: function(index) {
            if(this.board[index] || this.gameEnded) return;
            this.board[index] = this.currentValue;
            this.successMessage = 'Player '+this.currentValue+' has Played';
            this.showSuccess = true;
            this.currentValue = (this.currentValue == 1)? 2 : 1;
            this.checkGameEnded();
        },
        restartGame:function(){
            console.log('restartGame');
            this.board= [0,0,0,0,0,0,0,0,0];
            this.showSuccess= false;
            this.showFailure= false;
            this.successMessage= '';
            this.failMessage= '';
            this.currentValue= 1;
            this.gameEnded= false;
        },
        // ----------------------------------------------------------------------------------------
        // GAME LOGIC - START
        // ----------------------------------------------------------------------------------------
        hasRow: function(value){
            return  ((this.board[0]==value) && (this.board[1]==value) && (this.board[2]==value)) || 
            ((this.board[3]==value) && (this.board[4]==value) && (this.board[5]==value)) || 
            ((this.board[6]==value) && (this.board[7]==value) && (this.board[8]==value)) || 
            ((this.board[0]==value) && (this.board[3]==value) && (this.board[6]==value)) || 
            ((this.board[1]==value) && (this.board[4]==value) && (this.board[7]==value)) || 
            ((this.board[2]==value) && (this.board[5]==value) && (this.board[8]==value)) || 
            ((this.board[0]==value) && (this.board[4]==value) && (this.board[8]==value)) || 
            ((this.board[2]==value) && (this.board[4]==value) && (this.board[6]==value));
        },
        checkGameEnded: function(){
            if (this.hasRow(1)) {
                this.successMessage = 'Player 1 won the Game';
                this.showSuccess = true;
                this.gameEnded = true;
            }
            if (this.hasRow(2)) {
                this.successMessage = 'Player 2 won the Game';
                this.showSuccess = true;
                this.gameEnded = true;
            }
            if (this.isBoardComplete()) {
                this.successMessage = 'The Game ended in a Tie';
                this.showSuccess = true;
                this.gameEnded = true;
            }
            return false;
        },
        isBoardComplete:function(){
            var returnValue = true;
            this.board.forEach(function(element) {
                if (element === 0) {
                    returnValue = false;
                    return;
                }
            });
            return returnValue;
        }
        // ----------------------------------------------------------------------------------------
        // GAME LOGIC - END
        // ----------------------------------------------------------------------------------------        
    },
    computed:{
        currentPlayer: function(){ 
            return 'Player '+this.currentValue;
        }
    },
});
