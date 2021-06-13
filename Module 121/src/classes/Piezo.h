#ifndef PIEZO_H
#define PIEZO_H

class Piezo {
  private:
    byte pin;
    int frequency;
  public:
    Piezo() {}
    Piezo(byte pin, int frequency) {
      this->pin = pin;
      this->frequency = frequency;
      init();
    }
    void init() {
      pinMode(this->pin, OUTPUT);
    }

    void makeSound(int delay) { tone(this->pin, this->frequency, delay); }


}; 

#endif

