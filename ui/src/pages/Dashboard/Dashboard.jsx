import { useQuery } from "react-query";
import Button from "@mui/material/Button";
import Typography from "@mui/material/Typography";
import EventCard from "../../components/EventCard/EventCard";
import { CircularProgress, Grid } from "@mui/material";

const Dashboard = () => {
  const { data, isSuccess, isLoading } = useQuery("eventData", () =>
    fetch(`${process.env.REACT_APP_API_URL}/getActiveEvents`).then((res) =>
      res.json(),
    ),
  );

  return (
    <div>
      <Typography variant="h4" gutterBottom>
        Dashboard
      </Typography>

      {isSuccess && (
        <Grid container spacing={2}>
          {data.events.map((customer, i) => (
            <Grid item xs={12} sm={6} md={4} key={i}>
              <EventCard
                customerName={customer.customerName}
                eventDate={customer.eventDate}
                eventType={customer.eventType}
              />
            </Grid>
          ))}
        </Grid>
      )}

      {isLoading && (
        <CircularProgress
          style={{ position: "absolute", top: "50%", left: "50%" }}
        />
      )}

      <br />

      <Button variant="contained">Open Modal</Button>
    </div>
  );
};

export default Dashboard;
