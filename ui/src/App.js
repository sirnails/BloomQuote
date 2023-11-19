import Header from "./components/Header/Header";
import { ThemeProvider, createTheme } from "@mui/material/styles";
import CssBaseline from "@mui/material/CssBaseline";
import { QueryClient, QueryClientProvider } from "react-query";
import Dashboard from "./pages/Dashboard/Dashboard";
import "./index.css";
import { BrowserRouter, Route, Routes } from "react-router-dom";
import { ActiveCustomers } from "./pages/Customers/ActiveCustomers/ActiveCustomers";
import Customers from "./pages/Customers/Customers";
import ArchivedCustomers from "./pages/Customers/ArchivedCustomers/ArchivedCustomers";
import ActiveEvents from "./pages/Events/ActiveEvents/ActiveEvents";
import ArchivedEvents from "./pages/Events/ArchivedEvents/ArchivedEvents";
import { Events } from "./pages/Events/Events";
import Login from "./pages/Login/Login";
import Logout from "./pages/Logout/Logout";

const queryClient = new QueryClient();

const darkTheme = createTheme({
  palette: {
    mode: "dark",
  },
});

function App() {
  return (
    <ThemeProvider theme={darkTheme}>
      <CssBaseline />
      <QueryClientProvider client={queryClient}>
        <BrowserRouter>
          <Routes>
            <Route
              path="/"
              element={
                <Header>
                  <Dashboard />
                </Header>
              }
            />

            <Route path="login" element={<Login />} />

            <Route path="logout" element={<Logout />} />

            <Route
              path="customers"
              element={
                <Header>
                  <Customers />
                </Header>
              }
            >
              <Route path="active" element={<ActiveCustomers />} />
              <Route path="archived" element={<ArchivedCustomers />} />
            </Route>

            <Route
              path="events"
              element={
                <Header>
                  <Events />
                </Header>
              }
            >
              <Route path="active" element={<ActiveEvents />} />
              <Route path="archived" element={<ArchivedEvents />} />
            </Route>

            <Route
              path="*"
              element={
                <Header>
                  <h1>404</h1>
                </Header>
              }
            />
          </Routes>
        </BrowserRouter>
      </QueryClientProvider>
    </ThemeProvider>
  );
}

export default App;
