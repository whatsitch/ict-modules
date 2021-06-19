#ifndef LED_H
#define LED_H
#include "../miscellaneous/Colour.h"

class LED {
  private:
    byte pin;
    Colour colour;
  public:
    LED() {}
    LED(byte pin, Colour colour) {
      this->pin = pin;
      this->colour = colour;
      init();
    }
    void init() {
      pinMode(this->pin, OUTPUT);
      off();
    }
    void on() {
      digitalWrite(this->pin, HIGH);
    }
    void off() {
      digitalWrite(this->pin, LOW);
    }
}; 
#endif