#ifndef BUZZER_H
#define BUZZER_H

class Buzzer {
  private:
    byte pin;
  public:
    Buzzer(byte pin) {
      this->pin = pin;
      init();
    }
    void init() {
      pinMode(pin, OUTPUT);
    }
}; 


#endif

