import {
  Card,
  CardActions,
  CardContent,
  FormControl,
  FormHelperText,
  Input,
  InputLabel,
} from "@mui/material";
import Box from "@mui/material/Box";
import Typography from "@mui/material/Typography";
import Button from "@mui/material/Button";

export const AddCustomerForm = () => {
  return (
    <Card sx={{ maxWidth: 275 }}>
      <CardContent>
        <Typography variant="subtitle1" gutterBottom>
          Add Customer
        </Typography>

        <Box
          component="form"
          sx={{
            "& > :not(style)": { m: 1 },
          }}
          noValidate
          autoComplete="off"
        >
          <FormControl>
            <InputLabel htmlFor="first-name-input">First Name</InputLabel>
            <Input id="first-name-input" />
          </FormControl>
          <br />
          <FormControl>
            <InputLabel htmlFor="last-name-input">Last Name</InputLabel>
            <Input id="last-name-input" />
          </FormControl>
          <br />
          <FormControl>
            <InputLabel htmlFor="phone-number-input">Phone Number</InputLabel>
            <Input id="phone-number-input" />
          </FormControl>
          <br />
          <FormControl>
            <InputLabel htmlFor="email-input">Email address</InputLabel>
            <Input id="email-input" />
          </FormControl>
          <br />
          <FormControl>
            <InputLabel htmlFor="delivery-address-input">
              Delivery address
            </InputLabel>
            <Input id="delivery-address-input" />
          </FormControl>
        </Box>
      </CardContent>
      <CardActions>
        <Button variant="outlined">Add Customer</Button>
      </CardActions>
    </Card>
  );
};

export default AddCustomerForm;
