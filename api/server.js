const express = require("express");
var cors = require("cors");
const app = express();
const port = 8080;

app.use(cors());

// Customers endpoint
app.get("/events", (req, res) => {
  res.json({
    events: [
      {
        customerName: "Nicole Price",
        eventDate: "2025-05-05",
        address: "123 Fake Street",
        eventType: "Wedding",
      },
      {
        customerName: "Fred Smith",
        eventDate: "2025-07-02",
        address: "123 Fake Street Two",
        eventType: "Birthday",
      },
      {
        customerName: "Martin Berry",
        eventDate: "2025-01-11",
        address: "123 Fake Street Three",
        eventType: "Wedding",
      },
    ],
  });
});

app.listen(port, () => {
  console.log(`Server is running on http://localhost:${port}`);
});
