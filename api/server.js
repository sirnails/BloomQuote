const express = require("express");
var cors = require("cors");
const app = express();
const port = 8080;

app.use(cors());

// Customers endpoint
app.get("/customers", (req, res) => {
  res.json({
    customers: [
      {
        name: "Bob",
        age: 45,
        address: "123 Fake Street",
      },
      {
        name: "Fred",
        age: 67,
        address: "123 Fake Street Two",
      },
      {
        name: "Martin",
        age: 82,
        address: "123 Fake Street Three",
      },
    ],
  });
});

app.listen(port, () => {
  console.log(`Server is running on http://localhost:${port}`);
});
