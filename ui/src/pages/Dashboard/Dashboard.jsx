import { useQuery } from "react-query";
import Button from "@mui/material/Button";
import Typography from "@mui/material/Typography";
import EventCard from "../../components/EventCard/EventCard";
import { Grid } from "@mui/material";

const Dashboard = () => {
  const { isLoading, error, data } = useQuery("customerData", () =>
    fetch("http://localhost/customers").then((res) => res.json()),
  );

  return (
    <div>
      <Typography variant="h3" gutterBottom>
        Dashboard
      </Typography>

      <Grid container spacing={2}>
        <Grid item xs={4}>
          <EventCard
            customerName={"Nicole Price"}
            eventDate={"2025-05-05"}
            eventType={"Wedding"}
          />
        </Grid>
        <Grid item xs={4}>
          <EventCard
            customerName={"Nicole Price"}
            eventDate={"2025-05-05"}
            eventType={"Wedding"}
          />
        </Grid>
        <Grid item xs={4}>
          <EventCard
            customerName={"Nicole Price"}
            eventDate={"2025-05-05"}
            eventType={"Wedding"}
          />
        </Grid>
        <Grid item xs={4}>
          <EventCard
            customerName={"Nicole Price"}
            eventDate={"2025-05-05"}
            eventType={"Wedding"}
          />
        </Grid>
      </Grid>

      <br />

      <Button variant="contained">Open Modal</Button>
    </div>
  );
};

export default Dashboard;
