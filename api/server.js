const express = require("express");
var cors = require("cors");
const app = express();
const port = 80;

app.use(cors());

// Customers endpoint
app.get("/getActiveEvents", (req, res) => {
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
        eventDate: "2024-01-20",
        address: "123 Fake Street Two",
        eventType: "Birthday",
      },
      {
        customerName: "Martin Berry",
        eventDate: "2023-12-20",
        address: "123 Fake Street Three",
        eventType: "Wedding",
      },
    ],
  });
});

app.get("/getActiveCustomers", (req, res) => {
  res.json({
    customers: [
      {
        firstName: "Jade",
        lastName: "Smith",
        phoneNumber: "07478951235",
        email: "jade.smith@gmail.com",
        deliveryAddress: "37 High Street Worcester WR9 3HM",
      },
      {
        firstName: "Nicole",
        lastName: "Price",
        phoneNumber: "020 8987 0312",
        email: "Nicole.Price@gmail.com	",
        deliveryAddress: "62 Grove Park Rd, Haringey, N15 4SN	",
      },
      {
        firstName: "Antonia",
        lastName: "Jolley",
        email: "antonia.jolley234@gmail.com	",
        phoneNumber: null,
        deliveryAddress: null,
      },
    ],
  });
});

app.get("/getArchivedCustomers", (req, res) => {
  res.json({
    customers: [
      {
        firstName: "John",
        lastName: "Smith",
        email: "john.smith@gmail.com	",
        phoneNumber: null,
        deliveryAddress: null,
      },
    ],
  });
});

app.listen(port, () => {
  console.log(`Server is running on http://localhost:${port}`);
});
