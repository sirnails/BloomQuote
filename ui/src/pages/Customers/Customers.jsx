import { Outlet } from "react-router-dom";
import Typography from "@mui/material/Typography";

export const Customers = () => {
  return (
    <div>
      <Typography variant="h4" gutterBottom>
        Customers
      </Typography>
      <Outlet />
    </div>
  );
};

export default Customers;
