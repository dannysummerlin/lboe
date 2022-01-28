#define ENCODER_OPTIMIZE_INTERRUPTS
//#include <Encoder.h>
#include <RotaryEncoder.h>
#include <Bounce2.h>

static const int LEFT_BUTTON = 1;
static const int RIGHT_BUTTON = 0;
static const int ROTARY_APIN = 8;
static const int ROTARY_BPIN = 9;
static const int BOUNCE_INTERVAL = 20;
long lastScrollPosition = -999;

Bounce buttonL = Bounce();
Bounce buttonR = Bounce();
RotaryEncoder scrollWheel(ROTARY_APIN, ROTARY_BPIN, RotaryEncoder::LatchMode::FOUR3);

void setup() {
  buttonL.attach(LEFT_BUTTON, INPUT_PULLUP);
  buttonL.interval(BOUNCE_INTERVAL);
  buttonR.attach(RIGHT_BUTTON, INPUT_PULLUP);
  buttonR.interval(BOUNCE_INTERVAL);
  Serial.begin(115200);
}

void loop() {
  buttonL.update();
  buttonR.update();
  scrollWheel.tick();
  long scrollPosition = scrollWheel.getPosition();
  if(scrollPosition != lastScrollPosition) {
    int accelerator = scrollWheel.getRPM() > 3000 ? 10 : 2;
    Mouse.scroll( (scrollPosition - lastScrollPosition) * accelerator);
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
