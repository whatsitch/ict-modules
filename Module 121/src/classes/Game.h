#include "Button.h"
#include "LED.h"
#include "Piezo.h"
#include "../miscellaneous/GameStatus.h"
#include "../miscellaneous/GameMode.h"
#include "../miscellaneous/Colour.h"
class Game
{

private:
    LED leds[4];
    Button buttons[4];
    Piezo frequencies[4];
    Button startStopButton = Button(11);
    Button gameModeButton = Button(10);
    GameStatus status;
    GameMode mode;
    int memory[100];
    int memoryIndex = 0;
    int sequenceLightDelay;

public:
    Game()
    {
        init();
        this->status = OFFLINE;
    }
    void init()
    {
        this->leds[0] = LED(6, YELLOW);
        this->leds[1] = LED(7, BLUE);
        this->leds[2] = LED(8, RED);
        this->leds[3] = LED(9, GREEN);
        this->buttons[0] = Button(2);
        this->buttons[1] = Button(3);
        this->buttons[2] = Button(4);
        this->buttons[3] = Button(5);
        this->frequencies[0] = Piezo(12, 1000);
        this->frequencies[1] = Piezo(12, 800);
        this->frequencies[2] = Piezo(12, 600);
        this->frequencies[3] = Piezo(12, 400);
        //this->gameModeButton = Button(10);
        //this->startStopButton = Button(13);
        //piezo pin 12
    }

    void validateStartStopButton()
    {
        //Serial.println("validateStartStopButton");
        if (this->getStatus() == OFFLINE)
        {
            //Serial.println("status == 0ffline");
            if (this->startStopButton.isPressed())
            {
                //Serial.println("button pressed");
                if (this->getStatus() == OFFLINE)
                {
                    this->setStatus(IDLE);
                    //Serial.println("Testing");
                    //Serial.println(this->status);
                }
                delay(1000);
            }
        }
        /*else
        {
            if (this->startStopButton.isPressed())
            {

                Serial.println("SHUTING DOWN....");
                Serial.println(this->status);
                this->setStatus(OFFLINE);
                Serial.println(this->status);
            }
        }*/
    }

    void awaitUserInteraction()
    {
        // Serial.println("Await interaction");
        for (size_t i = 0; i < this->getNumberOfLEDs(); i++)
        {
            this->leds[i].on();
            if (this->gameModeButton.isPressed())
            {
                this->turnLedsOff();
                //Serial.println("gamemode button pressed once!");
                this->setMode(LIGHT);
                this->leds[0].on();
                if (this->gameModeButton.isPressed(100))
                {
                    //Serial.println("gamemode button pressed twice!");
                    this->leds[1].on();
                    this->setMode(SOUND);

                    if (this->gameModeButton.isPressed(100))
                    {
                        //Serial.println("gamemode button pressed for the third time!");

                        this->leds[2].on();
                        this->setMode(LIGHTANDSOUND);
                        //this->status = INIT;
                        if (this->gameModeButton.isPressed(300))
                        {
                            //Serial.println("gamemode button pressed for the fourth time! - resetting");
                            this->turnLedsOff();
                            this->setMode(OFF);
                            delay(1500);
                            break;
                        }
                    }
                }
            }
            delay(50);
            this->leds[i].off();

            if (this->getMode() != OFF)
            {
                //Serial.println("statsu before:");
                //Serial.println(this->getStatus());
                this->setStatus(PLAYING);
                //Serial.println("now playing with mode:");
                //Serial.println(this->getMode());
                //Serial.println("status now:");
                // Serial.println(this->getStatus());
            }
        }
        this->turnLedsOff();
    }

    void startLightSequence()
    {
        delay(500);
        this->updateGameMemory();
        delay(500);
        for (size_t i = 0; i < this->memoryIndex; i++)
        {
            delay(100);
            this->leds[memory[i]].on();
            delay(this->getSequenceLightDelay());
            this->leds[memory[i]].off();
        }
    }

    void startSoundSequence()
    {
        delay(500);
        this->updateGameMemory();
        delay(500);
        for (size_t i = 0; i < this->memoryIndex; i++)
        {
            delay(100);
            this->frequencies[memory[i]].makeSound(this->getSequenceLightDelay());
        }
    }

