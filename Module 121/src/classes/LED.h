#ifndef LED_H
#define LED_H
#include "../miscellaneous/Colour.h"

class LED {
  private:
    byte pin;
  public:
    LED() {}
    LED(byte pin, Colour colour) {
      // Use 'this->' to make the difference between the
      // 'pin' attribute of the class and the 
      // local variable 'pin' created from the parameter.
      this->pin = pin;
      init();
    }
    void init() {
      pinMode(this->pin, OUTPUT);
      // Always try to avoid duplicate code.
      // Instead of writing digitalWrite(pin, LOW) here,
      // call the function off() which already does that
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