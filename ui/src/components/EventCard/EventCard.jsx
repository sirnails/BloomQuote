import { Button, Card, CardActions, CardContent } from "@mui/material";
import Typography from "@mui/material/Typography";

export const EventCard = (props) => {
  const getStatusColor = (dateStr) => {
    const providedDate = new Date(dateStr);
    const currentDate = new Date();
    const timeDifference = providedDate - currentDate;
    const monthsAway = Math.round(timeDifference / (1000 * 60 * 60 * 24 * 30));

    if (monthsAway <= 1) return "error.main";
    else if (monthsAway <= 2) return "warning.main";
    return "";
  };

  return (
    <Card sx={{ minWidth: 275, bgcolor: getStatusColor(props.eventDate) }}>
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
        <Button size="small" sx={{ color: "text.secondary" }}>
          View Details
        </Button>
      </CardActions>
    </Card>
  );
};

export default EventCard;
