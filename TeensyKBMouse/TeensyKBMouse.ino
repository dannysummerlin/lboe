#define ENCODER_OPTIMIZE_INTERRUPTS
#include <Encoder.h>
#include <Bounce2.h>

static const int LEFT_BUTTON = 1;
static const int RIGHT_BUTTON = 0;
static const int ROTARY_APIN = 8;
static const int ROTARY_BPIN = 9;
static const int BOUNCE_INTERVAL = 20;
long lastScrollPosition = -999;

Bounce buttonL = Bounce();
Bounce buttonR = Bounce();
//Bounce buttonL = Bounce(LEFT_BUTTON, 10);
//Bounce buttonR = Bounce(RIGHT_BUTTON, 10);
Encoder scrollWheel(ROTARY_APIN, ROTARY_BPIN);

void setup() {
  buttonL.attach(LEFT_BUTTON, INPUT_PULLUP);
  buttonL.interval(BOUNCE_INTERVAL);
  buttonR.attach(RIGHT_BUTTON, INPUT_PULLUP);
  buttonR.interval(BOUNCE_INTERVAL);
//  pinMode(LEFT_BUTTON, INPUT_PULLUP);
//  pinMode(RIGHT_BUTTON, INPUT_PULLUP);
  Serial.begin(9600);
}

void loop() {
  buttonL.update();
  buttonR.update();
  long scrollPosition = scrollWheel.read();
  if(scrollPosition != lastScrollPosition) {
    Mouse.scroll(scrollPosition - lastScrollPosition);
    lastScrollPosition = scrollPosition;
  }
  if (buttonL.fallingEdge()) {
    Mouse.press(MOUSE_LEFT);
  }
  if (buttonL.risingEdge()) {
    Mouse.release(MOUSE_LEFT);
  }
  if (buttonR.fallingEdge()) {
    Mouse.press(MOUSE_RIGHT);
  }
  if (buttonR.risingEdge()) {
    Mouse.release(MOUSE_RIGHT);
  }
}
