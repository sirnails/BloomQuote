import Typography from "@mui/material/Typography";
import { Outlet } from "react-router-dom";

export const Events = () => {
  return (
    <div>
      <Typography variant="h4" gutterBottom>
        Events
      </Typography>
      <Outlet />
    </div>
  );
};
