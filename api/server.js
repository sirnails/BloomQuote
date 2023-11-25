const express = require("express");
var cors = require("cors");
const app = express();
const port = 3000;

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
        customerId: 1,
        firstName: "Jade",
        lastName: "Smith",
        phoneNumber: "07478951235",
        email: "jade.smith@gmail.com",
        deliveryAddress: "37 High Street Worcester WR9 3HM",
        events: 2,
      },
      {
        customerId: 2,
        firstName: "Nicole",
        lastName: "Price",
        phoneNumber: "020 8987 0312",
        email: "Nicole.Price@gmail.com",
        deliveryAddress: "62 Grove Park Rd, Haringey, N15 4SN	",
        events: 12,
      },
      {
        customerId: 3,
        firstName: "Antonia",
        lastName: "Jolley",
        email: "antonia.jolley234@gmail.com	",
        phoneNumber: null,
        deliveryAddress: null,
        events: 3,
      },
      {
        customerId: 4,
        firstName: "Michael",
        lastName: "Anderson",
        phoneNumber: "07765432109",
        email: "michael.anderson@yahoo.com",
        deliveryAddress: "18 Oak Lane Bristol BS8 2LR",
        events: 5,
      },
      {
        customerId: 5,
        firstName: "Sophie",
        lastName: "Garcia",
        phoneNumber: "07811223344",
        email: "sophie.garcia@hotmail.com",
        deliveryAddress: "25 Elm Street Manchester M1 3ST",
        events: 8,
      },
      {
        customerId: 6,
        firstName: "Daniel",
        lastName: "Lopez",
        phoneNumber: "01158974563",
        email: "daniel.lopez@gmail.com",
        deliveryAddress: "42 Maple Avenue London SW1A 1AA",
        events: 7,
      },
      {
        customerId: 7,
        firstName: "Emily",
        lastName: "Wright",
        phoneNumber: "07551234567",
        email: "emily.wright@gmail.com",
        deliveryAddress: "15 Pine Street Birmingham B3 2DF",
        events: 4,
      },
      {
        customerId: 8,
        firstName: "Robert",
        lastName: "Turner",
        phoneNumber: "01619876543",
        email: "robert.turner@yahoo.com",
        deliveryAddress: "30 Cedar Road Liverpool L17 8UH",
        events: 10,
      },
      {
        customerId: 9,
        firstName: "Grace",
        lastName: "Baker",
        phoneNumber: "07987654321",
        email: "grace.baker@hotmail.com",
        deliveryAddress: "53 Birch Lane Leeds LS1 1AA",
        events: 6,
      },
      {
        customerId: 10,
        firstName: "Alex",
        lastName: "Nguyen",
        phoneNumber: "020 7654 3210",
        email: "alex.nguyen@gmail.com",
        deliveryAddress: "24 Willow Avenue Glasgow G2 4AB",
        events: 15,
      },
      {
        customerId: 11,
        firstName: "Hannah",
        lastName: "Fisher",
        email: "hannah.fisher123@gmail.com",
        phoneNumber: null,
        deliveryAddress: null,
        events: 1,
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
