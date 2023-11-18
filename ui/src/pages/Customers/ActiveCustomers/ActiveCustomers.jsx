import Typography from "@mui/material/Typography";
import CustomerTable from "../CustomerTable";
import AddCustomerForm from "../AddCustomerForm";
import { useQuery } from "react-query";
import { CircularProgress } from "@mui/material";

export const ActiveCustomers = () => {
  const { data, isSuccess, isLoading } = useQuery("activeCustomerData", () =>
    fetch(`${process.env.REACT_APP_API_URL}/getActiveCustomers`).then((res) =>
      res.json(),
    ),
  );

  return (
    <div>
      <Typography variant="h5" gutterBottom>
        Active Customers
      </Typography>

      {isSuccess && (
        <div>
          <CustomerTable customers={data.customers} />
          <br />
          <AddCustomerForm />
        </div>
      )}
      {isLoading && <CircularProgress />}
    </div>
  );
};