    void startLightAndSoundSequence()
    {
        delay(500);
        this->updateGameMemory();
        delay(500);
        for (size_t i = 0; i < this->memoryIndex; i++)
        {
            delay(100);
            this->leds[memory[i]].on();
            this->frequencies[memory[i]].makeSound(this->getSequenceLightDelay());
            delay(this->getSequenceLightDelay());
            this->leds[memory[i]].off();
            delay(100);
        }
    }

    void readUserSequence()
    {
        int pressedButton;
        boolean wrongButtonPressed = false;
        for (size_t i = 0; i < this->memoryIndex && wrongButtonPressed == false; i++)
        {
            pressedButton = this->getPressedButton(5000);

            if (pressedButton == -1 | pressedButton != this->memory[i])
            {
                this->setStatus(GAMEOVER);
                this->setMode(OFF);
                this->memoryIndex = 0;
                memset(this->memory, 0, sizeof(this->memory));
                wrongButtonPressed = true;
                break;
            }
        }
    }

    int getPressedButton(int delay)
    {
        int pressedButton = -1;
        boolean buttonReleased = false;

        int currentMillis = millis();
        int previousMillis = currentMillis;

        while (currentMillis - previousMillis < delay & buttonReleased == false)
        {
            for (size_t i = 0; i < this->getNumberOfButtons() && buttonReleased == false; i++)
            {
                //Serial.println("sizeof buttons:");
                int number = this->getNumberOfButtons();
                //Serial.println(number);
                //Serial.println("sizeof leds: ");
                int number2 = this->getNumberOfLEDs();
                // Serial.println(number2);
                //Serial.println("okay=");
                //Serial.println("index:");
                //Serial.println(i);
                if (this->buttons[i].isPressed())
                {
                    //Serial.println("inside getPressedButton:");
                    //Serial.println("Index:");
                    //Serial.println(i);
                    pressedButton = i;
                    // Serial.println("pressed Button: ");
                    // Serial.println(pressedButton);
                    this->leds[i].on();
                    while (currentMillis - previousMillis < delay & buttonReleased == false)
                    {
                        if (!this->buttons[i].isPressed())
                        {
                            buttonReleased = true;
                        }
                        currentMillis = millis();
                    }

                    this->leds[i].off();

                    if (currentMillis - previousMillis > delay)
                    {
                        pressedButton = -1;
                    }
                }
            }
            currentMillis = millis();
        }

        return pressedButton;
    }

    /*----- get a random number between 0 and n (0, n included) -----*/
    int getRandomNumber(int upperBound)
    {
        return random() % upperBound;
    }

    void updateGameMemory()
    {
        this->memory[this->memoryIndex] = getRandomNumber(4);
        this->memoryIndex++;
    }

    /*----- LED handling -----*/

    void turnLedsOff()
    {
        for (size_t i = 0; i < sizeof(this->leds); i++)
        {
            this->leds[i].off();
        }
    }

    void turnLedsOn()
    {
        for (size_t i = 0; i < sizeof(this->leds); i++)
        {
            this->leds[i].on();
        }
    }

    void blinkLeds()
    {
        for (size_t i = 0; i < sizeof(this->leds); i++)
        {
            delay(100);
            this->turnLedsOn();
            delay(300);
            this->turnLedsOff();
            delay(100);
        }
    }

    /*----- getter / setter methods -----*/

    void setStatus(GameStatus status) { this->status = status; }

    GameStatus getStatus() { return this->status; }

    void setMode(GameMode mode) { this->mode = mode; }

    GameMode getMode() { return this->mode; }

    int getSequenceLightDelay()
    {
        int base = 1500;
        int delay = base - floor((pow(this->memoryIndex, 2.65)));
        Serial.println(delay);
        if (delay < 200)
        {
            return 200;
        }
        return delay;
    };

    void debug()
    {
        this->memoryIndex++;
        Serial.println(this->getSequenceLightDelay());
    }
    int getNumberOfButtons() { return sizeof(this->buttons) / sizeof(this->buttons[0]); }

    int getNumberOfLEDs() { return sizeof(this->leds) / sizeof(this->leds[0]); }
};
