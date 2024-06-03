const WebSocket = require("ws");
const express = require("express");
const app = express();
app.use(express.urlencoded({ extended: true }));
const server = require("http").createServer(app);
const wss = new WebSocket.Server({ server });

// Handle Web Socket Connection
wss.on("connection", function connection(ws) {
  console.log("New Connection Initiated");
   ws.on("message", function incoming(message) {
    const msg = JSON.parse(message);
    switch(msg.event) {
      case "connected":
        console.log(`A new call has connected.`);
        break;
      case "start":
        console.log(`Starting Media Stream ${msg.streamSid}`);
        break;
      case "media":
        console.log(`Receiving Audio...`)
        break;
      case "stop":
        console.log(`Call Has Ended`);
        break;
    }
  });
});

//Handle HTTP Request
app.get("/", (req, res) => {
  res.send("Hello World")
});

app.post("/", (req, res) => {
  res.set("Content-Type", "text/xml");
  const STEP = 'STEP', LANG = 'LANG', SPEAK = 'SPEAK', INPUT = 'INPUT';
  const twimlTemplate = `
    <Response>
      <Gather action="/?step=STEP&amp;lang=LANG" method="POST" input="INPUT" timeout="5" finishOnKey="#">
          <Say>SPEAK</Say>
      </Gather>
      <Say>We didn't receive any input. Goodbye.</Say>
    </Response>
`;
  try {
    if(req.query.step) {
      switch(req.query.step) {
        case 'gather1':
          console.log('g1', req.body);
          res.send(twimlTemplate
            .replace(STEP, 'gather2')
            .replace(LANG, 'en')
            .replace(SPEAK, "Enter a number of days for food")
            .replace(INPUT, "dtmf"));
          break;
        case 'gather2':
          console.log('g2', req.body);
          res.send(twimlTemplate
            .replace(STEP, 'gather2')
            .replace(LANG, 'en')
            .replace(SPEAK, "Enter a number of days for food")
            .replace(INPUT, 'dtmf'));
          break;
      }
    } else {
      res.send(twimlTemplate
        .replace(STEP, 'gather1')
        .replace(LANG, 'en')
        .replace(SPEAK, "Please enter the number of cartons.")
        .replace(INPUT,"dtmf"));
    }
  } catch(e) {
    console.error(e);
  }
});

console.log("Listening at Port 8080");
server.listen(8080);
