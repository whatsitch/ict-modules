#ifndef BUTTON_H
#define BUTTON_H

class Button
{
private:
  byte pin;
  byte state;
  byte lastReading;
  unsigned long lastDebounceTime = 0;
  unsigned long debounceDelay = 75;

public:
  Button() {}
  Button(byte pin)
  {
    this->pin = pin;
    this->lastReading = LOW;
    init();
  }
  void init()
  {
    pinMode(this->pin, INPUT);
    update();
  }
  void update()
  {
    byte newReading = digitalRead(this->pin);

    if (newReading != this->lastReading)
    {
      this->lastDebounceTime = millis();
    }
    if (millis() - this->lastDebounceTime > this->debounceDelay)
    {
      this->state = newReading;
    }
    this->lastReading = newReading;
  }

  void update(int delay)
  {
    int currentMillis = millis();
    int previousMillis = currentMillis;

    while (currentMillis - previousMillis < delay)
    {
      byte newReading = digitalRead(this->pin);

      if (newReading != this->lastReading)
      {
        this->lastDebounceTime = millis();
      }
      if (millis() - this->lastDebounceTime > this->debounceDelay)
      {
        this->state = newReading;
      }
      this->lastReading = newReading;

      currentMillis = millis();
    }
  }

  byte getState()
  {
    update();
    return this->state;
  }
  byte getState(int delay)
  {
    update(delay);
    return this->state;
  }
  bool isPressed()
  {
    return (getState() == HIGH);
  }
  bool isPressed(int delayn)
  {
    delay(1000);
    return (getState(delayn) == HIGH);
  }
};

#endif