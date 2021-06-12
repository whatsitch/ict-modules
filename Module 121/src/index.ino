#include "./classes/Button.h"
#include "./classes/LED.h"
#include "./classes/Game.h"
#include "./miscellaneous/GameStatus.h"
#include "./miscellaneous/GameMode.h"

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
  game.validateStartStopButton();

  if (game.getStatus() == IDLE)
  {
    game.awaitUserInteraction();
  }

  if (game.getStatus() == PLAYING)
  {
    if (game.getMode() == LIGHT)
    {
      game.startLightSequence();
    }
    else if (game.getMode() == SOUND)
    {
    }
    else if (game.getMode() == LIGHTANDSOUND)
    {
    }
    delay(100);
    game.readUserSequence();
  }

  if (game.getStatus() == GAMEOVER)
  {
    game.blinkLeds();
    game.setStatus(IDLE);
  }

  delay(200);
}