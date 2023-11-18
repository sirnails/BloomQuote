import {
  Paper,
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableHead,
  TableRow,
} from "@mui/material";
import Button from "@mui/material/Button";

export const CustomerTable = (props) => {
  return (
    <div>
      <TableContainer component={Paper}>
        <Table sx={{ minWidth: 650 }} aria-label="simple table">
          <TableHead>
            <TableRow>
              <TableCell align="center">View Customer Events</TableCell>
              <TableCell align="center">First Name</TableCell>
              <TableCell align="center">Last Name</TableCell>
              <TableCell align="center">Phone Number</TableCell>
              <TableCell align="center">Email</TableCell>
              <TableCell align="center">Delivery Address</TableCell>
              <TableCell align="center">Edit Customer Details</TableCell>
              <TableCell align="center">Delete Customer</TableCell>
            </TableRow>
          </TableHead>
          <TableBody>
            {props.customers.map((customer) => (
              <TableRow
                key={customer.lastName}
                sx={{ "&:last-child td, &:last-child th": { border: 0 } }}
              >
                <TableCell component="th" scope="row" align="center">
                  <Button variant="outlined">View Events (29)</Button>
                </TableCell>
                <TableCell component="th" scope="row" align="center">
                  {customer.firstName}
                </TableCell>
                <TableCell component="th" scope="row" align="center">
                  {customer.lastName}
                </TableCell>
                <TableCell component="th" scope="row" align="center">
                  {customer.phoneNumber}
                </TableCell>
                <TableCell component="th" scope="row" align="center">
                  {customer.email}
                </TableCell>
                <TableCell component="th" scope="row" align="center">
                  {customer.deliveryAddress}
                </TableCell>
                <TableCell component="th" scope="row" align="center">
                  <Button variant="outlined">Edit</Button>
                </TableCell>
                <TableCell component="th" scope="row" align="center">
                  <Button variant="outlined" color="error">
                    Delete
                  </Button>
                </TableCell>
              </TableRow>
            ))}
          </TableBody>
        </Table>
      </TableContainer>
    </div>
  );
};

export default CustomerTable;
