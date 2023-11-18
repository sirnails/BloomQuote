import Typography from "@mui/material/Typography";
import { useQuery } from "react-query";
import CustomerTable from "../CustomerTable";
import AddCustomerForm from "../AddCustomerForm";
import { CircularProgress } from "@mui/material";

export const ArchivedCustomers = () => {
  const { data, isSuccess, isLoading } = useQuery("archivedCustomerData", () =>
    fetch(`${process.env.REACT_APP_API_URL}/getArchivedCustomers`).then((res) =>
      res.json(),
    ),
  );
  return (
    <div>
      <Typography variant="h5" gutterBottom>
        Archived Customers
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
export default ArchivedCustomers;
