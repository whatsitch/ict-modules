#include "Button.h"
#include "LED.h"
#include "../miscellaneous/GameStatus.h"
#include "../miscellaneous/GameMode.h"
class Game
{

private:
public:
    LED leds[4];
    Button buttons[4];
    Button startStopButton = Button(11);
    Button gameModeButton = Button(10);
    GameStatus status;
    GameMode mode;
    int memory[100];
    int memoryIndex = 0;
    Game()
    {
        init();
        this->status = OFFLINE;
    }
    void init()
    {
        this->leds[0] = LED(6);
        this->leds[1] = LED(7);
        this->leds[2] = LED(8);
        this->leds[3] = LED(9);
        this->buttons[0] = Button(2);
        this->buttons[1] = Button(3);
        this->buttons[2] = Button(4);
        this->buttons[3] = Button(5);
        //this->gameModeButton = Button(10);
        //this->startStopButton = Button(13);
        //piezo pin 12
    }

    void setStatus(GameStatus status)
    {
        this->status = status;
    }
    GameStatus getStatus()
    {
        return this->status;
    }

    int getNumberOfButtons()
    {
        return sizeof(this->buttons) / sizeof(this->buttons[0]);
    }
    int getNumberOfLEDs()
    {
        return sizeof(this->leds) / sizeof(this->leds[0]);
    }
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

    void setup()
    {
    }

    void validateStartStopButton()
    {
        if (this->status == OFFLINE)
        {
            if (this->startStopButton.isPressed())
            {
                if (this->getStatus() == OFFLINE)
                {
                    this->setStatus(IDLE);
                    Serial.println(this->status);
                }
                else
                {
                    this->setStatus(OFFLINE);
                    Serial.println("SHUTING DOWN....");
                    delay(5000);
                }
            }
        }
    }

    void awaitInteraction()
    {
        Serial.println("Await interaction");
        for (size_t i = 0; i < this->getNumberOfLEDs(); i++)
        {
            this->leds[i].on();
            if (this->gameModeButton.isPressed())
            {
                this->turnLedsOff();
                Serial.println("gamemode button pressed once!");
                this->mode = LIGHT;
                this->leds[0].on();
                if (this->gameModeButton.isPressed(100))
                {
                    Serial.println("gamemode button pressed twice!");
                    this->leds[1].on();
                    this->mode = SOUND;
                   
                    
                    if (this->gameModeButton.isPressed(100))
                    {
                        Serial.println("gamemode button pressed third time!");

                        this->leds[2].on();
                        this->mode = LIGHTANDSOUND;
                        //this->status = INIT;
                        if (this->gameModeButton.isPressed(50))
                        {
                            Serial.println("gamemode button pressed twice!");
                            this->leds[1].on();
                            this->mode = SOUND;
                            //this->status = INIT;
                            if (this->gameModeButton.isPressed(50))
                            {
                                Serial.println("gamemode button pressed fourth time!");
                                this->turnLedsOff();
                                this->mode = LIGHT;
                                break;
                            }
                            this->status = INIT;
                            break;
                        }
                    }
                }
            }
            delay(50);
            this->leds[i].off();
        }
        this->turnLedsOff();
    }

    void startSequence()
    {
        delay(500);
        this->memory[this->memoryIndex] = random(0, 4);
        Serial.println("Next: ");
        Serial.println(this->memory[this->memoryIndex]);
        this->memoryIndex++;
        delay(500);
        for (size_t i = 0; i < this->memoryIndex; i++)
        {
            delay(300);
            this->leds[memory[i]].on();
            delay(700);
            this->leds[memory[i]].off();
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
            for (size_t i = 0; i < this->getNumberOfButtons(); i++)
            {
                Serial.println("sizeof buttons:");
                int number = this->getNumberOfButtons();
                Serial.println(number);
                Serial.println("sizeof leds: ");
                int number2 = this->getNumberOfLEDs();
                Serial.println(number2);
                Serial.println("okay=");
                Serial.println("index:");
                Serial.println(i);
                if (this->buttons[i].isPressed())
                {
                    Serial.println("inside getPressedButton:");
                    Serial.println("Index:");
                    Serial.println(i);
                    pressedButton = buttons[i].getState();
                    Serial.println("pressed Button: ");
                    Serial.println(pressedButton);
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

    void readUserSequence()
    {
        for (size_t i = 0; i < this->memoryIndex; i++)
        {
            Serial.println("readUserSequence:");
            Serial.println("User should push:");
            Serial.println(this->memory[i]);

            int pressedButton = this->getPressedButton(1000);

            Serial.println("Pressed Button:");
            Serial.println(pressedButton);

            if (pressedButton == -1 | pressedButton != this->memory[i])
            {
                this->status = GAMEOVER;
            }
        }
    }
};
