#include "./classes/Button.h"
#include "./classes/LED.h"
#include "./classes/Game.h"
#include "./miscellaneous/GameStatus.h"

/****************************************************************
 * 
 * Author: Marco Eugster, Noel Schneider, Nils Baumann
 *  
****************************************************************/
Game game = Game();

void setup()
{
  Serial.begin(9600);
}

void loop()
{
  game.validateStartStopButton();

  if (game.status == IDLE)
  {
    game.awaitInteraction();
  }
  /*
  if(game.status == INIT) {
    game.setup();
  }
  if(game.status == PLAYING) {
    game.startSequence();
  }*/

  delay(200);
}