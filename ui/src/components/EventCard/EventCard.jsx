import { Button, Card, CardActions, CardContent } from "@mui/material";
import Typography from "@mui/material/Typography";

export const EventCard = (props) => {
  const getCurrentDate = () => {
    const today = new Date();
    const dd = String(today.getDate()).padStart(2, "0");
    const mm = String(today.getMonth() + 1).padStart(2, "0"); //January is 0!
    const yyyy = today.getFullYear();

    return mm + "-" + dd + "-" + yyyy;
  };

  return (
    <Card sx={{ minWidth: 275 }}>
      <CardContent>
        <Typography sx={{ fontSize: 14 }} color="text.secondary" gutterBottom>
          {props.eventType}
        </Typography>
        <Typography variant="h5" component="div">
          {props.customerName}
        </Typography>
        <Typography sx={{ mb: 1.5 }} color="text.secondary">
          Event Date: {props.eventDate}
        </Typography>
      </CardContent>
      <CardActions>
        <Button size="small">View Details</Button>
      </CardActions>
    </Card>
  );
};

export default EventCard;
