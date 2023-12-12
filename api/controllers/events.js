const getEvents = async (req, res, next) => {
  res.status(200).json({
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
};
