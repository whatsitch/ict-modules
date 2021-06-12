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
  /*----- initialize pseudo random number generator -----*/
  randomSeed(analogRead(40));
}

void loop()
{
  Serial.println("gamemode status:");
  Serial.println(game.getStatus());
  game.validateStartStopButton();

  if (game.status == IDLE)
  {
    game.awaitUserInteraction();
  }
  /*
  if(game.status == INIT) {
    game.setup();
  }*/

  if(game.status == PLAYING) {
    game.startSequence();
    delay(100);
    game.readUserSequence();
  }

  if(game.status == GAMEOVER) {
    Serial.println("index -> GAMEOVER!!!");
    delay(8000);
  }

  delay(200);
}